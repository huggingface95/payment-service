<?php

namespace App\Http\Controllers;

use App\Models\Clickhouse\AuthenticationLog;
use App\Models\Members;
use App\Models\OauthCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        if(env('CHECK_IP') === true) {
            if (request('proceed')) {
                if (Cache::get('auth_user:' . $user->id)) {
                    JWTAuth::setToken(Cache::get('auth_user:' . $user->id))->invalidate();
                    $this->writeToAuthLog('logout');
                } else {
                    $this->writeToAuthLog('logout');
                }

                Cache::put('auth_user:' . $user->id, $token, env('JWT_TTL', 3600));
                $this->writeToAuthLog('login');
                return $this->respondWithToken($token);
            }

            if (request('cancel')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            if ($this->getAuthUserIp($user->email) != $this->getIp()) {
                return response()->json(['error' => 'This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users.'], 403);
            }

            if ($this->getAuthUserBrowser($user->email) != Agent::browser()) {
                return response()->json(['error' => 'This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users.'], 403);
            }

            if ($this->getAuthUser($user->email) == 'login') {
                return response()->json(['error' => 'This ID is currently in use on another device. Proceeding on this device, will automatically log out all other users.'], 403);
            }

        }

        $get_ip_address = $user->ipAddress()->pluck('ip_address')->toArray();
        if ($get_ip_address) {
            if (! in_array(request()->ip(), $get_ip_address)) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        if ($user->two_factor_auth_setting_id == 2 && $user->google2fa_secret) {
            $this->writeToAuthLog('login');
            OauthCodes::insert(['id' => $this->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => 1, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);
            if (Cache::get('auth_user:' . $user->id)) {
                Cache::put('auth_user:' . $user->id, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add('auth_user:' . $user->id, $token, env('JWT_TTL', 3600));
            }
            return $this->respondWithToken2Fa($token);
        } else {
            $this->writeToAuthLog('login');
            if (Cache::get('auth_user:' . $user->id)) {
                Cache::put('auth_user:' . $user->id, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add('auth_user:' . $user->id, $token, env('JWT_TTL', 3600));
            }
            return $this->respondWithToken($token);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->user();
        $this->writeToAuthLog('logout');
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    protected function respondWithToken2Fa($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'two_factor' => 'true',
        ]);
    }

    public function show2FARegistrationInfo(Request $request)
    {
        $secret = Google2FA::generateSecretKey(config('lumen2fa.key_length', 32));
        $user = auth()->user();
        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $QR_Image = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->{config('lumen2fa.user_identified_field')},
            $secret
        );
        $data = [
            'image' => $QR_Image,
            'code'  => $secret,
        ];

        return response()->json($data);
    }

    public function activate2FA(Request $request)
    {
        $this->validate($request, [
            'secret' => 'required',
            'code' => 'required',
        ]);

        $user = auth()->user();

        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $secretKey = $request->secret;
        $user->google2fa_secret =
            str_pad($secretKey, pow(2, ceil(log(strlen($secretKey), 2))), config('lumen2fa.string_pad', 'X'));
        $user->save();
        OauthCodes::insert(['id' => $this->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => 1, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);

        if ($this->verify2FA($request)->getData()->data == 'success') {
            $user->createToken($user->fullname)->accessToken;
            $user->two_factor_auth_setting_id = 2;
            $user->save();

            return response()->json(['data' => '2fa activated']);
        } else {
            return response()->json(['data' => 'Unable to verify your code'], 401);
        }
    }

    public function verify2FA(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        $user = auth()->user();

        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }

        $expires = OauthCodes::select('*')->where('user_id', $user->id)->orderByDesc('expires_at')->limit(1)->get();
        if (strtotime($expires[0]->expires_at) < strtotime(now())){
            auth()->invalidate();
            return response()->json(['error' => 'Token has expired'], 403);
        }

        $codes = json_decode($user->backup_codes);
        if (request('backup_code')) {
            foreach ($codes->backup_codes as $code) {
                if ($code[1] == 'true'){
                    return response()->json(['error' => 'This code has been already used'], 403);
                }
                if ($code[0] == request('backup_code')){
                    return response()->json(['data' => 'success']);
                }
                else {
                    return response()->json(['error' => 'No such code'], 403);
                }
            }
        }

        $token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->latest()->limit(1);
        $valid = Google2FA::verifyGoogle2FA($user->google2fa_secret, $request->code);
        if (! $valid) {
            $token->update(['twofactor_verified' => false]);

            return response()->json(['data' => 'Unable to verify your code']);
        }
        $token->update(['twofactor_verified' => true]);

        return response()->json(['data' => 'success']);
    }

    public function disable2FA(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'password' => 'required',
        ]);
        $user = auth()->user();
        if (! Hash::check($request->password, $user->getAuthPassword())) {
            return response()->json(['data' => 'Password is not valid']);
        }

        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->latest()->limit(1);
        $valid = Google2FA::verifyGoogle2FA($user->google2fa_secret, $request->code);

        if ($valid) {
            $user->google2fa_secret = null;
            $user->two_factor_auth_setting_id = 1;
            $user->save();
            $token->update(['twofactor_verified' => false]);
        } else {
            return response()->json(['data' => 'Unable to verify your code']);
        }

        return response()->json(['data' => 'Google 2fa disabled successful']);
    }

    public function generateBackupCodes(Request $request)
    {
        $user = auth()->user();
        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $codes = [];
        for ($i = 0; $i <= 9; $i++) {
            $codes[$i] = $this->generateUniqueCode();
        }

        return response()->json(['backup_codes' => $codes, 'user_id' => $user->id, '2fa_secret' => $user->google2fa_secret]);
    }

    public function storeBackupCodes(Request $request)
    {
        $this->validate($request, [
            'backup_codes' => 'required',
        ]);

        $user = auth()->user();
        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $user->backup_codes = $request->backup_codes;
        $user->save();

        return response()->json(['data' => 'Backup Codes stored success for user id '.$user->id]);
    }

    public function generateUniqueCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        return $code;
    }

    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip);
                    return $ip;
                }
            }
        }
        return request()->ip();
    }

    public function writeToAuthLog($status){
        $user = auth()->user();
        $log = AuthenticationLog::make(['id' => rand(0,4294967295), 'member' => $user->email, 'domain' => request()->getHttpHost(), 'browser' => Agent::browser()?Agent::browser():'unknown', 'platform' => Agent::platform()?Agent::platform():'unknown', 'device_type' => Agent::device()?Agent::device():'unknown', 'ip' => $this->getIp(), 'status' => $status, 'created_at' => now()]);
        $log->save();
    }

    public function getAuthUserIp($email)
    {
        $user = auth()->user();
        $getIp = AuthenticationLog::select('*')->
        where('member', '=', (string)$email)->
        where('status', '=', 'login')->
        orderByDesc('created_at')->
        limit(1)->
        getRows();
        if ($getIp) {
            return $getIp[0]['ip'];
        } else {
            $this->writeToAuthLog('login');
            $getIp = AuthenticationLog::select('*')->
            where('member', '=', (string)$email)->
            where('status', '=', 'login')->
            orderByDesc('created_at')->
            limit(1)->
            getRows();
            return $getIp[0]['ip'];
        }
    }

    public function getAuthUser($email)
    {
        $user = auth()->user();
        $getStatus = AuthenticationLog::select('*')->
        where('member', '=', (string)$email)->
        orderByDesc('created_at')->
        limit(1)->
        getRows();
        if ($getStatus) {
            return $getStatus[0]['status'];
        } else {
            $this->writeToAuthLog('logout');
            $getStatus = AuthenticationLog::select('*')->
            where('member', '=', (string)$email)->
            orderByDesc('created_at')->
            limit(1)->
            getRows();
            return $getStatus[0]['status'];
        }

    }

    public function getAuthUserBrowser($email)
    {
        $user = auth()->user();
        $getBrowser = AuthenticationLog::select('*')->
        where('member', '=', (string)$email)->
        where('status', '=', 'login')->
        orderByDesc('created_at')->
        limit(1)->
        getRows();
        if ($getBrowser) {
            return $getBrowser[0]['browser'];
        } else {
            $this->writeToAuthLog('login');
            $getBrowser = AuthenticationLog::select('*')->
            where('member', '=', (string)$email)->
            where('status', '=', 'login')->
            orderByDesc('created_at')->
            limit(1)->
            getRows();
            return $getBrowser[0]['browser'];
        }
    }

}

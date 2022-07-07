<?php

namespace App\Http\Controllers;

use App\Models\Clickhouse\ActivityLog;
use App\Models\Clickhouse\AuthenticationLog;
use App\Models\Members;
use Illuminate\Http\Request;
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

        if(env('CHECK_IP') == true) {

            if (request('proceed')) {
                $log = AuthenticationLog::make(['member' => $user->email, 'domain' => request()->getHttpHost(), 'browser' => Agent::browser(), 'platform' => Agent::platform(), 'device_type' => Agent::device(), 'ip' => request()->ip(), 'status' => 'logout', 'created_at' => now()]);
                $log->save();
                auth()->invalidate();
                return $this->respondWithToken(auth()->attempt($credentials));
            }

            if (request('cancel')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            if ($this->getAuthUser($user->email) == 'login') {
                return response()->json(['error' => 'This ID is currently in use on another device.'], 403);
            }

            if ($this->getAuthUserIp($user->email) != request()->ip()) {
                return response()->json(['error' => 'Your IP address was changed. You will be logged out'], 403);
            }

            if ($this->getAuthUserBrowser($user->email) != Agent::browser()) {
                return response()->json(['error' => 'Your Browser was changed. You will be logged out'], 403);
            }
        }

        $log = AuthenticationLog::make(['member' => $user->email, 'domain' => request()->getHttpHost(), 'browser' => Agent::browser(), 'platform' => Agent::platform(), 'device_type' => Agent::device(), 'ip' => request()->ip(), 'status' => 'login', 'created_at' => now()]);
        $log->save();

        $get_ip_address = $user->ipAddress()->pluck('ip_address')->toArray();
        if ($get_ip_address) {
            if (! in_array(request()->ip(), $get_ip_address)) {
                return response()->json(['error' => 'Access denied'], 403);
            }
        }

        if ($user->two_factor_auth_setting_id == 2 && $user->google2fa_secret) {
            if ($this->verify2FA(request())->getData()->data == 'success') {
                return $this->respondWithToken2Fa($token);
            } else {
                return response()->json(['data' => 'Unable to verify your code'], 401);
            }
        } else {
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
        $log = AuthenticationLog::make(['member' => $user->email, 'domain' => request()->getHttpHost(), 'browser' => Agent::browser(), 'platform' => Agent::platform(), 'device_type' => Agent::device(), 'ip' => request()->ip(), 'status' => 'logout', 'created_at' => now()]);
        $log->save();
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
        $codeLength = 16;

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        return $code;
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
                $log = AuthenticationLog::make(['member' => $user->email, 'domain' => request()->getHttpHost(), 'browser' => Agent::browser(), 'platform' => Agent::platform(), 'device_type' => Agent::device(), 'ip' => request()->ip(), 'status' => 'login', 'created_at' => now()]);
                $log->save();
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
            $log = AuthenticationLog::make(['member' => $user->email, 'domain' => request()->getHttpHost(), 'browser' => Agent::browser(), 'platform' => Agent::platform(), 'device_type' => Agent::device(), 'ip' => request()->ip(), 'status' => 'logout', 'created_at' => now()]);
            $log->save();
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
            $log = AuthenticationLog::make(['member' => $user->email, 'domain' => request()->getHttpHost(), 'browser' => Agent::browser(), 'platform' => Agent::platform(), 'device_type' => Agent::device(), 'ip' => request()->ip(), 'status' => 'login', 'created_at' => now()]);
            $log->save();
            $getBrowser = AuthenticationLog::select('*')->
            where('member', '=', (string)$email)->
            where('status', '=', 'login')->
            orderByDesc('created_at')->
            limit(1)->
            getRows();
        }
    }

}

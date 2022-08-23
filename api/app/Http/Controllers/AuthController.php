<?php

namespace App\Http\Controllers;

use App\Models\Clickhouse\AuthenticationLog;
use App\Models\Members;
use App\Models\OauthCodes;
use App\Models\OauthTokens;
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
        $this->middleware('jwt.auth', ['except' => ['login', 'verify2FA', 'show2FARegistrationInfo', 'activate2FA', 'generateBackupCodes', 'storeBackupCodes']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (Cache::get('login_attempt:'.request('email'))) {
            $user = Members::select('*')->where('email', request('email'))->first();
            if (!$user) {
                return response()->json(['error' => 'No such user'], 403);
            }
            if ($user->is_active == false) {
                return response()->json(['error' => 'Account is blocked. Please contact support'], 403);
            }
            if (Cache::get('block_account:'.$user->id)) {
                return response()->json(['error' => 'Your account temporary blocked. Try again later'], 403);
            }
            if (Cache::get('login_attempt:'.request('email')) == env('MFA_ATTEMPTS', '5')) {
                Cache::add('block_account:'.$user->id, 1, env('BLOCK_ACCOUNT_TTL', 100));
                Cache::put('login_attempt:'.request('email'), Cache::get('login_attempt:'.request('email')) + 1);

                return response()->json(['error' => 'Account is temporary blocked for '.env('BLOCK_ACCOUNT_TTL', 120) / 60 .' minutes'], 403);
            } elseif (Cache::get('login_attempt:'.request('email')) >= env('MFA_ATTEMPTS', '5') * 2 + 1) {
                $user->is_active = false;
                $user->save();
                Cache::forget('login_attempt:'.request('email'));

                return response()->json(['error' => 'Account is blocked. Please contact support'], 403);
            }
        }

        if (! $token = auth()->attempt($credentials)) {
            if (Cache::get('login_attempt:'.request('email'))) {
                Cache::put('login_attempt:'.request('email'), Cache::get('login_attempt:'.request('email')) + 1);
            } else {
                Cache::add('login_attempt:'.request('email'), Cache::get('login_attempt:'.request('email')) + 1);
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if ($user->is_active == false) {
            return response()->json(['error' => 'Account is blocked. Please contact support'], 403);
        }

        if (Cache::get('block_account:'.$user->id)) {
            return response()->json(['error' => 'Your account temporary blocked. Try again later'], 403);
        }

        if (env('CHECK_IP') === true) {
            if (request('proceed')) {
                if (Cache::get('auth_user:'.$user->id)) {
                    JWTAuth::setToken(Cache::get('auth_user:'.$user->id))->invalidate();
                    $this->writeToAuthLog('logout');
                } else {
                    $this->writeToAuthLog('logout');
                }

                if ($user->two_factor_auth_setting_id == 2 && !($user->google2fa_secret)) {
                    $this->writeToAuthLog('login');
                    $user->createToken($user->fullname)->accessToken;
                    OauthCodes::insert(['id' => $this->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => 1, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);
                    if (Cache::get('auth_user:'.$user->id)) {
                        Cache::put('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
                    } else {
                        Cache::add('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
                    }
                    $auth_token = OauthTokens::select('*')->where('user_id', $user->id)->orderByDesc('created_at')->limit(1)->get();

                    return response()->json(['2fa_token' => $auth_token[0]->id]);
                }

                if ($user->two_factor_auth_setting_id == 2 && $user->google2fa_secret) {
                    OauthCodes::insert(['id' => $this->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => 1, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);
                    if (Cache::get('auth_user:'.$user->id)) {
                        Cache::put('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
                    } else {
                        Cache::add('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
                    }

                    $auth_token = OauthTokens::select('*')->where('user_id', $user->id)->orderByDesc('created_at')->limit(1)->get();
                    if (Cache::get('login_attempt:'.$user->email)) {
                        Cache::forget('login_attempt:'.$user->email);
                    }

                    return response()->json(['two_factor' => 'true', 'auth_token' => $auth_token[0]->id]);
                } else {
                    Cache::put('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
                    if (Cache::get('login_attempt:'.$user->email)) {
                        Cache::forget('login_attempt:'.$user->email);
                    }
                    $this->writeToAuthLog('login');

                    return $this->respondWithToken($token);
                }
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

        if ($user->two_factor_auth_setting_id == 2 && !($user->google2fa_secret)) {
            $this->writeToAuthLog('login');
            $user->createToken($user->fullname)->accessToken;
            OauthCodes::insert(['id' => $this->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => 1, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);
            if (Cache::get('auth_user:'.$user->id)) {
                Cache::put('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
            }
            $auth_token = OauthTokens::select('*')->where('user_id', $user->id)->orderByDesc('created_at')->limit(1)->get();
            if (Cache::get('login_attempt:'.$user->email)) {
                Cache::forget('login_attempt:'.$user->email);
            }

            return response()->json(['2fa_token' => $auth_token[0]->id]);
        }

        if ($user->two_factor_auth_setting_id == 2 && $user->google2fa_secret) {
            $this->writeToAuthLog('login');
            OauthCodes::insert(['id' => $this->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => 1, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);
            if (Cache::get('auth_user:'.$user->id)) {
                Cache::put('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
            }
            $auth_token = OauthTokens::select('*')->where('user_id', $user->id)->orderByDesc('created_at')->limit(1)->get();

            return response()->json(['two_factor' => 'true', 'auth_token' => $auth_token[0]->id]);
        } else {
            $this->writeToAuthLog('login');
            if (Cache::get('auth_user:'.$user->id)) {
                Cache::put('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add('auth_user:'.$user->id, $token, env('JWT_TTL', 3600));
            }
            if (Cache::get('login_attempt:'.$user->email)) {
                Cache::forget('login_attempt:'.$user->email);
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
        if (request('2fa_token')) {
            $access_token = OauthTokens::select('*')->where('id', request('2fa_token'))->orderByDesc('created_at')->limit(1)->get();
            $user = Members::find($access_token[0]->user_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        if (request('access_token')) {
            $user = Members::find(JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
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

        if (request('access_token')) {
            $user = Members::find(JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }

        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();
            $user = Members::find($user_id[0]->user_id);
        } else {
            $user = auth()->user();
        }

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
        $user->createToken($user->fullname)->accessToken;
        if ($this->verify2FA($request)->getData()->data == 'success') {
            $user->two_factor_auth_setting_id = 2;
            $user->save();

            return response()->json(['data' => '2fa activated']);
        } else {
            return response()->json(['data' => 'Unable to verify your code'], 403);
        }
    }

    public function verify2FA(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();
            $user = Members::find($user_id[0]->user_id);
        } else {
            $user = auth()->user();
        }
        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }

        $expires = OauthCodes::select('*')->where('user_id', $user->id)->orderByDesc('expires_at')->limit(1)->get();
        if (strtotime($expires[0]->expires_at) < strtotime(now())) {
            return response()->json(['error' => 'Token has expired'], 403);
        }

        /*$expiresPersonalToken = OauthTokens::select('*')->where('user_id', $user->id)->orderByDesc('expires_at')->limit(1)->get();
        if (strtotime($expiresPersonalToken[0]->expires_at) < strtotime(now())) {
            return response()->json(['error' => 'Your Personal Access Token has expired'], 403);
        }*/

        if (Cache::get('mfa_attempt:'.$user->id)) {
            if (Cache::get('mfa_attempt:'.$user->id) == env('MFA_ATTEMPTS', '5')) {
                Cache::add('block_account:'.$user->id, 1, env('BLOCK_ACCOUNT_TTL', 100));
                JWTAuth::setToken(Cache::get('auth_user:'.$user->id))->invalidate();
                Cache::put('mfa_attempt:'.$user->id, Cache::get('mfa_attempt:'.$user->id) + 1);

                return response()->json(['error' => 'Account is temporary blocked for '.env('BLOCK_ACCOUNT_TTL', 120) / 60 .' minutes'], 403);
            } elseif (Cache::get('mfa_attempt:'.$user->id) >= env('MFA_ATTEMPTS', '5') * 2 + 1) {
                $user->is_active = false;
                $user->save();
                JWTAuth::setToken(Cache::get('auth_user:'.$user->id))->invalidate();
                Cache::forget('mfa_attempt:'.$user->id);
                JWTAuth::setToken(Cache::get('auth_user:'.$user->id))->invalidate();

                return response()->json(['error' => 'Account is blocked. Please contact support'], 403);
            }
        }

        if (request('backup_code') != null) {
            $codes = $user->backup_codes['backup_codes'];
            $data = '';
            foreach ($codes as $key => $code) {
                if ($code['code'] == request('backup_code') && $codes[$key]['use'] == 'true') {
                    return response()->json(['error' => 'This code has been already used'], 403);
                }
                if ($code['code'] == request('backup_code')) {
                    $codes[$key]['use'] = 'true';
                    $data = true;
                }
            }
            $user->backup_codes = [
                'backup_codes' => $codes,
            ];
            $user->save();
            if ($data == true) {
                $token = JWTAuth::fromUser($user);

                return response()->json(['data' => 'success', 'token' => $token]);
            } else {
                return response()->json(['error' => 'No such code'], 403);
            }
        }

        $access_token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->latest()->limit(1);
        $valid = Google2FA::verifyGoogle2FA($user->google2fa_secret, $request->code);
        if (! $valid) {
            $access_token->update(['twofactor_verified' => false]);
            if (Cache::get('mfa_attempt:'.$user->id)) {
                Cache::put('mfa_attempt:'.$user->id, Cache::get('mfa_attempt:'.$user->id) + 1);
            } else {
                Cache::add('mfa_attempt:'.$user->id, Cache::get('mfa_attempt:'.$user->id) + 1);
            }

            return response()->json(['data' => 'Unable to verify your code'], 403);
        }
        $access_token->update(['twofactor_verified' => true]);
        if (Cache::get('mfa_attempt:'.$user->id)) {
            Cache::forget('mfa_attempt:'.$user->id);
        }
        $token = JWTAuth::fromUser($user);

        return response()->json(['data' => 'success', 'token' => $token]);
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
        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();
            $user = Members::find($user_id[0]->user_id);
        } else {
            $user = auth()->user();
        }
        if (request('access_token')) {
            $user = Members::find(JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
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

        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();
            $user = Members::find($user_id[0]->user_id);
        } else {
            $user = auth()->user();
        }
        if (request('access_token')) {
            $user = Members::find(JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        if ($request->member_id) {
            $user = Members::find($request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $user->backup_codes = $request->backup_codes;
        $user->save();
        $token = JWTAuth::fromUser($user);
        return response()->json(['data' => 'Backup Codes stored success for user id '.$user->id, 'access_token' => $token]);
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

    public function getIp()
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);

                    return $ip;
                }
            }
        }

        return request()->ip();
    }

    public function writeToAuthLog($status): void
    {
        $user = auth()->user();

        DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->insert([
                'id' => rand(0, 4294967295),
                'member' => $user->email,
                'domain' => request()->getHttpHost(),
                'browser' => Agent::browser() ? Agent::browser() : 'unknown',
                'platform' => Agent::platform() ? Agent::platform() : 'unknown',
                'device_type' => Agent::device() ? Agent::device() : 'unknown',
                'ip' => $this->getIp(),
                'status' => $status,
                'created_at' => now(),
            ]);
    }

    public function getAuthUserIp(string $email): string
    {
        $getIp = DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->select(['ip'])
            ->where('member', '=', (string) $email)
            ->where('status', '=', 'login')
            ->orderByDesc('created_at')
            ->limit(1)
            ->get();

        if ($getIp) {
            return $getIp[0]['ip'];
        } else {
            return $this->getIp();
        }
    }

    public function getAuthUser(string $email): string
    {
        $getStatus = DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->select(['status'])
            ->where('member', '=', (string) $email)
            ->orderByDesc('created_at')
            ->limit(1)
            ->get();

        if ($getStatus) {
            return $getStatus[0]['status'];
        } else {
            $this->writeToAuthLog('logout');

            $getStatus = DB::connection('clickhouse')
                ->table((new AuthenticationLog)->getTable())
                ->select(['status'])
                ->where('member', '=', (string) $email)
                ->orderByDesc('created_at')
                ->limit(1)
                ->get();

            return $getStatus[0]['status'];
        }
    }

    public function getAuthUserBrowser(string $email)
    {
        $getBrowser = DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->select(['browser'])
            ->where('member', '=', (string) $email)
            ->where('status', '=', 'login')
            ->orderByDesc('created_at')
            ->limit(1)
            ->get();

        if ($getBrowser) {
            return $getBrowser[0]['browser'];
        } else {
            return Agent::browser();
        }
    }
}

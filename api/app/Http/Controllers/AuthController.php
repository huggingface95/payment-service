<?php

namespace App\Http\Controllers;

use App\Models\ApplicantIndividual;
use App\Models\Clickhouse\AuthenticationLog;
use App\Models\Members;
use App\Models\OauthCodes;
use App\Models\OauthTokens;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    private string $guard = '';

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(protected AuthService $authService)
    {
        //        $this->middleware('auth:api', ['except' => ['login']]);
        $this->middleware('jwt.auth', ['except' => ['login', 'verify2FA', 'show2FARegistrationInfo', 'activate2FA', 'generateBackupCodes', 'storeBackupCodes']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'client_type' => 'nullable|string'
        ]);

        $this->guard = $this->authService->getGuardByClientType($request->client_type);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $attemptCacheKey = 'login_attempt_'.$this->guard.':'.$request->email;

        if (Cache::get($attemptCacheKey)) {
            if ($this->guard == 'api') {
                $user = Members::select('id','member_status_id')->where('email', $request->email)->first();
            } else {
                $user = ApplicantIndividual::select('id', 'is_active')->where('email', $request->email)->first();
            }
            if (! $user) {
                return response()->json(['error' => 'No such user'], 403);
            }
            if ($user->is_active == false) {
                return response()->json(['error' => 'User is not active. Please contact support'], 403);
            }
            if (Cache::get('block_account_'.$this->guard.':'.$user->id)) {
                return response()->json(['error' => 'User temporary blocked. Try again later'], 403);
            }
            if (Cache::get($attemptCacheKey) == env('MFA_ATTEMPTS', '5')) {
                Cache::add('block_account_'.$this->guard.':'.$user->id, 1, env('BLOCK_ACCOUNT_TTL', 100));
                Cache::put($attemptCacheKey, Cache::get($attemptCacheKey) + 1);

                return response()->json(['error' => 'User is temporary blocked for '.env('BLOCK_ACCOUNT_TTL', 120) / 60 .' minutes'], 403);
            } elseif (Cache::get($attemptCacheKey) >= env('MFA_ATTEMPTS', '5') * 2 + 1) {
                $this->authService->setInactive($user);
                Cache::forget($attemptCacheKey);

                return response()->json(['error' => 'User is blocked. Please contact support'], 403);
            }
        }

        if (! $token = auth($this->guard)->attempt($credentials)) {
            if (Cache::get($attemptCacheKey)) {
                Cache::put($attemptCacheKey, Cache::get($attemptCacheKey) + 1);
            } else {
                Cache::add($attemptCacheKey, Cache::get($attemptCacheKey) + 1);
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth($this->guard)->user();
        $clientId = $this->authService->getClientTypeIdByGuard($this->guard);
        $authCacheKey = 'auth_user_'.$this->guard.':'.$user->id;
        $loginAttemptCacheKey = 'login_attempt_'.$this->guard.':'.$user->email;

        if ($user->is_need_change_password) {
            return response()->json(['message' => 'Please change password first', 'url' => route('password.change.member')], 403);
        }

        if ($user->is_active == false) {
            return response()->json(['error' => 'Account is blocked. Please contact support'], 403);
        }

        if (Cache::get('block_account_'.$this->guard.':'.$user->id)) {
            return response()->json(['error' => 'Your account temporary blocked. Try again later'], 403);
        }

        if (env('CHECK_IP') === true) {
            if (request('proceed')) {
                if (Cache::get($authCacheKey)) {
                    JWTAuth::setToken(Cache::get($authCacheKey))->invalidate();
                    Cache::forget($authCacheKey);
                }

                $this->writeToAuthLog('logout');

                if ($user->two_factor_auth_setting_id == 2 && ! ($user->google2fa_secret)) {
                    $this->writeToAuthLog('login');
                    $authTokenId = $user->createToken($user->fullname)->token->id;
                    OauthTokens::where('id', $authTokenId)->update(['client_id' => $clientId]);
                    OauthCodes::insert(['id' => $this->authService->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => $clientId, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);

                    if (Cache::get($authCacheKey)) {
                        Cache::put($authCacheKey, $token, env('JWT_TTL', 3600));
                    } else {
                        Cache::add($authCacheKey, $token, env('JWT_TTL', 3600));
                    }

                    return response()->json(['2fa_token' => $authTokenId]);
                }

                if ($user->two_factor_auth_setting_id == 2 && $user->google2fa_secret) {
                    OauthCodes::insert(['id' => $this->authService->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => $clientId, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);
                    if (Cache::get($authCacheKey)) {
                        Cache::put($authCacheKey, $token, env('JWT_TTL', 3600));
                    } else {
                        Cache::add($authCacheKey, $token, env('JWT_TTL', 3600));
                    }

                    $auth_token = OauthTokens::select('id')->where('user_id', $user->id)->where('client_id', $clientId)->orderByDesc('created_at')->limit(1)->get();
                    if (Cache::get($loginAttemptCacheKey)) {
                        Cache::forget($loginAttemptCacheKey);
                    }

                    return response()->json(['two_factor' => 'true', 'auth_token' => $auth_token[0]->id]);
                } else {
                    if (Cache::get($authCacheKey)) {
                        Cache::put($authCacheKey, $token, env('JWT_TTL', 3600));
                    } else {
                        Cache::add($authCacheKey, $token, env('JWT_TTL', 3600));
                    }
                    if (Cache::get($loginAttemptCacheKey)) {
                        Cache::forget($loginAttemptCacheKey);
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

        $this->writeToAuthLog('login');

        if ($user->two_factor_auth_setting_id == 2 && ! ($user->google2fa_secret)) {
            $authTokenId = $user->createToken($user->fullname)->token->id;
            OauthTokens::where('id', $authTokenId)->update(['client_id' => $clientId]);
            OauthCodes::insert(['id' => $this->authService->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => $clientId, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);

            if (Cache::get($authCacheKey)) {
                Cache::put($authCacheKey, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add($authCacheKey, $token, env('JWT_TTL', 3600));
            }

            if (Cache::get($loginAttemptCacheKey)) {
                Cache::forget($loginAttemptCacheKey);
            }

            return response()->json(['2fa_token' => $authTokenId]);
        }

        if ($user->two_factor_auth_setting_id == 2 && $user->google2fa_secret) {
            $authToken = $this->authService->getTwoFactorAuthToken($user, $clientId);

            if (Cache::get($authCacheKey)) {
                Cache::put($authCacheKey, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add($authCacheKey, $token, env('JWT_TTL', 3600));
            }

            return response()->json(['two_factor' => 'true', 'auth_token' => $authToken]);
        } else {
            if (Cache::get($authCacheKey)) {
                Cache::put($authCacheKey, $token, env('JWT_TTL', 3600));
            } else {
                Cache::add($authCacheKey, $token, env('JWT_TTL', 3600));
            }
            if (Cache::get($loginAttemptCacheKey)) {
                Cache::forget($loginAttemptCacheKey);
            }

            return $this->respondWithToken($token);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $clientType = JWTAuth::getPayload()->get('client_type');
        $guard = $this->authService->getGuardByClientType($clientType);

        return response()->json(auth($guard)->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        $this->writeToAuthLog('logout');
        $this->guard = $this->authService->getGuardByClientType($request->client_type);
        $authCacheKey = 'auth_user_'.$this->guard.':'.$user->id;
        if (Cache::get($authCacheKey)) {
            JWTAuth::setToken(Cache::get($authCacheKey))->invalidate();
            Cache::forget($authCacheKey);
        }
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $clientType = JWTAuth::getPayload()->get('client_type');
        $guard = $this->authService->getGuardByClientType($clientType);

        return $this->respondWithToken(auth($guard)->refresh());
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
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60,
        ]);
    }

    protected function respondWithToken2Fa($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60,
            'two_factor' => 'true',
        ]);
    }

    public function show2FARegistrationInfo(Request $request)
    {
        $this->guard = $this->authService->getGuardByClientType($request->client_type);
        $secret = Google2FA::generateSecretKey(config('lumen2fa.key_length', 32));

        if (request('2fa_token')) {
            $access_token = OauthTokens::select('*')->where('id', request('2fa_token'))->orderByDesc('created_at')->first();
            $user = $this->authService->getUserByClientId($access_token->client_id, $access_token->user_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        if ($request->access_token) {
            $user = $this->authService->getUserByGuard($this->guard, JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        if ($request->member_id) {
            $user = $this->authService->getUserByGuard($this->guard, $request->member_id);
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

        $this->guard = $this->authService->getGuardByClientType($request->client_type);

        if ($request->access_token) {
            $user = $this->authService->getUserByGuard($this->guard, JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }

        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();

            $user = $this->authService->getUserByGuard($this->guard, $user_id[0]->user_id);
        } else {
            $user = auth($this->guard)->user();
        }

        if ($request->member_id) {
            $user = $this->authService->getUserByGuard($this->guard, $request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }

        $clientId = $this->authService->getClientTypeIdByGuard($this->guard);
        $secretKey = $request->secret;
        $user->google2fa_secret =
            str_pad($secretKey, pow(2, ceil(log(strlen($secretKey), 2))), config('lumen2fa.string_pad', 'X'));
        $user->save();
        OauthCodes::insert(['id' => $this->authService->generateUniqueCode(), 'user_id' => $user->id, 'client_id' => $clientId, 'revoked' => 'true', 'expires_at' => now()->addMinutes(15)]);
        $authTokenId = $user->createToken($user->fullname)->token->id;
        OauthTokens::where('id', $authTokenId)->update(['client_id' => $clientId]);

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

        $this->guard = $this->authService->getGuardByClientType($request->client_type);

        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();
            $user = $this->authService->getUserByGuard($this->guard, $user_id[0]->user_id);
        } else {
            $user = auth($this->guard)->user();
        }

        if ($request->member_id) {
            $user = $this->authService->getUserByGuard($this->guard, $request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }

        $authCacheKey = 'auth_user:'.$user->id;
        $mtaAttemptCacheKey = 'mfa_attempt:'.$user->id;

        $expires = OauthCodes::select('*')->where('user_id', $user->id)->orderByDesc('expires_at')->limit(1)->get();
        if (strtotime($expires[0]->expires_at) < strtotime(now())) {
            return response()->json(['error' => 'Token has expired'], 403);
        }

        /*$expiresPersonalToken = OauthTokens::select('*')->where('user_id', $user->id)->orderByDesc('expires_at')->limit(1)->get();
        if (strtotime($expiresPersonalToken[0]->expires_at) < strtotime(now())) {
            return response()->json(['error' => 'Your Personal Access Token has expired'], 403);
        }*/

        if (Cache::get($mtaAttemptCacheKey)) {
            if (Cache::get($mtaAttemptCacheKey) == env('MFA_ATTEMPTS', '5')) {
                Cache::add('block_account:'.$user->id, 1, env('BLOCK_ACCOUNT_TTL', 100));
                JWTAuth::setToken(Cache::get($authCacheKey))->invalidate();
                Cache::put($mtaAttemptCacheKey, Cache::get($mtaAttemptCacheKey) + 1);

                return response()->json(['error' => 'Account is temporary blocked for '.env('BLOCK_ACCOUNT_TTL', 120) / 60 .' minutes'], 403);
            } elseif (Cache::get($mtaAttemptCacheKey) >= env('MFA_ATTEMPTS', '5') * 2 + 1) {
                $this->authService->setInactive($user);
                JWTAuth::setToken(Cache::get($authCacheKey))->invalidate();
                Cache::forget($mtaAttemptCacheKey);
                JWTAuth::setToken(Cache::get($authCacheKey))->invalidate();

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

        $clientId = $this->authService->getClientTypeIdByGuard($this->guard);
        $access_token = OauthTokens::where('user_id', $user->id)->where('client_id', $clientId)->latest()->limit(1);
        $valid = Google2FA::verifyGoogle2FA($user->google2fa_secret, $request->code);
        if (! $valid) {
            $access_token->update(['twofactor_verified' => false]);
            if (Cache::get($mtaAttemptCacheKey)) {
                Cache::put($mtaAttemptCacheKey, Cache::get($mtaAttemptCacheKey) + 1);
            } else {
                Cache::add($mtaAttemptCacheKey, Cache::get($mtaAttemptCacheKey) + 1);
            }

            return response()->json(['data' => 'Unable to verify your code'], 403);
        }
        $access_token->update(['twofactor_verified' => true]);
        if (Cache::get($mtaAttemptCacheKey)) {
            Cache::forget($mtaAttemptCacheKey);
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

        $this->guard = $this->authService->getGuardByClientType($request->client_type);

        $user = auth($this->guard)->user();
        if (! Hash::check($request->password, $user->getAuthPassword())) {
            return response()->json(['data' => 'Password is not valid']);
        }

        if ($request->member_id) {
            $user = $this->authService->getUserByGuard($this->guard, $request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }

        $valid = Google2FA::verifyGoogle2FA($user->google2fa_secret, $request->code);

        if ($valid) {
            $user->google2fa_secret = null;
            $user->two_factor_auth_setting_id = 1;
            $user->save();

            $clientId = $this->authService->getClientTypeIdByGuard($this->guard);
            $token = OauthTokens::where('user_id', $user->id)->where('client_id', $clientId)->latest()->limit(1);
            $token->update(['twofactor_verified' => false]);
        } else {
            return response()->json(['data' => 'Unable to verify your code']);
        }

        return response()->json(['data' => 'Google 2fa disabled successful']);
    }

    public function generateBackupCodes(Request $request)
    {
        $this->guard = $this->authService->getGuardByClientType($request->client_type);

        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();
            $user = $this->authService->getUserByGuard($this->guard, $user_id[0]->user_id);
        } else {
            $user = auth($this->guard)->user();
        }
        if (request('access_token')) {
            $user = $this->authService->getUserByGuard($this->guard, JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        if ($request->member_id) {
            $user = $this->authService->getUserByGuard($this->guard, $request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $codes = [];
        for ($i = 0; $i <= 9; $i++) {
            $codes[$i] = $this->authService->generateUniqueCode();
        }

        return response()->json(['backup_codes' => $codes, 'user_id' => $user->id, '2fa_secret' => $user->google2fa_secret]);
    }

    public function storeBackupCodes(Request $request)
    {
        $this->validate($request, [
            'backup_codes' => 'required',
        ]);

        $this->guard = $this->authService->getGuardByClientType($request->client_type);

        if ($request->auth_token) {
            $user_id = OauthTokens::select('user_id')->where('id', $request->auth_token)->orderByDesc('created_at')->limit(1)->get();
            $user = $this->authService->getUserByGuard($this->guard, $user_id[0]->user_id);
        } else {
            $user = auth($this->guard)->user();
        }
        if (request('access_token')) {
            $user = $this->authService->getUserByGuard($this->guard, JWTAuth::parseToken()->authenticate()->id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        if ($request->member_id) {
            $user = $this->authService->getUserByGuard($this->guard, $request->member_id);
            if (! $user) {
                return response()->json(['data' => 'Member not found']);
            }
        }
        $user->backup_codes = $request->backup_codes;
        $user->save();
        $token = JWTAuth::fromUser($user);

        return response()->json(['data' => 'Backup Codes stored success for user id '.$user->id, 'access_token' => $token]);
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

    private function writeToAuthLog($status): void
    {
        $user = auth($this->guard)->user();

        DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->insert([
                'id' => rand(0, 4294967295),
                'member' => $user->email,
                'client_type' => $this->guard,
                'domain' => request()->getHttpHost(),
                'browser' => Agent::browser() ? Agent::browser() : 'unknown',
                'platform' => Agent::platform() ? Agent::platform() : 'unknown',
                'device_type' => Agent::device() ? Agent::device() : 'unknown',
                'ip' => $this->getIp(),
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    }

    private function getAuthUserIp(string $email): string
    {
        $getIp = DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->select(['ip'])
            ->where('member', '=', (string) $email)
            ->where('client_type', '=', (string) $this->guard)
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

    private function getAuthUser(string $email): string
    {
        $getStatus = DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->select(['status'])
            ->where('member', '=', (string) $email)
            ->where('client_type', '=', (string) $this->guard)
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
                ->where('client_type', '=', (string) $this->guard)
                ->orderByDesc('created_at')
                ->limit(1)
                ->get();

            return $getStatus[0]['status'];
        }
    }

    private function getAuthUserBrowser(string $email)
    {
        $getBrowser = DB::connection('clickhouse')
            ->table((new AuthenticationLog)->getTable())
            ->select(['browser'])
            ->where('member', '=', (string) $email)
            ->where('client_type', '=', (string) $this->guard)
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

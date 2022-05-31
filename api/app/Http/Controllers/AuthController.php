<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Illuminate\Support\Facades\Hash;

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

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if ($user->two_factor_auth_setting_id == 2 && $user->google2fa_secret) {
            return $this->respondWithToken2Fa($token);
        }
        else {
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
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function respondWithToken2Fa($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'two_factor' => 'true'
        ]);
    }

    public function show2FARegistrationInfo()
    {
        $secret = Google2FA::generateSecretKey(config('lumen2fa.key_length', 32));
        $QR_Image = Google2FA::getQRCodeInline(
            config('app.name'),
            auth()->user()->{config('lumen2fa.user_identified_field')},
            $secret
        );
        $data = [
            "image" => $QR_Image,
            "code"  => $secret
        ];

        return response()->json($data);
    }

    public function activate2FA(Request $request)
    {
        $this->validate($request, [
            'secret' => 'required'
        ]);
        $user = auth()->user();
        $user->createToken($user->fullname)->accessToken;
        $secretKey = $request->secret;
        $user->two_factor_auth_setting_id = 2;
        $user->google2fa_secret =
            str_pad($secretKey, pow(2, ceil(log(strlen($secretKey), 2))), config('lumen2fa.string_pad', 'X'));
        $user->save();

        return response()->json(['data' => '2fa activated']);
    }

    public function verify2FA(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);
        $auth_user = auth()->user();
        $token = DB::table('oauth_access_tokens')->where('user_id', $request->user()->id)->latest()->limit(1);
        $valid = Google2FA::verifyGoogle2FA($auth_user->google2fa_secret, $request->code);
        if (!$valid) {
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
            'password' => 'required'
        ]);
        $auth_user = auth()->user();
        if (!Hash::check($request->password,$auth_user->getAuthPassword()))
        {
            return response()->json(['data' => 'Password is not valid']);
        }
        $token = DB::table('oauth_access_tokens')->where('user_id', $request->user()->id)->latest()->limit(1);
        $valid = Google2FA::verifyGoogle2FA($auth_user->google2fa_secret, $request->code);
        if ($valid) {
            $auth_user->google2fa_secret = null;
            $auth_user->two_factor_auth_setting_id = 1;
            $auth_user->save();
            $token->update(['twofactor_verified' => false]);
        } else {
            return response()->json(['data' => 'Unable to verify your code']);
        }
        return response()->json(['data' => 'Google 2fa disabled successful']);
    }

    public function generateBackupCodes()
    {
        $auth_user = auth()->user();
        $codes = [];
        for ($i = 0; $i <= 9; $i++) {
            $codes[$i] = $this->generateUniqueCode();
        }

        return response()->json(['backup_codes' => $codes, 'user_id' => $auth_user->id, '2fa_secret' => $auth_user->google2fa_secret]);
    }

    public function storeBackupCodes(Request $request)
    {
        $this->validate($request, [
            'backup_codes' => 'required'
        ]);
        $auth_user = auth()->user();
        $auth_user->backup_codes = $request->backup_codes;
        $auth_user->save();

        return response()->json(['data' => 'Backup Codes stored success for user id '.$auth_user->id]);

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
                $code = $code . $character;
            }

            return $code;
    }
}

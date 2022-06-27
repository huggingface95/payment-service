<?php
/**
 * User: aitspeko
 * Date: 19/09/2018
 * Time: 17:28
 *
 * Project: lumen-2fa
 */

return [
    'app_name'   => env('LUMEN2FA_APP_NAME', config('app.name', 'docudots.com')),
    'key_length' => env('LUMEN2FA_KEY_LENGTH', 32),
    'string_pad' => env('LUMEN2FA_STRING_PAD', 'X'),
    'user_identifier_field' => env('LUMEN2FA_USER_IDENTIFIER_FIELD', 'email'),
];

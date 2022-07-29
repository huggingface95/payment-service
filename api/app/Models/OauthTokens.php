<?php

namespace App\Models;

class OauthTokens extends BaseModel
{
    protected $table = 'oauth_access_tokens';

    public $incrementing = false;
}

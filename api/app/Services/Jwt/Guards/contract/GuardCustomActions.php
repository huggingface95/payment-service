<?php

namespace App\Services\Jwt\Guards\contract;

use Illuminate;

interface GuardCustomActions extends Illuminate\Contracts\Auth\Guard
{
    public function type();
}

<?php

namespace App\Services\Jwt\Guards;

use App\DTO\Auth\Credentials;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Traits\Macroable;

class JwtGuard implements Guard
{
    use GuardHelpers, Macroable {
        __call as macroCall;
    }

    protected $provider;

    protected Credentials $credentials;

    public function __construct(EloquentUserProvider $provider, Credentials $credentials)
    {
        $this->provider = $provider;
        $this->credentials = $credentials;
    }

    public function user()
    {
        return $this->provider->getModel() == get_class($this->credentials->model) ? $this->credentials->model : null;
    }

    public function check()
    {
        return $this->provider->getModel() == get_class($this->credentials->model);
    }

    public function guest()
    {
        // TODO: Implement guest() method.
    }

    public function id()
    {
        return $this->provider->getModel() == get_class($this->credentials->model) ? $this->credentials->model->id : null;
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function setUser(\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        // TODO: Implement setUser() method.
    }
}

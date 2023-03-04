<?php

namespace App\Services\Jwt\Guards;

use App\DTO\Auth\Credentials;
use App\Services\Jwt\Guards\contract\GuardCustomActions;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Support\Traits\Macroable;

class JwtGuard implements GuardCustomActions
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

    public function user(): \App\Models\BaseModel|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        return $this->provider->getModel() == get_class($this->credentials->model) ? $this->credentials->model : null;
    }

    public function check(): bool
    {
        return $this->provider->getModel() == get_class($this->credentials->model);
    }

    public function type(): ?string
    {
        return $this->credentials->type;
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

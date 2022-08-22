<?php

namespace App\GraphQL\Queries;

use App\DTO\GraphQLResponse\UserAuthResponse;
use App\DTO\TransformerDTO;
use Illuminate\Support\Facades\Auth;

class UsersQuery
{
    public function userAuthData($_, array $args)
    {
        return TransformerDTO::transform(UserAuthResponse::class, Auth::user());
    }
}

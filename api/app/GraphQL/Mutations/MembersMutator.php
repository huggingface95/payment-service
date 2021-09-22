<?php

namespace App\GraphQL\Mutations;

use App\Models\Members;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MembersMutator
{

    public function create($_, array $args)
    {
        if (!isset($args['password'])) {
            $password = Str::random(8);
        } else {
            $password =$args['password_hash'];
        }

        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);
        return Members::create($args);
    }

    public function invite($_, array $args)
    {
        $password = Str::random(8);
        $args['is_active'] = false;

        $args['password_hash'] = Hash::make($password);
        $args['password_salt'] = Hash::make($password);
        return Members::create($args);
    }

    public function setPassword($_, array $args)
    {

        $args['password_hash'] = Hash::make($args['password']);
        $args['password_salt'] = Hash::make($args['password']);
        return $args;
    }

}

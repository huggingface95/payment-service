<?php

namespace App\Events;

use App\Models\Account;

class AccountCreatedEvent extends Event
{
    public Account $account;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}

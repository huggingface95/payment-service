<?php

namespace App\Events;

use App\Models\Account;

class AccountUpdatedEvent extends Event
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

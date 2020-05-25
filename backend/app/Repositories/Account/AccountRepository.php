<?php

namespace App\Repositories\Account;

use Illuminate\Support\Facades\Auth;
use App\User;

class AccountRepository
{
    public function updateStatus($data)
    {
        User::where('id',auth()->user()->id)->update($data);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\AccountInterface;

class AccountController extends Controller
{

	private $account;

    public function __construct(AccountInterface $account)
    {
    	$this->account = $account;
    }	

    public function login()
    {
    	return $this->account->login();
    }

    public function logout()
    {
    	$this->account->logout();
    }
}

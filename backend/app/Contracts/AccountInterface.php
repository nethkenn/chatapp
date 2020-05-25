<?php
namespace App\Contracts;

interface AccountInterface {
	
	public function login();
	public function logout();
}
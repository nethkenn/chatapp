<?php

namespace App\Implementations\Account;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Contracts\AccountInterface;
use App\Repositories\Account\AccountRepository;
use App\Events\StatusEvent;

class JWT extends Controller implements AccountInterface
{
    private $AccountRepository;
    /**
     * Create a new AuthController instance.
     *  
     * @return void
     */
    public function __construct(AccountRepository $account)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->AccountRepository = $account;
    }

    /**
     * Get a JewishToJD(month, day, year) via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        //validate account
        if (! $token = auth()->attempt(request(['email', 'password']))) {
            return response()->json(['error' => 'Unauthorized'], 404);
        }
        //if account exist update status to online
        $this->AccountRepository->updateStatus(['status' => 'online']);
        //fire an event
        broadcast(new StatusEvent())->toOthers();
        //return token
        return $this->respondWithToken($token); 
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        //update status to offline and the lastActive 
        $this->AccountRepository->updateStatus(['status' => 'offline','lastActive' => now()]);
        //fire an event
        broadcast(new StatusEvent())->toOthers();
        //logout
        auth()->logout();
        //return message
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'expires_in' => auth()->factory()->getTTL() * 60,
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
        ]);
    }
}
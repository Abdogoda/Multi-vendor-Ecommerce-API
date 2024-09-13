<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller{
    
    use ApiResponseTrait;

    public function __invoke(Request $request){
        try {
            $user = User::find(Auth::user()->id);
            if (!$user) {
                return $this->errorResponse(__('messages.unauthorized_access'), 401);
            }
            
            $user->tokens->each(function ($token) {
                $token->revoke();
            });

            $token = $user->createToken('Personal Access Token')->accessToken;

            $data = [
                'token' => $token
            ];

            return $this->successResponse($data, __('messages.token_refreshed_successfully'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller{
    use ApiResponseTrait;
    public function __invoke(LoginRequest $request){
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => __('messages.invalid_credientials')], 401);
            }

            if($user->status != "active"){
                return response()->json(['error' => __('messages.deactivated_account')], 403);
            }
    
            $data = [
                'user' => new UserResource($user),
                'token' => $user->createToken('Personal Access Token')->accessToken,
            ];
            return $this->successResponse($data, __('messages.login_success'), 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
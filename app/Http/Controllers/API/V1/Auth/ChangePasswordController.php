<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller{
    
    use ApiResponseTrait;

    public function __invoke(ChangePasswordRequest $request){
        try {
            $user = User::find(Auth::user()->id);
            if (!$user) {
                return $this->errorResponse(__('messages.unauthorized_access'), 401);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->errorResponse(__('messages.current_password_incorrect'), 400);
            }
            
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            return $this->successResponse(null, __('messages.password_update_success'));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
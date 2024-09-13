<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller{
    use ApiResponseTrait;
    private $otp;

    public function __invoke(ResetPasswordRequest $request){
        try {
            $otp = new Otp;
            $otp_check = $otp->validate($request->email, $request->otp);

            if(!$otp_check->status){
                return $this->errorResponse($otp_check);
            }
            
            $user = User::where('email', $request->email)->first();
            if($user){
                $user->update(['password' => Hash::make($request->password)]);
                $user->tokens->each(function ($token) {
                    $token->revoke();
                });
                
                return $this->successResponse(null, __('messages.password_update_success'));
            }else{
                return $this->errorResponse(__('messages.not_found'));
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
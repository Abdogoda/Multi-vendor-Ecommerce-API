<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Ichtrojan\Otp\Otp;

class VerifyOTPController extends Controller{
    
    use ApiResponseTrait;
    private $otp;

    public function __invoke(VerifyOtpRequest $request){
        try {
            $otp = new Otp;
            $otp_check = $otp->validate($request->email, $request->otp);

            if(!$otp_check->status){
                return $this->errorResponse($otp_check);
            }
            
            $user = User::where('email', $request->email)->first();
            if($user){
                return $this->successResponse(null, __('messages.otp_verified'));
            }else{
                return $this->errorResponse(__('messages.not_found'));
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
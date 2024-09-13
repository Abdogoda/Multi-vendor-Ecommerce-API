<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendOTPRequest;
use App\Models\User;
use App\Notifications\OtpSendNotification;
use App\Traits\ApiResponseTrait;
use Exception;

class SendOTPController extends Controller{
    
    use ApiResponseTrait;

    public function __invoke(SendOTPRequest $request){
        try {
            $user = User::where('email', $request->email)->first();
            if($user){
                $subject = 'Your Verify OTP Code';
                $message = 'We received a request to verify your account. Please use the code below to verify your account.';
                $user->notify(new OtpSendNotification($subject, $message));
                
                return $this->successResponse(null, __('messages.otp_sent_successfully'));
            }else{
                return $this->errorResponse(__('messages.not_found'));
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
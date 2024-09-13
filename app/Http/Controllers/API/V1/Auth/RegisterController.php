<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCustomerRequest;
use App\Http\Requests\Auth\RegisterVendorRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Notifications\RegisterNotification;
use App\Services\UserRegisterService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller{
    use ApiResponseTrait;

    public $userRegisterService;

    public function __construct(UserRegisterService $userRegisterService){
        $this->userRegisterService = $userRegisterService;
    }

    public function customerRegister(RegisterCustomerRequest $request){
        try {
            DB::beginTransaction();
            
            $user = $this->userRegisterService->userRegister($request);

            $message = "You can now log in and start shopping.";
            $user->notify(new RegisterNotification($message));

            $data = [
                'user' => new UserResource($user),
                'token' => $user->createToken('Personal Access Token')->accessToken,
            ];
            $message = __('messages.register_success');

            DB::commit();
            return $this->successResponse($data, $message, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function vendorRegister(RegisterVendorRequest $request){
        try {
            DB::beginTransaction();
            
            $user = $this->userRegisterService->userRegister($request, "vendor");
            $vendor = $this->userRegisterService->vendorRegister($request, $user);
            
            $message = "Your account is under review and will be activated shortly. You will receive an email once your account is activated and ready to use.";
            $user->notify(new RegisterNotification($message));
            
            $vendor->load('user');

            $data = new VendorResource($vendor);
            $message = __('messages.registeration_message');

            DB::commit();
            return $this->successResponse($data, $message, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
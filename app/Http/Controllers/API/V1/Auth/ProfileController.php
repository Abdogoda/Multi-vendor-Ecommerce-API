<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\UpdateVednorProfile;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use App\Traits\FileControlTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller{
    use ApiResponseTrait, FileControlTrait;
    
    public function profile(Request $request){
        $user = User::find(auth()->user()->id);
        try {
            if($user){
                if($request->query('include_orders')){
                    $user->load('orders');
                }
                if($request->query('include_vendor')){
                    $user->load('vendor');
                }
                
                return $this->successResponse(new UserResource($user), __('messages.retrieve_success'));
            }else{
                return $this->errorResponse(__('messages.not_found'));
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
    
    public function updateProfile(UpdateProfileRequest $request){
        $user = User::find(auth()->user()->id);
        try {
            if($user){
                $data = $request->validated();
                if ($request->hasFile('profile_image')) {
                    $this->deleteFile($user->image);
                    $data['profile_image'] = $this->uploadFile($request->file('profile_image'), 'users/profile');
                }
                
                $user->update($data);
                
                return $this->successResponse(new UserResource($user), __('messages.update_success'));
            }else{
                $this->errorResponse(__('messages.not_found'));
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
    
    public function updateVendorProfile(UpdateVednorProfile $request){
        $user = User::find(Auth::user()->id);
        if(!$user || $user->status != 'active' || $user->role != 'vendor'){
            return $this->errorResponse(__('messages.unauthorized_access'), 401);
        }
        try {
            $vendor = $user->vendor;
            if($vendor){
                $data = $request->validated();
                if ($request->hasFile('shop_logo')) {
                    $this->deleteFile($vendor->shop_logo);
                    $data['shop_logo'] = $this->uploadFile($request->file('shop_logo'), 'users/vendors');
                }
                
                $vendor->update($data);
                
                return $this->successResponse(new VendorResource($vendor), __('messages.update_success'));
            }else{
                $this->errorResponse(__('messages.not_found'));
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }

}
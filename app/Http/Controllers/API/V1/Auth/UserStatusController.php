<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\ChangeStatusNotification;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserStatusController extends Controller{
    use ApiResponseTrait;

    public function __invoke(User $user, Request $request){
        
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|max:255|in:active,deactive',
            ]);
            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors()->all());
            }
            $user->update(['status' => $request->status]);
            if($user->status == 'active'){
                $message = "You can now log in and start your journy.";
                $user->notify(new ChangeStatusNotification($message));
            }
            
            DB::commit();
            return $this->successResponse(new UserResource($user), __('messages.update_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
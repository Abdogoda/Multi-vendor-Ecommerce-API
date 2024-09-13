<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCustomerRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\RegisterNotification;
use App\Traits\ApiResponseTrait;
use App\Traits\FileControlTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller{
    
    use ApiResponseTrait, FileControlTrait;
    
    public function index(PaginationRequest $request) {
        $perPage = $request->per_page ?? 10;
        
        $admins = User::where('role', 'admin')->orderBy('create_at', 'desc')->paginate($perPage);
        return $this->successResponse(UserResource::collection($admins), __('messages.retrieve_success'));
    }
    
    public function show(User $admin, Request $request){
        if ($request->query('include_products')) {
            $admin->load('products');
        }
        return $this->successResponse(new UserResource($admin), __('messages.retrieve_success'));
    }

    public function store(RegisterCustomerRequest $request){
        try {
            DB::beginTransaction();

            $data = $request->validated();
            if ($request->hasFile('profile_image')) {
                $data['profile_image'] = $this->uploadFile($request->file('profile_image'), 'users/profile');
            }
            $data['password'] = Hash::make($request->password);
            $data['role'] = 'admin';
            $data['status'] = 'active';
            $user = User::create($data);

            $message = "You can use the website and the admin dashboard now.";
            $user->notify(new RegisterNotification($message));

            $data = [
                'user' => new UserResource($user),
            ];
            $message = __('messages.register_success');

            DB::commit();
            return $this->successResponse($data, $message, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
    
}
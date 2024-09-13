<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vendor;
use App\Traits\FileControlTrait;
use Illuminate\Support\Facades\Hash;

class UserRegisterService{
 use FileControlTrait;
 public function userRegister($request, $role="customer"): User{
   $profile_image = null;
   if ($request->hasFile('profile_image')) {
     $profile_image = $this->uploadFile($request->file('profile_image'), 'users/profile');
   }
   $user = User::create([
     "first_name" => $request->first_name,
     "last_name" => $request->last_name,
     "email" => $request->email,
     "password" => Hash::make($request->password),
     "phone" => $request->phone,
     "address" => $request->address,
     "profile_image" => $profile_image,
     "role" => $role,
     'status' => $role == "customer" ? "active": "deactive" 
   ]);

   return $user;
 }
 public function vendorRegister($request, $user): Vendor{
   $shop_logo = null;
   if ($request->hasFile('shop_logo')) {
     $shop_logo = $this->uploadFile($request->file('shop_logo'), 'users/vendors');
   }
   $user->vendor()->create([
     "shop_name_en" => $request->shop_name_en,
     "shop_name_ar" => $request->shop_name_ar,
     "shop_address" => $request->shop_address,
     "shop_phone" => $request->shop_phone,
     "shop_email" => $request->shop_email,
     "shop_website" => $request->shop_website,
     "shop_logo" => $shop_logo,
     "description" => $request->description,
   ]);

   return $user->vendor;
 }
}
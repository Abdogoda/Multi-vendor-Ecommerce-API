<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller{
    use ApiResponseTrait;

    public function loginOrRegisterWithGoogle(){
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(){
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if the user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $token = $user->createToken('Personal Access Token')->accessToken;
            } else {
                // explode the first and the last name
                $fullName = $googleUser->getName();
                $nameParts = explode(' ', $fullName);
                $firstName = array_shift($nameParts);
                $lastName = implode(' ', $nameParts);

                // upload the google avatar
                $profileImageUrl = $googleUser->getAvatar();
                $profileImagePath = null;
                if ($profileImageUrl) {
                    // Download the image from the URL
                    $contents = file_get_contents($profileImageUrl);
                    $imageName = uniqid().'.jpg'; // You can change the extension based on the image type
                    Storage::disk('public')->put('uploads/users/profile_images/' . $imageName, $contents);
                    $profileImagePath = 'uploads/users/profile/' . $imageName;
                }
                // Create a new user
                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'profile_image' => $profileImagePath,
                    'password' => bcrypt(Str::random(24)), // Generate a random password
                    'role' => 'customer',
                    'status' => 'active'
                ]);

                $token = $user->createToken('authToken')->accessToken;
            }

            $data = [
                'user' => $user,
                'token' => $token
            ];

            return $this->successResponse($data, 200);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LogoutController extends Controller{
    use ApiResponseTrait;

    public function __invoke(Request $request){
        $token = $request->user()->token();
        $token->revoke();
        return $this->successResponse(null, __('messages.logout_success'), 200);
    }
}
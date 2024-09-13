<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomeFormRequest extends FormRequest{
    
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator){
        $errors = $validator->errors();

        $response = response()->json([
            'message' => __('messages.invalid_data'),
            'errors' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseRequest extends FormRequest
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'version' => '1.0.0',
                'isError' => true,
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            ));
    }
}

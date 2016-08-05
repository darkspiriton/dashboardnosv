<?php

namespace Dashboard\Http\Requests;

use Dashboard\Http\Requests\Request;

class RequestApplicationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required|max:255",
            "email" => "required|email",
            "phone" => "required"
        ];
    }

    public function response(array $errors)
    {
        return response()->json(["message" => "Revise bien su informacion", "errors" => $errors], 422);
    }
}

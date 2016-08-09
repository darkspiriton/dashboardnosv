<?php

namespace Dashboard\Http\Requests;

use Dashboard\Http\Requests\Request;

class ClientOutRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function response(array $array){
        return response()->json(["message" => "Parametros recibidos invalidos.", "errors" => $errors],422);
    }
}

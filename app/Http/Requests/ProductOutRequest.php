<?php

namespace Dashboard\Http\Requests;

use Dashboard\Http\Requests\Request;

class ProductOutRequest extends Request
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
            'products'      => 'required|array',
            'requestDate'   => 'required|date',
            'shipmentDate'  => 'required|date',
            'codOrder'      => 'required',
            'products.*.id' => 'required|integer|exists:auxproducts,id',
            'products.*.discount'   => 'required|numeric',
            'products.*.priceOut'   => 'required|numeric',
            'seller_id'     =>  'required|integer|exists:users,id',
            'client_id'     =>  'required|integer|exists:auxclients,id'
        ];
    }

    public function response(array $errors)
    {
        return response()->json(["message" => "Parametros recibidos invalidos."], 422);
    }
}

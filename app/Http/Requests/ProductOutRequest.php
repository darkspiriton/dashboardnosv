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
            'products'    => 'required|array',
            'requestDate'  => 'required|date',
            'shipmentDate'  => 'required|date',
            'codOrder'    => 'required',
            'products.*.id'    => 'required|integer|exists:auxproducts,id',
            'products.*.discount'    => 'required|numeric',
            'seller_id' => 'required|exists:users,id'
        ];
    }

    public function messages(){
        return [
            'products.required'    => 'Producto(s) son requeridos',
            'requestDate.required'  => 'Fecha de pedido es requerido',
            'shipmentDate.required'  => 'Fecha de salida es requerido',
            'codOrder.required'    => 'Codigo de orden es requerido',
            'seller_id.required' => 'Vendedor(a) es requerido'
        ];
    }

    public function response(array $errors)
    {
        return response()->json(["message" => "Parametros recibidos invalidos.", "errors" => $errors], 422);
    }
}

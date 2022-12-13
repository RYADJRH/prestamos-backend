<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shopping\AddProduct;
use App\Models\Beneficiary;
use App\Models\Shopping;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    public function index(Beneficiary $beneficiary): JsonResponse
    {
        $shoppings = $beneficiary->shoppings()->paginate(20);
        return new  JsonResponse(['shoppings' => $shoppings]);
    }

    public function store(AddProduct $request): JsonResponse
    {
        $product_name   = $request->product_name;
        $date_shopping  = $request->date_shopping;
        $producto_price = $request->producto_price;
        $id_beneficiary = $request->id_beneficiary;

        $this->authorize('create', [Shopping::class, $id_beneficiary]);

        $shopping = new Shopping();

        $shopping->product_name     = $product_name;
        $shopping->date_shopping    = $date_shopping;
        $shopping->producto_price   = $producto_price;
        $shopping->id_beneficiary   = $id_beneficiary;
        $shopping->saveOrFail();

        return new JsonResponse(['shopping' => $shopping]);
    }

    public function destroy(Shopping $shopping)
    {
        $shopping->delete();
    }

    public function update(Shopping $shopping, AddProduct $request)
    {
        $product_name   = $request->product_name;
        $date_shopping  = $request->date_shopping;
        $producto_price = $request->producto_price;

        $this->authorize('create', [Shopping::class, $shopping->id_beneficiary]);

        $shopping->product_name     = $product_name;
        $shopping->date_shopping    = $date_shopping;
        $shopping->producto_price   = $producto_price;

        $shopping->save();

        return new JsonResponse(['shopping' => $shopping]);
    }
}

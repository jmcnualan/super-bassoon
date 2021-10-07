<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class OrderController extends Controller
{
  public function order(Request $request)
  {
    //check input
    $validator = Validator::make($request->only('product_id', 'quantity'), [
      'product_id' => 'required|exists:Products,id',
      'quantity'   => 'required|numeric|min:0'
    ]);
    if($validator->fails()){
      $errorMessage = 'Invalid input';
      return response()->json(['message'=>$errorMessage], 400);
    }
    //check exists
    if(empty($product = Product::find($request->product_id))){
      $errorMessage = 'Product not found';
      return response()->json(['message'=>$errorMessage], 400);
    }
    //check quantity
    $availableStock = $product->available_stock - $request->quantity;
    if($availableStock < 0){
      $errorMessage = 'Failed to order this product due to unavailability of the stock';
      return response()->json(['message'=>$errorMessage], 400);
    }

    //deduct
    $product->available_stock = $availableStock;
    $product->save();

    //add to order
    $order = new Order;
    $order->user_id = auth()->user()->id;
    $order->product_id = $product->id;
    $order->quantity = $request->quantity;
    $order->save();
    
    $message = 'You have successfully ordered this product';
    return response()->json(['message'=>$message], 201);
  }
}

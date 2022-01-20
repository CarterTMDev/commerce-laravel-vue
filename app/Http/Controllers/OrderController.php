<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $validation = [
        "customer_id" => ['required', 'exists:App\Models\Customer,id'],
        "isShipped" => ['boolean'],
        "initial_cost" => ['required', 'numeric', 'max:999999.99', 'regex:/^[0-9]+(\.[0-9]?[0-9])?$/'],
        "shipping_cost" => ['required', 'numeric', 'max:999999.99', 'regex:/^[0-9]+(\.[0-9]?[0-9])?$/']
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve paginated orders from the table
        $orders = Order::orderBy('created_at', 'desc')->paginate(8);
        return $orders;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate inputs
        $validator = Validator::make($request->input(), $this->validation);
        if (!$validator->fails()) {
            // Save order
            $order = new Order($validator->validated());
            if ($order->save()) {
                // Order saved
                return $order;
            } else {
                // Return 400
                return response()->json($validator->errors(), 400);
            }
        } else {
            // Return 400
            return response()->json($validator->errors(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        // This should only respond to patch requests since you shouldn't
        // be able to update an order's id, updated_at, or created_at columns
        if ($request->isMethod('PATCH')) {
            // Create an updated order model
            $order->fill($request->input());
            // Validate inputs
            $validator = Validator::make($order->getAttributes(), $this->validation);
            if (!$validator->fails()) {
                $order->fill($validator->validated());
                if ($order->save()) {
                    // Order saved
                    return $order;
                } else {
                    // Return 400
                    return response()->json($validator->errors(), 400);
                }
            }
            return response()->json($validator->errors(), 400);
        } else {
            // Send 405: Method not allowed
            return response()->json([], 405);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if ($order->delete()) {
            return response()->json();
        } else {
            return response()->json([], 400);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve orders from the table
        $orders = Order::all();
        return $orders;
        // TODO: Paginate data
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check for fillable values
        $order = new Order();
        $orderAttr = $order->getFillable();
        $valid = true;
        // TODO: If request has an unexpected input item key, $valid = false
        foreach ($orderAttr as $attr) {
            if ($request->has($attr)) {
                $value = $request->input($attr);
                // TODO: input validation
                $order->setAttribute($attr, $value);
            } else {
                $valid = false;
            }
        }
        if ($valid) {
            // Save order
            if ($order->save()) {
                // Order saved
                return $order;
            } else {
                // Return 400
                return response()->json([], 400);
            }
        } else {
            // Return 400
            return response()->json([], 400);
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
        if ($request->isMethod('PATCH')) {
            // Check for requested changes to fillable values
            $orderAttr = $order->getAttributes();
            $updatedAttr = [];
            $valid = true;
            // TODO: If request has an unexpected input item key, $valid = false
            foreach ($orderAttr as $key => $value) {
                if ($request->has($key)) {
                    $column = $request->input($key);
                    if ($column != $value) {
                        // TODO: input validation
                        $updatedAttr[$key] = $column;
                    }
                }
            }
            if (!empty($updatedAttr) && $valid) {
                // Update order
                $order->fill($updatedAttr);
                // Save order
                if ($order->save()) {
                    return $order;
                } else {
                    return response()->json([], 400);
                }
            } else {
                return response()->json([], 400);
            }
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

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve customers from the table
        $customers = Customer::all();
        return $customers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        // Responds to /api/customer/{id}
        // Returns the customer with that id if one exists
        //  if not, returns 404
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        // This should only respond to patch requests since you shouldn't
        // be able to update a customer's id, updated_at, or created_at columns
        if ($request->isMethod('PATCH'))
        {
            // Check for requested changes to fillable values
            $customerAttr = $customer->getAttributes();
            $updatedAttr = [];
            foreach ($customerAttr as $key => $value) {
                if ($request->has($key))
                {
                    $column = $request->input($key);
                    if ($column != $value)
                    {
                        // TODO: input validation
                        $updatedAttr[$key] = $column;
                    }
                }
            }
            if (!empty($updatedAttr))
            {
                // Update customer
                $customer->fill($updatedAttr);
                // Save customer
                $customer->save();
            }
            return $customer;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    private $validation = [
        "first_name" => ['required', 'string', 'max:255'],
        "last_name" => ['required', 'string', 'max:255'],
        "email" => ['required', 'string', 'max:255', 'email', 'unique:customers'],
        "address_1" => ['required', 'string', 'max:255'],
        "address_2" => ['string', 'max:255', 'nullable'],
        "city" => ['required', 'string', 'max:255'],
        "state" => ['required', 'string', 'max:255'],
        "country" => ['required', 'string', 'max:255'],
        "zipcode" => ['required', 'string', 'max:255', 'regex:/^\d{5}(?:[- ]?\d{4})?$/']// TODO: Implement postal codes from countries other than the US
    ];

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
        // Validate inputs
        $validator = Validator::make($request->input(), $this->validation);
        if (!$validator->fails()) {
            // Save customer
            $customer = new Customer($validator->validated());
            if ($customer->save()) {
                // Customer saved
                return $customer;
            } else {
                // Return 400
                return response()->json($validator->errors(), 400);
            }
        } else {
            // Check if the validator failed due to a duplicate email address
            $emailErrors = $validator->errors()->get('email');
            if (in_array("The email has already been taken.", $emailErrors)) {
                // Return 409: Conflict
                return response()->json($validator->errors(), 409);
            }
            // Return 400
            return response()->json($validator->errors(), 400);
        }
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
        //  if not, returns 404 automatically
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
        if ($request->isMethod('PATCH')) {
            // Create an updated customer model
            $customer->fill($request->input());
            // Change 'unique' rule to ignore this customer's id
            $validationRules = $this->validation;
            $validationRules['email'][count($validationRules['email']) - 1] = Rule::unique('customers')->ignore($customer);
            // Validate inputs
            $validator = Validator::make($customer->getAttributes(), $validationRules);
            if (!$validator->fails()) {
                $customer->fill($validator->validated());
                if ($customer->save()) {
                    // Customer saved
                    return $customer;
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if ($customer->delete()) {
            return response()->json();
        } else {
            return response()->json([], 400);
        }
    }

    public function orders(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        return $customer->orders;
    }
}

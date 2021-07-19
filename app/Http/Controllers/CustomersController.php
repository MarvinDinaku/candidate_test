<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderTags;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::where('customers.status',1)
            ->paginate(10);

        $active = 1;

        return view('customers.index', compact('customers','active'));


    }

    public function indexCanceled()
    {
        $customers = Customer::where('customers.status',0)
            ->paginate(10);

        $active = 0;

        return view('customers.index', compact('customers','active'));

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create')->withCustomer(new Customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate customer form fields
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'company' => 'required|max:255',
        ]);

        $customer = Customer::create($validatedData);

        return redirect()->route('customers.edit', $customer)->withMessage('Customer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
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
        //Validate input before customer update
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);

        $customer->update($validatedData);

        return view('customers.edit', compact('customer'))->withMessage('Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        //Cancel customer
        $customer = Customer::find($id);
        $customer->status = 0;
        $customer->update();

        $order = Order::where('customer_id',$customer->id)->update(['status' => 0]);

        return redirect()->route('customers.index')->withMessage('Customer canceled successfully');
    }

    //Enable customer
    public function enable($id)
    {
        $customer = Customer::find($id);
        $customer->status = 1;
        $customer->update();

        $order = Order::where('customer_id',$customer->id)->update(['status' => 1]);

        return redirect()->route('customers.canceled')->withMessage('Customer enabled successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        //Delete customer orders
        $orders = Order::where('customer_id',$customer->id)->delete();

        return redirect()->route('customers.index')->withMessage('Customer deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\Order;
use App\Models\OrderTags;
use App\Models\Tag;
use Illuminate\Http\Request;
use function Sodium\compare;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::join('customers','customers.id','orders.customer_id')
            ->select("orders.*", 'customers.first_name as first_name',"customers.last_name as last_name")
            ->where('orders.status',1)
            ->paginate(10);

        $active = 1;
        return view('orders.index', compact('orders','active'));
    }

    //function that redirects to canceled orders
    public function indexCanceled()
    {
        $orders = Order::where('orders.status',0)
            ->paginate(10);

        $active = 0;
        return view('orders.index', compact('orders','active'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers  = Customer::where('customers.status',1)->get();
        $tags = Tag::all();
        $order_tags_arr[]=null;
        $order = New Order();
        return view('orders.create', compact('order','customers','tags','order_tags_arr'));
       // return view('orders.create')->with(new Order,compact($customers));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate fields on order creation
        $validatedData = $request->validate([
            'order_name' => 'required|max:255',
            'order_description' => 'required|max:255',
            'order_cost' => 'required|numeric',
            'customer_id'=>'required',

        ]);

        //Store new order
        $order = new Order();
        $order->title = $request->order_name;
        $order->description = $request->order_description;
        $order->cost = $request->order_cost;
        $order->customer_id = $request->customer_id;
        $order->status = 1;

        $order->save();

        $last_id = $order->id;

        //Store tags in Order_Tag table
        if($request->tags) {
            if ($request->tags) {
                foreach ($request->tags as $tag) {
                    $order_tags = new OrderTags();
                    $order_tags->tag_id = $tag;
                    $order_tags->order_id = $last_id;
                    $order_tags->save();
                }
            }
        }


        return redirect()->route('orders.index')->withMessage('Order created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $customers  = Customer::where('customers.status',1)->get();
        $tags = Tag::all();
        $order_tags = OrderTags::select('tag_id')->where('order_id',$order->id)->get()->toArray();

        $order_tags_arr[]=null;
        //Convert double array into one single array! User foreach for better performance than merge
        foreach($order_tags as $k => $v) {
            $order_tags_arr[] = $v['tag_id'];
        }


        return view('orders.edit', compact('order','customers','tags','order_tags_arr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {

        //Validate fields before update
        $validatedData = $request->validate([
            'order_name' => 'required|max:255',
            'order_description' => 'required|max:255',
            'order_cost' => 'required|numeric',
        ]);

        $order->update($request->all());

        //delete all current order tags and update with new ones
        $delete_order_tags = OrderTags::where('order_id',$order->id)->delete();

        //save the new tags choosen
        if($request->tags) {
            foreach ($request->tags as $tag) {
                $order_tags = new OrderTags();
                $order_tags->tag_id = $tag;
                $order_tags->order_id = $order->id;
                $order_tags->save();
            }
        }
        $customers  = Customer::where('customers.status',1)->get();
        $tags = Tag::all();

        $order_tags = OrderTags::select('tag_id')->where('order_id',$order->id)->get()->toArray();
        $order_tags_arr[]=null;
        foreach($order_tags as $k => $v) {
            $order_tags_arr[] = $v['tag_id'];
        }

        return view('orders.edit', compact('order','customers','tags','order_tags_arr'))->with('successMsg','Order is updated .');
    }

    //Cancel orders but do not remove from storage
    //Orders always revocable

    public function cancel($id)
    {
        $order = Order::find($id);
        $order->status = 0;
        $order->update();


        return redirect()->route('orders.index')->withMessage('Order canceled successfully');
    }

    //Function to enable canceled order
    public function enable($id)
    {
        $order = Order::find($id);

        //check if user is canceled otherwise update order status
        $custromer = Customer::find($order->customer_id);
        $customer_status = $custromer->status;

        //Check if order customer is active before enabling
        if($customer_status == 1){
            $order->status = 1;
            $order->update();
            return redirect()->route('orders.canceled')->withMessage('Order enabled successfully');

        }else{

            return redirect()->route('orders.canceled')
                ->withErrors(['Orders customer is inactive! Please activate customer first!']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->withMessage('Order deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function cart(){
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }
    public function add_cart(Request $request){
        Cart::instance('cart')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Products');
        return redirect()->back();
    }

    public function bok_cart($rowId){
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }
    public function dok_cart($rowId){
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }
    public function remove_cart($rowId){
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }
    public function empty_cart(){
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }


    public function apply_coupon_code(Request $request){
        $coupon_code = $request->coupon_code;
    if(isset($coupon_code))
    {
        $coupon = Coupon::where('code',$coupon_code)->where('expiry_date','>=',Carbon::today())->where('cart_value','<=',Cart::instance('cart')->subtotal())->first();
        if(!$coupon)
        {
            return back()->with('error','Invalid coupon code!');
        }
        Session()->put('coupon',[
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ]);
        $this->calculateDiscount();
        return back()->with('status','Coupon code has been applied!');
    }
    else{
        return back()->with('error','Invalid coupon code!');
    }
    }

    public function calculateDiscount(){
        $discount = 0;
    if(Session::has('coupon'))
    {
        if(Session::get('coupon')['type'] == 'fixed')
        {
            $discount = Session::get('coupon')['value'];
        }
        else
        {
            $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value'])/100;
        }

        $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
        $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax'))/100;
        $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

        Session::put('discounts',[
            'discount' => number_format(floatval($discount),2,'.',''),
            'subtotal' => number_format(floatval(Cart::instance('cart')->subtotal() - $discount),2,'.',''),
            'tax' => number_format(floatval((($subtotalAfterDiscount * config('cart.tax'))/100)),2,'.',''),
            'total' => number_format(floatval($totalAfterDiscount + $taxAfterDiscount),2,'.','')
        ]);
        }
    }

    public function remove_coupon_code(){
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success','Coupon has been removed!');
    }


    public function checkout(){
        if(!Auth::check()){
            return redirect()->route('login');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault',1)->first();
        return view('checkout', compact('address'));
    }

//    public function place_an_order(Request $request){
//     $user_id = Auth::user()->id;

//     $address = Address::where('user_id',$user_id)->where('isdefault',true)->first();
//     if(!$address){
//         $request->validate([
//             'name' => 'required|max:100',
//             'phone' => 'required|numeric|digits:10',
//             'zip' => 'required|numeric|digits:6',
//             'state' => 'required',
//             'city' => 'required',
//             'address' => 'required',
//             'locality' => 'required',
//             'landmark' => 'required'
//         ]);

//         $address = new Address();
//         $address->user_id = $user_id;
//         $address->name = $request->name;
//         $address->phone = $request->phone;
//         $address->zip = $request->zip;
//         $address->state = $request->state;
//         $address->city = $request->city;
//         $address->address = $request->address;
//         $address->locality = $request->locality;
//         $address->landmark = $request->landmark;
//         $address->country = '';
//         $address->isdefault = true;
//         $address->save();
//     }

//     $this->setAmountForCheckout();

//     $order = new Order();
//     $order->user_id = $user_id;
//     $order->subtotal = Session::get('checkout')['subtotal'];
//     $order->discount = Session::get('checkout')['discount'];
//     $order->tax = Session::get('checkout')['tax'];
//     $order->total = Session::get('checkout')['total'];
//     $order->name = $address->name;
//     $order->phone = $address->phone;
//     $order->locality = $address->locality;
//     $order->address = $address->address;
//     $order->city = $address->city;
//     $order->state = $address->state;
//     $order->country = $address->country;
//     $order->landmark = $address->landmark;
//     $order->zip = $address->zip;
//     $order->save();

//     foreach(Cart::instance('cart')->content() as $item)
//     {
//         $orderItem = new OrderItem();
//         $orderItem->product_id = $item->id;
//         $orderItem->order_id = $order->id;
//         $orderItem->price = $item->price;
//         $orderItem->quantity = $item->qty;
//         $orderItem->save();
//     }if($request->mode == "card"){

//        //

//     }elseif($request->mode == "paypal"){

//        //

//     }elseif($request->mode == "cod"){
//     $transaction = new Transactions();
//     $transaction->user_id = $user_id;
//     $transaction->order_id = $order->id;
//     $transaction->mode = $request->mode;
//     $transaction->status = "pending";
//     $transaction->save();
//     }


//     Cart::instance('cart')->destroy();
//     Session::forget('checkout');
//     Session::forget('coupon');
//     Session::forget('discounts');
//     Session::put('order_id', $order->id);
//     return redirect()->route('cart.order.confirmation');
//    }


//    public function setAmountForCheckout()
// {
//     if(!Cart::instance('cart')->content()->count() > 0)
//     {
//         Session::forget('checkout');
//         return;
//     }

//     if(Session::has('coupon'))
//     {
//         Session::put('checkout',[
//             'discount' => Session::get('discounts')['discount'],
//             'subtotal' =>  Session::get('discounts')['subtotal'],
//             'tax' =>  Session::get('discounts')['tax'],
//             'total' =>  Session::get('discounts')['total']
//         ]);
//     }
//     else
//     {
//         Session::put('checkout',[
//             'discount' => 0,
//             'subtotal' => Cart::instance('cart')->subtotal(),
//             'tax' => Cart::instance('cart')->tax(),
//             'total' => Cart::instance('cart')->total()
//         ]);
//     }
// }

//             public function confirmation()
//             {
//                 if(Session::has('order_id')){
//                 $order = Order::find(Session::get('order_id'));
//                 return view('order-confirmation',compact('order'));
//                 };
//             return redirect()->route('cart');
//             }



       public function place_order(Request $request){
        $user_id = Auth::user()->id;
        $address = Address::where('user_id',$user_id)->where('isdefault',true)->first();

        if(!$address){
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => 'required|numeric|digits:6',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required'
            ]);

            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country = 'India';
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save();
        }
        $this->setAmountForCheckout();


            $order = new Order();
            $order->user_id = $user_id;
            $order->subtotal = Session::get('checkout')['subtotal'];
            $order->discount = Session::get('checkout')['discount'];
            $order->tax = Session::get('checkout')['tax'];
            $order->total = Session::get('checkout')['total'];
            $order->name = $address->name;
            $order->phone = $address->phone;
            $order->locality = $address->locality;
            $order->address = $address->address;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->country = $address->country;
            $order->landmark = $address->landmark;
            $order->zip = $address->zip;
            $order->save();


        foreach(Cart::instance('cart')->content() as $item){
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        if($request->mode == "cart"){
            //
        }

       elseif($request->mode == "paypal"){
            //
        }

        elseif($request->mode == "cod"){
            $transaction = new Transactions();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
        }


           Cart::instance('cart')->destroy();
           Session::forget('checkout');
           Session::forget('coupon');
           Session::forget('discounts');
           Session::put('order_id', $order->id);
           return redirect()->route('cart.order.confirmation');
       }


       public function setAmountForCheckout(){
        if(!Cart::instance('cart')->content()->count() > 0){
            Session::forget('checkout');
            return;
        }

        if(Session::has('coupon')){
            Session::put('checkout',[
                'discount' => Session::get('discounts')['discount'],
                'subtotal' =>  Session::get('discounts')['subtotal'],
                'tax' =>  Session::get('discounts')['tax'],
                'total' =>  Session::get('discounts')['total'],
            ]);
        }

        else{
            Session::put('checkout',[
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total(),
            ]);
        }

       }


       public function confirmation(){
        if(Session::has('order_id')){
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart');
       }

}
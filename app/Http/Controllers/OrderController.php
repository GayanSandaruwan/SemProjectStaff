<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Item;
use App\Order;

use App\ReturnItems;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class OrderController extends Controller
{
    
    public function getOrders(){
        $orders = Order::all();
        $heading = "Orders Table";
        return view('orderTable',['orders'=>$orders, 'heading'=>$heading]);
    }
    
    public function newOrders(){
        $orders = Order::all();
        foreach ($orders as $order){
            if($order->Cancel=="New Order"){
                $newOrder[]=$order;
            }
        }
        $heading = "New Orders Table";
        return view('orderTable',['orders'=>$newOrder, 'heading'=>$heading]);
    }

    public function proceedOrders(){
        $orders = Order::all();
        foreach ($orders as $order){
            if($order->Cancel=="Proceeded"){
                $newOrder[]=$order;
            }
        }
        if (!isset($newOrder)){
            $newOrder=array();
            $heading = "No Proceed Orders";
            return view('orderTable',['orders'=>$newOrder, 'heading'=>$heading]);
        }
        $heading = "Proceed Orders Table";
        return view('orderTable',['orders'=>$newOrder, 'heading'=>$heading]);
    }

    public function cancelOrders(){
        $orders = Order::all();
        foreach ($orders as $order){
            if($order->Cancel=="Cancelled"){
                $newOrder[]=$order;
            }
        }
        if (!isset($newOrder)){
            $newOrder=array();
            $heading = "No Cancelled Orders";
            return view('orderTable',['orders'=>$newOrder, 'heading'=>$heading]);
        }

        $heading = "Cancelled Orders Table";
        return view('orderTable',['orders'=>$newOrder, 'heading'=>$heading]);
    }

    public function FinishOrders(){
        $orders = Order::all();
        foreach ($orders as $order){
            if($order->Cancel=="Finished"){
                $newOrder[]=$order;
            }
        }
        if (!isset($newOrder)){
            $newOrder=array();
            $heading = "No Finished Orders";
            return view('orderTable',['orders'=>$newOrder, 'heading'=>$heading]);
        }
        $heading = "Finished Orders Table";
        return view('orderTable',['orders'=>$newOrder, 'heading'=>$heading]);
    }

    public function returnOrders(){
        $return = ReturnItems::all();
        foreach ($return as $re){
            $orders[]=Order::where('id',$re->order_id)->first();
        }
        if (!isset($orders)){
            $orders = array();
            $heading = "Returned Orders Table";
            return view('orderTable',['orders'=>$orders, 'heading'=>$heading]);
        }
        $heading = "Returned Orders Table";
        return view('orderTable',['orders'=>$orders, 'heading'=>$heading]);
    }
    
    public function orderDetail($id){
        return view('orderDetails',['orderID'=>$id]);
    }
    
    public function returnAccept($id){
        Order::where('id',$id)->update(['Cancel'=>"Returned"]);
        return redirect()->route('orderTable');
    }
    
    public function returnReject($id){
        Order::where('id',$id)->update(['Cancel'=>"Finished"]);
        return redirect()->route('orderTable');
    }

    public function proceedCancel($id){
        Order::where('id',$id)->update(['Cancel'=>"Cancelled"]);
        return redirect()->route('orderTable');
    }

    public function proceedFinish($id){
        Order::where('id',$id)->update(['Cancel'=>"Finished"]);
        return redirect()->route('orderTable');
    }

    public function newAccept($id){
        Order::where('id',$id)->update(['Cancel'=>"Proceeded"]);
        $cartitems = CartItem::where('order_id',$id)->get();
        foreach ($cartitems as $ci){
            $item = Item::where('id',$ci->itemID)->first();
            $newQuantity = $item->quantity - $ci->quantity;
            Item::where('id',$item->id)->update(['quantity'=>$newQuantity]);
        }
        return redirect()->route('orderTable');
    }

    public function newReject($id){
        Order::where('id',$id)->update(['Cancel'=>"Cancelled"]);
        return redirect()->route('orderTable');
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemsController extends Controller
{
    public function index (){
        $orderitems= OrderItem::with(['order','product'])->get();

        return view ('admin.orderitems.index',compact('orderitems'));
    }
}

<?php

namespace App\Presentation\Controllers;

use App\Domain\Entities\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function orders(): View
    {
        $orders = Order::with('items.product', 'coupon')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.orders', compact('orders'));
    }
} 
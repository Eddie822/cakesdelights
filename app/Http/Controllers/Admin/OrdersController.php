<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with('user') // Cargamos al usuario para mostrar el nombre
            ->latest()
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }
}

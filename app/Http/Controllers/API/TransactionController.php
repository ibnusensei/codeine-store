<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 5);
        $status = $request->input('status');

        if ($id) {
            $data = Transaction::with(['items.product'])->find($id);

            if ($data) {
                return ResponseFormatter::success(
                    $data,
                    'Data Retrieved Successfully'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Sorry, No Data',
                    404
                );
            }
        }

        $data = Transaction::with(['items.product'])->where('users_id', Auth::user()->id);

        if ($status) {
            $data->where('status', $status);
        }

        return ResponseFormatter::success(
            $data->paginate($limit),
            'Data Retrieved Successfully'
        );
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:products,id',
            'total_price' => 'required',
            'shipping_price' => 'required',
            'status' => 'required|in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED',

        ]);

        $data = Transaction::create([
            'users_id' => Auth::user()->id,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'shipping_price' => $request->shipping_price,
            'status' => $request->status
        ]);

        foreach ($request->items as $product) {
            TransactionItem::create([
                'users_id' => Auth::user()->id,
                'products_id' => $product['id'],
                'transactions_id' => $data->id,
                'quantity' => $product['quantity']
            ]);
        }

        return ResponseFormatter::success(
            $data->load('items.product'),
            'Transaction Successfully'
        );
    }
}

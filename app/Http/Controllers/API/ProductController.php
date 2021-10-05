<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if ($id) {
            $data = Product::with(['category', 'galleries'])->find($id);

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

        $data = Product::with(['category', 'galleries']);

        if ($name) {
            $data->where('name', 'like', '%' .$name. '%');
        }

        if ($description) {
            $data->where('description', 'like', '%' .$description. '%');
        }

        if ($tags) {
            $data->where('tags', 'like', '%' .$tags. '%');
        }

        if ($price_from) {
            $data->where('price', '>=', $price_from);
        }

        if ($price_to) {
            $data->where('price', '<=', $price_to);
        }

        if ($categories) {
            $data->where('category', '>=', $categories);
        }

        return ResponseFormatter::success(
            $data->paginate($limit),
            'Data Retrieved Successfully'
        );

    }
}

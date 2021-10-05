<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $show_product = $request->input('show_product');

        if ($id) {
            $data = ProductCategory::with(['products'])->find($id);

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

        $data = ProductCategory::query();

        if ($name) {
            $data->where('name', 'like', '%' .$name. '%');
        }

        if ($show_product) {
            $data->with('products');
        }

        return ResponseFormatter::success(
            $data->paginate($limit),
            'Data Retrieved Successfully'
        );
    }
}

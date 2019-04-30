<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
use Yajra\DataTables\DataTables;
session_start();

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        $employeeId = Session::get('employeeId');
        if ($employeeId == NULL) {
            return Redirect::to('/logout')->send();
        }
    }

    public function index()
    {
        return view('products');
    }

    public function getProductAll(){            
        $all_published_product = \App\Product::where('availableQty','!=',0)->get();
        return $all_published_product;

    }
    public function product_ss()
    {
        
        $product = Product::where('availableQty','!=',0)->get();
        return Datatables::of($product)
            ->editColumn('pName','{{$pName}}({{$size}})')
              ->addColumn(
                  'action','<button data-toggle="popover"
                   data-trigger="hover" data-content="Stock:
                   {{$availableQty}}" title="Available stock"
                   class="btn-primary btn btn-sm" onclick=\'select_pro_gtter("{{$ID}}",
                   "{{$pName}}","{{$availableQty}}","{{$size}}","{{$price}}")\'>
                   <i class="fa fa-mail-forward"></i></button>')
              ->make();
    }

    public function store(Request $request)
    {         
        $products = new Product($request->data);
        $products->availableQty= $products->quantity;
        $products->save();
        return $products;

    }


    public function manage_products()
    {
        
        return view('products');
    }

    public function allProducts()
    {
              
        return Product::all();
    }

    public function update_product(Request $request, $id)
    {
              
        return Product::where('ID',$id)->update($request->data);
    }

    public function update_product_stock(Request $request, $id)
    {
              
        return Product::where('ID',$id)->update($request->data);
    }

    public function manage_products_ss()
    {
              
        return Product::where('availableQty','!=',0)->get();
    }
}

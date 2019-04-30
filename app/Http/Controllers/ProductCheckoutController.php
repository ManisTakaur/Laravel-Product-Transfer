<?php

namespace App\Http\Controllers;

use App\ProductCheckout;
use App\Employee;
use App\Product;
use App\CheckoutDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
use Illuminate\Foundation\Exceptions\Handler;
session_start();

class ProductCheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        //$employeeId = Session::get('employeeId');
        $employeeId=Session::get('employeeId');
        if($employeeId == NULL)
        {
            return Redirect::to('/login')->send();
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function createcheckout(){
        return view("createcheckout");
    }
    public function savecheckout(Request $request){
      //Sreturn $request;
        DB::beginTransaction();
        $input = $request->all();
        $checkout_id = CheckoutDetail::select('checkoutId')
            ->orderBy('checkoutId', 'desc')
            ->get();


       $max = sizeof($input['data']);

        if(sizeof($checkout_id)==0)
            $checkout_checkout_id=20000002;
        else
            $checkout_checkout_id= $checkout_id[0]->checkoutId;

      //try {

        for($i = 0; $i < $max-1;$i++)
        {
            $data = new CheckoutDetail();
            $data['checkoutId']= $checkout_checkout_id+1;
            $data['productId'] = $input['data'][$i]['proID'];
            $data['checkoutQuantity'] = $input['data'][$i]['quantity'];
            $data['checkoutPrice'] = $input['data'][$i]['price'];
            $data['checkoutAmount'] = ceil($input['data'][$i]['quantity']*$input['data'][$i]['price']);
            $data->save();
            $data_stock_available = Product::find($input['data'][$i]['proID']);
            Product::where('ID', $input['data'][$i]['proID'])
                ->update(['availableQty' => $data_stock_available->availableQty- $input['data'][$i]['quantity']]);
            
        }
        $datacheckout = new ProductCheckout();
        $datacheckout['id'] = $checkout_checkout_id+1;
        $datacheckout['grand_total'] =$input['data'][$max-1]['grand_total'];
        $datacheckout['supplier_id'] = Session::get('categoryEmployId');
        $datacheckout['company_id'] = 1;

        $datacheckout->save();

        Session::put('message', 'checkout Created Successfully!');
        // } catch (ValidationException $e) {
        //     DB::rollback();
        //     return response()->json(['message' => 'DB problem'], 500);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json(['message' => 'DB problem'], 500);
        // }
        DB::commit();
        return response()->json(['message' => 'Successfully Data Saved',"id"=>$checkout_checkout_id+1], 201);

    }
    
    public function managecheckout(){
        
        $checkout = ProductCheckout::orderBy('id','asc')->get();
        
        return view("Managecheckout",compact(['checkout']));
    }

   

    public function checkoutDetails($ID){
        return $checkouts = CheckoutDetail::with(['Product'])->where('checkoutId',$ID)->get();
    
    }
    
    

}

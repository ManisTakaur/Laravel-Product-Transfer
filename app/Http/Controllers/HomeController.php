<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Appointment;
use App\Product;
use App\Shipment;
use App\Customer;
use App\Softwarepayment;
use App\Supplyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Carbon\Carbon;
use Session;
session_start();

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details = $this->detailsDashboard();
        return view('welcome',compact('details'));
    }

    public function detailsDashboard(){
        $date = date("m/d/y");
        $productAvailable = Product::all()->where('availableQty','!=',0)->sum('availableQty');
        $payment =$this->checkSoftwarePayment();
        $dateMonth = date("m");
        $dateYear = date("Y");

        $profit_info = $this->getSalesInfo($dateMonth,$dateYear);

        return compact(['invoice','productAvailable','payment','profit_info']);
    }

    public function getSalesInfo($monthID,$yearID){
      $salesInfo = Invoice::whereMonth('created_at', $monthID)->whereYear('created_at',  $yearID)
          ->distinct()->get();
      $AppointmentsalesInfo = Appointment::where('appointment_status',1)->whereMonth('updated_at',$monthID)->whereYear('updated_at', $yearID)
          ->distinct()->get();
        //   $productCheckoutInfo = CheckoutTotal::select(DB::raw('sum(totalPrice) as sumOfTotalPrice'))
        //   ->whereYear('created_at', '=', $yearID)
        //   ->get()
        //   ->groupBy(function ($date) {
        //       return Carbon::parse($date->created_at)->format('m');
        //   });    

          $total_vat = Invoice::whereMonth('created_at', $monthID)->whereYear('created_at',$yearID)->sum('vat_value');
          $total_vat_Appointment = Appointment::where('appointment_status',1)->whereMonth('updated_at',  $monthID)->whereYear('updated_at', $yearID)->sum('vat_value');
          $total_vat = $total_vat+$total_vat_Appointment;

          $total_offer = Invoice::whereMonth('created_at', $monthID)->whereDate('created_at',$yearID)->sum('special_discount');
          $total_offer_Appointment = Appointment::where('appointment_status',1)->whereMonth('updated_at',$monthID)->whereYear('updated_at', $yearID)->sum('special_discount');
          $total_offer = $total_offer+$total_offer_Appointment;

          $total_service_sold_App = Appointment::where('appointment_status',1)
          ->whereMonth('updated_at', $monthID)->whereYear('updated_at',$yearID)->get();
        //  return $total_service_sold_App;
          $totalSalesOfmonthProduct=0;
          $totalSalesOfmonthService=0;
          $totalcostOfgoodSold =0;
        //  return $salesInfo;
          foreach ($salesInfo as $valueS) {
              foreach ($valueS->sales as $value){
                 // $totalcostOfgoodSold =$totalcostOfgoodSold+($value->product->price*$value->saleQuantity);
                 if($value->type=='product'){
                     $totalSalesOfmonthProduct= $totalSalesOfmonthProduct+ $value->saleAmount;
                     $totalcostOfgoodSold =$totalcostOfgoodSold+($value->product->price*$value->saleQuantity);
                 }
                 else if($value->type=='service')
                    $totalSalesOfmonthService= $totalSalesOfmonthService+ $value->saleAmount;
              }
          }
          foreach ($total_service_sold_App as $valueSAp) {
                    $totalSalesOfmonthService= $totalSalesOfmonthService+ $valueSAp->total_price;

          }

        return compact(['totalSalesOfmonthProduct','totalSalesOfmonthService','totalcostOfgoodSold','total_vat']);
    }
    public function checkSoftwarePayment(){
        $paymentDetails = Softwarepayment::select('*')->orderBy('id','desc')->limit(1)->get();
        $code = $paymentDetails[0]->date;

       // $code =base64_encode($code);
        //$code =base64_decode($code);



        //return $code;
        $decode =explode("-", $code);
        //return $decode;

        $start =explode(",", $decode[1]);
        return $start;
        $end =explode(",",date("Y,m,d"));
        $messageDays = ((int)$start[2] - (int)$end[2]);
        $messageMonth = ((int)$start[1] - (int)$end[1]);
        $messageYear = ((int)$start[0] - (int)$end[0]);
             $message = abs($messageDays)." Days " .abs($messageMonth)." Months ".abs($messageYear)." Year Left.";
        $noticeOffCounter =3;
        if((int)$start[0]-(int)$end[0]<=0) {
            $noticeOffCounter-=1;
            if((int)$start[1]-(int)$end[1]<=0) {
                $noticeOffCounter-=1;
                $message = abs($messageDays)." Days " .abs($messageMonth)." Months ".abs($messageYear)." Year Left.";
                if ((int)$start[2] - (int)$end[2]<=0) {
                    $noticeOffCounter-=1;
                    $message = "0 days Left. Make your Payment";

                }
            }
        }
        return compact(['message','noticeOffCounter','messageDays']);
    }
    public function saveSoftwarePayment(Request $request){

        if(Softwarepayment::where('code',$request->code)->exists()){
            Softwarepayment::where('code', $request->code)
                ->update(['mode' => 1]);
            Session::put('message','You entered previous subscription');
            return Redirect::to('/settings');

        }
        $date =base64_decode($request->code);
        $data =new Softwarepayment();
        $data['code'] = $request->code;
        $data['date'] =$date;
        $data->save();
        return Redirect::to('/settings');

    }

    public function checkcustomerspecialday(){
        //$test=Carbon::now();
       //$t=$test->addDays(5);

        $dt= Carbon::tomorrow();
        $month=$dt->month;
        $day=$dt->day;

       // return $day;
        if($day<10){
            $formatDate=$month . '/0' . $day;
           // return $formatDate;
        }
        else{
            $formatDate=$month . '/' . $day;
           // return $formatDate;
        }


        return Customer::where('customerSpecialDateTwo',$formatDate)
                       ->orWhere('customerSpecialDate',$formatDate)
                       //->first() ;
                       ->get();

    }
}

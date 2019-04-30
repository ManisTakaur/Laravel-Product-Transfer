<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
//use App\Http\Requests;
use Cache;

use DB;
use Session;
session_start();
class AdminController extends Controller
{
    
    public function index()
    {
        $employeeId=Session::get('employeeId');
        if($employeeId != NULL)
        {
            return Redirect::to('/create-checkout')->send();
        }
        else{
            return Redirect::to('/login')->send();
        }
    }
    public function login(){
        return view("admin.login");

    }
    public function admin_login_check(Request $request)
    {
        $employee=$request->username;
        $employeePassword=md5($request->employeePassword);
        if($employeePassword=="")
        {
          Session::put('exception','Password is Empty!');
          return Redirect::to('/');
        }

        $result = Employee::join('tbl_categoryemploy', 'tbl_employee.categoryEmployId', '=', 'tbl_categoryemploy.categoryEmployId')
            ->where('username',$employee)
            ->where('employeePassword',$employeePassword)
            ->first();

        if($result)
        {
          
            Session::put('employeeId',$result->employeeId);
            Session::put('categoryEmployId',$result->categoryEmployId);
            Session::put('categoryName',$result->categoryEmployType);
            Session::put('employeeName',$result->employeeName);
            Session::put('employeePassword',$result->employeePassword);
            
            Session::put('date',$result->date);
            
            if( $result->categoryEmployId == 4){ 
                return Redirect::to('/view-checkout');
            }
            else{
                return Redirect::to('/create-checkout');
            }    

        }
        else{
            Session::flush();
            Session::put('exception','Username or password incorrect!');
            return Redirect::to('/');
        }
    }

    public function logout() {
        Session::flush();
        Cache::flush();
        Session::put('message','Successfully Logout!');
        return Redirect::to('/');
    }
}

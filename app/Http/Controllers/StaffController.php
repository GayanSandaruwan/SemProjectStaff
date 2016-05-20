<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class StaffController extends Controller
{


    public $Pf_Id;
    public function getDashbord()
    {
        return view('dashbord');

    }


    public function postSignUp(Request $request) {

        $Pf_Id=$this->create_Pf_Id();
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $email = $request['email'];
        $phone = $request['phone'];
        $password = bcrypt($request['password1']);
        $adress1 = $request['adress1'];
        $adress2 = $request['adress2'];
        $adress3 = $request['adress3'];
        $adress4 = $request['adress4'];
//        $privilageLevel=$request['privilageLevel'];
//        $username=$request['username'];

        $staff = new Staff();

        $staff->first_name=$first_name;
        $staff->last_name =$last_name;
        $staff->email=$email;
        $staff->phone=$phone;
        $staff->password=$password;
        $staff->adress1=$adress1;
        $staff->adress2=$adress2;
        $staff->adress3=$adress3;
        $staff->adress4=$adress4;
        $staff->username= $Pf_Id;
       // $staff->username=$username;

        $staff->save();
        return view('staffsignin');

    }
    public function postSignIn(Request $request){

        if(Auth::attempt(['username'=>$request['username'],'password'=>$request['password']])){
            return redirect()->route('newItem');

        }

        else{
            //$Error=array('error' => 'Password Doesnt match');
            return Redirect::back()->with('Error',"Email and Password didn't match");

        }
    }

    public function create_Pf_Id(){

        $staffCount=Staff::count();
        global $Pf_Id;
        $Pf_Id='PF00'.''.($staffCount+1);
        return $Pf_Id;
    }

    public function getSignUpForm(){
        
        return view('createstaffmember',['Pf_Id'=>$this->create_Pf_Id()]);
//        return redirect()->route('createstaffmember')->with('Pf_Id',$this->create_Pf_Id());
    }
    public function staffSignOut(){

        Auth::logout();
        return view('staffsignin');
           // route('home');
    }
}


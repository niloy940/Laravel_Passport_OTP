<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ParcelAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Nexmo\Laravel\Facade\Nexmo;

class SMSController extends Controller
{
    public function sendSMS(Request $request)
    {
        //using laravel/nexmo-notification-channel

        // request()->user()->notify(new ParcelAssigned());

        $users = User::all();

        $json_smsdata = [];
        foreach ($users as $user) {
            // Notification::send($users, new ParcelAssigned($user->name));

            $name    = $user->name;
            $phone   = $user->phone_number;
            $message = rawurlencode("Hi $name, Thanks for using our app!
                                    Regards,
                                    Niloy
                                    ");
            $json_smsdata[]= ['to'=>$phone,'message'=>$message];
        }

        $smsdata = json_encode($json_smsdata);

//        echo $smsdata;

         $token = '3759db6ae8969db86d37e46ce7b00484';
         $smsdata = $smsdata;

         $url = 'http://api.greenweb.com.bd/api2.php';

         $data = [
             'smsdata' => "$smsdata",
             'token' => "$token"
         ];

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_ENCODING, '');
         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $smsresult = curl_exec($ch);

//        Result
         echo $smsresult;

//        Error Display
         echo curl_error($ch);
    }
}

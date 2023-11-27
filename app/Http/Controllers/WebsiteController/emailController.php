<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyTestMail;


class emailController extends Controller
{
   

    public function sendTestEmail($details,$email)
    {
       
      
        Mail::to($email)->send(new MyTestMail($details));
    }

}

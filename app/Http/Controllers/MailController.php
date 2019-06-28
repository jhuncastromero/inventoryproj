<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class MailController extends Controller
{
    //
    public function send() {

    	$objDemo = new \stdClass();
    	$objDemo->demo_one = 'Sample email value 1';
    	$objDemo->demo_two = 'Sample email value 2';
    	$objDemo->sender = 'System Admin';
    	$objDemo->receiver = 'jhun';

    	Mail::to("jhun@ssagroup.com")->send(new DemoEmail($objDemo));


    }
    public function test(){
    	return view('mails.sample');
    }
}

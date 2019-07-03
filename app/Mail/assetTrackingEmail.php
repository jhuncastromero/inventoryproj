<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class assetTrackingEmail extends Mailable
{
    use Queueable, SerializesModels;

     public $email_asset;
    /**
     * Create a new message instance.
     *
     * @return void
     */

   

    public function __construct($email_asset)
    {
        //
       $this->email_asset = $email_asset;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
     
      return $this->from('ssagroupphit@gmail.com')
               ->subject('SSA Manila Asset Tracking')
               ->view('module3.deployment_it.asset_email_individual');
               
    }
}

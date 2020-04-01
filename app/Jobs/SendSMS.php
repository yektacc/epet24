<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mobile_number;
    public $otp;

    /**
     * Create a new job instance.
     *
     * @param $mobile_number
     * @param $otp
     */
    public function __construct($mobile_number, $otp)
    {
        //
        $this->mobile_number = $mobile_number;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $FROM = "30005367";
        $TO = $this->mobile_number;
        $TEXT = $this->otp;
        $USERNAME = "ysms9453";
        $PASSWORD = "24269734";
        $DOMAIN = "0098";

        $client = new \GuzzleHttp\Client();
        $response = $client->post("http://www.0098sms.com/sendsmslink.aspx?FROM=$FROM&TO=$TO&TEXT=$TEXT&USERNAME=$USERNAME&PASSWORD=$PASSWORD&DOMAIN=$DOMAIN");
    }
}

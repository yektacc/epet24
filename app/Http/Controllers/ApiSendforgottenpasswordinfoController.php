<?php namespace App\Http\Controllers;

		use App\Helper\AppConfig;
		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendforgottenpasswordinfoController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_users";        
				$this->permalink   = "sendforgottenpasswordinfo";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

                try{
                    $user = DB::table('app_users')
                        ->where('mobile_number',$postdata['mobile_number'])
                        ->where('is_active','1')
                        ->first();

                    if(!empty($user)) {

                        $otp = AppConfig::generate_otp_code();
                        $epet = "epet24 verification code: ";
                        //send otp via sms
                        AppConfig::sendSMS($postdata['mobile_number'],$epet.$otp);

                        //delete last otp with this mobile number
                        DB::table('otp_temp')->where('mobile_number',$postdata['mobile_number'])->delete();

                        //insert new otp for this mobile number
                        DB::table('otp_temp')->insert([
                            'mobile_number' => $postdata['mobile_number'],
                            'verification_code' => $otp
                        ]);

                        $data['response_code'] = 0;
                        $data['mobile_number'] = $postdata['mobile_number'];
                        //$data['verification_code'] = $otp;
                    }
                    else{
                        $data['response_code'] = 4;
                        $data['response_message'] = USER_DOES_NOT_EXIST;
                    }

                    $results['api_status'] = 1;
                    $results['api_message'] = 'forgotten password';
                    $results['data'] = $data;
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error';
                }

                echo json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                die;
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}
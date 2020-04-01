<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use App\Helper\AppConfig;

		class ApiEdituserprofileController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_users";        
				$this->permalink   = "edituserprofile";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();

                    DB::table("app_users")
                        ->where("id",$user_id)
                        ->update([
                            "firstname" => $postdata["firstname"],
                            "lastname" => $postdata["lastname"],
                            "national_code" => $postdata["national_code"],
                            "email" => $postdata["email"],
                            "credit_card_number" => $postdata["credit_card_number"],
                            "receive_newsletter" => $postdata["receive_newsletter"]
                        ]);

                    if(isset($postdata['mobile_number'])){
                        $otp = AppConfig::generate_otp_code();

                        //send otp via sms
                        AppConfig::sendSMS($postdata['mobile_number'],$otp);

                        //delete last otp with this mobile number
                        DB::table('otp_temp')->where('mobile_number',$postdata['mobile_number'])->delete();

                        //insert new otp for this mobile number
                        DB::table('otp_temp')->insert([
                            'mobile_number' => $postdata['mobile_number'],
                            'verification_code' => $otp
                        ]);
                        $data['verification_code'] = $otp;
                        $data['mobile_number'] = $postdata['mobile_number'];
                    }

                    $data['response_code'] = 0;

                    $results['api_status'] = 1;
                    $results['api_message'] = 'user profile edited';
                    $results['data'] = $data;

                } catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error';
                }
//
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
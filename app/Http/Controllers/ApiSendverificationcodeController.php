<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendverificationcodeController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "otp_temp";        
				$this->permalink   = "sendverificationcode";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

                try{
		    $otp_record = DB::table('otp_temp')
				->where('mobile_number',$postdata['mobile_number'])
				->first();

                    if ($otp_record->verification_code == $postdata['verification_code']){

                        $data['response_code'] = 0;

                        if($postdata['verification_type'] == "registeration"){
                            //complete registration process and user's session must be saved
                            DB::statement("call complete_registration(?)",
                                [
                                    $postdata['mobile_number']
                                ]
                            );

                            $session_id = DB::table('app_user_sessions')->max('id');
                            $data['session_id'] = $session_id;
                            //todo: send user profile and his shopping cart
                        }
                        else if($postdata['verification_type'] == "changing_profile"){
                            $user_id = DB::table('app_user_sessions')
                                ->where('id',$postdata['session_id'])
                                ->pluck('user_id')
                                ->first();

                            DB::table('app_users')
                                ->where('id',$user_id)
                                ->update([
                                    'mobile_number' => $postdata["mobile_number"]
                                ]);
                        }
                    }
                    else{
                        $data['response_code'] = 2;
                        $data['response_message'] = VERIFICATION_MISMATCHED;
                    }

                    $results['api_status'] = 1;
                    $results['api_message'] = 'verification';
                    $results['data'] = $data;
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'.$exception;
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
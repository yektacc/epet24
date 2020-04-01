<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiUpdateuserfcmtokenController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_users";        
				$this->permalink   = "updateuserfcmtoken";    
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
					'fcm_token' => $postdata['fcm_token']
					]);
					
					$results['api_message'] = "token updated";
				}
				catch (\Exception $exception){
					$results['api_status'] = 0;
					$results['api_message'] = 'server error'. $exception;
				}

				$results['api_status'] = 1;
						
				$results['response_code'] = 0;
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

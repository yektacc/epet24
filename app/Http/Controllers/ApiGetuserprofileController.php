<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetuserprofileController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_users";        
				$this->permalink   = "getuserprofile";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();
                    $user = DB::table("app_users")->where("id",$user_id)->first();
                    $profile = [
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                        'mobile_number' => $user->mobile_number,
                        'credit_card_number' => $user->credit_card_number,
                        'national_code' => $user->national_code
                               ];
                    $results['api_status'] = 1;
                    $results['api_message'] = 'user profile';
                    $results['data'] = ['personal_information' => $profile];

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
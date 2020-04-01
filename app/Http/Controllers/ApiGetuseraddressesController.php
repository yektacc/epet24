<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetuseraddressesController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_customer_addresses";        
				$this->permalink   = "getuseraddresses";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{

                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
			->first();


                    $addresses = DB::table("user_addresses")
					   ->where("user_id",$user_id)
					   ->where("deleted_at",NULL)
                        ->select('address_id','user_id','transferee_name','transferee_mobile_number','province_name','province_id','city_name','city_id','remained','postal_code','editable')
                        ->get();
                    $results['api_status'] = 1;
                    $results['api_message'] = 'user addresses list';
                    $results['data'] = $addresses;

                } catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'.$exception;
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
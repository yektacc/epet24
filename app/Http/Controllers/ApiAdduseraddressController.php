<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiAdduseraddressController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_customer_addresses";        
				$this->permalink   = "adduseraddress";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                    $user_id = DB::table('app_user_sessions')
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();

                    $address_id = DB::table('addresses')->insertGetId([
                        'id' => NULL,
                        'city_id' => $postdata['city_id'],
                        'district_id' => $postdata['district_id'],
                        'remained' => $postdata['address'],
                        'longitude' => NULL, // todo: user select from map
                        'latitude' => NULL, // todo: user select from map
                        'postal_code' => $postdata['postal_code'],
                    ],'id');

                    DB::table("prd_customer_addresses")->insert([
                        'id' => NULL,
                        'user_id' => $user_id,
                        'transferee_name' => $postdata['transferee_name'],
                        'transferee_mobile_number' => $postdata['transferee_mobile_number'],
                        'transferee_address' => $address_id,
                    ]);

                    $data['response_code'] = 0;

                    $results['api_status'] = 1;
                    $results['api_message'] = 'user address added';
                    $results['data'] = $data;

                }
                catch (\Exception $exception){
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
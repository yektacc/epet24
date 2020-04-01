<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendfcrequestController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "fc_requests";        
				$this->permalink   = "sendfcrequest";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
		try{
				$fc_request_id = DB::table('fc_requests')->insertGetId([
						'request_type' => $postdata['request_type'],
						'customer_id' => $postdata['customer_id'],
						'aml_type_id' => $postdata['aml_type_id'],
						'animal_name' =>$postdata['animal_name'],
						'animal_gender' => $postdata['animal_gender'],
						'sterilization' =>$postdata['sterilization'],
						'animal_age' =>$postdata['animal_age'],
						'province_id' => $postdata['province_id'],
						'city_id' => $postdata['city_id'],
						'animal_location' => $postdata['animal_location'],
						'phone_number' => $postdata['phone_number'],
						'time' => $postdata['time'],
						'phone_number' => $postdata['phone_number'],
						'description' => $postdata['description'],
						'is_active' => $postdata['is_active'],
						'valid_until' => $postdata['valid_until'],
						'requestgift' => $postdata['requestgift'],
						],'id');

		    $results['api_status'] = 1;
                    $results['api_message'] = 'request saved';
                    $results['response_code'] = 0;
                    $results['fc_request_id'] = $fc_request_id;
				}
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'. $exception;
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
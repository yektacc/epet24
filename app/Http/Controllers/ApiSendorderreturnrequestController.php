<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use App\Helper\AppConfig;

		class ApiSendorderreturnrequestController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_return";        
				$this->permalink   = "sendorderreturnrequest";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        try{
				if(isset($postdata['session_id']))
				{
					$user_id = DB::table("app_user_sessions")
						->where("id",$postdata['session_id'])
						->pluck("user_id")
						->first();

					$result_query = DB::table('prd_order_return')
						->where('order_id',$postdata['order_id'])
						->where('order_item_id',$postdata['order_item_id'])
						->pluck('id')
						->first();
				}
				if($result_query == null) {
				
					DB::table('prd_order_return')->insert([
						'id' => NULL,
						'order_id' => $postdata['order_id'],
						'order_item_id' => $postdata['order_item_id'],
						'app_user_id' => $user_id,
						'srv_center_id' => $postdata['srv_center_id'],
						'comment' => $postdata['comment']
						]);
					$mobile_number = DB::table('srv_centers')
						->where('id',$postdata['srv_center_id'])
						->pluck('center_mobile')
						->first();
					$order_code = DB::table('prd_orders')
						->where('id',$postdata['order_id'])

						->pluck('order_code')
						->first();
					//send sms to store admin for user return request
					$alert_text = " محموله به شماره سفارش".$order_code."دارای مرجوعی میباشد. لطفا به پنل فروشگاه مرجعه فرمائید  .";
					AppConfig::sendSMS($mobile_number,$alert_text);
					
					
					$results['api_status'] = 1;
					$results['response_code'] = 1;
					$results['api_message'] = "sucsses";
					}
					else {
					$results['api_status'] = 1;
					$results['response_code'] = 2;
					$results['api_message'] = "request added before";
					}
				}
				catch (\Exception $exception){
					$results['api_status'] = 0;
					$results['api_message'] = 'server error'. $exception;
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
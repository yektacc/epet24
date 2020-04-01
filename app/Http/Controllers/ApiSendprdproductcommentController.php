<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendprdproductcommentController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_products_comments";        
				$this->permalink   = "sendprdproductcomment";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				try{
					
					if($postdata['session_id'])
					{
						$user_id = DB::table("app_user_sessions")
							->where("id",$postdata['session_id'])
							->pluck("user_id")
							->first();
						if($postdata['prd_sale_item_id'])
						{
							if($postdata['comment'])
							{
								$result_query = DB::table('prd_products_comments')->insert([
									'prd_sale_item_id' => $postdata['prd_sale_item_id'],
									'app_user_id' => $user_id,
									'comment' => $postdata['comment']
									]);  
								$results['api_message'] = "comment added";
							}
							else
								$results['api_message'] = "comment required";
						}
						else
							$results['api_message'] = "prd_sale_item_id required";
					}
					else
						$results['api_message'] = "session_id required";

				}
				catch (\Exception $exception){
					$results['api_status'] = 0;
					$results['api_message'] = 'server error'. $exception;
				}
			//
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
<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendprdsaleitemscoreController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_sale_item_score";        
				$this->permalink   = "sendprdsaleitemscore";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				try{
					
                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();
										
					$prd_score_id = DB::table('prd_sale_item_score')
					->where('app_user_id',$user_id)
					->pluck('id')  					
					->first();

					if(!$prd_score_id)
					{
						$result_query = DB::table('prd_sale_item_score')->insert([
							'prd_sale_item_id' => $postdata['prd_sale_item_id'],
							'app_user_id' => $user_id,
							'score' => $postdata['score']
							]);  
						$results['api_message'] = "rating added";
					}
					else
					{
						DB::table("prd_sale_item_score")->where("id",$prd_score_id)->update([
							'score' => $postdata['score']
						]);
						$results['api_message'] = "rating edited";
					}
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
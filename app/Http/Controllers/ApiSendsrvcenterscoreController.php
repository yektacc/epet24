<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use carbon\Carbon;

		class ApiSendsrvcenterscoreController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "srv_center_score";        
				$this->permalink   = "sendsrvcenterscore";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
				try{
					
                    $user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
                        ->first();

					$seller_score_id =  DB::table('srv_center_score')
					->where('app_user_id',$user_id)
					->where('srv_center_id',$postdata['srv_center_id'])
					->pluck('id')
					->first();

					if(!$seller_score_id)
					{
						$result_query = DB::table('srv_center_score')->insert([
							'srv_center_id' => $postdata['srv_center_id'],
							'app_user_id' => $user_id,
							'score' => $postdata['score']
							]);  
						
						$avg_score = DB::table('srv_center_score')
								->where('srv_center_id',$postdata['srv_center_id'])
								->avg('score');
								//var_dump($avg_score);
						DB::table('srv_centers')
							->where('id',$postdata['srv_center_id'])
							->update(['center_score' => $avg_score]);
						$results['api_message'] = "rating added";
					}
					else
					{
						DB::table("srv_center_score")
						->where("id",$seller_score_id)
						->update([
						'score' => $postdata['score'],
						'created_at' => Carbon::now()
						]);
						$avg_score = DB::table('srv_center_score')
								->where('srv_center_id',$postdata['srv_center_id'])
								->avg('score');
								//var_dump($avg_score);
						DB::table('srv_centers')
							->where('id',$postdata['srv_center_id'])
							->update(['center_score' => $avg_score]);
						
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
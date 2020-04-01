<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetshoppingordersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_products";        
				$this->permalink   = "getshoppingorders";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
				$where_clause = "1";
				$where_parameters = [];

				$order_status = 4;

				try
				{
					
					if(isset($postdata['session_id'])){
						$user_id = DB::table("app_user_sessions")
							->where("id",$postdata['session_id'])
							->pluck("user_id")
							->first();					
						$where_clause .= " AND (customer_id = ?)";
						$where_parameters[] = $user_id;
					}
					$where_clause .= " AND (status_id = ?)";
					$where_parameters[] = $order_status;

					$query_result = DB::table('shop_orders')
					->join("shop_order_items", 'shop_order_items.order_id','=','shop_orders.id')
					->whereRaw($where_clause, $where_parameters)
					->get();


                    $results['api_status'] = 1;
                    $results['api_message'] = 'shop orders';
                    $results['data'] = $query_result->toArray();
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'.$exception;
                }

                echo json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                die;							
		    }

		}
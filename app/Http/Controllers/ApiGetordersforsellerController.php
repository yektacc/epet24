<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetordersforsellerController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_orders";        
				$this->permalink   = "getordersforseller";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
				try{
                    $where_clause = "1";
                    $where_parameters = [];
                    if(isset($postdata['seller_id'])){
                        $where_clause .= " AND (product_seller_id = ?)";
                        $where_parameters[] = $postdata['seller_id'];
                    }					
					$query_result = DB::table('shop_order_items')
						->whereRaw($where_clause, $where_parameters);
					
					if(isset($postdata['per_page']))
						$query_result = $query_result->paginate($postdata['per_page']);
					
					else
						$query_result = $query_result->get();	
						
					$results['api_status'] = 1;
					$results['api_message'] = 'shop_order_items';
					$results['data'] = $query_result->toArray();
				}
				catch (\Exception $exception){
					$results['api_status'] = 0;
					$results['api_message'] = 'server error'.$exception;
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
<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetproductsofsellerController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "products";        
				$this->permalink   = "getproductsofseller";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
				try{
					$where_clause = "1";
					$where_parameters = [];

					if(isset($postdata['seller_id'])){
						$where_clause .= " AND (seller_center_id = ?)";
						$where_parameters[] = $postdata['seller_id'];
					}

					$query_result = DB::table('products')
						->whereRaw($where_clause, $where_parameters)
						->get();

						$results['api_status'] = 1;
						$results['api_message'] = 'products';
						$results['data'] = $query_result->toArray();
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
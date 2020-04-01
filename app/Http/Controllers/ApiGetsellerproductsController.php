<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetsellerproductsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_products";        
				$this->permalink   = "getsellerproducts";
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                    $query_result = DB::table('products')
                        ->where('seller_shop_id',$postdata['seller_shop_id']);

                    if(isset($postdata['per_page'])){
                        $query_result = $query_result->paginate($postdata['per_page']);
                    }
                    else{
                        $query_result = $query_result->get();
                    }

                    $results['api_status'] = 1;
                    $results['api_message'] = 'seller products';
                    $results['data'] = $query_result->toArray();
                }
                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error';
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
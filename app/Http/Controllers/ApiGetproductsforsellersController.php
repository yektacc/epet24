<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetproductsforsellersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_product_variants";        
				$this->permalink   = "getproductsforsellers";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				$query_result = DB::table('prd_product_variants')
					//->join("prd_product_variants",'prd_product_variants.id','=','prd_sale_item.variant_id')
//					->join("prd_products",'prd_product_variants.prd_products_id','=','prd_products.id')
					->get();

                    $results['api_status'] = 1;
                    $results['api_message'] = 'prd_product_variants';
					$results['data'] = $query_result->toArray();

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
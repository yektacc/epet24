<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetproductsbrandsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_brands";        
				$this->permalink   = "getproductsbrands";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
//			$where_clause = "1";
//			$where_parameters = [];

//			if(isset($postdata['maincategory_id'])){
//				$where_clause .= " AND (basecategory_id = ?)";
//				$where_parameters[] = $postdata['maincategory_id'];
//			}
//			if(isset($postdata['subcategory_id'])){
//				$where_clause .= " AND (subcategory_id = ?)";
//				$where_parameters[] = $postdata['subcategory_id'];
//			}
			$query_results = DB::table('prd_brands')
//				->whereRaw($where_clause,$where_parameters)
				->get();
			$results['api_status'] = 1;
			$results['api_message'] = 'products';
			$results['data'] = $query_results->toArray();
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

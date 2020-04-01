<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetcategoryidbynameController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "products_category_structure";        
				$this->permalink   = "getcategoryidbyname";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
				DB::table('api_call_stat')
                                   ->where('api_name', '=', 'getcategoryidbyname')
                                   ->update(['count'=> DB::raw('count + 1')]);
				$where_clause = "1";
				$where_parameters = [];

				if($postdata['maincategory_name'])
				{
					$where_clause .= " AND (basecategory_name_fa = ?)";
					$where_parameters[] = $postdata['maincategory_name'];
				}
				if($postdata['subcategory_name'])
				{
					$where_clause .= " AND (subcategory_name_fa = ?)";
					$where_parameters[] = $postdata['subcategory_name'];
				}		
				if($postdata['type_name'])
				{
					$where_clause .= " AND (type_name_fa = ?)";
					$where_parameters[] = $postdata['type_name'];
				}							
				$query_result = DB::table('products_category_structure')
				->whereRaw($where_clause, $where_parameters)
				->select('basecategory_id','subcategory_id','type_id')
				->get();
				
                $results['api_status'] = 1;
                $results['api_message'] = 'products_category_structure';
                $results['data'] = $query_result->toArray();
                echo json_encode($results,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                die;				
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
			
		    }

		}
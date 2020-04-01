<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetcategorynamebyidController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "products_category_structure";        
				$this->permalink   = "getcategorynamebyid";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        DB::table('api_call_stat')
                                   ->where('api_name', '=', 'getcategorynamebyid')
                                   ->update(['count'=> DB::raw('count + 1')]);
		$where_clause = "1";
		$where_parameters = [];

		if($postdata['maincategory_id'])
		{
		    $where_clause .= " AND (basecategory_id = ?)";
		    $where_parameters[] = $postdata['basecategory_id'];
		}
		if($postdata['subcategory_id'])
		{
		    $where_clause .= " AND (subcategory_id = ?)";
		    $where_parameters[] = $postdata['subcategory_id'];
		}		
		if($postdata['type_id'])
		{
		    $where_clause .= " AND (type_id = ?)";
		    $where_parameters[] = $postdata['type_id'];
		}							
		$query_result = DB::table('products_category_structure')
		->whereRaw($where_clause, $where_parameters)
		->select('basecategory_name_fa','subcategory_name_fa','type_name_fa')
		->get();
		
                $results['api_status'] = 1;
                $results['api_message'] = 'get_category_structure_by_id';
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
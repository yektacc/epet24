<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetcitiesnameController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cities";        
				$this->permalink   = "getcitiesname";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
		DB::table('api_call_stat')
                                ->where('api_name', '=', 'getanimalstype')
                                ->update(['count'=> DB::raw('count + 1')]);

		$where_clause = "1";
                $where_parameters = [];
				if(isset($postdata['province_id']))
				{
					$where_clause .= " AND (province_id =?)";                   
					$where_parameters[] = $postdata['province_id'];
					
					$query_result = DB::table('cities')
					->whereRaw($where_clause,$where_parameters)
					->get();
				}
                $results['api_status'] = 1;
                $results['api_message'] = 'cities';
                $results['data'] = $query_result->toArray();
                echo json_encode($results,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                die;
		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process
			DB::table('api_call_stat')
                                ->where('api_name', '=', 'getcitiesname')
                                ->update(['count'=> DB::raw('count + 1')]);

		    }

		}
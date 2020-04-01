<?php namespace App\Http\Controllers;
		use Carbon\Carbon;
		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetfcrequestlistController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "fc_requests";        
				$this->permalink   = "getfcrequestlist";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
				//var_dump($postdata."=>".$postdata['request_type']); exit;
				DB::table('api_call_stat')
                                     ->where('api_name', '=', 'getfcrequestlist')
                                     ->update(['count'=> DB::raw('count + 1')]);

				$where_clause = "1";
				$where_parameters = [];
                if (isset($postdata['request_type'])){

                    $where_clause .= " AND (";
                    $req_type = explode(',',$postdata['request_type']);
                    $count = count($req_type);
                    
                    for ($i = 0 ; $i < $count ; $i++){
                        $where_clause .= "request_type = ? ";
                        $where_parameters[] = $req_type[$i];
                        if ($i+1 < $count)
                            $where_clause .= "OR ";
                    }
					$where_clause .= ")";
//					var_dump($where_clause);
//					var_dump($where_parameters);
				}	
				if(isset($postdata['id']))
				{
					$where_clause .= " AND (fc_requests.id = ?)";
					$where_parameters[] = $postdata['id'];
				}	
				if(isset($postdata['aml_type_id']))
				{
					$where_clause .= " AND (";
                    $aml_type_id = explode(',',$postdata['aml_type_id']);
                    $count = count($aml_type_id);
                    
                    for ($i = 0 ; $i < $count ; $i++){
                        $where_clause .= "fc_requests.aml_type_id = ? ";
                        $where_parameters[] = $aml_type_id[$i];
                        if ($i+1 < $count)
                            $where_clause .= "OR ";
                    }
					$where_clause .= ")";

				}	
				if(isset($postdata['province_id']) && isset($postdata['city_id']))
				{
					$where_clause .= " AND (fc_requests.province_id = ?) AND (fc_requests.city_id = ?)";
					$where_parameters[] = $postdata['province_id'];
					$where_parameters[] = $postdata['city_id'];
				}

				$query_result = DB::table('fc_requests')
					->join('cities',"cities.id","=","fc_requests.city_id")
					->join('provinces',"provinces.id","=","fc_requests.province_id")
					->join('aml_types',"aml_types.id","=","fc_requests.aml_type_id")
				->whereRaw($where_clause, $where_parameters)
				->select('fc_requests.*','cities.city_name','provinces.province_name','aml_types.title')
				->whereDate('fc_requests.valid_until', '>=', Carbon::now()->toDateString())
				->where("deleted_at",NULL)
				->where("is_active",1)
				->orderby('id', 'DESC')
				->get();

                $results['api_status'] = 1;
                $results['api_message'] = 'fc_requests';
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
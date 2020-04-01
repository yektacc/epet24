<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		

		class ApiGetsrvcentersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "srv_centers";        
				$this->permalink   = "getsrvcenters";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process

                $where_clause = "1";
				$where_parameters = [];
								
                if (isset($postdata['center_type'])){
                    $where_clause .= " AND (";
                    $req_type = explode(',',$postdata['center_type']);
                    $count = count($req_type);
                    
                    for ($i = 0 ; $i < $count ; $i++){
                        $where_clause .= "center_type = ? ";
                        $where_parameters[] = $req_type[$i];
                        if ($i+1 < $count)
                            $where_clause .= "OR ";
                    }
					$where_clause .= ")";
				}
				if(!isset($postdata['center_name']) && !isset($postdata['province_id']) && !isset($postdata['city_id']))
				{
					if (isset($postdata['center_name'])){
						$where_clause .= " AND (center_name like ?)";
						$where_parameters[] = '%'.$postdata['center_name'].'%';
					}
					if (isset($postdata['province_id'])){
						$where_clause .= " AND (province_id = ?)";
						$where_parameters[] = $postdata['province_id'];
					}	
					if (isset($postdata['city_id'])){
						$where_clause .= " AND (city_id = ?)";
						$where_parameters[] = $postdata['city_id'];
					}
				}
				else if(isset($postdata['center_name']) && isset($postdata['province_id']) && isset($postdata['city_id']))	
				{
					$where_clause .= " AND (center_name like ?)";
					$where_parameters[] = '%'.$postdata['center_name'].'%';

					$where_clause .= " AND (province_id = ?)";
					$where_parameters[] = $postdata['province_id'];

					$where_clause .= " AND (city_id = ?)";
					$where_parameters[] = $postdata['city_id'];
				}
				else if(isset($postdata['province_id']) || isset($postdata['city_id']))
				{
						
					if (isset($postdata['province_id'])){
					$where_clause .= " AND (province_id = ?)";
					$where_parameters[] = $postdata['province_id'];					
					}
					if (isset($postdata['city_id'])){
					$where_clause .= " AND (city_id = ?)";
					$where_parameters[] = $postdata['city_id'];	
					}
				}	
				else if(isset($postdata['center_name']))
				{
					$where_clause .= " AND (center_name like ?)";
					$where_parameters[] = '%'.$postdata['center_name'].'%';
				}
							
				if (isset($postdata['center_id'])){
					$where_clause .= " AND (id = ?)";
					$where_parameters[] = $postdata['center_id'];
//					
				}
				if(isset($postdata['filter_type']))
				{
					if($postdata['filter_type'] == "newest")
					{
						$query_result = DB::table('srv_centers')
						->whereRaw($where_clause, $where_parameters)
						->where('deleted_at', NULL)
						->where('is_active', 1)
						->orderby('id', 'DESC')
						->get();					
					}	
					else if($postdata['filter_type'] == "best_score")
					{
						$query_result = DB::table('srv_centers')
						->whereRaw($where_clause, $where_parameters)
						->where('deleted_at', NULL)
						->where('is_active', 1)
						->orderby('center_score', 'DESC')
						->get();					
					}	
				}				
				else
				{	
					//var_dump($where_parameters); exit;
					$query_result = DB::table('srv_centers')
					->whereRaw($where_clause, $where_parameters)
					->where('deleted_at', NULL)
					->where('is_active', 1)
//					->join('provinces',"provinces.id","=","srv_centers.province_id")
					->get();
				}
                $results['api_status'] = 1;
                $results['api_message'] = 'srv_centers';
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
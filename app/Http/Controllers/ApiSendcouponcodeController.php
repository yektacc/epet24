<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendcouponcodeController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_coupons_codes";        
				$this->permalink   = "sendcouponcode";    
				$this->method_type = "post";    
		    }
		

		public function hook_before(&$postdata) {
		//This method will be execute before run the main process
                $where_clause = "1";
		$where_parameters = [];
					
                if (isset($postdata['seller_ids'])){
                    $where_clause .= " AND (";
                    $seller_id = explode(',',$postdata['seller_ids']);
                    $count = count($seller_id);
					
                    for ($i = 0 ; $i < $count ; $i++){
                        $where_clause .= "seller_id = ? ";
                        $where_parameters[] = $seller_id[$i];
                        if ($i+1 < $count)
                            $where_clause .= "OR ";
                    }
					$where_clause .= ")";
				}	
				
				$query_result = DB::table('prd_coupons')
						->join('prd_coupons_codes','prd_coupons_codes.prd_coupon_id','=','prd_coupons.id')
						->whereRaw($where_clause, $where_parameters)
						->where('prd_coupons_codes.is_used',0)
						->where('prd_coupons_codes.code','=',$postdata['code'])
						->get();	

				$query_result = $query_result->toArray();
				if($query_result)
				{
					$data['status'] = "success";
					$data['coupon_code_id'] = $query_result[0]->id;
					$data['discount_price'] = $query_result[0]->discount_price;
					$data['seller_id'] = $query_result[0]->seller_id;

				}
				else
				{
					$data['status'] = "failed";
					$data['message'] = "coupon invalid";
				}
                $results['api_status'] = 1;
                $results['api_message'] = 'prd_coupons';
                $results['data'] = $data;
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
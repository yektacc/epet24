<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetunpaidordersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_orders";        
				$this->permalink   = "getunpaidorders";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				try
				{
					$where_clause = "1";
					$where_parameters = [];
					
					$query_result = [];
					if(isset($postdata['session_id']))
					{
						$user_id = DB::table("app_user_sessions")
                        ->where("id",$postdata['session_id'])
                        ->pluck("user_id")
						->first();	
						
                        $where_clause .= " AND (prd_orders.customer_id = ?)";
                        $where_parameters[] = $user_id;					
					}
					if(isset($postdata['seller_id']))
					{
						
                        $where_clause .= " AND (prd_sale_item.seller_id = ?)";
                        $where_parameters[] = $postdata['seller_id'];					
					}					
				
					
					$rows = DB::table('prd_orders')
						
						->join('prd_order_item',"prd_order_item.order_id","=","prd_orders.id")
						->join('prd_sale_item',"prd_order_item.item_id","=","prd_sale_item.id")
					/*
						->join('prd_product_variants',"prd_sale_item.variant_id","=","prd_product_variants.id")
						->join('prd_products',"prd_products.id","=","prd_product_variants.prd_products_id")	
						*/
						//->where("prd_orders.customer_id",$user_id)
						->whereRaw($where_clause, $where_parameters)
						->select("prd_sale_item.id as prd_sale_item_id","prd_orders.id as order_id", "prd_orders.order_code as order_code as order_code", "prd_orders.order_date as order_date"
								,"prd_orders.total_amount as total_amount", "prd_orders.is_completed as is_completed" 
								)				
					->get();
					//var_dump($rows); exit;

						$rows = $rows->toArray();

						$cur_order_id = 0;
						$where_clause2 = "1";
						$where_parameters2 = [];
						foreach ($rows as $row)
						{
							if($row->order_id != $cur_order_id)
							{
								$cur_order_id = $row->order_id;
								$payment = DB::table('prd_order_payments')
									->where('order_id',$row->order_id)
									->get();

								$payment = $payment->toArray();
								
									
								if($payment[0]->id == 0)
								{
	
									if(isset($postdata['seller_id']))
									{
										
										$where_clause2 .= " AND (prd_sale_item.id = ?)";
										$where_parameters2[] = $row->prd_sale_item_id;											
									}

									$items = DB::table('prd_order_item')
										->join('prd_sale_item',"prd_order_item.item_id","=","prd_sale_item.id")
										->join('prd_product_variants',"prd_sale_item.variant_id","=","prd_product_variants.id")
										->join('prd_products',"prd_products.id","=","prd_product_variants.prd_products_id")	
										->whereRaw($where_clause2, $where_parameters2)
										->where("prd_order_item.order_id",$row->order_id)
										//->select("prd_orders.id as order_id", "prd_orders.order_code as order_code as order_code", "prd_orders.order_date as order_date"
										//		,"prd_orders.total_amount as total_amount", "prd_orders.is_completed as is_completed" 
											//	)				
									->get();
									$items = $items->toArray();	
									$query_result2 = [];


									foreach($items as $item)
									{
										$seller = DB::table('srv_centers')
											->join('cities',"cities.id","=","srv_centers.city_id")
											->where('srv_centers.id',$item->seller_id)
											->get();
										$seller = $seller->toArray();

										//var_dump($seller); exit;
										$query_result2[] = [
											"variant_id" => $item->variant_id,
											"variant_code" => $item->variant_code,
											"product_title_fa" => $item->product_title_fa,
											"seller" => $seller[0]->center_name,
											"city" => $seller[0]->city_name
																				
										];
									}
									//var_dump($items); exit;
							
									$query_result[] = [
										"order_id" => $row->order_id,
										"order_code" => $row->order_code,
										"order_date" => $row->order_date,
										"total_amount" => $row->total_amount,
										"is_completed" => $row->is_completed,
										"center_name" => $row->center_name,
										"items"=> $query_result2
									];
								}
							}//if($row->order_id != $cur_order_id)
						}//foreach ($rows as $row)
					
					$results['api_status'] = 1;
					$results['api_message'] = 'prd_orders';
					$results['data'] = $query_result;
				}
			
				catch (\Exception $exception)
				{
					$results['api_status'] = 0;
					$results['api_message'] = 'server error'.$exception;
				}
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
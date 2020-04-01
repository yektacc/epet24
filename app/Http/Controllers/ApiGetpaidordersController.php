<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetpaidordersController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_payments";        
				$this->permalink   = "getpaidorders";    
				$this->method_type = "post";    
		    }
		

		public function hook_before(&$postdata) {
			//This method will be execute before run the main process
			try
			{

				$query_result = [];

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
					->join('prd_order_payments', "prd_order_payments.order_id", "=","prd_orders.id")
					->join('payments', "prd_order_payments.payment_id", "=","payments.id")
					->join('prd_order_item',"prd_order_item.order_id","=","prd_orders.id")
					->join('prd_sale_item',"prd_order_item.item_id","=","prd_sale_item.id")
					->whereRaw($where_clause, $where_parameters)
					//->where("prd_orders.customer_id",$user_id)
					->select("prd_orders.id as order_id", "prd_orders.order_code as order_code"
					, "prd_orders.order_date as order_date", "prd_orders.transferee as transferee"
					, "prd_orders.total_amount as total_amount", "prd_orders.is_completed as is_completed"
					, "payments.ref_id as ref_id", "payments.payment_date","prd_orders.shipment_cost as shipment_cost", "prd_order_item.quantity as quantity")
					->orderby('order_id' ,'DESC')
					->get();
								
				$rows = $rows->toArray();
				//			var_dump($rows); exit;
				foreach ($rows as $row)
				{
					$payment = DB::table('prd_order_payments')
						->where('order_id',$row->order_id)
						->get();
					$payment = $payment->toArray();
		//			var_dump($payment); exit;
					if($payment[0]->id != 0)
					{
						$items = DB::table('prd_order_item')
							->join('prd_sale_item',"prd_order_item.item_id","=","prd_sale_item.id")
							->join('prd_product_variants',"prd_sale_item.variant_id","=","prd_product_variants.id")
							->join('prd_products',"prd_products.id","=","prd_product_variants.prd_products_id")	
							->where("prd_order_item.order_id",$row->order_id)
							->select("prd_product_variants.id as variant_id","prd_sale_item.id as item_id", "prd_product_variants.variant_code as variant_code", "prd_order_item.quantity as quantity"
								, "prd_products.product_title_fa as product_title_fa", "prd_order_item.id as order_item_id", "prd_sale_item.seller_id as seller_id", "prd_order_item.unit_price as unit_price")
							->get();
		
						$items = $items->toArray();

						$user_address = DB::table('user_addresses')
							->where("address_id",$row->transferee)
							->select("province_name", "city_name", "remained", "transferee_name", "transferee_mobile_number")
							->get();
							
						$user_address = $user_address->toArray();
						//var_dump($user_address); exit;
									
						$query_result2 = [];
						foreach($items as $item)
						{
							$seller = DB::table('srv_centers')
								->join('cities',"cities.id","=","srv_centers.city_id")
								->where('srv_centers.id',$item->seller_id)
								->select("srv_centers.id as srv_center_id", "srv_centers.center_name as center_name", "cities.city_name as city_name")
								->get();
							$seller = $seller->toArray();

							//var_dump($seller); exit;
							
							$query_result2[] = [
								"variant_id" => $item->variant_id,
								"item_id" => $item->item_id,
								"variant_code" => $item->variant_code,
								"quantity" => $item->quantity,
								"unit_price" => $item->unit_price,
								"product_title_fa" => $item->product_title_fa,
								"seller" => $seller[0]->center_name,
								"srv_center_id" => $seller[0]->srv_center_id,
								"order_item_id" => $item->order_item_id,
								"seller_city" => $seller[0]->city_name,
								];
											}
								//var_dump($items); exit;
											
							$query_result[] = [
								"order_id" => $row->order_id,
								"order_code" => $row->order_code,
								"order_date" => $row->order_date,
								"total_amount" => $row->total_amount,
								"shipment_cost" => $row->shipment_cost,
					//			"is_completed" => $row->is_completed,
					//			"center_name" => $row->center_name,
								"user_address" => $user_address[0]->province_name." - ". $user_address[0]->city_name." - ".$user_address[0]->remained." - ".$user_address[0]->transferee_name." - ".$user_address[0]->transferee_mobile_number,
								"bank_ref_id" =>$row->ref_id,
								"items"=> $query_result2
									];
						}
					}//if($payment[0]->id != 0)
					
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
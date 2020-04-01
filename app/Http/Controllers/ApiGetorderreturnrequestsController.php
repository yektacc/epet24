<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetorderreturnrequestsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_return";        
				$this->permalink   = "getorderreturnrequests";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
				if(isset($postdata['srv_center_id']))
				{
					$query_result = DB::table('prd_order_return')
						->join('prd_order_item', "prd_order_item.id", "=","prd_order_return.order_item_id")
						->join('prd_sale_item', "prd_order_item.item_id", "=","prd_sale_item.id")
						->join('prd_product_variants', "prd_sale_item.variant_id", "=","prd_product_variants.id")
						->join('prd_products', "prd_product_variants.prd_products_id", "=","prd_products.id")
						->where('prd_order_return.srv_center_id', $postdata['srv_center_id'])
						->select("prd_products.product_title_fa as product_title_fa", "prd_order_item.quantity as quantity"
							   , "prd_sale_item.sale_price as sale_price", "prd_sale_item.created_at as order_date"
							   , "prd_order_return.comment as comment", "prd_order_return.app_user_id as user_id")
						->get();
					$row = $query_result->toArray();
					$query_result2 = DB::table('app_users')
								->where('id',$row[0]->user_id)
								->select("firstname", "lastname", "mobile_number")
								->get();
					$row_user = $query_result2->toArray();
					
					$query_result_final = [
						"name" => $row_user[0]->firstname." ".$row_user[0]->lastname,
						"mobile_number" => $row_user[0]->mobile_number,
						"product_title_fa" => $row[0]->product_title_fa,
						"quantity" => $row[0]->quantity,
						"sale_price" => $row[0]->sale_price,
						"order_date" => $row[0]->order_date,
						"comment" => $row[0]->comment

					];
					$results['api_message'] = "prd_order_return";
					$results['api_status'] = 1;
				}
				else
				{
					$results['api_status'] = 0;
					$results['api_message'] = "srv_center_id field is required";
				}
				//var_dump( $query_result_final); exit;
				$results['data'] = $query_result_final;
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
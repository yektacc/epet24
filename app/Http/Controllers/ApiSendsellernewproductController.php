<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendsellernewproductController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_sale_item";        
				$this->permalink   = "sendsellernewproduct";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				//var_dump($request->srv_center_id); exit;   
				try{
					
					$sale_item_id = DB::table('prd_sale_item')
									->where('variant_id',$postdata['variant_id'])
									->where('seller_id',$postdata['seller_id'])
									->pluck('id')  					
									->first();
					

					if(!$sale_item_id)
					{
						//var_dump($sale_item_id); exit;
						
						DB::table('prd_sale_item')->insert([
							'variant_id' => $postdata['variant_id'],
							'seller_id' => $postdata['seller_id'],
							'shipment_time' => $postdata['shipment_time'],
							'guaranty_id' => $postdata['guaranty_id'],
							'main_price' => $postdata['main_price'],
							'sale_price' => $postdata['sale_price'],
							'sale_discount' => $postdata['sale_discount'],
							'maximum_orderable' => $postdata['maximum_orderable'],
							'is_bounded' => $postdata['is_bounded'],
							'stock_quantity' => $postdata['stock_quantity'],
							'description' => $postdata['sale_description'],
							'is_exist' => $postdata['is_exist'],
							'tag_id' => $postdata['tag_id'],
							'tag_price' => $postdata['tag_price'],
							'tag_is_active' => $postdata['tag_is_active'],
						// 'tag_starttime' => $postdata['tag_starttime'],
						//  'tag_stoptime' => $postdata['tag_stoptime']
							]);  
						$results['api_message'] = 'new product added';
					}
					else
					{
						$results['api_message'] = 'duplicate product';
					}



					$results['api_status'] = 1;
					
					$results['response_code'] = 0;
					$results['sale_item_id'] = $sale_item_id;
				}
				catch (\Exception $exception){
					$results['api_status'] = 0;
					$results['api_message'] = 'server error'. $exception;
				}
			//
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
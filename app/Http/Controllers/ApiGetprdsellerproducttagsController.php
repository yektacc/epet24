<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetprdsellerproducttagsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_sale_item";        
				$this->permalink   = "getprdsellerproducttags";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				try
				{
					$query_result = DB::table('prd_sale_item')
						->join("prd_product_variants",'prd_sale_item.variant_id','=','prd_product_variants.id')
						->join("prd_products",'prd_products.id','=','prd_product_variants.prd_products_id')
						->join("srv_centers",'srv_centers.id','=','prd_sale_item.seller_id')
						->select('prd_sale_item.*','prd_sale_item.id as prd_sale_item_id','prd_products.id as product_id', 'prd_products.*', 'prd_product_variants.*','srv_centers.city_id','srv_centers.center_name')
						->where('prd_sale_item.tag_id',$postdata['tag_id'])
						->where('prd_sale_item.tag_is_active',$postdata['is_active'])
						->where('prd_sale_item.deleted_at', NULL)
						->orderby('prd_sale_item.id', 'DESC')
						->paginate(PAGE_SIZE);   
					
					$results['api_status'] = 1;
					$results['api_message'] = 'prd_sale_item';
					$results['data'] = $query_result->toArray();
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
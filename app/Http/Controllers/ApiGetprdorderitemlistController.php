<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiGetprdorderitemlistController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_order_item";        
				$this->permalink   = "getprdorderitemlist";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		        try {
		if(isset($postdata['order_id']) && isset($postdata['item_id'])){
		$packing_time = DB::table('prd_order_item')
		        ->where('order_id',$postdata['order_id'])
		        ->where('item_id',$postdata['item_id'])
		        ->pluck('packing_time')
		        ->first();
		if(! $packing_time == NULL){ 
		    $results['api_status'] = 1;
                    $results['packing_time'] = $packing_time;
                    }
                else
                    {
                if($packing_time == NULL){
                    $results['api_status'] = 2;
                    $results['api_message'] = "packing_time is null";
	                    }
	                }
		    }
		}

                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'.$exception;
                }

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
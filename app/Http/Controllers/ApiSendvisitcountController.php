<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendvisitcountController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {
				$this->table       = "prd_sale_item";
				$this->permalink   = "sendvisitcount";
				$this->method_type = "post";
		    }

		    public function hook_before(&$postdata) {
				//This method will be execute before run the main process
				try
				{
	    	if (isset($postdata['sale_item_id'])){
				$where_clause = "(id = ?)";
				$where_parameters[] = $postdata['sale_item_id'];

		DB::table('prd_sale_item')
				->whereRaw($where_clause,$where_parameters)
				->update(['visit_count'=> DB::raw('visit_count + 1')]);
					}
		$results['api_status'] = 1;
		$results['api_message'] = 'prd_sale_item';
		$results['response_code'] = 0;
				}

                catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error'. $exception;
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

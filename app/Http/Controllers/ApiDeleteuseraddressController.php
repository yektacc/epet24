<?php namespace App\Http\Controllers;

		use Carbon\Carbon;
        use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiDeleteuseraddressController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "prd_customer_addresses";        
				$this->permalink   = "deleteuseraddress";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                    DB::table("prd_customer_addresses")->where('id',$postdata['transferee_address_id'])->update([
                        'deleted_at' => Carbon::now()
                    ]);

                    $address_id = DB::table('prd_customer_addresses')
                        ->where('id',$postdata['transferee_address_id'])
                        ->pluck('transferee_address')
                        ->first();

                    DB::table('addresses')->where('id',$address_id)->update([
                        'deleted_at' => Carbon::now()
                    ]);

                    $data['response_code'] = 0;

                    $results['api_status'] = 1;
                    $results['api_message'] = 'user address deleted';
                    $results['data'] = $data;

                } catch (\Exception $exception){
                    $results['api_status'] = 0;
                    $results['api_message'] = 'server error';
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
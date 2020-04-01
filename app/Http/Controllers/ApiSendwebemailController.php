<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use Carbon\Carbon;

		class ApiSendwebemailController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "web_email";        
				$this->permalink   = "sendwebemail";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		try{
/*		
		    $query_result = DB::table('web_email')
		    ->where('email',$postdata['email'])
		    ->pluck('id')
		    ->first();
		
		if($query_result == null){
		    DB::table('web_email')->insert([
			'id' => NULL,
			'email' => $postdata['email'],
			'created_at' => Carbon::now(),
			]);
		} 
*/		
 DB::table('web_email')->updateOrInsert([
        'email' => $postdata['email']],[
        'created_at' => Carbon::now()
    ]);
    
		$results['api_status'] = 1;
		$results['api_message'] = 'web_email';
//		$results['data'] = $query_result->toArray();
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
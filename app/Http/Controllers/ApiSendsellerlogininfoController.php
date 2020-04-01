<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use Carbon\Carbon;
        use Illuminate\Support\Facades\Hash;

		class ApiSendsellerlogininfoController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "cms_users";        
				$this->permalink   = "sendsellerlogininfo";    
				$this->method_type = "post";    
			}
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
		try
		{
			$query_result = "";
			$cms_user = DB::table('cms_users')
			->where('email',$postdata['email'])
			->first();
			
			if(!empty($cms_user) && Hash::check($postdata['password'],$cms_user->password))

			{
				$query_result = DB::table('srv_centers')
				->where('cms_user_id',$cms_user->id)
				->where('deleted_at',NULL)
				->where('is_active',1)
				->get();
				
				$results['data'] = $query_result->toArray();
			if(!empty($results['data']))
			{
				
				$results['data'] = $query_result->toArray();
				$results['api_status'] = 1;
				$results['api_message'] = 'cms_users';
			}
			else
			{
				$results['data'] = "";
				$results['api_status'] = 0;
				$results['api_message'] = 'server error';
			
			}
			}
		}
				catch (\Exception $exception)
			{
				$results['api_status'] = 0;
				$results['api_message'] = 'server error';
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
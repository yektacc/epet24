<?php namespace App\Http\Controllers;

		use Carbon\Carbon;
		use Illuminate\Support\Facades\Hash;
		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendlogininfoController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_users";        
				$this->permalink   = "sendlogininfo";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

                try{
                    $user = DB::table('app_users')
                        ->where('mobile_number',$postdata['mobile_number'])
                        ->where('is_active','1')->first();

                    $app_user_id = DB::table('app_users')
                        ->where('mobile_number',$postdata['mobile_number'])
                        ->where('is_active','1')
                        ->pluck('id')
                        ->first();

			if(!empty($user) && Hash::check($postdata['password'],$user->password)){
			$session_id = DB::table('app_user_sessions')->insertGetId([
                            'id' => NULL,
                            'user_id' => $user->id,
                            'client_ip' => $postdata['client_ip'],
                            'login_date' => Carbon::now()
                        ],'id');

                        $data['response_code'] = 0;
                        $data['session_id'] = $session_id;
                        $data['client_ip'] = $postdata['client_ip'];
                        $data['app_user_id'] = $app_user_id;
                    }

                    else{
                        $data['response_code'] = 3;
                        $data['response_message'] = LOGIN_FAILED;
                    }

                    $results['api_status'] = 1;
                    $results['api_message'] = 'login';
                    $results['data'] = $data;
                }
                catch (\Exception $exception){
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
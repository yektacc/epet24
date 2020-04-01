<?php namespace App\Http\Controllers;

        use Carbon\Carbon;
        use Illuminate\Support\Facades\Hash;
        use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiSendchangedpasswordinfoController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_users";        
				$this->permalink   = "sendchangedpasswordinfo";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

                try{
                    $user = DB::table('app_users')
                        ->where('mobile_number',$postdata['mobile_number'])
                        ->where('is_active','1')
                        ->first();

                    DB::table('app_users')
                        ->where('id',$user->id)
                        ->update([
                            'password' => Hash::make($postdata['password'])
                        ]);

                    $session_id = DB::table('app_user_sessions')
                        ->where('user_id',$user->id)
                        ->where('logout_date',NULL)
                        ->max('id');

                    if(empty($session_id)){
                        $session_id = DB::table('app_user_sessions')->insertGetId([
                            'id' => NULL,
                            'user_id' => $user->id,
                            'login_date' => Carbon::now(),
                            'logout_date' => NULL,
                            'client_ip' => NULL
                        ],'id');
                    }

                    $data['session_id'] = $session_id;

                    //todo: send user profile and his shopping cart

                    $results['api_status'] = 1;
                    $results['api_message'] = 'changing password';
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
<?php namespace App\Http\Controllers;

		use App\Helper\AppConfig;
		use Illuminate\Support\Facades\Hash;
		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use Carbon\Carbon;

		class ApiSendregisterationinfoController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "app_users";        
				$this->permalink   = "sendregisterationinfo";    
				$this->method_type = "post";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
                try{
                    $user = DB::table('app_users')
                        ->where('mobile_number',$postdata['mobile_number'])
                        ->first();

                    if(!empty($user) && $user->is_active == 1){
                        $data['response_code'] = 1;
                        $data['response_message'] = USER_ALREADY_EXISTS;
                    }
                    else {
//                    DB::transaction(function () use (&$postdata, &$user){
                        if(empty($user)){
                        $app_users_id = DB::table('app_users')->insertGetId([
                                'id' => NULL,
                                'firstname' => NULL,
                                'lastname' => NULL,
                                'password' => Hash::make($postdata['password']),
                                'email' => NULL,
                                'mobile_number' => $postdata['mobile_number'],
                                'is_active' => 1,
                                'is_confirmed' => 1,
                            ],'id');
                        DB::table('app_user_roles')->insert([
                        'id' => NULL,
                        'user_id' => $app_users_id,
                        'role_id' => 2,
                        'created_at' => Carbon::now()
                        ]);
                        }
                        $otp = AppConfig::generate_otp_code();

                        //send otp via sms
                        AppConfig::sendSMS($postdata['mobile_number'],$otp);

                        //delete last otp with this mobile number
                        DB::table('otp_temp')->where('mobile_number',$postdata['mobile_number'])->delete();

                        //insert new otp for this mobile number
                        DB::table('otp_temp')->insert([
                            'mobile_number' => $postdata['mobile_number'],
                            'verification_code' => $otp
                        ]);

                        $data['response_code'] = 0;
                        $data['mobile_number'] = $postdata['mobile_number'];
                        $data['verification_code'] = $otp;

//                    });
                    }

                    $results['api_status'] = 1;
                    $results['api_message'] = 'registeration';
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

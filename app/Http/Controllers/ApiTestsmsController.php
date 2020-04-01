<?php namespace App\Http\Controllers;

        use Carbon\Carbon;
        use App\Helper\AppConfig;
        use Illuminate\Contracts\Auth\CanResetPassword;
        use Session;
		use Request;
		use DB;
		use CRUDBooster;		

		class ApiTestsmsController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "addresses";        
				$this->permalink   = "testsms";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process
				//send otp via sms to customer
				$alert_text = "پرداخت سفارش شماره ".$order_code." با موفقیت انجام شد. سبد خرید شما بزودی برای بسته بندی و ارسال مورد پردازش قرار خواهد گرفت.";
				$mobile_number = "09120422991";
				AppConfig::sendSMS($mobile_number,$alert_text);
				$results['api_status'] = 0;
				$results['api_message'] = 'success';
			
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

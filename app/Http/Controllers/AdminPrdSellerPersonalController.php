<?php namespace App\Http\Controllers;

	use App\Helper\AppConfig;
    use Carbon\Carbon;
    use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPrdSellerPersonalController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "prd_seller_personal";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"کد فروشنده","name"=>"seller_code"];
			$this->col[] = ["label"=>"نام فروشنده","name"=>"firstname"];
			$this->col[] = ["label"=>"نام خانوادگی فروشنده","name"=>"lastname"];
			$this->col[] = ["label"=>"جنسیت","name"=>"seller_gender","join"=>"prd_seller_genders,title"];
			$this->col[] = ["label"=>"تلفن همراه","name"=>"seller_mobile_number"];
			$this->col[] = ["label"=>"تلفن ثابت","name"=>"seller_phone"];
			$this->col[] = ["label"=>"فعال است؟","name"=>"is_active","join"=>"answers,answer_text"];
			$this->col[] = ["label"=>"کد ملی","name"=>"seller_national_code"];
			$this->col[] = ["label"=>"تاریخ تولد","name"=>"seller_birth_date"];
			$this->col[] = ["label"=>"شماره شناسنامه","name"=>"seller_birth_certificate"];
			$this->col[] = ["label"=>"قرارداد دارد؟","name"=>"is_confirmed_with_contract","join"=>"answers,answer_text"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'نام','name'=>'firstname','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'نام خانوادگی','name'=>'lastname','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'جنسیت فروشنده','name'=>'seller_gender','type'=>'radio','validation'=>'required','width'=>'col-sm-6','dataenum'=>'1|مرد;2|زن'];
			$this->form[] = ['label'=>'تلفن همراه','name'=>'seller_mobile_number','type'=>'multitext','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تلفن ثابت','name'=>'seller_phone','type'=>'multitext','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'استان','name'=>'province_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9','datatable'=>'provinces,province_name'];
			$this->form[] = ['label'=>'شهر','name'=>'city_id','type'=>'select','validation'=>'required','width'=>'col-sm-6','datatable'=>'cities,city_name','parent_select'=>'province_id'];
			$this->form[] = ['label'=>'آدرس','name'=>'seller_address','type'=>'text','validation'=>'required','width'=>'col-sm-9','placeholder'=>'مثال: محله، کوچه، نام فروشگاه، پلاک'];
			$this->form[] = ['label'=>'کدپستی','name'=>'postal_code','type'=>'number','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'فعال است؟','name'=>'is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-8','datatable'=>'answers_yesno,answers_text'];
			$this->form[] = ['label'=>'کد ملی فروشنده','name'=>'seller_national_code','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تاریخ تولد فروشنده','name'=>'seller_birth_date','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'شماره شناسنامه فروشنده','name'=>'seller_birth_certificate','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'قرارداد دارد؟','name'=>'is_confirmed_with_contract','type'=>'radio','validation'=>'required','width'=>'col-sm-5','datatable'=>'answers_yesno,answers_text'];
			$this->form[] = ['label'=>'تصویر روی کارت ملی','name'=>'card_front_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تصویر پشت کارت ملی','name'=>'card_back_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'شماره نامه ارزش افزوده','name'=>'vat_number','type'=>'text','validation'=>'min:3|max:20','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'شروع ارزش افزوده','name'=>'vat_startdate','type'=>'date','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'پایان ارزش افزوده','name'=>'vat_enddate','type'=>'date','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تصویر ارزش افزوده','name'=>'vat_picture','type'=>'upload','validation'=>'image','width'=>'col-sm-6'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'نام','name'=>'firstname','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'نام خانوادگی','name'=>'lastname','type'=>'text','validation'=>'required','width'=>'col-sm-8'];
			//$this->form[] = ['label'=>'جنسیت فروشنده','name'=>'seller_gender','type'=>'radio','validation'=>'required','width'=>'col-sm-6','dataenum'=>'1|مرد;2|زن'];
			//$this->form[] = ['label'=>'تلفن همراه','name'=>'seller_mobile_number','type'=>'multitext','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'تلفن ثابت','name'=>'seller_phone','type'=>'multitext','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'استان','name'=>'province_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9','datatable'=>'provinces,province_name'];
			//$this->form[] = ['label'=>'شهر','name'=>'city_id','type'=>'select','validation'=>'required','width'=>'col-sm-6','datatable'=>'cities,city_name','parent_select'=>'province_id'];
			//$this->form[] = ['label'=>'آدرس','name'=>'seller_address','type'=>'text','validation'=>'required','width'=>'col-sm-9','placeholder'=>'مثال: محله، کوچه، نام فروشگاه، پلاک'];
			//$this->form[] = ['label'=>'کدپستی','name'=>'postal_code','type'=>'number','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'وضعیت فعالیت','name'=>'is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-8','datatable'=>'answers_yesno,answers_text'];
			//$this->form[] = ['label'=>'کد ملی فروشنده','name'=>'seller_national_code','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'تاریخ تولد فروشنده','name'=>'seller_birth_date','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'شماره شناسنامه فروشنده','name'=>'seller_birth_certificate','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'وضعیت قرارداد','name'=>'is_confirmed_with_contract','type'=>'radio','validation'=>'required','width'=>'col-sm-5','datatable'=>'answers_yesno,answers_text'];
			//$this->form[] = ['label'=>'تصویر روی کارت ملی','name'=>'card_front_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'تصویر پشت کارت ملی','name'=>'card_back_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'شماره نامه ارزش افزوده','name'=>'vat_number','type'=>'text','validation'=>'min:3|max:20','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'شروع ارزش افزوده','name'=>'vat_startdate','type'=>'date','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'پایان ارزش افزوده','name'=>'vat_enddate','type'=>'date','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'تصویر ارزش افزوده','name'=>'vat_picture','type'=>'upload','validation'=>'image','width'=>'col-sm-6'];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
/*		    $userId = CRUDBooster::myId();
		    echo $userId;
		    $isAdmin = CRUDBooster::isSuperadmin();
		    $storeAssignedtoUser = DB::table('srv_centers')
                    ->where('cms_user_id', '=', $userId)
                    ->first();
		    if ($isAdmin) {

		    }else {
			$query->where('cms_users.id',$storeAssignedtoUser->cms_user_id);
		    }
*/	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
/*	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }
*/
	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
/*
	    public function hook_before_add(&$postdata) {        
	        //Your code here
            DB::transaction(function () use (&$postdata) {
                $personal_info_id = DB::table('prd_seller_personal')->insertGetId([
                    'id'=>NULL,
                    'seller_birth_date'=>$postdata['seller_birth_date'],
                    'seller_gender'=>$postdata['seller_gender'],
                    'seller_birth_certificate'=>$postdata['seller_birth_certificate'],
//                    'seller_phone'=>$postdata['seller_phone'],
//                    'seller_mobile_number'=>$postdata['seller_mobile_number'],
                    'is_confirmed_with_contract'=>1,
                    'is_active'=>1,
                    'card_front_picture'=>$postdata['card_front_picture'],
                    'card_back_picture'=>$postdata['card_back_picture'],
                ],'id');
                        
                DB::table('prd_seller_personal')->insert([
//                    'id'=>NULL,
                    'seller_code'=>AppConfig::generate_seller_code(),
//                    'seller_type'=>1,
//                    'user_id'=>$postdata['app_users_id'],
//                    'personal_seller'=>$personal_info_id,
//                    'company_seller'=>NULL,
//                    'created_at'=>Carbon::now()
                ])
//            });

//            CRUDBooster::redirect(CRUDBooster::mainPath(),NULL,"success");

        }
*/
	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
/*	    public function hook_after_add($id) {        
	        //Your code here

	    }
*/
	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
/*	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

            DB::transaction(function () use (&$postdata,$id) {
                $personal_info_id = DB::table('prd_seller_personal')->where('id',$id)->update([
                    'seller_birth_date'=>$postdata['seller_birth_date'],
                    'seller_gender'=>$postdata['seller_gender'],
                    'seller_birth_certificate'=>$postdata['seller_birth_certificate'],
//                    'seller_phone'=>$postdata['seller_phone'],
//                    'seller_mobile_number'=>$postdata['seller_mobile_number'],
                    'card_front_picture'=>$postdata['card_front_picture'],
                    'card_back_picture'=>$postdata['card_back_picture'],
                ]);

                DB::table('prd_seller')->where('personal_seller',$id)->update([
                    'user_id'=>$postdata['user_id'],
                    'personal_seller'=>$personal_info_id,
                    'updated_at'=>Carbon::now()
                ]);

            });

            CRUDBooster::redirect(CRUDBooster::mainPath(),NULL,"success");
	    }
*/
	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

	    //By the way, you can still create your own method in here... :)
/*
        public function getDetail($id)
        {
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

//            $data = [];
//            $data['page_title'] = 'جزئیات فروشندگان حقیقی';

//            $row = DB::table('prd_seller')
//                ->where('id',$id)
//                ->first();

//            $personal = DB::table('prd_seller_personal')
                ->where('id',$row->personal_seller)
                ->first();

            $gender = DB::table('prd_seller_genders')
                ->where('id',$personal->seller_gender)
                ->first();

            $code = DB::table('app_users')
                ->where('id',$row->user_id)
                ->first();

            $fields['کاربر'] = $code->firstname." ".$code->lastname;
            $fields['تاریخ تولد'] = $personal->seller_birth_date;
            $fields['جنسیت'] = $gender->title;
            $fields['کد ملی'] =  $personal->seller_national_code;
            $fields['شماره شناسنامه'] = $personal->seller_birth_certificate;
            $fields['تصویر کارت ملی'] = $personal->card_front_picture;
            $fields['تصویر پشت کارت ملی'] = $personal->card_back_picture;
            $data['fields'] = $fields;
            $this->cbView('custom_detail_view',$data);
        }

*/
	}
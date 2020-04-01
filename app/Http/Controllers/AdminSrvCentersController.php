<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminSrvCentersController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {
	

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "center_name";
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
			$this->table = "srv_centers";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"نام مالک","name"=>"seller_id","join"=>"prd_seller,shop_name_fa"];
			$this->col[] = ["label"=>"نام مرکز","name"=>"center_name"];
			$this->col[] = ["label"=>"نوع مرکز","name"=>"center_type","join"=>"srv_categories,category_name"];
			$this->col[] = ["label"=>"استان","name"=>"province_id","join"=>"provinces,province_name"];
			$this->col[] = ["label"=>"شهر","name"=>"city_id","join"=>"cities,city_name"];
			$this->col[] = ["label"=>"منطقه","name"=>"district_id","join"=>"districts,district_name"];
//			$this->col[] = ["label"=>"محله و غیره","name"=>"remaind"];
//			$this->col[] = ["label"=>"لوگو","name"=>"center_logo"];
			$this->col[] = ["label"=>"امتیاز","name"=>"center_score"];
			$this->col[] = ["label"=>"آدرس وب سایت","name"=>"center_website"];
			$this->col[] = ["label"=>"آدرس ایمیل","name"=>"center_email"];
			$this->col[] = ["label"=>"توضیحات","name"=>"center_description"];
			$this->col[] = ["label"=>"فعال","name"=>"is_active","join"=>"answers_yesno,answers_text"];
//			$this->col[] = ["label"=>"روزهای کاری اول","name"=>"workingday1"];
//			$this->col[] = ["label"=>"از ساعت","name"=>"timefrom1"];
//			$this->col[] = ["label"=>"تا ساعت","name"=>"timeto1"];
//			$this->col[] = ["label"=>"روزهای کاری دوم","name"=>"workingday2"];
//			$this->col[] = ["label"=>"از ساعت","name"=>"timefrom2"];
//			$this->col[] = ["label"=>"تا ساعت","name"=>"timeto2"];
//			$this->col[] = ["label"=>"روزهای کاری سوم","name"=>"workingday3"];
//			$this->col[] = ["label"=>"از ساعت","name"=>"timefrom3"];
//			$this->col[] = ["label"=>"تا ساعت","name"=>"timeto3"];
			$this->col[] = ["label"=>"کاربر داشبورد","name"=>"cms_user_id","join"=>"cms_users,name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'انتخاب مرکز','name'=>'seller_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-9','datamodal_table'=>'prd_seller','datamodal_columns'=>'shop_name_fa,seller_code','datamodal_size'=>'large'];
			$this->form[] = ['label'=>'فعال','name'=>'is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-9','datatable'=>'answers_yesno,answers_text'];
			$this->form[] = ['label'=>'نام مرکز','name'=>'center_name','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'تلفن همراه پشتیبانی','name'=>'center_phone','validation'=>'required','type'=>'text','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'نوع مرکز','name'=>'center_type','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'srv_categories,category_name'];
			$this->form[] = ['label'=>'استان','name'=>'province_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9','datatable'=>'provinces,province_name'];
			$this->form[] = ['label'=>'شهر','name'=>'city_id','type'=>'select','validation'=>'required','width'=>'col-sm-9','datatable'=>'cities,city_name','parent_select'=>'province_id'];
			$this->form[] = ['label'=>'منطقه','name'=>'district_id','type'=>'select','width'=>'col-sm-9','datatable'=>'districts,district_name','parent_select'=>'city_id'];
			$this->form[] = ['label'=>'محله','name'=>'remaind','type'=>'text','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'لوگو','name'=>'center_logo','type'=>'upload','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'امتیاز','name'=>'center_score','type'=>'number','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'آدرس وب سایت','name'=>'center_website','type'=>'text','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'آدرس ایمیل','name'=>'center_email','type'=>'text','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'توضیحات','name'=>'center_description','type'=>'textarea','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'روزهای کاری اول','name'=>'workingday1','type'=>'checkbox','validation'=>'required','width'=>'col-sm-9','dataenum'=>'1|شنبه;2|یکشنبه;3|دوشنبه;4|سه شنبه;5|چهارشنبه;6|پنجشنبه;7|جمعه'];
			$this->form[] = ['label'=>'از ساعت','name'=>'timefrom1','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'تا ساعت','name'=>'timeto1','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'روزهای کاری دوم','name'=>'workingday2','type'=>'checkbox','validation'=>'required','width'=>'col-sm-9','dataenum'=>'1|شنبه;2|یکشنبه;3|دوشنبه;4|سه شنبه;5|چهارشنبه;6|پنجشنبه;7|جمعه'];
			$this->form[] = ['label'=>'از ساعت','name'=>'timefrom2','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'تا ساعت','name'=>'timeto2','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'روزهای کاری سوم','name'=>'workingday3','type'=>'checkbox','validation'=>'required','width'=>'col-sm-9','dataenum'=>'1|شنبه;2|یکشنبه;3|دوشنبه;4|سه شنبه;5|چهارشنبه;6|پنجشنبه;7|جمعه'];
			$this->form[] = ['label'=>'از ساعت','name'=>'timefrom3','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'تا ساعت','name'=>'timeto3','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'ساعت کاری تعطیلات','name'=>'holidays','type'=>'textarea','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'مختصات جغرافیایی','name'=>'ne_location','type'=>'text','width'=>'col-sm-9','placeholder'=>'35.6970118,51.209732'];
			$this->form[] = ['label'=>'اتاق چت خدمات فعال؟','name'=>'chat_is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-9','datatable'=>'answers_yesno,answers_text'];
			$this->form[] = ['label'=>'کاربر داشبورد','name'=>'cms_user_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-9','datamodal_table'=>'cms_users','datamodal_columns'=>'name,email','datamodal_where'=>'id_cms_privileges=3'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'انتخاب مرکز','name'=>'seller_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-9','datamodal_table'=>'prd_seller','datamodal_columns'=>'shop_name_fa,seller_code','datamodal_size'=>'large'];
			//$this->form[] = ['label'=>'فعال','name'=>'is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-9','datatable'=>'answers_yesno,answers_text'];
			//$this->form[] = ['label'=>'نام مرکز','name'=>'center_name','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'نوع مرکز','name'=>'center_type','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'srv_categories,category_name'];
			//$this->form[] = ['label'=>'استان','name'=>'province_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9','datatable'=>'provinces,province_name'];
			//$this->form[] = ['label'=>'شهر','name'=>'city_id','type'=>'select','validation'=>'required','width'=>'col-sm-9','datatable'=>'cities,city_name','parent_select'=>'province_id'];
			//$this->form[] = ['label'=>'منطقه','name'=>'district_id','type'=>'select','width'=>'col-sm-9','datatable'=>'districts,district_name','parent_select'=>'city_id'];
			//$this->form[] = ['label'=>'محله','name'=>'remaind','type'=>'text','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'لوگو','name'=>'center_logo','type'=>'upload','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'امتیاز','name'=>'center_score','type'=>'number','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'آدرس وب سایت','name'=>'center_website','type'=>'text','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'تلفن','name'=>'center_phone','type'=>'text','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'آدرس ایمیل','name'=>'center_email','type'=>'text','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'توضیحات','name'=>'center_description','type'=>'textarea','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'روزهای کاری اول','name'=>'workingday1','type'=>'checkbox','validation'=>'required','width'=>'col-sm-9','datatable'=>'weekday,day_name'];
			//$this->form[] = ['label'=>'از ساعت','name'=>'timefrom1','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'تا ساعت','name'=>'timeto1','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'روزهای کاری دوم','name'=>'workingday2','type'=>'checkbox','validation'=>'required','width'=>'col-sm-9','datatable'=>'weekday,day_name'];
			//$this->form[] = ['label'=>'از ساعت','name'=>'timefrom2','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'تا ساعت','name'=>'timeto2','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'روزهای کاری سوم','name'=>'workingday3','type'=>'checkbox','validation'=>'required','width'=>'col-sm-9','datatable'=>'weekday,day_name'];
			//$this->form[] = ['label'=>'از ساعت','name'=>'timefrom3','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'تا ساعت','name'=>'timeto3','type'=>'time','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'ساعت کاری تعطیلات','name'=>'holidays','type'=>'textarea','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'مختصات جغرافیایی','name'=>'ne_location','type'=>'text','width'=>'col-sm-9','placeholder'=>'35.6970118,51.209732'];
			//$this->form[] = ['label'=>'کاربر داشبورد','name'=>'cms_user_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-9','datamodal_table'=>'cms_users','datamodal_columns'=>'name,email','datamodal_where'=>'id_cms_privileges=3'];
			//$this->form[] = ['label'=>'اتاق چت خدمات','name'=>'chat_is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-9'];
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
            $userId = CRUDBooster::myId();
            $cms_user_id = CRUDBooster::myId();
//	    echo $userId;
	 if ($cms_user_id == 2){
                        $isTopadmin = $cms_user_id - 1;
                        }

	    $isAdmin = CRUDBooster::isSuperadmin();
	    $storeAssignedtoUser = DB::table('srv_centers')
                    ->where('cms_user_id', '=', $userId)
                    ->first();
	    if ($isAdmin) {

	    } elseif ($isTopadmin) {

	    }else {
	    $query->where('cms_users.id',$storeAssignedtoUser->cms_user_id);
	    }
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

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
			DB::table('prd_sale_item')->where('seller_id',$id)->update([
				"deleted_at" => now()
			]);

	    }



	    //By the way, you can still create your own method in here... :) 


	}
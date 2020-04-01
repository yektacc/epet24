<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPrdSellerCompanyController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "prd_seller_company";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"نام شرکت","name"=>"company_name_fa"];
			$this->col[] = ["label"=>"صاحبان امضاء","name"=>"signature_owners"];
			$this->col[] = ["label"=>"نوع شرکت","name"=>"company_type","join"=>"prd_company_type,title"];
			$this->col[] = ["label"=>"وضعیت قراداد","name"=>"is_confirmed_with_contract","join"=>"answers,answer_text"];
			$this->col[] = ["label"=>"وضعیت دسترسی","name"=>"is_active","join"=>"answers,answer_text"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'نام فارسی','name'=>'company_name_fa','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'نام انگلیسی','name'=>'company_name_en','type'=>'text','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'نوع شرکت','name'=>'company_type','type'=>'select2','validation'=>'required','width'=>'col-sm-5','datatable'=>'prd_company_type,title'];
			$this->form[] = ['label'=>'صاحبان امضاء','name'=>'signature_owners','type'=>'textarea','validation'=>'required|string|min:5|max:500','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تلفن همراه','name'=>'seller_mobile_number','type'=>'multitext','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'تلفن ثابت','name'=>'seller_phone','type'=>'multitext','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'استان','name'=>'province_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9','datatable'=>'provinces,province_name'];
			$this->form[] = ['label'=>'شهر','name'=>'city_id','type'=>'select','validation'=>'required','width'=>'col-sm-6','datatable'=>'cities,city_name','parent_select'=>'province_id'];
			$this->form[] = ['label'=>'آدرس','name'=>'seller_address','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'کدپستی','name'=>'postal_code','type'=>'number','validation'=>'required','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'شناسه ملی','name'=>'national_id','type'=>'number','validation'=>'required|numeric|min:9999999999|max:99999999999','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'فعال است؟','name'=>'is_active','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-2','datatable'=>'answers_yesno,answers_text'];
			$this->form[] = ['label'=>'قرارداد دارد؟','name'=>'is_confirmed_with_contract','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-6','datatable'=>'answers_yesno,answers_text'];
			$this->form[] = ['label'=>'تصویر آخرین روزنامه رسمی','name'=>'card_front_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تصویر روزنامه صاحبان امضاء','name'=>'card_back_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تصویر اساسنامه','name'=>'paper_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'شماره نامه ارزش افزوده','name'=>'vat_number','type'=>'text','validation'=>'min:3|max:32','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'شروع ارزش افزوده','name'=>'vat_startdate','type'=>'date','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'پایان ارزش افزوده','name'=>'vat_enddate','type'=>'date','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'تصویر ارزش افزوده','name'=>'vat_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'نام فارسی','name'=>'company_name_fa','type'=>'text','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'نام انگلیسی','name'=>'company_name_en','type'=>'text','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'نوع شرکت','name'=>'company_type','type'=>'select2','validation'=>'required','width'=>'col-sm-5','datatable'=>'prd_company_type,title'];
			//$this->form[] = ['label'=>'صاحبان امضاء','name'=>'signature_owners','type'=>'textarea','validation'=>'required|string|min:5|max:500','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'تلفن همراه','name'=>'seller_mobile_number','type'=>'multitext','validation'=>'required','width'=>'col-sm-9','placeholder'=>'1400123456789'];
			//$this->form[] = ['label'=>'تلفن ثابت','name'=>'seller_phone','type'=>'multitext','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'استان','name'=>'province_id','type'=>'select2','validation'=>'required','width'=>'col-sm-9','datatable'=>'provinces,province_name'];
			//$this->form[] = ['label'=>'شهر','name'=>'city_id','type'=>'select','validation'=>'required','width'=>'col-sm-6','datatable'=>'cities,city_name','parent_select'=>'province_id'];
			//$this->form[] = ['label'=>'آدرس','name'=>'seller_address','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'کدپستی','name'=>'postal_code','type'=>'number','validation'=>'required','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'شناسه ملی','name'=>'national_id','type'=>'number','validation'=>'required|numeric|min:9999999999|max:99999999999','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'فعال است؟','name'=>'is_active','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-2','datatable'=>'answers_yesno,answers_text'];
			//$this->form[] = ['label'=>'قرارداد دارد؟','name'=>'is_confirmed_with_contract','type'=>'radio','validation'=>'required|integer','width'=>'col-sm-6','datatable'=>'answers_yesno,answers_text'];
			//$this->form[] = ['label'=>'تصویر آخرین روزنامه رسمی','name'=>'card_front_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'تصویر روزنامه صاحبان امضاء','name'=>'card_back_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'تصویر اساسنامه','name'=>'paper_picture','type'=>'upload','validation'=>'required|image','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'شماره نامه ارزش افزوده','name'=>'vat_number','type'=>'text','validation'=>'min:3|max:32','width'=>'col-sm-9'];
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

	    }



	    //By the way, you can still create your own method in here... :) 


	}
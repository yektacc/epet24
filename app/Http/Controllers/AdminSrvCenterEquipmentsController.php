<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminSrvCenterEquipmentsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {
			$cms_user_id = CRUDBooster::myId();
			$seller_id = DB::table('srv_centers')
				->where('cms_user_id','=',$cms_user_id)
				->value('seller_id');
			$isAdmin = CRUDBooster::isSuperadmin();
			 if ($cms_user_id == 2){
                        $isTopadmin = $cms_user_id - 1;
                        }

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
			$this->table = "srv_center_equipments";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"نام مرکز","name"=>"center_department_id","join"=>"srv_centers,center_name"];
			$this->col[] = ["label"=>"استان","name"=>"(select province_name from provinces where srv_centers.province_id = provinces.id) as province_name","join"=>"srv_centers,province_id"];
			$this->col[] = ["label"=>"شهر","name"=>"(select city_name from cities where srv_centers.city_id = cities.id) as city_name","join"=>"srv_centers,city_id"];
//			$this->col[] = ["label"=>"شهر","name"=>"(select city_name from cities where srv_centers.city_id = cities.id) as city_name","join"=>"srv_centers,city_id"];
			$this->col[] = ["label"=>"نام تجهیزات","name"=>"equipment"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			if ($isAdmin){
			$this->form[] = ['label'=>'جستجوی مرکز مورد نظر','name'=>'center_department_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'srv_centers','datamodal_columns'=>'center_name,center_description','datamodal_size'=>'large'];
			}elseif ($isTopadmin) {
			$this->form[] = ['label'=>'جستجوی مرکز مورد نظر','name'=>'center_department_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'srv_centers','datamodal_columns'=>'center_name,center_description','datamodal_size'=>'large'];
			} else {
			$this->form[] = ['label'=>'جستجوی مرکز مورد نظر','name'=>'center_department_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'srv_centers','datamodal_columns'=>'center_name,center_description','datamodal_size'=>'large','datamodal_where'=>'seller_id = '.$seller_id];
			}
			$this->form[] = ['label'=>'عنوان تجهیزات','name'=>'equipment','type'=>'checkbox','validation'=>'required','width'=>'col-sm-10','datatable'=>'srv_equipments,equipment_name'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'جستجوی مرکز مورد نظر','name'=>'center_department_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'srv_centers','datamodal_columns'=>'center_name,city_name,district_name,center_description','datamodal_size'=>'large'];
			//$this->form[] = ['label'=>'عنوان تجهیزات','name'=>'equipment_id','type'=>'checkbox','validation'=>'required','width'=>'col-sm-10','datatable'=>'srv_equipments,equipment_name'];
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
			$cms_user_id = CRUDBooster::myId();

			if ($cms_user_id == 2){
			$isTopadmin = $cms_user_id - 1;
			}
//			$me = CRUDBooster::myName();

			$srv_centers_sellerId = DB::table('srv_centers')
					->where('cms_user_id','=',$cms_user_id)
					->pluck('seller_id')
					->first();
		
			$isAdmin = CRUDBooster::isSuperadmin();

			if ($isAdmin) {
			}
			elseif ($isTopadmin) {
			}
			else {
			$query->where('srvcenter_sellerid','=',$srv_centers_sellerId);
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
			$cms_user_id = CRUDBooster::myId();
			$app_seller_id = DB::table('srv_centers')
			->where('cms_user_id','=',$cms_user_id)
			->pluck('seller_id')
			->first();

			
			$equipment_id = DB::table('srv_center_equipments')
			//->where('srvcenter_sellerid',$app_seller_id)
			->orderby('id','DESC')
			->pluck('id')
			->first();

			DB::table('srv_center_equipments')
			->where('id',$equipment_id)
			->update(['srvcenter_sellerid'=>$app_seller_id]);

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
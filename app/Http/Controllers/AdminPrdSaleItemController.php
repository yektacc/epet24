<?php namespace App\Http\Controllers;

	use App\Helper\AppConfig;
    use Carbon\Carbon;
    use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPrdSaleItemController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "prd_sale_item";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"کد تنوع","name"=>"variant_id","join"=>"prd_product_variants,variant_code"];
			$this->col[] = ["label"=>"عنوان محصول","name"=>"variant_id","join"=>"product_variants,product_title_fa"];
			$this->col[] = ["label"=>"نام فروشگاه","name"=>"seller_id","join"=>"srv_centers,center_name"];
			$this->col[] = ["label"=>"محل فروشگاه","name"=>"(select cities.city_name from cities where id=srv_centers.city_id) as city_name"];
			$this->col[] = ["label"=>"موجودی","name"=>"is_exist","join"=>"prd_product_existence,title","callback_php"=>'($row->is_exist=="Active")?"<span class=\"label label-danger\">ناموجود</span>":"<span class=\"label label-success\">موجود</span>"'];
			$this->col[] = ["label"=>"قیمت (تومان)","name"=>"sale_price"];
			$this->col[] = ["label"=>"تخفیف (تومان)","name"=>"sale_discount"];
			$this->col[] = ["label"=>"درصد تخفیف","name"=>"discount_percent"];
			$this->col[] = ["label"=>"زمان ارسال","name"=>"shipment_time"];
			$this->col[] = ["label"=>"عنوان تگ","name"=>"tag_id","join"=>"prd_tagname,tag_name"];
			$this->col[] = ["label"=>"تگ فعال است؟","name"=>"tag_is_active","join"=>"answers_yesno,answers_text","callback_php"=>'($row->tag_is_active=="Active")?"<span class=\"label label-danger\">خیر</span>":"<span class=\"label label-success\">بله</span>"'];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'محصول','name'=>'variant_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'product_variants','datamodal_columns'=>'product_code,product_brand,product_title_fa,color_title','datamodal_size'=>'large'];
			$this->form[] = ['label'=>'فروشگاه','name'=>'seller_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'srv_centers','datamodal_columns'=>'center_name,center_description','datamodal_size'=>'large','datamodal_where'=>'center_type=4 AND deleted_at IS NULL'];
			$this->form[] = ['label'=>'زمان ارسال به ساعت','name'=>'shipment_time','type'=>'number','validation'=>'required','width'=>'col-sm-1'];
			$this->form[] = ['label'=>'وضعیت موجود بودن','name'=>'is_exist','type'=>'radio','validation'=>'required','width'=>'col-sm-10','dataenum'=>'0|ناموجود;1|موجود'];
			$this->form[] = ['label'=>'عنوان گارانتی','name'=>'guaranty_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'prd_guarantees,title'];
			$this->form[] = ['label'=>'قیمت اصلی (تومان)','name'=>'main_price','type'=>'money','width'=>'col-sm-2'];
			$this->form[] = ['label'=>'قیمت فروش (تومان)','name'=>'sale_price','type'=>'money','validation'=>'required','width'=>'col-sm-2'];
//			$this->form[] = ['label'=>'تخفیف (تومان)','name'=>'sale_discount','type'=>'money','validation'=>'required','width'=>'col-sm-2'];
			$this->form[] = ['label'=>'موجودی انبار','name'=>'stock_quantity','type'=>'number','validation'=>'required','width'=>'col-sm-2'];
			$this->form[] = ['label'=>' تعداد قابل سفارش','name'=>'maximum_orderable','type'=>'number','width'=>'col-sm-2'];
			$this->form[] = ['label'=>'توضیحات خاص','name'=>'description','type'=>'textarea','width'=>'col-sm-10','placeholder'=>'توضیحات خاص در مورد محصول شامل نحوه حمل، زمان ارسال و ...'];
			$this->form[] = ['label'=>'عنوان تگ','name'=>'tag_id','type'=>'datamodal','width'=>'col-sm-9','datamodal_table'=>'prd_tagname','datamodal_columns'=>'tag_name,description'];
			$this->form[] = ['label'=>'قیمت تگ','name'=>'tag_price','type'=>'money','width'=>'col-sm-2'];
			$this->form[] = ['label'=>'شروع تگ','name'=>'tag_starttime','type'=>'datetime','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'پایان تگ','name'=>'tag_stoptime','type'=>'datetime','width'=>'col-sm-9'];
			$this->form[] = ['label'=>'تگ فعال است؟','name'=>'tag_is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-9','dataenum'=>'1|بله;0|خیر'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'محصول','name'=>'variant_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'product_variants','datamodal_columns'=>'product_code,product_brand,product_title_fa,color_title','datamodal_size'=>'large'];
			//$this->form[] = ['label'=>'فروشگاه','name'=>'seller_id','type'=>'datamodal','validation'=>'required','width'=>'col-sm-10','datamodal_table'=>'srv_centers','datamodal_columns'=>'center_name,center_description','datamodal_size'=>'large','datamodal_where'=>'center_type=4 AND deleted_at IS NULL'];
			//$this->form[] = ['label'=>'زمان ارسال به ساعت','name'=>'shipment_time','type'=>'number','validation'=>'required','width'=>'col-sm-1'];
			//$this->form[] = ['label'=>'وضعیت موجود بودن','name'=>'is_exist','type'=>'radio','validation'=>'required','width'=>'col-sm-10','dataenum'=>'0|ناموجود;1|موجود'];
			//$this->form[] = ['label'=>'عنوان گارانتی','name'=>'guaranty_id','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'prd_guarantees,title'];
			//$this->form[] = ['label'=>'قیمت فروش (تومان)','name'=>'sale_price','type'=>'money','validation'=>'required','width'=>'col-sm-2'];
			//$this->form[] = ['label'=>'تخفیف (تومان)','name'=>'sale_discount','type'=>'money','validation'=>'required','width'=>'col-sm-2'];
			//$this->form[] = ['label'=>'موجودی انبار','name'=>'stock_quantity','type'=>'number','validation'=>'required','width'=>'col-sm-2'];
			//$this->form[] = ['label'=>'توضیحات خاص','name'=>'description','type'=>'textarea','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'عنوان تگ','name'=>'tag_id','type'=>'datamodal','width'=>'col-sm-9','datamodal_table'=>'prd_tagname','datamodal_columns'=>'tag_name,description'];
			//$this->form[] = ['label'=>'قیمت تگ','name'=>'tag_price','type'=>'money','width'=>'col-sm-2'];
			//$this->form[] = ['label'=>'شروع تگ','name'=>'tag_starttime','type'=>'datetime','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'پایان تگ','name'=>'tag_stoptime','type'=>'datetime','width'=>'col-sm-9'];
			//$this->form[] = ['label'=>'تگ فعال است؟','name'=>'tag_is_active','type'=>'radio','validation'=>'required','width'=>'col-sm-9','dataenum'=>'1|بله;0|خیر'];
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
//	    echo $userId;
	    $isAdmin = CRUDBooster::isSuperadmin();
	    $storeAssignedtoUser = DB::table('srv_centers')
	            ->where('cms_user_id', '=', $userId)
                    ->first();
	    if ($isAdmin) {

	    }else {
	    $query->where('srv_centers.cms_user_id',$storeAssignedtoUser->cms_user_id);
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
            if ($postdata['main_price'] == "") {
                $postdata['main_price'] = $postdata['sale_price'];
            }

            if($postdata['variant_id'] == ''|| $postdata['seller_id'] == '')
                CRUDBooster::redirect(CRUDBooster::mainPath('add'),"محصول یا فروشگاه انتخاب نشده است!","warning");

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
            if ($postdata['main_price'] == "") {
                $postdata['main_price'] = $postdata['sale_price'];
            }

            if($postdata['variant_id'] == ''|| $postdata['seller_id'] == '')
                CRUDBooster::redirect(CRUDBooster::mainPath('add'),"محصول یا فروشگاه انتخاب نشده است!","warning");
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
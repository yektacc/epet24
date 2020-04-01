<?php namespace App\Http\Controllers;

	use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Route;
    use Session;
    use Carbon\carbon;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminAppUsersController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "firstname";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "app_user_roles";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"نوع کاربر","name"=>"role_id","join"=>"app_roles,title"];
//			$this->col[] = ["label"=>"نوع کاربر","name"=>"(select app_roles.title from app_roles join app_roles on app_roles.id = app_user_roles.role_id where app_user_roles.role_id = 2) as role_id"];
			$this->col[] = ["label"=>"نام","name"=>"user_id","join"=>"app_users,firstname"];
			$this->col[] = ["label"=>"نام خانوادگی","name"=>"user_id","join"=>"app_users,lastname"];
			$this->col[] = ["label"=>"آدرس ایمیل","name"=>"user_id","join"=>"app_users,email"];
			$this->col[] = ["label"=>"شماره تلفن همراه","name"=>"user_id","join"=>"app_users,mobile_number"];
			$this->col[] = ["label"=>"وضعیت فعال بودن","name"=>"(select activation_status.title from activation_status join app_users on app_users.is_active = activation_status.id where app_users.id = app_user_roles.user_id) as is_active"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'نوع کاربر','name'=>'role_id','type'=>'select','validation'=>'required','width'=>'col-sm-10','datatable'=>'app_roles,title','datatable_where'=>'id=2'];
			$this->form[] = ['label'=>'شماره تلفن همراه','name'=>'mobile_number','type'=>'number','validation'=>'required|numeric','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'نام','name'=>'firstname','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'نام خانوادگی','name'=>'lastname','type'=>'text','width'=>'col-sm-10'];
//			$this->form[] = ['label'=>'رمز عبور','name'=>'password','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'آدرس ایمیل','name'=>'email','type'=>'email','width'=>'col-sm-10','placeholder'=>'لطفا آدرس ایمیل را صحیح و غیرتکراری وارد کنید'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'نوع کاربر','name'=>'role_id','type'=>'select','validation'=>'required','width'=>'col-sm-9','datatable'=>'app_roles,title'];
			//$this->form[] = ['label'=>'نام','name'=>'firstname','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'نام خانوادگی','name'=>'lastname','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'رمز عبور','name'=>'password','type'=>'password','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'آدرس ایمیل','name'=>'email','type'=>'email','width'=>'col-sm-10','placeholder'=>'لطفا آدرس ایمیل را صحیح وارد کنید'];
			//$this->form[] = ['label'=>'شماره تلفن همراه','name'=>'mobile_number','type'=>'number','validation'=>'required|numeric','width'=>'col-sm-10'];
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
	        $this->addaction = array(
                ["label"=>"","icon"=>"fa fa-eye","color"=>"primary","url"=>CRUDBooster::mainpath('detail/[id]')],
            );


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
            $postdata['password'] = Hash::make($postdata['password']);
            DB::transaction(function () use (&$postdata){
                $user_id = DB::table('app_users')->insertGetId([
                    'id' => NULL,
                    'firstname' => $postdata['firstname'],
                    'lastname' => $postdata['lastname'],
                    'password' => $postdata['password'],
                    'email' => $postdata['email'],
                    'mobile_number' => $postdata['mobile_number'],
                    'is_active' => 1,
                //    'national_code' => NULL,
                ],'id');

                DB::table('app_user_roles')->insertGetId([
                    'id' => NULL,
                    'user_id' => $user_id,
                    'role_id' => 2
                ],'id');

            });
//
            CRUDBooster::redirect(CRUDBooster::mainPath(),NULL,"success");
//
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
            $postdata['password'] = Hash::make($postdata['password']);
            DB::transaction(function () use (&$postdata,&$id){
            
                $user_role = DB::table('app_user_roles')
		    ->where('id',$id)
		    ->first();
                $user_id = $user_role->user_id;

                DB::table('app_users')
                ->where('id',$user_id)
                ->update([
                    'firstname' => $postdata['firstname'],
                    'lastname' => $postdata['lastname'],
                    'password' => $postdata['password'],
                    'email' => $postdata['email'],
                    'mobile_number' => $postdata['mobile_number'],
                    'is_active' => 1,
                //    'national_code' => NULL,
                ]);

                DB::table('app_user_roles')
                ->where('id',$id)
                ->update([
                    'role_id' => 2
                ]);
            });
//
            CRUDBooster::redirect(CRUDBooster::mainPath(),NULL,"success");
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
	        $user_role = DB::table('app_user_roles')
	        ->where('id',$id)
	        ->first();
                
                $user_id = $user_role->user_id;
                
                DB::table('app_users')
                ->where('id',$user_id)
                ->update([
                    'deleted_at' => Carbon::now(),
                ]);


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


	    public function getDetail($id)
        {
            if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

            $data = [];
            $data['page_title'] = 'جزئیات اطلاعات کاربر';

            $ids = DB::table('app_user_roles')->where('id',$id)->get()->pluck('user_id');
            $users = DB::table('app_users')->where('id',$ids[0])->get();
            $fields['نام'] = $users[0]->firstname;
            $fields['نام خانوادگی'] = $users[0]->lastname;
            $fields['آدرس ایمیل'] = $users[0]->email;
            $fields['شماره تلفن همراه'] = $users[0]->mobile_number;
            $data['fields'] = $fields;
            $this->cbView('custom_detail_view',$data);
        }

        public function getEdit($id)
        {
            parent::cbLoader();
            $user_role = DB::table('app_user_roles')->where('id',$id)->first();
            $user = DB::table('app_users')->where('id',$user_role->user_id)->first();
            $row['id'] = $id;
            $row['role_id'] = $user_role->role_id;
            $row['user_id'] = $user_role->user_id;
            $row['firstname'] = $user->firstname;
            $row['lastname'] = $user->lastname;
            $row['password'] = $user->password;
            $row['email'] = $user->email;
            $row['mobile_number'] = $user->mobile_number;
            $row = (object)$row;

//            var_dump($row);
//            die;

            if (! CRUDBooster::isRead() && $this->global_privilege == false || $this->button_edit == false) {
                CRUDBooster::insertLog(trans("crudbooster.log_try_edit", [
                    'name' => $row->{$this->title_field},
                    'module' => CRUDBooster::getCurrentModule()->name,
                ]));
                CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
            }

            $page_menu = Route::getCurrentRoute()->getActionName();
            $page_title = trans("crudbooster.edit_data_page_title", ['module' => CRUDBooster::getCurrentModule()->name, 'name' => $row->{$this->title_field}]);
            $command = 'edit';
            Session::put('current_row_id', $id);

            return view('crudbooster::default.form', compact('id', 'row', 'page_menu', 'page_title', 'command'));
        }

        //By the way, you can still create your own method in here... :)


	}
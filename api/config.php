<?php
class config{
	function db_config(){
		//Configure Database
		$db_addr = 'localhost'; // Database Address
		$db_user = 'rtazecom_admin'; // Database User
		$db_pass = 'Alpha403@114'; // Database Password
		$db_name = 'rtazecom_rtaze'; // Database Name
		//Configure Database
		
		$data = ['db_addr'=>$db_addr,'db_user'=>$db_user,'db_pass'=>$db_pass,'db_name'=>$db_name];
		return $data;
	}
	
	function img_dir($module){
		if($module=="brands"){
			//Brand Path and size configurations
			$original_path = 'admin/images/brands/original/';
			$path2 = 'admin/images/brands/thumbnail/';
			$path2_width = '50';
			$path2_height = '50';
			//Brand Path and size configurations
			$path2_config = array($path2,$path2_width,$path2_height);
			$data = [$original_path,$path2_config];
		}
		elseif($module=="category"){
			//Category Path and size configurations
			$original_path = 'admin/images/category/original/';
			$path2 = 'admin/images/category/comp/';
			$path2_width = '200';
			$path2_height = '200';
			$path2_config = array($path2,$path2_width,$path2_height);
			$path3 = 'admin/images/category/thumbnail/';
			$path3_width = '50';
			$path3_height = '50';
			$path3_config = array($path3,$path4_width,$path3_height);
			//Brand Path and size configurations
			$data = [$original_path,$path2_config,$path3_config];
		}
		elseif($module=="subcategory"){
			//Sub Category Path and size configurations
			$original_path = 'admin/images/subcat/original/';
			$path2 = 'admin/images/subcat/comp/';
			$path2_width = '200';
			$path2_height = '200';
			$path2_config = array($path2,$path2_width,$path2_height);
			$path3 = 'admin/images/subcat/thumbnail/';
			$path3_width = '50';
			$path3_height = '50';
			$path3_config = array($path3,$path4_width,$path3_height);
			//Brand Path and size configurations
			$data = [$original_path,$path2_config,$path3_config];
		}
		elseif($module=="variants"){
			$original_path = 'admin/images/variants/original/';
			$path2 = 'admin/images/variants/comp/';
			$path2_width = '200';
			$path2_height = '200';
			$path2_config = array($path2,$path2_width,$path2_height);
			$path3 = 'admin/images/variants/thumbnail/';
			$path3_width = '50';
			$path3_height = '50';
			$path3_config = array($path3,$path4_width,$path3_height);
			//Brand Path and size configurations
			$data = [$original_path,$path2_config,$path3_config];
		}
		elseif($module=="collections"){
			$original_path = 'admin/images/collections/original/';
			$path2 = 'admin/images/collections/comp/';
			$path2_width = '200';
			$path2_height = '200';
			$path2_config = array($path2,$path2_width,$path2_height);
			$path3 = 'admin/images/collections/thumbnail/';
			$path3_width = '50';
			$path3_height = '50';
			$path3_config = array($path3,$path4_width,$path3_height);
			//Brand Path and size configurations
			$data = [$original_path,$path2_config,$path3_config];
		}
		
		return $data;
	}
}

?>
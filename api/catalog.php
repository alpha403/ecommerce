<?php
require_once 'common.php';
class catalog extends common{
	function __construct($req_data){
		$this->req = $req_data;
	}
	
	function main($image=""){
		$res_data = ["status"=>"fail"];
		if($this->req['req']=="upload_image"){
			$res_data = $this->upload_image($image);
		}
		elseif($this->req['req']=='add_brand'){
			$res_data = $this->add_brand();
		}
		elseif($this->req['req']=='get_brand_by_id'){
			$res_data = $this->get_brand_by_id();
		}
		elseif($this->req['req']=='get_all_brands'){
			$res_data = $this->get_all_brands();
		}
		elseif($this->req['req']=='get_img_path'){
			$res_data = $this->get_img_path();
		}
		elseif($this->req['req']=='edit_brand'){
			$res_data = $this->edit_brand();
		}
		elseif($this->req['req']=='deactivate_brand'){
			$res_data = $this->deactivate_brand();
		}
		elseif($this->req['req']=='activate_brand'){
			$res_data = $this->activate_brand();
		}
		elseif($this->req['req']=='brand_paging'){
			$res_data = $this->brand_paging();
		}
		elseif($this->req['req']=='add_category'){
			$res_data = $this->add_category();
		}
		elseif($this->req['req']=='get_category_by_id'){
			$res_data = $this->get_category_by_id();
		}
		elseif($this->req['req']=='get_all_categories'){
			$res_data = $this->get_all_categories();
		}
		elseif($this->req['req']=='edit_category'){
			$res_data = $this->edit_category();
		}
		elseif($this->req['req']=='deactivate_category'){
			$res_data = $this->deactivate_category();
		}
		elseif($this->req['req']=='activate_category'){
			$res_data = $this->activate_category();
		}
		elseif($this->req['req']=='category_paging'){
			$res_data = $this->category_paging();
		}
		elseif($this->req['req']=='add_subcat'){
			$res_data = $this->add_subcat();
		}
		elseif($this->req['req']=='get_subcat_by_id'){
			$res_data = $this->get_subcat_by_id();
		}
		elseif($this->req['req']=='get_all_subcat'){
			$res_data = $this->get_all_subcat();
		}
		elseif($this->req['req']=='edit_subcat'){
			$res_data = $this->edit_subcat();
		}
		elseif($this->req['req']=='deactivate_subcat'){
			$res_data = $this->deactivate_subcat();
		}
		elseif($this->req['req']=='activate_subcat'){
			$res_data = $this->activate_subcat();
		}
		elseif($this->req['req']=='subcat_paging'){
			$res_data = $this->subcat_paging();
		}
		elseif($this->req['req']=='get_subcat_by_catid'){
			$res_data = $this->get_subcat_by_catid();
		}
		elseif($this->req['req']=='add_product'){
			$res_data = $this->add_product();
		}
		elseif($this->req['req']=='get_product_by_id'){
			$res_data = $this->get_product_by_id();
		}
		elseif($this->req['req']=='get_all_products'){
			$res_data = $this->get_all_products();
		}
		elseif($this->req['req']=='get_product_by_id_adv'){
			$res_data = $this->get_product_by_id_adv();
		}
		elseif($this->req['req']=='edit_product'){
			$res_data = $this->edit_product();
		}
		elseif($this->req['req']=='deactivate_product'){
			$res_data = $this->deactivate_product();
		}
		elseif($this->req['req']=='activate_product'){
			$res_data = $this->activate_product();
		}
		elseif($this->req['req']=='product_paging'){
			$res_data = $this->product_paging();
		}
		elseif($this->req['req']=='add_variant'){
			$res_data = $this->add_variant();
		}
		elseif($this->req['req']=='getVariantByProduct'){
			$res_data = $this->getVariantByProduct();
		}
		elseif($this->req['req']=='update_variant'){
			$res_data = $this->update_variant();
		}
		elseif($this->req['req']=='add_collection'){
			$res_data = $this->add_collection();
		}
		elseif($this->req['req']=='get_coll_by_id'){
			$res_data = $this->get_coll_by_id();
		}
		elseif($this->req['req']=='get_all_colls'){
			$res_data = $this->get_all_colls();
		}
		elseif($this->req['req']=='edit_coll'){
			$res_data = $this->edit_coll();
		}
		elseif($this->req['req']=='deactivate_coll'){
			$res_data = $this->deactivate_coll();
		}
		elseif($this->req['req']=='activate_coll'){
			$res_data = $this->activate_coll();
		}
		elseif($this->req['req']=='coll_paging'){
			$res_data = $this->coll_paging();
		}
		elseif($this->req['req']=='addToCollection'){
			$res_data = $this->addToCollection();
		}
		elseif($this->req['req']=='get_all_coll_products'){
			$res_data = $this->get_all_coll_products();
		}
		elseif($this->req['req']=='removeFromCollection'){
			$res_data = $this->removeFromCollection();
		}
		elseif($this->req['req']=='productByCollection'){
			$res_data = $this->productByCollection();
		}
		
		
		
		
		
		return $res_data;
	}
	
	function upload_image($image){
		$module = $this->req['mod'];
		if($this->session_exist()){
			$allowed = array('png', 'jpg','jpeg');
			$data = $this->upload_files($image,$allowed,$module);
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session Time Out"];
		}
		return $data;
	}
	
	function add_brand(){
		if($this->session_exist()){
			$con = $this->db_connect();
			$brand_name = mysqli_real_escape_string($con,$this->req['brand_name']);
			$logo = $this->req['images'];
			if($brand_name==""){
				$data = ["status"=>"fail","msg"=>"Brand name can not be empty."];
			}
			elseif(sizeof($logo)==0){
				$data = ["status"=>"fail","msg"=>"Logo can not be empty."];
			}
			else{
				$sql = "SELECT name FROM brands WHERE name='$brand_name'";
				$res = mysqli_query($con,$sql);
				$count = mysqli_num_rows($res);
				if($count>0){
					$data = ["status"=>"fail","msg"=>"Brand already exists"];
				}
				else{
					$sql = "INSERT INTO brands (name,logo,status) VALUES ('$brand_name','$logo[0]',1)";
					$res = mysqli_query($con,$sql);
					$brand_id = mysqli_insert_id($con);
					if($res){
						$data = ["status"=>"success","msg"=>"Brand added successfuly","brand_id"=>$brand_id];
					}
					else{
						$data = ["status"=>"fail","msg"=>"Someting went wrong"];
					}
				}
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session expired"];
		}
		return $data;
	}
	
	function get_brand_by_id(){
		$brand_id = $this->req['brand_id'];
		$con = $this->db_connect();
		$sql = "SELECT * FROM brands WHERE id='$brand_id'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$brand_data = [$row['id'],$row['name'],$row['logo'],$row['status']];
		$data = ["status"=>"success","msg"=>"Brand Fetched Successfully","brand_data"=>$brand_data];
		return $data;
	}
	
	function get_all_brands(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$offset = $this->req['offset'];
		$limit = $this->req['limit'];
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM brands WHERE status LIKE '$status%' ORDER BY id DESC LIMIT $limit OFFSET $offset";
		}
		else{
			$sql = "SELECT * FROM brands WHERE status LIKE '$status%' AND name LIKE '$query%' LIMIT $limit OFFSET $offset";
		}
		$res = mysqli_query($con,$sql);
		$brand_data = array();
		while($r=mysqli_fetch_array($res)){
			$brand_data_array = array();
			$brand_data_array[] = $r['id'];
			$brand_data_array[] = $r['name'];
			$brand_data_array[] = $r['logo'];
			$brand_data_array[] = $r['status'];
			$brand_data[] = $brand_data_array;
		}
		$data = ["status"=>"success","msg"=>"Brands Fetched Successfully","brand_data"=>$brand_data,"sql"=>$sql];
		return $data;
	}
	
	function get_img_path(){
		$module = $this->req['mod'];
		$paths = $this->file_path($module);
		$data = ["status"=>"success","msg"=>"Paths fetched successfuly","paths"=>$paths];
		return $data;
	}
	
	function edit_brand(){
		if($this->session_exist()){
			$brand_name = $this->req['brand_name'];
			$logo = $this->req['images'];
			$brand_id = $this->req['brand_id'];
			$con = $this->db_connect();
			$sql = "SELECT logo FROM brands WHERE id='$brand_id'";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$curr_logo = $row['logo'];
			
			if($curr_logo!=$logo[0]){
				$this->del_image("brands",$curr_logo);
			}
			$sql = "UPDATE brands SET logo='$logo[0]',name='$brand_name' WHERE id='$brand_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Brand Updated Successfully"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
			return $data;
		}	
	}
	
	function deactivate_brand(){
		if($this->session_exist()){
			$brand_id = $this->req['brand_id'];
			$con = $this->db_connect();
			$sql = "UPDATE brands SET status=0 WHERE id='$brand_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Brand status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function activate_brand(){
		if($this->session_exist()){
			$brand_id = $this->req['brand_id'];
			$con = $this->db_connect();
			$sql = "UPDATE brands SET status=1 WHERE id='$brand_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Brand status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function brand_paging(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM brands WHERE status LIKE '$status%' ORDER BY id DESC";
		}
		else{
			$sql = "SELECT * FROM brands WHERE status LIKE '$status%' AND name LIKE '$query%' ORDER BY id DESC";
		}
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		$page_count = $count/2;
		$page_count = ceil($page_count);
	
		$data = ["status"=>"success","msg"=>"Page count calculated","page_count"=>$page_count];
		
		return $data;
	}
	
	function add_category(){
		if($this->session_exist()){
			$category_name = $this->req['category_name'];
			$category_image = $this->req['images'];
			if($category_name==""){
				$data = ["status"=>"fail","msg"=>"Category name can not be empty."];
			}
			elseif(sizeof($category_image)==0){
				$data = ["status"=>"fail","msg"=>"Category image can not be empty."];
			}
			else{
				$con = $this->db_connect();
				$sql = "SELECT name FROM categories WHERE name='$category_name'";
				$res = mysqli_query($con,$sql);
				$count = mysqli_num_rows($res);
				if($count>0){
					$data = ["status"=>"fail","msg"=>"Category already exists"];
				}
				else{
					$sql = "INSERT INTO categories (name,image,status) VALUES ('$category_name','$category_image[0]',1)";
					$res = mysqli_query($con,$sql);
					$category_id = mysqli_insert_id($con);
					if($res){
						$data = ["status"=>"success","msg"=>"Category added successfuly","category_id"=>$category_id];
					}
					else{
						$data = ["status"=>"fail","msg"=>"Someting went wrong"];
					}
				}
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session expired"];
		}
		return $data;
	}
	
	function get_category_by_id(){
		$category_id = $this->req['category_id'];
		$con = $this->db_connect();
		$sql = "SELECT * FROM categories WHERE id='$category_id'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$category_data = [$row['id'],$row['name'],$row['image'],$row['status']];
		$data = ["status"=>"success","msg"=>"Category Fetched Successfully","category_data"=>$category_data];
		return $data;
	}
	
	function get_all_categories(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$offset = $this->req['offset'];
		$limit = $this->req['limit'];
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM categories WHERE status LIKE '$status%' ORDER BY id DESC LIMIT $limit OFFSET $offset";
		}
		else{
			$sql = "SELECT * FROM categories WHERE status LIKE '$status%' AND name LIKE '$query%' LIMIT $limit OFFSET $offset";
		}
		$res = mysqli_query($con,$sql);
		$category_data = array();
		while($r=mysqli_fetch_array($res)){
			$category_data_array = array();
			$category_data_array[] = $r['id'];
			$category_data_array[] = $r['name'];
			$category_data_array[] = $r['image'];
			$category_data_array[] = $r['status'];
			$category_data[] = $category_data_array;
		}
		$data = ["status"=>"success","msg"=>"Categories Fetched Successfully","category_data"=>$category_data];
		return $data;
	}
	
	function edit_category(){
		if($this->session_exist()){
			$category_name = $this->req['category_name'];
			$category_image = $this->req['images'];
			$category_id = $this->req['category_id'];
			$con = $this->db_connect();
			$sql = "SELECT image FROM categories WHERE id='$category_id'";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$curr_image = $row['image'];
			
			if($curr_image!=$category_image[0]){
				$this->del_image("category",$curr_logo);
			}
			$sql = "UPDATE categories SET image='$category_image[0]',name='$category_name' WHERE id='$category_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Category Updated Successfully"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
			return $data;
		}	
	}
	
	function deactivate_category(){
		if($this->session_exist()){
			$category_id = $this->req['category_id'];
			$con = $this->db_connect();
			$sql = "UPDATE categories SET status=0 WHERE id='$category_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"category status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function activate_category(){
		if($this->session_exist()){
			$category_id = $this->req['category_id'];
			$con = $this->db_connect();
			$sql = "UPDATE categories SET status=1 WHERE id='$category_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Category status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}	
	
	function category_paging(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM categories WHERE status LIKE '$status%' ORDER BY id DESC";
		}
		else{
			$sql = "SELECT * FROM categories WHERE status LIKE '$status%' AND name LIKE '$query%' ORDER BY id DESC";
		}
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		$page_count = $count/2;
		$page_count = ceil($page_count);
	
		$data = ["status"=>"success","msg"=>"Page count calculated","page_count"=>$page_count];
		
		return $data;
	}
	
	function add_subcat(){
		if($this->session_exist()){
			$category_name = $this->req['category_name'];
			$subcat_name = $this->req['subcat_name'];
			$subcat_image = $this->req['images'];
			if($category_name==""){
				$data = ["status"=>"fail","msg"=>"Category name can not be empty."];
			}
			elseif($subcat_name==""){
				$data = ["status"=>"fail","msg"=>"Sub Category name can not be empty."];
			}
			elseif(sizeof($subcat_image)==0){
				$data = ["status"=>"fail","msg"=>"Sub Category image can not be empty."];
			}
			else{
				$con = $this->db_connect();
				$sql = "SELECT name FROM sub_categories WHERE name='$subcat_name'";
				$res = mysqli_query($con,$sql);
				$count = mysqli_num_rows($res);
				if($count>0){
					$data = ["status"=>"fail","msg"=>"Sub Category already exists"];
				}
				else{
					
					$sql = "SELECT id FROM categories WHERE name='$category_name'";
					$res = mysqli_query($con,$sql);
					$cat_count = mysqli_num_rows($res);
					
					if($cat_count>0){
						$row = mysqli_fetch_array($res);
						$category_id = $row['id'];
						$sql = "INSERT INTO sub_categories (category_id,name,image,status) VALUES ('$category_id','$subcat_name','$subcat_image[0]',1)";
						$res = mysqli_query($con,$sql);
						$subcat_id = mysqli_insert_id($con);
						if($res){
							$data = ["status"=>"success","msg"=>"Sub Category added successfuly","subcat_id"=>$subcat_id];
						}
						else{
							$data = ["status"=>"fail","msg"=>"Someting went wrong"];
						}
					}
					else{
						$data = ["status"=>"fail","msg"=>"Invalid Category"];
					}
				}
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session expired"];
		}
		return $data;
	}
	
	function get_subcat_by_id(){
		$subcat_id = $this->req['subcat_id'];
		$con = $this->db_connect();
		$sql = "SELECT sub_categories.id, sub_categories.name, sub_categories.image, sub_categories.status, categories.name as category FROM sub_categories INNER JOIN categories ON sub_categories.category_id = categories.id WHERE sub_categories.id='$subcat_id'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$subcat_data = [$row['id'],$row['name'],$row['image'],$row['status'],$row['category']];
		$data = ["status"=>"success","msg"=>"Brand Fetched Successfully","subcat_data"=>$subcat_data];
		return $data;
	}
	
	function get_category_by_name($name){
		$con = $this->db_connect();
		$sql = "SELECT * FROM categories WHERE name='$name'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$cat_id = $row['id'];
		$cat_name = $row['name'];
		$cat_image = $row['image'];
		$cat_status = $row['status'];
		$count = mysqli_num_rows($res);
		$data = ["status"=>"success","category_data"=>$cat_status,"count"=>$count];
		return $data;
	}
	
	function get_all_subcat(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$category = $this->req['category'];
		$offset = $this->req['offset'];
		$limit = $this->req['limit'];	
			$con = $this->db_connect();
			if(empty($query)){
				$sql = "SELECT sub_categories.id, sub_categories.name, sub_categories.image, sub_categories.status, categories.name as category FROM sub_categories INNER JOIN categories ON sub_categories.category_id = categories.id WHERE sub_categories.status LIKE '$status%' AND categories.name LIKE '$category%' ORDER BY sub_categories.id LIMIT $limit OFFSET $offset";
			}
			else{
				$sql = "SELECT sub_categories.id, sub_categories.name, sub_categories.image, sub_categories.status, categories.name as category FROM sub_categories INNER JOIN categories ON sub_categories.category_id = categories.id WHERE sub_categories.status LIKE '$status%' AND categories.name LIKE '$category%' AND sub_categories.name LIKE '$query%' ORDER BY sub_categories.id LIMIT $limit OFFSET $offset";
			}
			$res = mysqli_query($con,$sql);
			$subcat_data = array();
			while($r=mysqli_fetch_array($res)){
				$subcat_data_array = array();
				$subcat_data_array[] = $r['id'];
				$subcat_data_array[] = $r['name'];
				$subcat_data_array[] = $r['image'];
				$subcat_data_array[] = $r['status'];
				$subcat_data_array[] = $r['category'];
				$subcat_data[] = $subcat_data_array;
			}
			$data = ["status"=>"success","msg"=>"Sub Categories Fetched Successfully","subcat_data"=>$subcat_data];
		
		return $data;
	}
	
	function edit_subcat(){
		if($this->session_exist()){
			$category_name = $this->req['category_name'];
			$subcat_name = $this->req['subcat_name'];
			$subcat_id = $this->req['subcat_id'];
			$subcat_image = $this->req['images'];
			if($category_name==""){
				$data = ["status"=>"fail","msg"=>"Category name can not be empty."];
			}
			elseif($subcat_name==""){
				$data = ["status"=>"fail","msg"=>"Sub Category name can not be empty."];
			}
			elseif(sizeof($subcat_image)==0){
				$data = ["status"=>"fail","msg"=>"Sub Category image can not be empty."];
			}
			else{
				$con = $this->db_connect();
				$sql = "SELECT name FROM sub_categories WHERE name='$subcat_name'";
				$res = mysqli_query($con,$sql);
				$count = mysqli_num_rows($res);
				if($count>0){
					$row = mysqli_fetch_array($res);
					$fetched_subcat = $row['name'];
					if($fetched_subcat==$subcat_name){
						$allow = "pass";
					}
					else{
						$allow = "fail";
					}
				}
				else{
					$allow = "pass";
				}
					
				if($allow=="pass"){
					$sql = "SELECT id FROM categories WHERE name='$category_name'";
					$res = mysqli_query($con,$sql);
					$cat_count = mysqli_num_rows($res);
					
					if($cat_count>0){
						$row = mysqli_fetch_array($res);
						$category_id = $row['id'];
						$sql = "UPDATE sub_categories SET category_id='$category_id',name='$subcat_name',image='$subcat_image[0]' WHERE id='$subcat_id'";
						$res = mysqli_query($con,$sql);
						if($res){
							$data = ["status"=>"success","msg"=>"Sub Category updated successfuly","sql"=>$sql];
						}
						else{
							$data = ["status"=>"fail","msg"=>"Something went wrong"];
						}
					}
					else{
						$data = ["status"=>"fail","msg"=>"Invalid Category"];
					}
				}
				else{
					$data = ["status"=>"fail","msg"=>"Sub Category with same name already exists"];
				}
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session expired"];
		}
		return $data;
	}
	
	function deactivate_subcat(){
		if($this->session_exist()){
			$subcat_id = $this->req['subcat_id'];
			$con = $this->db_connect();
			$sql = "UPDATE sub_categories SET status=0 WHERE id='$subcat_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Sub category status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function activate_subcat(){
		if($this->session_exist()){
			$subcat_id = $this->req['subcat_id'];
			$con = $this->db_connect();
			$sql = "UPDATE sub_categories SET status=1 WHERE id='$subcat_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Sub Category status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function subcat_paging(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$category = $this->req['category'];
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT sub_categories.id, sub_categories.name, sub_categories.image, sub_categories.status, categories.name as category FROM sub_categories INNER JOIN categories ON sub_categories.category_id = categories.id WHERE sub_categories.status LIKE '$status%' AND categories.name LIKE '$category%' ORDER BY sub_categories.id DESC";
		}
		else{
			$sql = "SELECT sub_categories.id, sub_categories.name, sub_categories.image, sub_categories.status, categories.name as category FROM sub_categories INNER JOIN categories ON sub_categories.category_id = categories.id WHERE sub_categories.status LIKE '$status%' AND categories.name LIKE '$category%' AND sub_categories.name LIKE '$query%' ORDER BY sub_categories.id DESC";
		}
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		$page_count = $count/2;
		$page_count = ceil($page_count);
	
		$data = ["status"=>"success","msg"=>"Page count calculated","page_count"=>$page_count];
		
		return $data;
	}
	
	function get_subcat_by_catid(){
		$category_id = $this->req['category_id'];
		$con = $this->db_connect();
		$sql = "SELECT id,name,image,status FROM sub_categories WHERE category_id='$category_id'";
		$res = mysqli_query($con,$sql);
		$subcat_data = array();
		while($r=mysqli_fetch_array($res)){
			$subcat = array();
			$subcat[] = $r['id'];
			$subcat[] = $r['name'];
			$subcat[] = $r['image'];
			$subcat[] = $r['status'];
			$subcat_data[] = $subcat;
		}
		$data = ["status"=>"success","msg"=>"sub categories fetched successfuly","subcat_data"=>$subcat_data];
		return $data;
	}
	
	function add_product(){
		if($this->session_exist()){
			$product_name = $this->req['product_name'];
			$product_description = $this->req['product_description'];
			$brand_id = $this->req['brand_id'];
			$category_id = $this->req['category_id'];
			$subcat_id = $this->req['subcat_id'];
			
			if($product_name==""){
				$data = ["status"=>"fail","msg"=>"Product name can not be empty."];
			}
			elseif($product_description==""){
				$data = ["status"=>"fail","msg"=>"Product description can not be empty."];
			}
			elseif($brand_id==""){
				$data = ["status"=>"fail","msg"=>"Brand name can not be empty."];
			}
			elseif($category_id==""){
				$data = ["status"=>"fail","msg"=>"Category name can not be empty."];
			}
			elseif($subcat_id==""){
				$data = ["status"=>"fail","msg"=>"Sub Category name can not be empty."];
			}
			else{
				$con = $this->db_connect();
				$sql = "SELECT name FROM products WHERE name='$product_name' AND brand_id='$brand_id' AND sub_category_id='$subcat_id'";
				$res = mysqli_query($con,$sql);
				$count = mysqli_num_rows($res);
				if($count>0){
					$data = ["status"=>"fail","msg"=>"Product already exists"];
				}
				else{
					
					$sql = "SELECT id FROM sub_categories WHERE id='$subcat_id' AND category_id='$category_id'";
					$res = mysqli_query($con,$sql);
					$cat_count = mysqli_num_rows($res);
					
					if($cat_count>0){
						$sql = "INSERT INTO products (sub_category_id,brand_id,name,description,display_image,status) VALUES ('$subcat_id','$brand_id','$product_name','$product_description','',1)";
						$res = mysqli_query($con,$sql);
						$product_id = mysqli_insert_id($con);
						if($res){
							$data = ["status"=>"success","msg"=>"Product added successfuly","product_id"=>$product_id];
						}
						else{
							$data = ["status"=>"fail","msg"=>"Someting went wrong","sql"=>$sql];
						}
					}
					else{
						$data = ["status"=>"fail","msg"=>"Invalid Category or sub category"];
					}
				}
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session expired"];
		}
		return $data;
	}
	
	function get_product_by_id(){
		$product_id = $this->req['product_id'];
		$con = $this->db_connect();
		$sql = "SELECT products.id,products.name as p_name,products.description,products.status,sub_categories.name as sub_name,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.id='$product_id'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$product_data = [$row['id'],$row['p_name'],$row['description'],$row['display_image'],$row['b_name'],$row['cat_name'],$row['sub_name'],$row['status']];
		$data = ["status"=>"success","msg"=>"Product Fetched Successfully","product_data"=>$product_data,"sql"=>$sql];
		return $data;
	}
	
	function get_all_products(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$brand = $this->req['brand'];
		if($brand==""){
			$brand = "brands.id";
		}
		$category = $this->req['category'];
		if($category==""){
			$category = "categories.id";
		}
		$subcat = $this->req['subcat'];
		if($subcat==""){
			$subcat = "sub_categories.id";
		}
		$offset = $this->req['offset'];
		$limit = $this->req['limit'];	
			$con = $this->db_connect();
			if(empty($query)){
				$sql = "SELECT products.id,products.name as p_name,products.description,products.status,sub_categories.name as sub_name,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.status LIKE '$status%' AND brands.id=$brand AND categories.id=$category AND sub_categories.id=$subcat ORDER BY products.id LIMIT $limit OFFSET $offset";
			}
			else{
				$sql = "SELECT products.id,products.name as p_name,products.description,products.status,sub_categories.name as sub_name,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.status LIKE '$status%' AND brands.id=$brand AND categories.id=$category AND sub_categories.id=$subcat AND products.name LIKE '$query%' ORDER BY products.id LIMIT $limit OFFSET $offset";
			}
			$res = mysqli_query($con,$sql);
			$product_data = array();
			while($r=mysqli_fetch_array($res)){
				$product_data_array = array();
				$product_data_array[] = $r['id'];
				$product_data_array[] = $r['p_name'];
				$product_data_array[] = $r['description'];
				$product_data_array[] = $r['display_image'];
				$product_data_array[] = $r['status'];
				$product_data_array[] = $r['b_name'];
				$product_data_array[] = $r['cat_name'];
				$product_data_array[] = $r['sub_name'];
				$product_data[] = $product_data_array;
			}
			$data = ["status"=>"success","msg"=>"Products Fetched Successfully","product_data"=>$product_data];
		
		return $data;
	}
	
	function get_product_by_id_adv(){
		$product_id = $this->req['product_id'];
		$con = $this->db_connect();
		$sql = "SELECT products.id,products.name as p_name,products.description,products.sub_category_id as subcat_id,products.brand_id as bid,products.status,sub_categories.name as sub_name,categories.id as cat_id,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.id='$product_id'";
		$res = mysqli_query($con,$sql);
		$product_data = array();
		while($r=mysqli_fetch_array($res)){
				$product_data['id'] = $r['id'];
				$product_data['product_name'] = $r['p_name'];
				$product_data['description'] = $r['description'];
				$product_data['display_image'] = $r['display_image'];
				$product_data['status'] = $r['status'];
				$product_data['brand'] = $r['b_name'];
				$product_data['brand_id'] = $r['bid'];
				$product_data['cat_name'] = $r['cat_name'];
				$product_data['cat_id'] = $r['cat_id'];
				$product_data['subcat'] = $r['sub_name'];
				$product_data['subcat_id'] = $r['subcat_id'];
			}
			
		$data = ["status"=>"success","msg"=>"Products Fetched Successfully","product_data"=>$product_data];
		
		return $data;	
	}
	
	function edit_product(){
		if($this->session_exist()){
			$product_name = $this->req['product_name'];
			$product_description = $this->req['product_description'];
			$brand_id = $this->req['brand_id'];
			$category_id = $this->req['category_id'];
			$subcat_id = $this->req['subcat_id'];
			$product_id = $this->req['product_id'];
			
			if($product_name==""){
				$data = ["status"=>"fail","msg"=>"Product name can not be empty."];
			}
			elseif($product_description==""){
				$data = ["status"=>"fail","msg"=>"Product description can not be empty."];
			}
			elseif($brand_id==""){
				$data = ["status"=>"fail","msg"=>"Brand name can not be empty."];
			}
			elseif($category_id==""){
				$data = ["status"=>"fail","msg"=>"Category name can not be empty."];
			}
			elseif($subcat_id==""){
				$data = ["status"=>"fail","msg"=>"Sub Category name can not be empty."];
			}
			else{
				$con = $this->db_connect();
				$sql = "SELECT id FROM sub_categories WHERE id='$subcat_id' AND category_id='$category_id'";
					$res = mysqli_query($con,$sql);
					$cat_count = mysqli_num_rows($res);
					
					if($cat_count>0){
						$sql = "UPDATE products SET sub_category_id='$subcat_id',brand_id='$brand_id',name='$product_name',description='$product_description' WHERE id='$product_id'";
						$res = mysqli_query($con,$sql);
						if($res){
							$data = ["status"=>"success","msg"=>"Product Updated successfuly"];
						}
						else{
							$data = ["status"=>"fail","msg"=>"Someting went wrong"];
						}
					}
					else{
						$data = ["status"=>"fail","msg"=>"Invalid Category or sub category"];
					}
				
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session expired"];
		}
		return $data;
	}
	
	function deactivate_product(){
		if($this->session_exist()){
			$product_id = $this->req['product_id'];
			$con = $this->db_connect();
			$sql = "UPDATE products SET status=0 WHERE id='$product_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Product status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function activate_product(){
		if($this->session_exist()){
			$product_id = $this->req['product_id'];
			$con = $this->db_connect();
			$sql = "UPDATE products SET status=1 WHERE id='$product_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Product status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	
	function product_paging(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$brand = $this->req['brand'];
		if($brand==""){
			$brand = "brands.id";
		}
		$category = $this->req['category'];
		if($category==""){
			$category = "categories.id";
		}
		$subcat = $this->req['subcat'];
		if($subcat==""){
			$subcat = "sub_categories.id";
		}
		
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT products.id,products.name as p_name,products.description,products.status,sub_categories.name as sub_name,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.status LIKE '$status%' AND brands.id=$brand AND categories.id=$category AND sub_categories.id=$subcat ORDER BY products.id";
		}
		else{
			$sql = "SELECT products.id,products.name as p_name,products.description,products.status,sub_categories.name as sub_name,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.status LIKE '$status%' AND brands.id=$brand AND categories.id=$category AND sub_categories.id=$subcat AND products.name LIKE '$query%' ORDER BY products.id";
		}
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		$page_count = $count/2;
		$page_count = ceil($page_count);
	
		$data = ["status"=>"success","msg"=>"Page count calculated","page_count"=>$page_count];
		
		return $data;
	}
	
	function add_variant(){
		$product_id = $this->req['product_id'];
		$sku = $this->req['sku'];
		$size = $this->req['size'];
		$color = $this->req['color'];
		$price = $this->req['price'];
		$discount = $this->req['discount'];
		$stock = $this->req['stock'];
		$images = $this->req['images'];
		if($product_id==""){
			$data = ["status"=>"fail","msg"=>"Something Went Wrong"];
		}
		elseif($sku==""){
			$data = ["status"=>"fail","msg"=>"SKU Can not be blank"];
		}
		elseif($size==""){
			$data = ["status"=>"fail","msg"=>"Size Can not be blank"];
		}
		elseif($color==""){
			$data = ["status"=>"fail","msg"=>"Color Can not be blank"];
		}
		elseif($price==""){
			$data = ["status"=>"fail","msg"=>"Price Can not be blank"];
		}
		elseif($discount==""){
			$data = ["status"=>"fail","msg"=>"Discount Can not be blank"];
		}
		elseif(sizeof($images)==0){
			$data = ["status"=>"fail","msg"=>"Please select atleast one Image"];
		}
		else{
			$con = $this->db_connect();
			mysqli_autocommit($con,FALSE);
			$sql = "SELECT id FROM variants WHERE sku='$sku'";
			$res = mysqli_query($con,$sql);
			$count = mysqli_num_rows($res);
			if($count>0){
				$data = ["status"=>"fail","msg"=>"Variant Already Exists"];
			}
			else{
				$sql = "INSERT INTO variants (product_id,sku,color,size,stock,price,discount,status) VALUES ('$product_id','$sku','$color','$size','$stock','$price','$discount',1)";
				$res = mysqli_query($con,$sql);
				if($res){
					$variant_id = mysqli_insert_id($con);
					$sql = "INSERT INTO variant_images (variant_id,image) VALUES";
					for($i=0;$i<=sizeof($images)-1;$i++){
						if($i==sizeof($images)-1){
							$sql.= "('$variant_id','$images[$i]')";
						}
						else{
							$sql.= "('$variant_id','$images[$i]'),";
						}
					}
					
					$res = mysqli_query($con,$sql);
					if($res){
						if (!mysqli_commit($con)) {
                          $data = ["status"=>"failed","msg"=>"Something went wrong"];
						}
						else{
							$data = ["status"=>"success","msg"=>"Variant Added Successfully","variant_id"=>$variant_id];
						}
					}
					else{
						mysqli_rollback($con);
						$data = ["status"=>"failed","msg"=>"Something went wrong"];
					}
				}
				else{
					mysqli_rollback($con);
                    $data = ["status"=>"failed","msg"=>"Something went wrong"];
				}
			}
			
		}
		return $data;
	}
	
	function getVariantByProduct(){
		$product_id = $this->req['product_id'];
		$con = $this->db_connect();
		$sql = "SELECT variants.id,variants.sku,variants.color,variants.size,variants.stock,variants.price,variants.discount,variants.status,variant_images.image FROM variants INNER JOIN variant_images ON variants.id=variant_images.variant_id WHERE variants.product_id='$product_id'";
		$res = mysqli_query($con,$sql);
		$all_var_data = array();
		while($r=mysqli_fetch_array($res)){
			$count++;
			$variant_data = array();
			$variant_data["id"] = $r['id'];
			$variant_data["sku"] = $r['sku'];
			$variant_data["color"] = $r['color'];
			$variant_data["size"] = $r['size'];
			$variant_data["stock"] = $r['stock'];
			$variant_data["price"] = $r['price'];
			$variant_data["discount"] = $r['discount'];
			$variant_data["status"] = $r['status'];
			$variant_data["image"] = $r['image'];
			$all_var_data[] = $variant_data;
		}
		
		$variants_data = array();
		for($i=0;$i<=sizeof($all_var_data)-1;$i++){
			if($i==0){
				$var_data = array();
				$var_img = array();
				$var_data["id"] = $all_var_data[$i]["id"];
				$var_data["sku"] = $all_var_data[$i]["sku"];
				$var_data["color"] = $all_var_data[$i]["color"];
				$var_data["size"] = $all_var_data[$i]["size"];
				$var_data["stock"] = $all_var_data[$i]["stock"];
				$var_data["price"] = $all_var_data[$i]["price"];
				$var_data["discount"] = $all_var_data[$i]["discount"];
				$var_data["status"] = $all_var_data[$i]["status"];
				$var_img[] = $all_var_data[$i]["image"];
				$var_data["images"] = $var_img;
				$variants_data[] = $var_data;
			}
			else{
				$check_sku = $all_var_data[$i]["sku"];
				$check_status = "false";
				for($x=0;$x<=sizeof($variants_data)-1;$x++){
					if($check_sku==$variants_data[$x]["sku"]){
						$check_status = "true";
						$pos = $x;
					}
				}
				
				if($check_status=="false"){
					$var_data = array();
					$var_img = array();
					$var_data["id"] = $all_var_data[$i]["id"];
					$var_data["sku"] = $all_var_data[$i]["sku"];
					$var_data["color"] = $all_var_data[$i]["color"];
					$var_data["size"] = $all_var_data[$i]["size"];
					$var_data["stock"] = $all_var_data[$i]["stock"];
					$var_data["price"] = $all_var_data[$i]["price"];
					$var_data["discount"] = $all_var_data[$i]["discount"];
					$var_data["status"] = $all_var_data[$i]["status"];
					$var_img[] = $all_var_data[$i]["image"];
					$var_data["images"] = $var_img;
					$variants_data[] = $var_data;
				}
				else{
					$variants_data[$pos]["images"][] = $all_var_data[$i]["image"];
				}
			}
		}
		
		$data = ["status"=>"success","msg"=>"Variants Fetched","variants"=>$variants_data,"paths"=>$this->file_path("variants")];
		return $data;
	}
	
	function update_variant(){
		if($this->session_exist()){
			$images=$this->req['images'];
			$sku = $this->req['sku'];
			$color = $this->req['color'];
			$size= $this->req['size'];
			$stock= $this->req['stock'];
			$price= $this->req['price'];
			$discount= $this->req['discount'];
			$status= $this->req['status'];
			$variant= $this->req['variant'];
			$con = $this->db_connect();
			if($sku==""){
				$data = ["status"=>"fail","msg"=>"SKU can not be blank"];
			}
			elseif($color==""){
				$data = ["status"=>"fail","msg"=>"Color can not be blank"];
			}
			elseif($size==""){
				$data = ["status"=>"fail","msg"=>"Size can not be blank"];
			}
			elseif($stock==""){
				$data = ["status"=>"fail","msg"=>"Stock can not be blank"];
			}
			elseif($price==""){
				$data = ["status"=>"fail","msg"=>"Price can not be blank"];
			}
			elseif($discount==""){
				$data = ["status"=>"fail","msg"=>"Discount can not be blank"];
			}
			elseif($status==""){
				$data = ["status"=>"fail","msg"=>"Status can not be blank"];
			}
			elseif(sizeof($images)==0){
				$data = ["status"=>"fail","msg"=>"Atleast one image must be there"];
			}
			else{
				$sql = "UPDATE variants SET sku='$sku',color='$color',size='$size',stock='$stock',price='$price',discount='$discount',status='$status' WHERE id='$variant'";
				$res = mysqli_query($con,$sql);
				if($res){
					$sql = "DELETE FROM variant_images WHERE variant_id='$variant'";
					$res = mysqli_query($con,$sql);
					if($res){
						$sql = "INSERT INTO variant_images (variant_id,image) VALUES";
						for($i=0;$i<=sizeof($images)-1;$i++){
							if($i==sizeof($images)-1){
								$sql .= "('$variant','$images[$i]')";
							}
							else{
								$sql .= "('$variant','$images[$i]'),";
							}
						}
						
						$res = mysqli_query($con,$sql);
						if($res){
							$data = ["status"=>"success","msg"=>"Variant Updated Successfully"];
						}
						else{
							$data = ["status"=>"fail","msg"=>"Something Went Wrong 1","sql"=>$sql];
						}
					}
					else{
						$data = ["status"=>"fail","msg"=>"Something Went Wrong 2"];
					}
				}
				else{
					$data = ["status"=>"fail","msg"=>"Something Went Wrong 3"];
				}
			}
			
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session Expired"];
		}
		return $data;
	}
	
	function add_collection(){
		if($this->session_exist()){
			$banner = $this->req['images'];
			$con = $this->db_connect();
			$coll_name = mysqli_real_escape_string($con,$this->req['coll_name']);
			if($coll_name==""){
				$data = ["status"=>"fail","msg"=>"Collection name can not be empty."];
			}
			elseif(sizeof($banner)==0){
				$data = ["status"=>"fail","msg"=>"Banner can not be empty."];
			}
			else{
				
				$sql = "SELECT name FROM collection WHERE name='$coll_name'";
				$res = mysqli_query($con,$sql);
				$count = mysqli_num_rows($res);
				if($count>0){
					$data = ["status"=>"fail","msg"=>"Collection already exists"];
				}
				else{
					$sql = "INSERT INTO collection (name,banner,status) VALUES ('$coll_name','$banner[0]',1)";
					$res = mysqli_query($con,$sql);
					$coll_id = mysqli_insert_id($con);
					if($res){
						$data = ["status"=>"success","msg"=>"Collection added successfuly","coll_id"=>$coll_id];
					}
					else{
						$data = ["status"=>"fail","msg"=>"Something went wrong"];
					}
				}
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session expired"];
		}
		return $data;
	}
	
	function get_coll_by_id(){
		$coll_id = $this->req['coll_id'];
		$con = $this->db_connect();
		$sql = "SELECT * FROM collection WHERE id='$coll_id'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$coll_data = [$row['id'],$row['name'],$row['banner'],$row['status']];
		$data = ["status"=>"success","msg"=>"Collection Fetched Successfully","coll_data"=>$coll_data];
		return $data;
	}
	
	function get_all_colls(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$offset = $this->req['offset'];
		$limit = $this->req['limit'];
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM collection WHERE status LIKE '$status%' ORDER BY id DESC LIMIT $limit OFFSET $offset";
		}
		else{
			$sql = "SELECT * FROM collection WHERE status LIKE '$status%' AND name LIKE '$query%' LIMIT $limit OFFSET $offset";
		}
		$res = mysqli_query($con,$sql);
		$coll_data = array();
		while($r=mysqli_fetch_array($res)){
			$coll_data_array = array();
			$coll_data_array[] = $r['id'];
			$coll_data_array[] = $r['name'];
			$coll_data_array[] = $r['banner'];
			$coll_data_array[] = $r['status'];
			$coll_data[] = $coll_data_array;
		}
		$data = ["status"=>"success","msg"=>"Collections Fetched Successfully","coll_data"=>$coll_data];
		return $data;
	}
	
	function edit_coll(){
		if($this->session_exist()){
			$coll_name = $this->req['coll_name'];
			$banner = $this->req['images'];
			$coll_id = $this->req['coll_id'];
			$con = $this->db_connect();
			$sql = "SELECT banner FROM collection WHERE id='$coll_id'";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$curr_banner = $row['banner'];
			
			if($curr_banner!=$banner[0]){
				$this->del_image("collections",$curr_banner);
			}
			$sql = "UPDATE collection SET banner='$banner[0]',name='$coll_name' WHERE id='$coll_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Collection Updated Successfully"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
			return $data;
		}	
	}
	
	function deactivate_coll(){
		if($this->session_exist()){
			$coll_id = $this->req['coll_id'];
			$con = $this->db_connect();
			$sql = "UPDATE collection SET status=0 WHERE id='$coll_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Collection status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function activate_coll(){
		if($this->session_exist()){
			$coll_id = $this->req['coll_id'];
			$con = $this->db_connect();
			$sql = "UPDATE collection SET status=1 WHERE id='$coll_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Collection status Updated"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"session expired"];
		}
		return $data;
	}
	
	function coll_paging(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM collection WHERE status LIKE '$status%' ORDER BY id DESC";
		}
		else{
			$sql = "SELECT * FROM collection WHERE status LIKE '$status%' AND name LIKE '$query%' ORDER BY id DESC";
		}
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		$page_count = $count/2;
		$page_count = ceil($page_count);
	
		$data = ["status"=>"success","msg"=>"Page count calculated","page_count"=>$page_count];
		
		return $data;
	}
	
	function addToCollection(){
		$product_id = $this->req['product_id'];
		$coll_id = $this->req['coll_id'];
		$con = $this->db_connect();
		$sql = "SELECT id FROM collection_products WHERE collection_id='$coll_id' AND product_id='$product_id'";
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		if($count>0){
			$data = ["status"=>"fail","msg"=>"Product Already Exists in collection"];
		}
		else{
			$sql = "INSERT INTO collection_products (collection_id,product_id) VALUES ($coll_id,$product_id)";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Product added to collection"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
		}
		
		return $data;
	}
	
	function get_all_coll_products(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$brand = $this->req['brand'];
		$coll_id = $this->req['coll_id'];
		if($brand==""){
			$brand = "brands.id";
		}
		$category = $this->req['category'];
		if($category==""){
			$category = "categories.id";
		}
		$subcat = $this->req['subcat'];
		if($subcat==""){
			$subcat = "sub_categories.id";
		}
		$offset = $this->req['offset'];
		$limit = $this->req['limit'];	
			$con = $this->db_connect();
			if(empty($query)){
				$sql = "SELECT products.id,products.name as p_name,products.description,products.status,sub_categories.name as sub_name,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.status LIKE '$status%' AND brands.id=$brand AND categories.id=$category AND sub_categories.id=$subcat ORDER BY products.id LIMIT $limit OFFSET $offset";
			}
			else{
				$sql = "SELECT products.id,products.name as p_name,products.description,products.status,sub_categories.name as sub_name,categories.name as cat_name, brands.name as b_name FROM products INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE products.status LIKE '$status%' AND brands.id=$brand AND categories.id=$category AND sub_categories.id=$subcat AND products.name LIKE '$query%' ORDER BY products.id LIMIT $limit OFFSET $offset";
			}
			$res = mysqli_query($con,$sql);
			$product_data = array();
			while($r=mysqli_fetch_array($res)){
				$product_data_array = array();
				$pro_id = $r['id'];
				$product_data_array[] = $r['id'];
				$product_data_array[] = $r['p_name'];
				$product_data_array[] = $r['description'];
				$product_data_array[] = $r['display_image'];
				$product_data_array[] = $r['status'];
				$product_data_array[] = $r['b_name'];
				$product_data_array[] = $r['cat_name'];
				$product_data_array[] = $r['sub_name'];
				
				$sql2 = "SELECT id FROM collection_products WHERE collection_id='$coll_id' AND product_id='$pro_id'";
				$res2 = mysqli_query($con,$sql2);
				$count = mysqli_num_rows($res2);
				if($count>0){
					$product_data_array[] = "Added";
				}
				else{
					$product_data_array[] = "Not Added";
				}
				
				$product_data[] = $product_data_array;
			}
			$data = ["status"=>"success","msg"=>"Products Fetched Successfully","product_data"=>$product_data];
		
		return $data;
	}
	
	function removeFromCollection(){
		$product_id = $this->req['product_id'];
		$coll_id = $this->req['coll_id'];
		$con = $this->db_connect();
		$sql = "DELETE FROM collection_products WHERE collection_id='$coll_id' AND product_id='$product_id'";
		$res = mysqli_query($con,$sql);
		if($res){
			$data = ["status"=>"success","msg"=>"Product removed from collection successfuly"];
		}
		else{
			$data = ["status"=>"fail","msg"=>"Somthing went wrong"];
		}
		return $data;
	}
	
	function productByCollection(){
		$coll_id = $this->req['coll_id'];
		$con = $this->db_connect();
		$sql = "SELECT products.id,categories.name as cat_name,sub_categories.name as subcat_name,brands.name as bname,products.name as pname,products.description,products.display_image,products.status FROM collection_products INNER JOIN products ON collection_products.product_id=products.id INNER JOIN sub_categories ON products.sub_category_id=sub_categories.id INNER JOIN categories ON sub_categories.category_id=categories.id INNER JOIN brands ON products.brand_id=brands.id WHERE collection_products.collection_id='$coll_id'";
		$res = mysqli_query($con,$sql);
		$product_data = array();
			while($r=mysqli_fetch_array($res)){
				$product_data_array = array();
				$product_data_array[] = $r['id'];
				$product_data_array[] = $r['pname'];
				$product_data_array[] = $r['description'];
				$product_data_array[] = $r['display_image'];
				$product_data_array[] = $r['status'];
				$product_data_array[] = $r['bname'];
				$product_data_array[] = $r['cat_name'];
				$product_data_array[] = $r['subcat_name'];
				$product_data[] = $product_data_array;
			}
		$data = ["status"=>"success","msg"=>"Collection products fetched successfuly","product_data"=>$product_data];	
		return $data;
	}
	
	
}

?>
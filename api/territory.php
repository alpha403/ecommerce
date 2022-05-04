<?php
require_once('common.php');
class territory extends common {
	function __construct($req_data){
		$this->req = $req_data;
	}
	
	function main($image=""){
		$res_data = ["status"=>"fail"];
		if($this->req['req']=="add_country"){
			$res_data = $this->add_country();
		}
		elseif($this->req['req']=="get_country_by_id"){
			$res_data = $this->get_country_by_id();
		}
		elseif($this->req['req']=="get_all_countries"){
			$res_data = $this->get_all_countries();
		}
		elseif($this->req['req']=="edit_country"){
			$res_data = $this->edit_country();
		}
		elseif($this->req['req']=="deactivate_country"){
			$res_data = $this->deactivate_country();
		}
		elseif($this->req['req']=="activate_country"){
			$res_data = $this->activate_country();
		}
		elseif($this->req['req']=="country_paging"){
			$res_data = $this->country_paging();
		}
		
		
		return $res_data;
	}
	
	function add_country(){
		if($this->session_exist()){
			$con = $this->db_connect();
			$country_name = mysqli_real_escape_string($con,$this->req['country_name']);
			$currency = mysqli_real_escape_string($con,$this->req['currency']);
			$currency_symbol = mysqli_real_escape_string($con,$this->req['currency_symbol']);
			if($country_name==""){
				$data = ["status"=>"fail","msg"=>"Country name can not be empty."];
			}
			elseif($currency==""){
				$data = ["status"=>"fail","msg"=>"currency can not be empty."];
			}
			elseif($currency_symbol==""){
				$data = ["status"=>"fail","msg"=>"currency symbol can not be empty."];
			}
			else{
				$sql = "SELECT name FROM countries WHERE name='$country_name'";
				$res = mysqli_query($con,$sql);
				$count = mysqli_num_rows($res);
				if($count>0){
					$data = ["status"=>"fail","msg"=>"Country already exists"];
				}
				else{
					$sql = "INSERT INTO countries (name,currency,currency_symbol,status) VALUES ('$country_name','$currency','$currency_symbol',1)";
					$res = mysqli_query($con,$sql);
					$country_id = mysqli_insert_id($con);
					if($res){
						$data = ["status"=>"success","msg"=>"Country added successfuly","country_id"=>$country_id];
					}
					else{
						$data = ["status"=>"fail","msg"=>"Someting went wrong"];
					}
				}
			}
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session Expired!"];
		}
		return $data;
	}
	
	function get_country_by_id(){
		$country_id = $this->req['country_id'];
		$con = $this->db_connect();
		$sql = "SELECT * FROM countries WHERE id='$country_id'";
		$res = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($res);
		$country_data = [$row['id'],$row['name'],$row['currency'],$row['currency_symbol'],$row['status']];
		$data = ["status"=>"success","msg"=>"Country Fetched Successfully","country_data"=>$country_data];
		return $data;
	}
	
	function get_all_countries(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		$offset = $this->req['offset'];
		$limit = $this->req['limit'];
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM countries WHERE status LIKE '$status%' ORDER BY id DESC LIMIT $limit OFFSET $offset";
		}
		else{
			$sql = "SELECT * FROM countries WHERE status LIKE '$status%' AND name LIKE '$query%' LIMIT $limit OFFSET $offset";
		}
		$res = mysqli_query($con,$sql);
		$country_data = array();
		while($r=mysqli_fetch_array($res)){
			$country_data_array = array();
			$country_data_array[] = $r['id'];
			$country_data_array[] = $r['name'];
			$country_data_array[] = $r['currency'];
			$country_data_array[] = $r['currency_symbol'];
			$country_data_array[] = $r['status'];
			$country_data[] = $country_data_array;
		}
		$data = ["status"=>"success","msg"=>"Countries Fetched Successfully","country_data"=>$country_data];
		return $data;
	}
	
	function edit_country(){
		if($this->session_exist()){
			$country_name = $this->req['country_name'];
			$currency = $this->req['currency'];
			$currency_symbol = $this->req['currency_symbol'];
			$country_id = $this->req['country_id'];
			$con = $this->db_connect();
			
			$sql = "UPDATE countries SET name='$country_name',currency='$currency',currency_symbol='$currency_symbol' WHERE id='$country_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Country Updated Successfully"];
			}
			else{
				$data = ["status"=>"fail","msg"=>"Something went wrong"];
			}
			return $data;
		}	
	}
	
	function deactivate_country(){
		if($this->session_exist()){
			$country_id = $this->req['country_id'];
			$con = $this->db_connect();
			$sql = "UPDATE countries SET status=0 WHERE id='$country_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Country status Updated"];
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
	
	function activate_country(){
		if($this->session_exist()){
			$country_id = $this->req['country_id'];
			$con = $this->db_connect();
			$sql = "UPDATE countries SET status=1 WHERE id='$country_id'";
			$res = mysqli_query($con,$sql);
			if($res){
				$data = ["status"=>"success","msg"=>"Country status Updated"];
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
	
	function country_paging(){
		$query = $this->req['query'];
		$status = $this->req['status'];
		
		$con = $this->db_connect();
		if(empty($query)){
			$sql = "SELECT * FROM countries WHERE status LIKE '$status%' ORDER BY id DESC";
		}
		else{
			$sql = "SELECT * FROM countries WHERE status LIKE '$status%' AND name LIKE '$query%' ORDER BY id DESC";
		}
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		$page_count = $count/2;
		$page_count = ceil($page_count);
	
		$data = ["status"=>"success","msg"=>"Page count calculated","page_count"=>$page_count];
		
		return $data;
	}
}
?>
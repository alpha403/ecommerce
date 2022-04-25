<?php
require_once 'common.php';
class admin extends common{
	function __construct($req_data){
		$this->req = $req_data;
	}
	
	function main(){
		$res_data = ["status"=>"fail"];
		if($this->req['req']=="userinfo"){
			$res_data = $this->userinfo();
		}
		return $res_data;
	}
	
	function userinfo(){
		if($this->session_exist()){
			$this->session_init();
			$con = $this->db_connect();
			$user = $_SESSION['user'];
			$sql = "SELECT name FROM users WHERE email='$user'";
			$res = mysqli_query($con,$sql);
			$row = mysqli_fetch_array($res);
			$name = $row['name'];
			$data = ["status"=>"success","msg"=>"Data Fetched","name"=>$name];
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session does not exists"];
		}
		return $data;
	}
}
?>
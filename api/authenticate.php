<?php
require_once 'common.php';
class authenticate extends common{
	function __construct($req_data){
		$this->req = $req_data;
	}
	
	function main(){
		$res_data = ["status"=>"fail"];
		if($this->req['req']=='admin_login'){
			$res_data = $this->admin_login();
		}
		elseif($this->req['req']=="check_session"){
			$res_data = $this->check_session();
		}
		elseif($this->req['req']=="logout"){
			$res_data = $this->logout();
		}
		
		return $res_data;
	}
	
	function admin_login(){
		$con = $this->db_connect();
		$user = md5($this->req['user']);
		$pass = md5($this->req['pass']);
		$sql = "SELECT * FROM users WHERE email='$user' AND password='$pass'";
		$res = mysqli_query($con,$sql);
		$count = mysqli_num_rows($res);
		if($count==1){
			session_start();
			$_SESSION['user']=$user;
			$data = ["status"=>"success","msg"=>"Login Success"];
		}
		else{
			$data = ["status"=>"fail","msg"=>"Login Failed"];
		}
		return $data;
	}
	
	function check_session(){
		if($this->session_exist()){
			$data = ["status"=>"success","msg"=>"Session Exists"];
		}
		else{
			$data = ["status"=>"fail","msg"=>"Session doesn't exists"];
		}
		return $data;
	}
	
	function logout(){
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_destroy();
		$data = ["status"=>"success","msg"=>"Logout Successful"];
		return $data;
	}
}

?>
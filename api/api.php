<?php
include 'authenticate.php';
include 'admin.php';
include 'catalog.php';
include 'territory.php';
$req_data = $_POST['req_data'];
$req_data = json_decode($req_data, true);
function detect_module($req_data){
	$data = ["status"=>"failed"];
	try {
		if(isset($_FILES['fileName'])){
			$auth_obj = new $req_data["module"]($req_data);
			$data = $auth_obj->main($_FILES);
		}
		else{
			$auth_obj = new $req_data["module"]($req_data);
			$data = $auth_obj->main();
		}
		
	} catch(Throwable $e) {
		$data = ["status"=>"failed", 'Error' => $e->getMessage()];
	}
	print_r(json_encode($data));
}

detect_module($req_data);

?>
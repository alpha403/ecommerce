<?php
require_once 'config.php';
class common extends config{
	function db_connect(){
		$con = mysqli_connect($this->db_config()['db_addr'],$this->db_config()['db_user'],$this->db_config()['db_pass'],$this->db_config()['db_name']) or die("cannot connect");
		return $con;
	}
	
	function session_exist(){
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		
		if(empty($_SESSION['user'])){
			$data = False;
		}
		else{
			$data = True;
		}
		return $data;
	}
	
	function session_init(){
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}
	
	function base_path(){
		$base_path = $_SERVER['DOCUMENT_ROOT']."/";
		return $base_path;
	}
	
	function file_data($module){
		$path_data = $this->img_dir($module);
		return $path_data;
	}
	
	function file_path($module){
		$path_data = $this->img_dir($module);
		$paths = array($path_data[0]);
		for($i=1;$i<=sizeof($path_data)-1;$i++){
			$paths[] = $path_data[$i][0];
		}
		return $paths;
	}
	
	function resizeCropFromCenter($finalW, $finalH, $srcFilePath){
		//ReSIZE IMAGE WITH IMAGE MAGICK
		//CROP-RESIZE FROM THE CENTER
		//DESIRED SIZE OF RESIZED & CROPPED IMAGE 360 X 360 (Square)
		
		$finalWHRatio = $finalW/$finalH;
		$im = new imagick($srcFilePath);
		$im-> setResolution(600, 600);
		
		//SOMETIME IMAGE IS ROTATED (DON'T KNOW WHY)
		//NEED TO APPLY THESE SCENARIOS BEFORE APPLYING DOWN STREAM PROCESSING
		$this->autoRotateImage($im);
		
		//IS IMAGE LANDSCAPE OR PORTRAIT?
		//We need to Know this so that we can determine to crop top and bottom (for portraits)
		//or left and right (for landscape)
		
		$geo = $im->getImageGeometry();
		$srcImageWidth = $geo['width'];
		$srcImageHeight = $geo['height'];
		$srcWHRatio = $srcImageWidth/$srcImageHeight;
		
		$resizedH = '';
		$resizedW = '';
		if($srcWHRatio > $finalWHRatio){
			//img is landscape
			$resizedH = $finalH;
			$resizedW = $srcWHRatio*$finalH;
			
			//Resize image keeping aspect ratio
			$im->resizeImage($resizedW, $resizedH, Imagick::FILTER_LANCZOS, 1);
			
		}
		else{
			//Img is PORTRAIT
			$resizedW = $finalH;
			$resizedH = $finalW/$srcWHRatio;
			
			//RESIZE image keeping Aspect Ratio
			$im->resizeImage($resizedW, $resizedH, Imagick::FILTER_LANCZOS, 1);
			
		}
		
		//return Imagick Object, not a filename
		return $im;
		
	}
	
	function autoRotateImage($image){
		$orientation = $image->getImageOrientation();
		
		switch($orientation){
			case imagick::ORIENTATION_BOTTOMRIGHT:
				$image->rotateimage("#000", 180);
			break;
			
			case imagick::ORIENTATION_RIGHTTOP:
				$image->rotateimage("#000", 90);
			break;

			case imagick::ORIENTATION_LEFTBOTTOM:
				$image->rotateimage("#000", -90);
			break;	
		}
		
		$image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
	}
	
	function resize_image($finalW, $finalH, $srcFilePath, $saveToFolder, $fileName){
		$image = $this->resizeCropFromCenter($finalW, $finalH, $srcFilePath);
		//save to local storage
		$saveImgtoPath = $saveToFolder.$fileName;
		$result = file_put_contents($saveImgtoPath, $image);
		if(!$result){
			$data = False;
		}
		else{
			$data = True;
		}
		return $data;
	}
	
	function upload_files($file,$allowed,$module){
		$filename = $file['fileName']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if (!in_array($ext, $allowed)) {
			$data = ["status"=>"failed","msg"=>"file not allowed"];
		}
		else{
			$temp = explode(".", $file['fileName']["name"]);
			$newfilename = round(microtime(true)) . '.' . end($temp);
			
			move_uploaded_file($file['fileName']["tmp_name"], $this->base_path().$this->file_data($module)[0].$newfilename);
			
			$uploaded_path = $this->base_path().$this->file_data($module)[0].$newfilename;
			
			if(file_exists($uploaded_path)){
				$upload_success = False;
				for($i=1;$i<=sizeof($this->file_data($module))-1;$i++){
					$path = $this->base_path().$this->file_data($module)[$i][0];
					$upload_success = $this->resize_image($this->file_data($module)[$i][1], $this->file_data($module)[$i][2], $uploaded_path, $path, $newfilename);
				}
				
				if($upload_success){
					$paths = $this->file_path($module);
				
					$data = ["status"=>"success","msg"=>"File uploaded Successfully","image"=>$newfilename,"paths"=>$paths];
				}
				else{
					$data = ["status"=>"failed","msg"=>"Error While Uploading Image1"];
				}
			}
			else{
				$data = ["status"=>"failed","msg"=>"Error While Uploading Image2"];
			}
			
		}
		
		return $data;
	}
	
	function del_image($module,$image){
		$paths = $this->file_path($module);
		$base_path = $this->base_path();
		for($i=0;$i<=sizeof($paths)-1;$i++){
			unlink($base_path.$paths[$i].$image);
		}
	}
	
}
?>
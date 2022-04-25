<?php
include 'includes/active_menu.php';
$dashboard = "side-menu--active";
$option = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <?php include 'includes/head.php'; ?>
		<title>Rtaze | Dashboard</title>
    </head>
    <!-- END: Head -->
    <body class="app" style="display:none;">
        <!-- BEGIN: Mobile Menu -->
        <?php include 'includes/mobile_menu.php'; ?>
        <!-- END: Mobile Menu -->
        <div class="flex">
            <!-- BEGIN: Side Menu -->
            <?php include 'includes/side_menu.php'; ?>
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                <!-- BEGIN: Top Bar -->
                <?php include 'includes/topbar.php'; ?>
                <!-- END: Top Bar -->
                <div class="grid grid-cols-12 gap-6">
                    
                    
                </div>
            </div>
            <!-- END: Content -->
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
        <div class="dark-mode-switcher shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
            <input class="input input--switch border" type="checkbox" value="1">
        </div>
        <!-- END: Dark Mode Switcher-->
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBG7gNHAhDzgYmq4-EHvM4bqW1DNj2UCuk&libraries=places"></script>
        <?php include 'includes/js.php'; ?>
        <!-- END: JS Assets-->
		<script>
			function check_session(){
			var module = 'authenticate';
			var req = 'check_session';
			var req_data = JSON.stringify({module:module,req:req});
			$.post(api_url,{req_data},function(data){
				data = JSON.parse(data);
				if(data.status=="fail"){
					window.location.href = 'login';
				}
				else{
					$("body").show();
				}
			});
		}
		check_session();
		userinfo();
		</script>
    </body>
</html>
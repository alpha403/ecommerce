<?php
include 'includes/active_menu.php';
$catalog = "side-menu--active";
$option = "Catalog";
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
				<!-- Mid Content Start-->
                <h2 class="intro-y text-lg font-medium mt-10">
                    Brands
                </h2>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
                        <a href="javascript:;" data-toggle="modal" data-target="#add_brand" class="button inline-block bg-theme-1 text-white">Add New Brand</a>
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="brand_status" onchange="search_brand();">
									<option value="">Select Status</option>
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <input type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search..." id="brand_query" onkeyup="search_brand();">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
									<th class="whitespace-no-wrap">BRAND NAME</th>
                                    <th class="text-center whitespace-no-wrap">STATUS</th>
                                    <th class="text-center whitespace-no-wrap">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="brand_table">
								<tr id="brand_loading" style="display:none;">
									<td class="linear-background"></td>
									<td class="w-40 linear-background"></td>
									<td class="linear-background"></td>
								</tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- END: Data List -->
                    <!-- BEGIN: Pagination -->
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-no-wrap items-center">
                        <ul class="pagination" id="brand_paging">
                            <li> <a class="pagination__link" href="">1</a> </li>
                            <li> <a class="pagination__link pagination__link--active" href="">2</a> </li>
                            <li> <a class="pagination__link" href="">3</a> </li>
                        </ul>
                       
                    </div>
                    <!-- END: Pagination -->

				<!-- Mid content Ends-->
            </div>
			
            <!-- END: Content -->
        </div>
		<!-- Add Brand Modal start-->
		<div class="modal" id="add_brand">
			<div class="modal__content p-10">
				 <div>
					<label>Brand Name</label>
					<input type="text" id="brand_name" class="input w-full border mt-2" placeholder="Example:Nike"> 
				</div> 
				<div class="mt-3"> 
					<label>Brand Logo</label>
					<input type="file" id="logo" style="display:none;" onchange="upload_image('catalog','logo_output','logo','logo_con','brand_logo_uploader','logo','brands');">
					<div class="image_uploader_box" id="brand_logo_uploader" onclick="activate_image_upload('logo');">
						Click to add brand logo
					</div>
				</div>
				<div class="mt-3" id="logo_output">
					<!-- data from ajax -->
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" onclick="add_brand();">Add</button> 
				</div>
			</div>
		</div>
		<!-- Add Brand Modal Ends-->
		<!-- Edit Brand Modal start-->
		<div class="modal" id="edit_brand">
			<div class="modal__content p-10">
				 <div>
					<label>Brand Name</label>
					<input type="text" id="edit_brand_name" class="input w-full border mt-2" placeholder="Example:Nike"> 
				</div> 
				<div class="mt-3"> 
					<label>Brand Logo</label>
					<input type="file" id="edit_logo" style="display:none;" onchange="upload_image('catalog','edit_logo_output','editlogo','edit_logo_con','edit_brand_logo_uploader','edit_logo','brands');">
					<div class="image_uploader_box" id="edit_brand_logo_uploader" onclick="activate_image_upload('edit_logo');">
						Click to add brand logo
					</div>
				</div>
				<div class="mt-3" id="edit_logo_output">
					<!-- data from ajax -->
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" id="edit_brand_btn" onclick="edit_brand();">Edit</button> 
				</div>
			</div>
		</div>
		<!-- Edit Brand Modal Ends-->
		<!-- Upload Error Modal starts-->
		<div class="modal" id="upload_error">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Oops...</div>
					 <div class="text-gray-600 mt-2" id="upload_error_msg"></div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white">Ok</button> </div>
				 <div class="p-5 text-center border-t border-gray-200 dark:border-dark-5"> <a href="" class="text-theme-1 dark:text-theme-10">Why do I have this issue?</a> </div>
			 </div>
		</div>
		<!-- upload error modal ends-->
		<!-- upload success modal starts-->
		<div class="modal" id="upload-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Success!</div>
					 <div class="text-gray-600 mt-2">Brand Added Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" id="upload_success_load" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- upload success modal ends-->
		<!-- upload success modal starts-->
		<div class="modal" id="update-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Success!</div>
					 <div class="text-gray-600 mt-2">Brand Updated Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- upload success modal ends-->
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
					userinfo();
					$("body").show();
				}
			});
		}
		check_session();
		
		$(document).ready(function() {
			$('#catalog_option').trigger('click');
			var query = $("#brand_query").val();
			var status = $("#brand_status").val();
			var offset = 0;
			var limit = 2;
			brand_page_data(query,status,offset,limit);
			get_brand_paging(query,status,limit);
		});
		
		</script>
    </body>
</html>
<?php
include 'includes/active_menu.php';
$catalog = "side-menu--active";
$option = "Categories";
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <?php include 'includes/head.php'; ?>
		<title>Rtaze | Categories</title>
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
                    Categories
                </h2>
                <div class="grid grid-cols-12 gap-6 mt-2">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
                        <a href="javascript:;" data-toggle="modal" data-target="#add_category" class="button inline-block bg-theme-1 text-white">Add New Category</a>
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="category_status" onchange="search_category();">
									<option value="">Select Status</option>
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <input type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search..." id="category_query" onkeyup="search_category();">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                            </div>
                        </div>
                    </div>
					<!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
						<table class="table table-report -mt-2">
                            <thead>
                                <tr>
									<th class="whitespace-no-wrap">Category</th>
                                    <th class="text-center whitespace-no-wrap">STATUS</th>
                                    <th class="text-center whitespace-no-wrap">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="category_table">
								<tr id="category_loading" style="display:none;">
									<td class="linear-background"></td>
									<td class="w-40 linear-background"></td>
									<td class="linear-background"></td>
								</tr>
                            </tbody>
                        </table>
					</div>
                    <!-- End: Data List -->
					<!-- BEGIN: Pagination -->
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-no-wrap items-center">
                        <ul class="pagination" id="category_paging">
                            <li> <a class="pagination__link" href="">1</a> </li>
                            <li> <a class="pagination__link pagination__link--active" href="">2</a> </li>
                            <li> <a class="pagination__link" href="">3</a> </li>
                        </ul>
                       
                    </div>
                    <!-- END: Pagination -->
                </div>
				
            </div>
            <!-- END: Content -->
        </div>
		<!-- Add Category Modal start-->
		<div class="modal" id="add_category">
			<div class="modal__content p-10">
				 <div>
					<label>Category Name</label>
					<input type="text" id="category_name" class="input w-full border mt-2" placeholder="Example:Mens"> 
				</div> 
				<div class="mt-3"> 
					<label>Category Image</label>
					<input type="file" id="cat_image" style="display:none;" onchange="upload_image('catalog','cat_image_output','cat_img','cat_img_con','cat_image_uploader','cat_image','category');">
					<div class="image_uploader_box" id="cat_image_uploader" onclick="activate_image_upload('cat_image');">
						Click to add brand logo
					</div>
				</div>
				<div class="mt-3" id="cat_image_output">
					<!-- data from ajax -->
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" onclick="add_category();">Add</button> 
				</div>
			</div>
		</div>
		<!-- Add Category Modal Ends-->
		<!-- Edit Category Modal start-->
		<div class="modal" id="edit_category">
			<div class="modal__content p-10">
				 <div>
					<label>Category Name</label>
					<input type="text" id="edit_category_name" class="input w-full border mt-2" placeholder="Example:Mens"> 
				</div> 
				<div class="mt-3"> 
					<label>Category Image</label>
					<input type="file" id="edit_category_image" style="display:none;" onchange="upload_image('catalog','edit_category_image_output','editcategory','edit_category_con','edit_category_image_uploader','edit_category_image','category');">
					<div class="image_uploader_box" id="edit_category_image_uploader" onclick="activate_image_upload('edit_category_image');">
						Click to add category image
					</div>
				</div>
				<div class="mt-3" id="edit_category_image_output">
					<!-- data from ajax -->
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" id="edit_category_btn" onclick="edit_category();">Edit</button> 
				</div>
			</div>
		</div>
		<!-- Edit Category Modal Ends-->
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
		<!-- update success modal starts-->
		<div class="modal" id="update-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Success!</div>
					 <div class="text-gray-600 mt-2">Category Updated Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- update success modal ends-->
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
					userinfo();
				}
			});
		}
		check_session();
		
		$(document).ready(function() {
			$('#catalog_option').trigger('click');
			var query = $("#category_query").val();
			var status = $("#category_status").val();
			var offset = 0;
			limit = 2;
			category_page_data(query,status,offset,limit);
			get_category_paging(query,status,limit);
		});
		
		</script>
    </body>
</html>
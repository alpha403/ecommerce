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
                    Collections
                </h2>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
                        <a href="javascript:;" data-toggle="modal" data-target="#add_collection" class="button inline-block bg-theme-1 text-white">Add New Collection</a>
                        <div class="hidden md:block mx-auto text-gray-600"></div>
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="coll_status" onchange="search_coll();">
									<option value="">Select Status</option>
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <input type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search..." id="coll_query" onkeyup="search_coll();">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
									<th class="whitespace-no-wrap">COLLECTION NAME</th>
                                    <th class="text-center whitespace-no-wrap">STATUS</th>
                                    <th class="text-center whitespace-no-wrap">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="coll_table">
								<tr id="coll_loading" style="display:none;">
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
                        <ul class="pagination" id="coll_paging">
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
		<div class="modal" id="add_collection">
			<div class="modal__content p-10">
				 <div>
					<label>Collection Name</label>
					<input type="text" id="coll_name" class="input w-full border mt-2" placeholder="Example:Summer Collection"> 
				</div> 
				<div class="mt-3"> 
					<label>Collection Banner</label>
					<input type="file" id="coll_banner" style="display:none;" onchange="upload_image('catalog','coll_banner_output','coll_bannner','coll_banner_con','coll_banner_uploader','coll_banner','collections');">
					<div class="image_uploader_box" id="coll_banner_uploader" onclick="activate_image_upload('coll_banner');">
						Click to add Collection banner
					</div>
				</div>
				<div class="mt-3" id="coll_banner_output">
					<!-- data from ajax -->
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" onclick="add_collection();">Add</button> 
				</div>
			</div>
		</div>
		<!-- Add Brand Modal Ends-->
		<!-- Edit Brand Modal start-->
		<div class="modal" id="edit_coll">
			<div class="modal__content p-10">
				 <div>
					<label>Collection Name</label>
					<input type="text" id="edit_coll_name" class="input w-full border mt-2" placeholder="Example:Summer Collection"> 
				</div> 
				<div class="mt-3"> 
					<label>Collection Banner</label>
					<input type="file" id="edit_coll_banner" style="display:none;" onchange="upload_image('catalog','edit_coll_banner_output','editcollbanner','edit_coll_banner_con','edit_coll_banner_uploader','edit_coll_banner','collections');">
					<div class="image_uploader_box" id="edit_coll_banner_uploader" onclick="activate_image_upload('edit_coll_banner');">
						Click to add Collection Banner
					</div>
				</div>
				<div class="mt-3" id="edit_coll_banner_output">
					<!-- data from ajax -->
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" id="edit_coll_btn" onclick="edit_coll();">Edit</button> 
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
		
		<!-- Collection Add Product start-->
		<div class="modal" id="add_collection_product">
			<div class="modal__content modal__content--xl p-10">
			<h2 class="intro-y text-lg font-medium mt-2">
                    Add Products to Collection
            </h2>
			<div class="grid grid-cols-12 gap-6 mt-2">
					<div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
                        
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-40 relative text-gray-700 dark:text-gray-300">
								<input type="hidden" id="coll_id" value="">
                                <select class="rtaze_select" id="search_product_brand" onchange="search_product_collection();">
									<option value="">Select Brand</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="product_status" onchange="search_product_collection();">
									<option value="">Select Status</option>
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <input type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search..." id="product_query" onkeyup="search_product_collection();">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                            </div>
                        </div>
                    </div>
					<div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
						
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="search_product_category" onchange="product_subcat_select('search_product_category','search_product_subcat');search_product_collection();">
									<option value="">Select Category</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="search_product_subcat" onchange="search_product_collection();">
									<option value="">Select Sub Category</option>
								</select> 
                            </div>
                        </div>
                    </div>
					<!-- BEGIN: Data List -->
                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
						<table class="table table-report -mt-2">
                            <thead>
                                <tr>
									<th class="whitespace-no-wrap">Product</th>
                                    <th class="text-center whitespace-no-wrap">Brand</th>
                                    <th class="text-center whitespace-no-wrap">Category</th>
									<th class="text-center whitespace-no-wrap">Sub Category</th>
									<th class="text-center whitespace-no-wrap">Status</th>
									<th class="text-center whitespace-no-wrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="product_table">
								<tr id="product_loading" style="display:none;">
									<td class="linear-background"></td>
									<td class="w-40 linear-background"></td>
									<td class="linear-background"></td>
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
                        <ul class="pagination" id="product_paging">
                            <li> <a class="pagination__link" href="">1</a> </li>
                            <li> <a class="pagination__link pagination__link--active" href="">2</a> </li>
                            <li> <a class="pagination__link" href="">3</a> </li>
                        </ul>
                       
                    </div>
					</div>
                    <!-- END: Pagination -->
					<h2 class="intro-y text-lg font-medium mt-5">
                    Collection Products
					</h2>
					<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
						<table class="table table-report mt-5">
                            <thead>
                                <tr>
									<th class="whitespace-no-wrap">Product</th>
                                    <th class="text-center whitespace-no-wrap">Brand</th>
                                    <th class="text-center whitespace-no-wrap">Category</th>
									<th class="text-center whitespace-no-wrap">Sub Category</th>
									<th class="text-center whitespace-no-wrap">Status</th>
									<th class="text-center whitespace-no-wrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="collproduct_table">
								<tr id="collproduct_loading" style="display:none;">
									<td class="linear-background"></td>
									<td class="w-40 linear-background"></td>
									<td class="linear-background"></td>
									<td class="linear-background"></td>
									<td class="w-40 linear-background"></td>
									<td class="linear-background"></td>
								</tr>
                            </tbody>
                        </table>
					</div>
				
				 
				
			</div>
		</div>
		<!-- Collection Add Product Ends-->
		<!-- Product Add Error Modal starts-->
		<div class="modal" id="coll_add_error">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Oops...</div>
					 <div class="text-gray-600 mt-2" id="coll_add_error_msg"></div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white">Ok</button> </div>
				 <div class="p-5 text-center border-t border-gray-200 dark:border-dark-5"> <a href="" class="text-theme-1 dark:text-theme-10">Why do I have this issue?</a> </div>
			 </div>
		</div>
		<!-- Product Add Error modal ends-->
		<!-- PRoduct add success modal starts-->
		<div class="modal" id="coll-add-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Success!</div>
					 <div class="text-gray-600 mt-2">Product Added To Collection Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- PRoduct Add success modal ends-->
		<!-- PRoduct remove success modal starts-->
		<div class="modal" id="coll-remove-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Success!</div>
					 <div class="text-gray-600 mt-2">Product Removed From Collection Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- PRoduct remove success modal ends-->
		
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
			var query = $("#coll_query").val();
			var status = $("#coll_status").val();
			var offset = 0;
			var limit = 2;
			coll_page_data(query,status,offset,limit);
			get_coll_paging(query,status,limit);
			brand_product_select();
			category_product_select()
		});
		
		</script>
    </body>
</html>
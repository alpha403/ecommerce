<?php
include 'includes/active_menu.php';
$catalog = "side-menu--active";
$option = "Products";
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <?php include 'includes/head.php'; ?>
		<script src="https://kit.fontawesome.com/005a751206.js" crossorigin="anonymous"></script>
		<title>Rtaze | Products</title>
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
                    Products
                </h2>
				<div class="grid grid-cols-12 gap-6 mt-2">
					<div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
                        <a href="javascript:;" data-toggle="modal" data-target="#add_product" class="button inline-block bg-theme-1 text-white">Add Product</a>
                        <div class="hidden md:block mx-auto text-gray-600"></div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-40 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="search_product_brand" onchange="search_product();">
									<option value="">Select Brand</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="product_status" onchange="search_product();">
									<option value="">Select Status</option>
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <input type="text" class="input w-56 box pr-10 placeholder-theme-13" placeholder="Search..." id="product_query" onkeyup="search_product();">
                                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i> 
                            </div>
                        </div>
                    </div>
					<div class="intro-y col-span-12 flex flex-wrap sm:flex-no-wrap items-center mt-2">
                        <div class="hidden md:block mx-auto text-gray-600"></div>
						
                        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="search_product_category" onchange="product_subcat_select('search_product_category','search_product_subcat');search_product();">
									<option value="">Select Category</option>
								</select> 
                            </div>
                        </div>
						<div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                            <div class="w-56 relative text-gray-700 dark:text-gray-300">
                                <select class="rtaze_select" id="search_product_subcat" onchange="search_product();">
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
                    <!-- END: Pagination -->
				</div>
				<!-- Mid Content Ends-->
            </div>
            <!-- END: Content -->
        </div>
		<!-- Add Product Modal start-->
		<div class="modal" id="add_product">
			<div class="modal__content p-10">
				 <div>
					<label>Product Name</label>
					<input type="text" id="product_name" class="input w-full border mt-2" placeholder="Example:Jeans"> 
				</div>
				<div class="mt-2">
					<label>Description</label>
					<textarea data-feature="all" id="product_description" class="summernote" name="editor"></textarea> 
				</div>
				<div class="mt-2">
					<label>Brand</label>
					<select id="product_brand" class="input w-full border mt-2">
						<option value="">Select Brand</option>
					</select> 
				</div>
				<div class="mt-2">
					<label>Category</label>
					<select id="product_category" class="input w-full border mt-2" onchange="product_subcat_select('product_category','product_subcat');">
						<option value="">Select Category</option>
					</select> 
				</div>
				<div class="mt-2">
					<label>Sub Category</label>
					<select id="product_subcat" class="input w-full border mt-2">
						<option value="">Select Sub Category</option>
					</select> 
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" onclick="add_product();">Add</button> 
				</div>
			</div>
		</div>
		<!-- Add Product Modal Ends-->
		<!-- Edit Product Modal start-->
		<div class="modal" id="edit_product">
			<div class="modal__content p-10">
				 <div>
					<label>Product Name</label>
					<input type="text" id="edit_product_name" class="input w-full border mt-2" placeholder="Example:Jeans"> 
				</div>
				<div class="mt-2">
					<label>Description</label>
					<textarea data-feature="all" id="edit_product_description" class="summernote" name="editor"></textarea> 
				</div>
				<div class="mt-2">
					<label>Brand</label>
					<select id="edit_product_brand" class="input w-full border mt-2">
						<option value="">Select Brand</option>
					</select> 
				</div>
				<div class="mt-2">
					<label>Category</label>
					<select id="edit_product_category" class="input w-full border mt-2" onchange="product_subcat_select('edit_product_category','edit_product_subcat');">
						<option value="">Select Category</option>
					</select> 
				</div>
				<div class="mt-2">
					<label>Sub Category</label>
					<select id="edit_product_subcat" class="input w-full border mt-2">
						<option value="">Select Sub Category</option>
					</select> 
				</div>
				<div class="mt-2" style="overflow:hidden;width:100%;">
					<button type="button" class="button bg-theme-1 text-white mt-5" id="edit_product_btn" onclick="edit_product();">Add</button> 
				</div>
			</div>
		</div>
		<!-- Edit Product Modal Ends-->
		<!-- Add product Error Modal starts-->
		<div class="modal" id="add_product_error">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Oops...</div>
					 <div class="text-gray-600 mt-2" id="add_error_msg"></div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white">Ok</button> </div>
				 <div class="p-5 text-center border-t border-gray-200 dark:border-dark-5"> <a href="" class="text-theme-1 dark:text-theme-10">Why do I have this issue?</a> </div>
			 </div>
		</div>
		<!-- Add Product Error modal ends-->
		<!-- add success modal starts-->
		<div class="modal" id="add-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Success!</div>
					 <div class="text-gray-600 mt-2">Product Added Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" id="add_success_load" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- upload success modal ends-->
		<!-- Edit success modal starts-->
		<div class="modal" id="edit-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Success!</div>
					 <div class="text-gray-600 mt-2">Product Updated Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" id="add_success_load" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- Edit success modal ends-->
		<!-- Edit success modal starts-->
		<div class="modal" id="variants-modal">
			<div class="modal__content p-10">
				<div>
					<div class="label-cover">
						<div class="label-inner">SKU</div>
						<div class="label-inner">Color</div>
					</div>
					<input type="text" id="variant_sku" class="input w-half border mt-2" placeholder="SKU">
					<input type="text" id="variant_color" class="input w-half border mt-2" placeholder="Variant Color"> 
					<div class="label-cover mt-2">Size</div>
					<select id="variant_size" class="input w-full border mt-2">
						<option value="S">S</option>
						<option value="L">L</option>
						<option value="XL">XL</option>
						<option value="XXL">XXL</option>
						<option value="XXXL">XXXL</option>
					</select>
					<div class="label-cover mt-2">
						<div class="label-inner">Stock</div>
						<div class="label-inner">Price</div>
					</div>
					<input type="text" id="variant_stock" class="input w-half border mt-2" placeholder="Variant Stock"> 
					<input type="text" id="variant_price" class="input w-half border mt-2" placeholder="Variant Price"> 
					<div class="label-cover mt-2">Discount</div>
					<input type="text" id="variant_discount" class="input w-half border mt-2" placeholder="Variant Discount"> 
					<div class="mt-3"> 
						<label>Variant Images</label>
						<input type="file" id="var_image" style="display:none;" onchange="upload_image('catalog','var_image_output','var_img','var_img_con','var_image_uploader','var_image','variants');">
						<div class="add_var_img"  onclick="activate_image_upload('var_image');">
							<i data-feather="plus" class="mx-auto"></i> 
						</div>
						<div id="var_image_uploader"></div>
					</div>
					<div class="mt-3" id="var_image_output">
						<!-- data from ajax -->
					</div>
					<div class=" pb-8"> <button type="button" class="button w-24 bg-theme-1 text-white" id="add_var_btn" onclick="add_variant();">Add</button> </div>
				</div>
				
				<div id="variant-output">
					<div class="var-box">
						<div class="label-cover">
							<div class="label-inner">SKU</div>
							<div class="label-inner">Color</div>
						</div>
						<input type="text" id="variant_sku" class="input w-half border mt-2" placeholder="SKU">
						<input type="text" id="variant_color" class="input w-half border mt-2" placeholder="Variant Color"> 
						<div class="label-cover mt-2">Size</div>
						<select id="variant_size" class="input w-full border mt-2">
							<option value="S">S</option>
							<option value="L">L</option>
							<option value="XL">XL</option>
							<option value="XXL">XXL</option>
							<option value="XXXL">XXXL</option>
						</select>
						<div class="label-cover mt-2">
							<div class="label-inner">Stock</div>
							<div class="label-inner">Price</div>
						</div>
						<input type="text" id="variant_stock" class="input w-half border mt-2" placeholder="Variant Stock"> 
						<input type="text" id="variant_price" class="input w-half border mt-2" placeholder="Variant Price"> 
						<div class="label-cover mt-2">Discount</div>
						<input type="text" id="variant_discount" class="input w-half border mt-2" placeholder="Variant Discount"> 
						<div class="mt-3"> 
							<label>Variant Images</label>
							<input type="file" id="var_image" style="display:none;" onchange="upload_image('catalog','var_image_output','var_img','var_img_con','var_image_uploader','var_image','variants');">
							<div class="add_var_img"  onclick="activate_image_upload('var_image');">
								<i data-feather="plus" class="mx-auto"></i> 
							</div>
							<div id="var_image_uploader"></div>
						</div>
						<div class="mt-3" id="var_image_output">
							<!-- data from ajax -->
						</div>
						<div class=" pb-8"> <button type="button" class="button w-24 bg-theme-1 text-white" id="add_var_btn" onclick="add_variant();">Save</button> </div>
					</div>
				</div>
				
			</div>			
		</div>
		
		<!-- Edit success modal ends-->
		<!-- Edit Error Modal starts-->
		<div class="modal" id="var_error">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="x-circle" class="w-16 h-16 text-theme-6 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5">Oops...</div>
					 <div class="text-gray-600 mt-2" id="var_error_msg"></div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white">Ok</button> </div>
				 <div class="p-5 text-center border-t border-gray-200 dark:border-dark-5"> <a href="" class="text-theme-1 dark:text-theme-10">Why do I have this issue?</a> </div>
			 </div>
		</div>
		<!-- Edit Error modal ends-->
		<!-- Edit success modal starts-->
		<div class="modal" id="var-success">
			 <div class="modal__content">
				 <div class="p-5 text-center"> <i data-feather="check-circle" class="w-16 h-16 text-theme-9 mx-auto mt-3"></i>
					 <div class="text-3xl mt-5" >Success!</div>
					 <div class="text-gray-600 mt-2">Variant Updated Successfully.</div>
				 </div>
				 <div class="px-5 pb-8 text-center"> <button type="button" data-dismiss="modal" class="button w-24 bg-theme-1 text-white" id="add_success_load" onclick="">Ok</button> </div>
			 </div>
		</div>
		
		<!-- Edit success modal ends-->
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
					brand_product_select();
					category_product_select();
					var query = $("#product_query").val();
					var status = $("#product_status").val();
					var offset = 0;
					var limit = 2;
					brand = $("#search_product_brand").val();
					category = $("#search_product_category").val();
					subcat = $("#search_product_subcat").val();
					product_page_data(query,status,offset,limit,brand,category,subcat);
					get_product_paging(query,status,limit,brand,category,subcat);
				}
			});
		}
		
		check_session();
		
		$(document).ready(function() {
			$('#catalog_option').trigger('click');
		});
		</script>
    </body>
</html>
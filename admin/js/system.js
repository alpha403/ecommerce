var api_url = window.location.origin+'/api/api.php';

function admin_login(){
	var user = $("#email").val();
	var pass = $("#password").val();
	var module = 'authenticate';
	var req = 'admin_login';
	var req_data = JSON.stringify({user:user,pass:pass,module:module,req:req});
	$.post(api_url,{req_data},function(data){
		var data = JSON.parse(data);
		if(data.status=="success"){
			window.location.href="dashboard";
		}
		else{
			$("#email").val('');
			$("#password").val('');
			$("#error-space").html('Username or password is incorrect');
		}
	});
}

function logout(){
	var module = "authenticate";
	var req = "logout";
	var req_data = JSON.stringify({module:module,req:req});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			window.location.href='login';
		}
		else{
			alert("something went wrong");
		}
	});
}

function userinfo(){
	var module = "admin";
	var req = "userinfo";
	var req_data = JSON.stringify({module:module,req:req});
	
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=='success'){
			$("#userinfo_name").html(data.name);
		}
		else{
			check_session();
		}
	});
}

function activate_image_upload(input_id){
	alert("called");
	$('#'+input_id).trigger('click');
}

function upload_image(module,output_id,image_id_prefix,container_id_prefix,uploader,file_source_id,mod){
	$("#"+uploader).hide();
	var image_data = create_image(output_id,image_id_prefix,container_id_prefix,uploader);
	var form_data = new FormData();
	form_data.append('fileName', $('#'+file_source_id)[0].files[0]);
	form_data.append('req_data', JSON.stringify({"module":module,"req":"upload_image",mod:mod}));
	$.ajax({
        url: api_url,
        type:"POST",
        processData:false,
        contentType: false,
        data: form_data,
	    	success: function(response){
					var response=JSON.parse(response);
					if(response.status=="success"){
						$("#"+image_data[0]).removeClass('linear_logo');
						$("#"+image_data[0]).removeClass('linear-background');
						$("#"+image_data[1]).html('<img src="'+window.location.origin+'/'+response.paths[response.paths.length - 1]+response.image+'">');
					}
					else{
						$("#upload_error_msg").html(response.msg);
						$("#upload_error").modal("show");
						$("#"+uploader).show();
						close_image(image_data[0],uploader);
					}
                }
      });
	
}

function close_image(id,uploader){
	$("#"+id).remove();
	$("#"+uploader).show();
}

function create_image(output_id,image_id_prefix,container_id_prefix,uploader){
	var img_count = $('#'+output_id).find('img').length;
	var img_suffix = parseInt(img_count) + 1;
	var con_id = container_id_prefix+'-'+img_suffix;
	var str = Math.random().toString(36).substr(2, 5);
	var image_out = str+'-'+img_suffix;
	$("#"+output_id).append('<div class="image_out linear_logo linear-background" id="'+con_id+'">'+
						'<div class="delete_uploaded_image" onclick="close_image(\''+con_id+'\',\''+uploader+'\');">'+
							'X'+
						'</div>'+
						'<div id="'+image_out+'">'+
						'</div>'+
					'</div>');
	var img_data = [con_id,image_out];
	return img_data;
}

function add_brand(){
	var count = get_all_img_by_id('logo_output');
	var brand_name = $("#brand_name").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(brand_name==""){
		$("#upload_error_msg").html('Brand name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"add_brand",brand_name:brand_name,images:count});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#brand_name").val('');
				$("#logo_output").html('');
				$('#brand_logo_uploader').show();
				$("#add_brand").modal('hide');
				$("#upload_success_load").attr("onclick","load_brand("+data.brand_id+");");
				$("#upload-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}


function load_brand(brand_id){
	$("#brand_loading").show();
	req_data = JSON.stringify({module:"catalog",req:"get_brand_by_id",brand_id:brand_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			setTimeout(function() {
				if(data.brand_data[3]==1){
					var status = "Active";
				}
				else{
					var status = "Deactive";
				}
			$("#brand_loading").hide();
			$("#brand_table").prepend('<tr>'+
										'<td id="brand-'+data.brand_data[0]+'">'
											+data.brand_data[1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="brstatus-'+data.brand_data[0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_brand_editor('+data.brand_data[0]+')">'+ 'Edit </a>'+
                                           '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="brdeact-'+data.brand_data[0]+'" onclick="deactivate_brand('+data.brand_data[0]+');"> Deactivate </a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
							//timeout here	
			 //your code to be executed after 1 second
			}, 1000);				
		}
		
	});
}

function get_all_img_by_id(output_id){
	var image_collection = [];
	var img_count = $('#'+output_id).find('img').length;
	image_collection.push(img_count);
	var images = $('#'+output_id).find('img').map(function() { return this.src; }).get();
	for(var i=0;i<=images.length-1;i++){
		img = images[i];
		img_url_array = img.split('/');
		img_name = img_url_array[img_url_array.length-1];
		image_collection.push(img_name);
	}
	return image_collection;
}

function brand_page_data(query,status,offset,limit){
	var req_data = JSON.stringify({module:"catalog",req:"get_all_brands",query:query,status:status,offset:offset,limit:limit});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#brand_table").html('');
			for(var i=0;i<=data.brand_data.length-1;i++){
				if(data.brand_data[i][3]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="brdeact-'+data.brand_data[i][0]+'" onclick="deactivate_brand('+data.brand_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Deactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="brdeact-'+data.brand_data[i][0]+'" onclick="activate_brand('+data.brand_data[i][0]+')"> Activate </a>';
				}
				$("#brand_table").append('<tr>'+
										'<td id="brand-'+data.brand_data[i][0]+'">'+data.brand_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="brstatus-'+data.brand_data[i][0]+'"> '+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_brand_editor('+data.brand_data[i][0]+')">'+ 'Edit </a>'+
                                           active_btn+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
			}
			
		}
	});
}

function load_brand_editor(brand_id){
	req_data = JSON.stringify({module:"catalog",req:"get_brand_by_id",brand_id:brand_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#edit_logo_output").html('');
			$("#edit_brand_name:text").val(data.brand_data[1]);
			$("#edit_brand_logo_uploader").hide();
			var img_data = create_image('edit_logo_output','editlogo','container_id_prefix','edit_brand_logo_uploader');
			$("#"+img_data[0]).removeClass('linear-background');
			$("#"+img_data[0]).removeClass('linear-logo');
			req_data = JSON.stringify({module:"catalog",req:"get_img_path",mod:"brands"});
			$.post(api_url,{req_data},function(data2){
				data2 = JSON.parse(data2);
				if(data2.status=="success"){
					$("#"+img_data[1]).html('<img src="'+window.location.origin+'/'+data2.paths[data2.paths.length - 1]+data.brand_data[2]+'">');
				}
				else{
					alert("Something went wrong");
				}
			});
			$("#edit_brand_btn").attr("onclick","edit_brand("+brand_id+");");
			$("#edit_brand").modal('show');
		}
	});
}

function edit_brand(brand_id){
	var count = get_all_img_by_id('edit_logo_output');
	var brand_name = $("#edit_brand_name").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(brand_name==""){
		$("#upload_error_msg").html('Brand name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"edit_brand",brand_name:brand_name,images:count,brand_id:brand_id});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#edit_brand_name").val('');
				$("#edit_logo_output").html('');
				$('#edit_brand_logo_uploader').show();
				$("#edit_brand").modal('hide');
				req_data = JSON.stringify({module:"catalog","req":"get_brand_by_id",brand_id:brand_id});
				$.post(api_url,{req_data},function(data2){
					data2 = JSON.parse(data2);
					if(data2.status=="success"){
						$("#brand-"+brand_id).html(data2.brand_data[1]);
						$("#brstatus-"+brand_id).html(get_active_text(data2.brand_data[3]));
					}
				});
				$("#update-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function get_active_text(number){
	if(number==1){
		var status = "Active";
	}
	else{
		var status = "Deactive";
	}
	return status;
}

function deactivate_brand(brand_id){
	req_data = JSON.stringify({module:"catalog",req:"deactivate_brand",brand_id:brand_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#brdeact-"+brand_id).html('Activate');
			$("#brdeact-"+brand_id).attr("onclick",'activate_brand('+brand_id+');');
			$("#brstatus-"+brand_id).html("Deactive");
		}
	});
}

function activate_brand(brand_id){
	req_data = JSON.stringify({module:"catalog",req:"activate_brand",brand_id:brand_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#brdeact-"+brand_id).html('Dectivate');
			$("#brdeact-"+brand_id).attr("onclick",'deactivate_brand('+brand_id+');');
			$("#brstatus-"+brand_id).html("Active");
		}
	});
}

function get_brand_paging(query,status,limit){
	var req_data = JSON.stringify({module:"catalog",req:"brand_paging",query:query,status:status});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#brand_paging").html('');
			var offset = 0;
			for(i=1;i<=data.page_count;i++){
				if(i==1){
					$("#brand_paging").append('<li> <a class="pagination__link pagination__link--active" onclick="brand_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);" href="#">'+i+'</a> </li>');
				}
				else{
				$("#brand_paging").append('<li> <a class="pagination__link" href="#" onclick="brand_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);">'+i+'</a> </li>');
				}
				offset = parseInt(offset)+limit;
			}
		}
	});
}

function activate_pagenum(element){
	$(".pagination__link").removeClass('pagination__link--active');
	$(element).addClass('pagination__link--active');
}

function search_brand(){
	var query = $("#brand_query").val();
	var status = $("#brand_status").val();
	var offset = 0;
	var limit = 2;
	brand_page_data(query,status,offset,limit);
	get_brand_paging(query,status,limit);
}

function add_category(){
	var count = get_all_img_by_id('cat_image_output');
	var category_name = $("#category_name").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(category_name==""){
		$("#upload_error_msg").html('Brand name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"add_category",category_name:category_name,images:count});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#category_name").val('');
				$("#cat_image_output").html('');
				$('#cat_image_uploader').show();
				$("#add_category").modal('hide');
				$("#upload_success_load").attr("onclick","load_category("+data.category_id+");");
				$("#upload-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function load_category(category_id){
	$("#category_loading").show();
	req_data = JSON.stringify({module:"catalog",req:"get_category_by_id",category_id:category_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			setTimeout(function() {
				if(data.category_data[3]==1){
					var status = "Active";
				}
				else{
					var status = "Deactive";
				}
			$("#category_loading").hide();
			$("#category_table").prepend('<tr>'+
										'<td id="category-'+data.category_data[0]+'">'
											+data.category_data[1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="catstatus-'+data.category_data[0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_category_editor('+data.category_data[0]+')">'+ 'Edit </a>'+
                                           '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="catdeact-'+data.category_data[0]+'" onclick="deactivate_category('+data.category_data[0]+');"> Deactivate </a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
							//timeout here	
			 //your code to be executed after 1 second
			}, 1000);				
		}
		
	});
}

function category_page_data(query,status,offset,limit){
	var req_data = JSON.stringify({module:"catalog",req:"get_all_categories",query:query,status:status,offset:offset,limit:limit});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#category_table").html('');
			for(var i=0;i<=data.category_data.length-1;i++){
				if(data.category_data[i][3]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="catdeact-'+data.category_data[i][0]+'" onclick="deactivate_category('+data.category_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Deactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="catdeact-'+data.category_data[i][0]+'" onclick="activate_category('+data.category_data[i][0]+')"> Activate </a>';
				}
				$("#category_table").append('<tr>'+
										'<td id="category-'+data.category_data[i][0]+'">'+data.category_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="catstatus-'+data.category_data[i][0]+'"> '+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_category_editor('+data.category_data[i][0]+')">'+ 'Edit </a>'+
                                           active_btn+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
			}
			
		}
	});
}


function load_category_editor(category_id){
	req_data = JSON.stringify({module:"catalog",req:"get_category_by_id",category_id:category_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#edit_category_image_output").html('');
			$("#edit_category_name:text").val(data.category_data[1]);
			$("#edit_category_image_uploader").hide();
			var img_data = create_image('edit_category_image_output','editcategory','edit_category_con','edit_category_image_uploader');
			$("#"+img_data[0]).removeClass('linear-background');
			$("#"+img_data[0]).removeClass('linear-logo');
			req_data = JSON.stringify({module:"catalog",req:"get_img_path",mod:"category"});
			$.post(api_url,{req_data},function(data2){
				data2 = JSON.parse(data2);
				if(data2.status=="success"){
					$("#"+img_data[1]).html('<img src="'+window.location.origin+'/'+data2.paths[data2.paths.length - 1]+data.category_data[2]+'">');
				}
				else{
					alert("Something went wrong");
				}
			});
			$("#edit_category_btn").attr("onclick","edit_category("+category_id+");");
			$("#edit_category").modal('show');
		}
	});
}

function edit_category(category_id){
	var count = get_all_img_by_id('edit_category_image_output');
	var category_name = $("#edit_category_name").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(category_name==""){
		$("#upload_error_msg").html('Category name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"edit_category",category_name:category_name,images:count,category_id:category_id});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#edit_category_name").val('');
				$("#edit_category_name").html('');
				$('#edit_category_image_uploader').show();
				$("#edit_category").modal('hide');
				req_data = JSON.stringify({module:"catalog","req":"get_category_by_id",category_id:category_id});
				$.post(api_url,{req_data},function(data2){
					data2 = JSON.parse(data2);
					if(data2.status=="success"){
						$("#category-"+category_id).html(data2.category_data[1]);
						$("#catstatus-"+category_id).html(get_active_text(data2.category_data[3]));
					}
				});
				$("#update-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function deactivate_category(category_id){
	req_data = JSON.stringify({module:"catalog",req:"deactivate_category",category_id:category_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#catdeact-"+category_id).html('Activate');
			$("#catdeact-"+category_id).attr("onclick",'activate_category('+category_id+');');
			$("#catstatus-"+category_id).html("Deactive");
		}
	});
}

function activate_category(category_id){
	req_data = JSON.stringify({module:"catalog",req:"activate_category",category_id:category_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#catdeact-"+category_id).html('Dectivate');
			$("#catdeact-"+category_id).attr("onclick",'deactivate_category('+category_id+');');
			$("#catstatus-"+category_id).html("Active");
		}
	});
}

function get_category_paging(query,status,limit){
	var req_data = JSON.stringify({module:"catalog",req:"category_paging",query:query,status:status});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#category_paging").html('');
			var offset = 0;
			for(i=1;i<=data.page_count;i++){
				if(i==1){
					$("#category_paging").append('<li> <a class="pagination__link pagination__link--active" onclick="category_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);" href="#">'+i+'</a> </li>');
				}
				else{
				$("#category_paging").append('<li> <a class="pagination__link" href="#" onclick="category_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);">'+i+'</a> </li>');
				}
				offset = parseInt(offset)+limit;
			}
		}
	});
}

function search_category(){
	var query = $("#category_query").val();
	var status = $("#category_status").val();
	var offset = 0;
	var limit = 2;
	category_page_data(query,status,offset,limit);
	get_category_paging(query,status,limit);
}

function getcat_select(select_id){
	var query = "";
	var status = "";
	var offset = 0;
	var limit = 10000000;
	var req_data = JSON.stringify({module:"catalog",req:"get_all_categories",query:query,status:status,offset:offset,limit:limit});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			var options = '<option value="">Select Category</option>';
			for(var i=0;i<=data.category_data.length-1;i++){
				options += '<option>'+data.category_data[i][1]+'</option>';
			}
			$("#"+select_id).html(options);
		}
	});
}

function add_subcat(){
	var count = get_all_img_by_id('subcat_image_output');
	var subcat_name = $("#subcat_name").val();
	var category_name = $("#add_subcat_category").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(subcat_name==""){
		$("#upload_error_msg").html('Sub Category name can not be empty');
		$("#upload_error").modal("show");
	}
	else if(category_name==""){
		$("#upload_error_msg").html('Category name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"add_subcat",category_name:category_name,subcat_name:subcat_name,images:count});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#subcat_name").val('');
				$("#subcat_image_output").html('');
				$('#subcat_image_uploader').show();
				$("#add_subcat").modal('hide');
				$("#upload_success_load").attr("onclick","load_subcat("+data.subcat_id+");");
				$("#upload-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}


function load_subcat(subcat_id){
	$("#subcat_loading").show();
	req_data = JSON.stringify({module:"catalog",req:"get_subcat_by_id",subcat_id:subcat_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			setTimeout(function() {
				if(data.subcat_data[3]==1){
					var status = "Active";
				}
				else{
					var status = "Deactive";
				}
			$("#subcat_loading").hide();
			$("#subcat_table").prepend('<tr>'+
										'<td id="subcat-'+data.subcat_data[0]+'">'
											+data.subcat_data[1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="subcat_cat-'+data.subcat_data[0]+'">'+data.subcat_data[4]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="subcatstatus-'+data.subcat_data[0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_subcat_editor('+data.subcat_data[0]+')">'+ 'Edit </a>'+
                                           '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="subcatdeact-'+data.subcat_data[0]+'" onclick="deactivate_subcat('+data.subcat_data[0]+');"> Deactivate </a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
							//timeout here	
			 //your code to be executed after 1 second
			}, 1000);				
		}
		
	});
}


function subcat_page_data(query,status,offset,limit,category){
	var req_data = JSON.stringify({module:"catalog",req:"get_all_subcat",query:query,status:status,category:category,offset:offset,limit:limit});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#subcat_table").html('');
			for(var i=0;i<=data.subcat_data.length-1;i++){
				if(data.subcat_data[i][3]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="subcatdeact-'+data.subcat_data[i][0]+'" onclick="deactivate_subcat('+data.subcat_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Deactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="subcatdeact-'+data.subcat_data[i][0]+'" onclick="activate_subcat('+data.subcat_data[i][0]+')"> Activate </a>';
				}
				$("#subcat_table").append('<tr>'+
										'<td id="subcat-'+data.subcat_data[i][0]+'">'+data.subcat_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="subcat_category-'+data.subcat_data[i][0]+'"> '+data.subcat_data[i][4]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="subcatstatus-'+data.subcat_data[i][0]+'"> '+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_subcat_editor('+data.subcat_data[i][0]+')">'+ 'Edit </a>'+
                                           active_btn+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
			}
			
		}
	});
}

function load_subcat_editor(subcat_id){
	req_data = JSON.stringify({module:"catalog",req:"get_subcat_by_id",subcat_id:subcat_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#edit_subcat_image_output").html('');
			$("#edit_subcat_name:text").val(data.subcat_data[1]);
			$("#edit_subcat_category").val(data.subcat_data[4]);
			$("#edit_subcat_image_uploader").hide();
			var img_data = create_image('edit_subcat_image_output','editsubcat','edit_subcat_con','edit_subcat_image_uploader');
			$("#"+img_data[0]).removeClass('linear-background');
			$("#"+img_data[0]).removeClass('linear-logo');
			req_data = JSON.stringify({module:"catalog",req:"get_img_path",mod:"subcategory"});
			$.post(api_url,{req_data},function(data2){
				data2 = JSON.parse(data2);
				if(data2.status=="success"){
					$("#"+img_data[1]).html('<img src="'+window.location.origin+'/'+data2.paths[data2.paths.length - 1]+data.subcat_data[2]+'">');
				}
				else{
					alert("Something went wrong");
				}
			});
			$("#edit_subcat_btn").attr("onclick","edit_subcat("+subcat_id+");");
			$("#edit_subcat").modal('show');
		}
	});
}

function edit_subcat(subcat_id){
	var count = get_all_img_by_id('edit_subcat_image_output');
	var subcat_name = $("#edit_subcat_name").val();
	var category_name = $("#edit_subcat_category").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(category_name==""){
		$("#upload_error_msg").html('Category name can not be empty');
		$("#upload_error").modal("show");
	}
	else if(subcat_name==""){
		$("#upload_error_msg").html('Sub Category name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"edit_subcat",category_name:category_name,subcat_name:subcat_name,images:count,subcat_id:subcat_id});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#edit_subcat_name").val('');
				$('#edit_subcat_image_uploader').show();
				$("#edit_subcat").modal('hide');
				req_data = JSON.stringify({module:"catalog","req":"get_subcat_by_id",subcat_id:subcat_id});
				$.post(api_url,{req_data},function(data2){
					data2 = JSON.parse(data2);
					if(data2.status=="success"){
						$("#subcat-"+subcat_id).html(data2.subcat_data[1]);
						$("#subcat_category-"+subcat_id).html(data2.subcat_data[4]);
						$("#subcatstatus-"+subcat_id).html(get_active_text(data2.subcat_data[3]));
						
					}
				});
				$("#update-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function deactivate_subcat(subcat_id){
	req_data = JSON.stringify({module:"catalog",req:"deactivate_subcat",subcat_id:subcat_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#subcatdeact-"+subcat_id).html('Activate');
			$("#subcatdeact-"+subcat_id).attr("onclick",'activate_subcat('+subcat_id+');');
			$("#subcatstatus-"+subcat_id).html("Deactive");
		}
	});
}

function activate_subcat(subcat_id){
	req_data = JSON.stringify({module:"catalog",req:"activate_subcat",subcat_id:subcat_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#subcatdeact-"+subcat_id).html('Dectivate');
			$("#subcatdeact-"+subcat_id).attr("onclick",'deactivate_subcat('+subcat_id+');');
			$("#subcatstatus-"+subcat_id).html("Active");
		}
	});
}

function get_subcat_paging(query,status,limit,category){
	var req_data = JSON.stringify({module:"catalog",req:"subcat_paging",query:query,status:status,category:category});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#subcat_paging").html('');
			var offset = 0;
			for(i=1;i<=data.page_count;i++){
				if(i==1){
					$("#subcat_paging").append('<li> <a class="pagination__link pagination__link--active" onclick="subcat_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+','+category+');activate_pagenum(this);" href="#">'+i+'</a> </li>');
				}
				else{
				$("#subcat_paging").append('<li> <a class="pagination__link" href="#" onclick="subcat_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+','+category+');activate_pagenum(this);">'+i+'</a> </li>');
				}
				offset = parseInt(offset)+limit;
			}
		}
	});
}

function search_subcat(){
	var query = $("#subcat_query").val();
	var status = $("#subcat_status").val();
	var category = $("#subcatcat_category").val();
	var offset = 0;
	var limit = 2;
	subcat_page_data(query,status,offset,limit,category);
	get_subcat_paging(query,status,limit,category);
}

function brand_product_select(){
	var query = "";
	var status = "";
	var offset = 0;
	var limit = 10000000;
	var req_data = JSON.stringify({module:"catalog",req:"get_all_brands",query:query,status:status,offset:offset,limit:limit});
	$.post(api_url,{req_data:req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			for(var i=0;i<=data.brand_data.length-1;i++){
				$("#product_brand").append('<option value="'+data.brand_data[i][0]+'">'+data.brand_data[i][1]+'</option>');
				$("#search_product_brand").append('<option value="'+data.brand_data[i][0]+'">'+data.brand_data[i][1]+'</option>');
				$("#edit_product_brand").append('<option value="'+data.brand_data[i][0]+'">'+data.brand_data[i][1]+'</option>');
			}
		}
	});
}

function category_product_select(){
	var query = "";
	var status = "";
	var offset = 0;
	var limit = 10000000;
	var req_data = JSON.stringify({module:"catalog",req:"get_all_categories",query:query,status:status,offset:offset,limit:limit});
	$.post(api_url,{req_data:req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			for(var i=0;i<=data.category_data.length-1;i++){
				$("#product_category").append('<option value="'+data.category_data[i][0]+'">'+data.category_data[i][1]+'</option>');
				$("#search_product_category").append('<option value="'+data.category_data[i][0]+'">'+data.category_data[i][1]+'</option>');
				$("#edit_product_category").append('<option value="'+data.category_data[i][0]+'">'+data.category_data[i][1]+'</option>');
			}
		}
	});
}

function product_subcat_select(cat_box_id,subcat_box_id){
	var category_id = $("#"+cat_box_id).val();
	var req_data = JSON.stringify({module:"catalog",req:"get_subcat_by_catid",category_id:category_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#"+subcat_box_id).html('<option value="">Select Sub Category</option>');
			for(var i=0;i<=data.subcat_data.length-1;i++){
				$("#"+subcat_box_id).append('<option value="'+data.subcat_data[i][0]+'">'+data.subcat_data[i][1]+'</option>');
			}
		}
	});
}

function add_product(){
	var product_name = $("#product_name").val();
	var product_description = $("#product_description").val();
	var brand_id = $("#product_brand").val();
	var category_id = $("#product_category").val();
	var subcat_id = $("#product_subcat").val();
	
	if(product_name==""){
		$("#add_error_msg").html('Product name can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(product_description==""){
		$("#add_error_msg").html('Product description can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(brand_id==""){
		$("#add_error_msg").html('Brand Name can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(category_id==""){
		$("#add_error_msg").html('Category can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(subcat_id==""){
		$("#add_error_msg").html('Sub Category can not be empty');
		$("#add_product_error").modal("show");
	}
	else{
		var req_data = JSON.stringify({module:"catalog",req:"add_product",product_name:product_name,product_description,brand_id:brand_id,category_id:category_id,subcat_id:subcat_id});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#product_name").val('');
				$("#product_description").val('');
				$("#add_product").modal('hide');
				$("#add_success_load").attr("onclick","load_product("+data.product_id+");");
				$("#add-success").modal('show');
			}
			else{
				$("#add_error_msg").html(data.msg);
				$("#add_product_error").modal("show");
			}
		})
	}
}

function load_product(product_id){
	$("#product_loading").show();
	req_data = JSON.stringify({module:"catalog",req:"get_product_by_id",product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			setTimeout(function() {
				if(data.product_data[7]==1){
					var status = "Active";
				}
				else{
					var status = "Deactive";
				}
			$("#product_loading").hide();
			$("#product_table").prepend('<tr class="added">'+
										'<td id="product-'+data.product_data[0]+'">'
											+data.product_data[1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_brand-'+data.product_data[0]+'">'+data.product_data[4]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_cat-'+data.product_data[0]+'">'+data.product_data[5]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_subcat-'+data.product_data[0]+'">'+data.product_data[6]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="productstatus-'+data.product_data[0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_product_editor('+data.product_data[0]+')">'+ 'Edit </a>'+
                                           '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="productdeact-'+data.product_data[0]+'" onclick="deactivate_product('+data.product_data[0]+');"> Deactivate </a>'+
										   '<a class="flex items-center mr-3" href="javascript:;" onclick="variants('+data.product_data[0]+')">Variants</a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
							//timeout here	
			 //your code to be executed after 1 second
			}, 1000);				
		}
		
	});
}

function product_page_data(query,status,offset,limit,brand,category,subcat){
	var req_data = JSON.stringify({module:"catalog",req:"get_all_products",query:query,status:status,brand:brand,category:category,subcat:subcat,offset:offset,limit:limit});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#product_table .added").remove();
			for(var i=0;i<=data.product_data.length-1;i++){
				if(data.product_data[i][4]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="productdeact-'+data.product_data[i][0]+'" onclick="deactivate_product('+data.product_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Deactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="productdeact-'+data.product_data[i][0]+'" onclick="activate_product('+data.product_data[i][0]+')"> Activate </a>';
				}
				$("#product_table").append('<tr class="added">'+
										'<td id="product-'+data.product_data[i][0]+'">'
											+data.product_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_brand-'+data.product_data[i][0]+'">'+data.product_data[i][5]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_cat-'+data.product_data[i][0]+'">'+data.product_data[i][6]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_subcat-'+data.product_data[i][0]+'">'+data.product_data[i][7]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="productstatus-'+data.product_data[i][0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_product_editor('+data.product_data[i][0]+')">'+ 'Edit </a>'+active_btn+
											'<a class="flex items-center mr-3" href="javascript:;" onclick="variants('+data.product_data[i][0]+')"> &nbsp;&nbsp;Variants</a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
			}
			
		}
	});
}

function load_product_editor(product_id){
	var req_data = JSON.stringify({module:"catalog",req:"get_product_by_id_adv",product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#edit_product_name").val(data.product_data.product_name);
			$("#edit_product_description").val(data.product_data.description);
			$(".note-placeholder").hide();
			$(".note-editable").text(data.product_data.description);
			
			$("#edit_product_brand").val(data.product_data.brand_id);
			$("#edit_product_category").val(data.product_data.cat_id);
			//product_subcat_select('edit_product_category','edit_product_subcat');
			$("#edit_product_subcat").append('<option value="'+data.product_data.subcat_id+'">'+data.product_data.subcat+'</option>');
			$("#edit_product_subcat").val(data.product_data.subcat_id);
			
			$("#edit_product_btn").attr("onclick","edit_product("+product_id+");");
			$("#edit_product").modal('show');
			
		}
	});
}

function edit_product(product_id){
	var product_name = $("#edit_product_name").val();
	var product_description = $("#edit_product_description").val();
	var brand_id = $("#edit_product_brand").val();
	var category_id = $("#edit_product_category").val();
	var subcat_id = $("#edit_product_subcat").val();
	
	if(product_name==""){
		$("#add_error_msg").html('Product name can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(product_description==""){
		$("#add_error_msg").html('Product description can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(brand_id==""){
		$("#add_error_msg").html('Brand Name can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(category_id==""){
		$("#add_error_msg").html('Category can not be empty');
		$("#add_product_error").modal("show");
	}
	else if(subcat_id==""){
		$("#add_error_msg").html('Sub Category can not be empty');
		$("#add_product_error").modal("show");
	}
	else{
		var req_data = JSON.stringify({module:"catalog",req:"edit_product",product_name:product_name,product_description,brand_id:brand_id,category_id:category_id,subcat_id:subcat_id,product_id:product_id});
		$.post(api_url,{req_data},function(data){
			data=JSON.parse(data);
			if(data.status=="success"){
				fetch_edited_product(product_id);
				$("#edit_product").modal('hide');
				$("#edit-success").modal('show');
			}
		});
	}
}

function fetch_edited_product(product_id){
	var req_data = JSON.stringify({module:"catalog",req:"get_product_by_id_adv",product_id:product_id});
				$.post(api_url,{req_data},function(data2){
					data2 = JSON.parse(data2);
					if(data2.status=="success"){
						$("#product-"+product_id).html(data2.product_data.product_name);
						$("#product_brand-"+product_id).html(data2.product_data.brand);
						$("#product_cat-"+product_id).html(data2.product_data.cat_name);
						$("#product_subcat-"+product_id).html(data2.product_data.subcat);
						$("#subcatstatus-"+product_id).html(get_active_text(data2.product_data.subcat));
					}
				});
}

function deactivate_product(product_id){
	req_data = JSON.stringify({module:"catalog",req:"deactivate_product",product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#productdeact-"+product_id).html('Activate');
			$("#productdeact-"+product_id).attr("onclick",'activate_product('+product_id+');');
			$("#productstatus-"+product_id).html("Deactive");
		}
	});
}

function activate_product(product_id){
	var req_data = JSON.stringify({module:"catalog",req:"activate_product",product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#productdeact-"+product_id).html('Dectivate');
			$("#productdeact-"+product_id).attr("onclick",'deactivate_product('+product_id+');');
			$("#productstatus-"+product_id).html("Active");
		}
	});
}

function get_product_paging(query,status,limit,brand,category,subcat){
	var req_data = JSON.stringify({module:"catalog",req:"product_paging",query:query,status:status,brand:brand,category:category,subcat:subcat});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#product_paging").html('');
			var offset = 0;
			for(i=1;i<=data.page_count;i++){
				if(i==1){
					$("#product_paging").append('<li> <a class="pagination__link pagination__link--active" onclick="product_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+',\''+brand+'\',\''+category+'\',\''+subcat+'\');activate_pagenum(this);" href="#">'+i+'</a> </li>');
				}
				else{
				$("#product_paging").append('<li> <a class="pagination__link" href="#" onclick="product_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+',\''+brand+'\',\''+category+'\',\''+subcat+'\');activate_pagenum(this);">'+i+'</a> </li>');
				}
				offset = parseInt(offset)+limit;
			}
		}
	});
}

function search_product(){
	var brand = $("#search_product_brand").val();
	var category = $("#search_product_category").val();
	var subcat = $("#search_product_subcat").val();
	var query = $("#product_query").val();
	var status = $("#product_status").val();
	var offset = 0;
	var limit = 2;
	product_page_data(query,status,offset,limit,brand,category,subcat);
	get_product_paging(query,status,limit,brand,category,subcat);
}

function variants(product_id){
	var req_data = JSON.stringify({module:"catalog",req:"getVariantByProduct",product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#variant-output").html('');
			for(var i=0;i<=data.variants.length-1;i++){
				if(data.variants[i].status=="1"){
					status="Active";
				}
				else{
					status = "Inactive";
				}
				$("#variant-output").append('<div id="varbox-'+data.variants[i].id+'" class="var-box">'+
						'<div class="label-cover">'+
							'<div class="label-inner">SKU</div>'+
							'<div class="label-inner">Color</div>'+
						'</div>'+
						'<input type="text" id="variant_sku" class="input w-half border mt-2" placeholder="SKU" value="'+data.variants[i].sku+'">'+
						'<input type="text" id="variant_color" class="input w-half border mt-2" placeholder="Variant Color" value="'+data.variants[i].color+'">'+ 
						'<div class="label-cover mt-2">Size</div>'+
						'<select id="variant_size" class="input w-full border mt-2">'+
							'<option value="'+data.variants[i].size+'">'+data.variants[i].size+'</option>'+
							'<option value="S">S</option>'+
							'<option value="L">L</option>'+
							'<option value="XL">XL</option>'+
							'<option value="XXL">XXL</option>'+
							'<option value="XXXL">XXXL</option>'+
						'</select>'+
						'<div class="label-cover mt-2">'+
							'<div class="label-inner">Stock</div>'+
							'<div class="label-inner">Price</div>'+
						'</div>'+
						'<input type="text" id="variant_stock" class="input w-half border mt-2" placeholder="Variant Stock" value="'+data.variants[i].stock+'">'+ 
						'<input type="text" id="variant_price" class="input w-half border mt-2" placeholder="Variant Price" value="'+data.variants[i].price+'">'+ 
						'<div class="label-cover mt-2">'+
							'<div class="label-inner">Discount</div>'+
							'<div class="label-inner">Status</div>'+
						'</div>'+
						'<input type="text" id="variant_discount" class="input w-half border mt-2" placeholder="Variant Discount" value="'+data.variants[i].discount+'">'+
						'<select id="variant_status" class="input w-half border mt-2">'+
						'<option value="'+data.variants[i].status+'">'+status+'</option>'+
						'<option value="1">Active</option>'+
						'<option value="0">Inactive</option>'+
						'</select>'+
						'<div class="mt-3">'+ 
							'<label>Variant Images</label>'+
							'<input type="file" id="var_image-'+data.variants[i].id+'" style="display:none;" onchange="upload_image(\'catalog\',\'var_image_output-'+data.variants[i].id+'\',\'var_img-'+data.variants[i].id+'\',\'var_img_con-'+data.variants[i].id+'\',\'var_image_uploader-'+data.variants[i].id+'\',\'var_image-'+data.variants[i].id+'\',\'variants\');">'+
							'<div class="add_var_img"  onclick="activate_image_upload(\'var_image-'+data.variants[i].id+'\');" style="text-align:center;">'+
								'+'+ 
							'</div>'+
							'<div id="var_image_uploader-'+data.variants[i].id+'"></div>'+
						'</div>'+
						'<div class="mt-3" id="var_image_output-'+data.variants[i].id+'" style="width:100%;overflow:hidden;">'+
							'<!-- data from ajax -->'+
						'</div><br>'+
						'<div class=" pb-8"> <button type="button" class="button w-24 bg-theme-1 text-white" id="add_var_btn" onclick="update_variant('+data.variants[i].id+');">Save</button> </div>'+
					'</div>');
					
					$("#var_image_output-"+data.variants[i].id).html('');
					var image_output = 'var_image_output-'+data.variants[i].id;
					var var_image = 'var_image-'+data.variants[i].id;
					var var_image_con = 'var_img__con-'+data.variants[i].id;
					var var_image_uploader = 'var_image_uploader-'+data.variants[i].id;
					for(x=0;x<=data.variants[i].images.length-1;x++){
						var img_data = create_image(image_output,var_image,var_image_con,var_image_uploader);
						$("#"+img_data[0]).removeClass('linear-background');
						$("#"+img_data[0]).removeClass('linear-logo');
						$("#"+img_data[1]).html('<img src="'+window.location.origin+'/'+data.paths[data.paths.length - 1]+data.variants[i].images[x]+'">');
					}
					
					
			}
				
		}
	});
	$("#add_var_btn").attr("onclick","add_variant("+product_id+")");
	$("#variants-modal").modal("show");	
}

function add_variant(product_id){
	var sku = $("#variant_sku").val();
	var size = $("#variant_size").val();
	var color = $("#variant_color").val();
	var stock = $("#variant_stock").val();
	var price = $("#variant_price").val();
	var discount = $("#variant_discount").val();
	var count = get_all_img_by_id('var_image_output');
	if(sku==""){
		$("#add_error_msg").html("SKU can not be blank");
		$("#add_product_error").modal("show");
	}
	else if(color==""){
		$("#add_error_msg").html("Color can not be blank");
		$("#add_product_error").modal("show");
	}
	else if(size==""){
		$("#add_error_msg").html("Size can not be blank");
		$("#add_product_error").modal("show");
	}
	else if(price==""){
		$("#add_error_msg").html("Price can not be blank");
		$("#add_product_error").modal("show");
	}
	else if(discount==""){
		$("#add_error_msg").html("Discount can not be blank");
		$("#add_product_error").modal("show");
	}
	else if(count[0]==0){
		$("#add_error_msg").html("Please select atleast One Image");
		$("#add_product_error").modal("show");
	}
	else{
		count.shift();
		var req_data = JSON.stringify({module:"catalog",req:"add_variant",product_id:product_id,sku:sku,size:size,color:color,stock:stock,price:price,discount:discount,images:count});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#variant_sku").val('');
				$("#variant_color").val('');
				$("#variant_stock").val('');
				$("#variant_price").val('');
				$("#variant_discount").val('');
				$("#var_image_output").html('');
				$("#variants-modal").modal("hide");
				$("#var-success").modal("show");
			}
			else{
				$("#add_error_msg").html(data.msg);
				$("#add_product_error").modal("show");
			}
		})
	}
}

function update_variant(var_id){
	var count = get_all_img_by_id('var_image_output-'+var_id);
	var sku = $("#varbox-"+var_id+" #variant_sku").val();
	var color = $("#varbox-"+var_id+" #variant_color").val();
	var size = $("#varbox-"+var_id+" #variant_size").val();
	var stock = $("#varbox-"+var_id+" #variant_stock").val();
	var price = $("#varbox-"+var_id+" #variant_price").val();
	var discount = $("#varbox-"+var_id+" #variant_discount").val();
	var status = $("#varbox-"+var_id+" #variant_status").val();
	
	if(sku==""){
		$("#var_error_msg").html("SKU can not be blank");
		$("#var_error").modal("show");
	}
	else if(color==""){
		$("#var_error_msg").html("Color can not be blank");
		$("#var_error").modal("show");
	}
	else if(size==""){
		$("#var_error_msg").html("Size can not be blank");
		$("#var_error").modal("show");
	}
	else if(stock==""){
		$("#var_error_msg").html("Stock can not be blank");
		$("#var_error").modal("show");
	}
	else if(price==""){
		$("#var_error_msg").html("Price can not be blank");
		$("#var_error").modal("show");
	}
	else if(discount==""){
		$("#var_error_msg").html("Discount can not be blank");
		$("#var_error").modal("show");
	}
	else if(status==""){
		$("#var_error_msg").html("Status can not be blank");
		$("#var_error").modal("show");
	}
	else if(count[0]==0){
		$("#var_error_msg").html('Atleast one image must be there.');
		$("#var_error").modal("show");
	}
	else{
		count.shift(); // Remove first element (Image count)
		var req_data = JSON.stringify({module:"catalog",req:"update_variant",images:count,sku:sku,color:color,size:size,stock:stock,price:price,discount:discount,status:status,variant:var_id});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#var-success").modal("show");
			}
			else{
				$("#var_error_msg").html('Something Went Wrong');
				$("#var_error").modal("show");
			}
		})
	}	
}

function add_collection(){
	var count = get_all_img_by_id('coll_banner_output');
	var coll_name = $("#coll_name").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(coll_name==""){
		$("#upload_error_msg").html('Coll name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"add_collection",coll_name:coll_name,images:count});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#coll_name").val('');
				$("#coll_banner_output").html('');
				$('#coll_banner_uploader').show();
				$("#add_collection").modal('hide');
				$("#upload_success_load").attr("onclick","load_collection("+data.coll_id+");");
				$("#upload-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function load_collection(coll_id){
	$("#coll_loading").show();
	req_data = JSON.stringify({module:"catalog",req:"get_coll_by_id",coll_id:coll_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			setTimeout(function() {
				if(data.coll_data[3]==1){
					var status = "Active";
				}
				else{
					var status = "Inactive";
				}
			$("#coll_loading").hide();
			$("#coll_table").prepend('<tr class="added">'+
										'<td id="coll-'+data.coll_data[0]+'">'
											+data.coll_data[1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="collstatus-'+data.coll_data[0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-60">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_coll_editor('+data.coll_data[0]+')">'+ 'Edit </a>'+
                                           '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="colldeact-'+data.coll_data[0]+'" onclick="deactivate_coll('+data.coll_data[0]+');"> Deactivate </a>'+
										   '<a class="flex items-center mr-3" href="javascript:;" onclick="add_coll_product('+data.coll_data[0]+')">'+ ' &nbsp;&nbsp;&nbsp;Add Products </a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
							//timeout here	
			 //your code to be executed after 1 second
			}, 1000);				
		}
		
	});
}

function coll_page_data(query,status,offset,limit){
	var req_data = JSON.stringify({module:"catalog",req:"get_all_colls",query:query,status:status,offset:offset,limit:limit});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$(".added").remove();
			for(var i=0;i<=data.coll_data.length-1;i++){
				if(data.coll_data[i][3]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="colldeact-'+data.coll_data[i][0]+'" onclick="deactivate_coll('+data.coll_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Deactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="colldeact-'+data.coll_data[i][0]+'" onclick="activate_coll('+data.coll_data[i][0]+')"> Activate </a>';
				}
				$("#coll_table").append('<tr class="added">'+
										'<td id="coll-'+data.coll_data[i][0]+'">'+data.coll_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="collstatus-'+data.coll_data[i][0]+'"> '+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-60">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_coll_editor('+data.coll_data[i][0]+')">'+ 'Edit </a>'+
                                           active_btn+
										   '<a class="flex items-center mr-3" href="javascript:;" onclick="add_coll_product('+data.coll_data[i][0]+')">'+ ' &nbsp;&nbsp;&nbsp;Add Products </a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
			}
			
		}
	});
}

function load_coll_editor(coll_id){
	req_data = JSON.stringify({module:"catalog",req:"get_coll_by_id",coll_id:coll_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#edit_coll_banner_output").html('');
			$("#edit_coll_name:text").val(data.coll_data[1]);
			$("#edit_coll_banner_uploader").hide();
			var img_data = create_image('edit_coll_banner_output','editcollbanner','container_id_prefix','edit_coll_banner_uploader');
			$("#"+img_data[0]).removeClass('linear-background');
			$("#"+img_data[0]).removeClass('linear-logo');
			req_data = JSON.stringify({module:"catalog",req:"get_img_path",mod:"collections"});
			$.post(api_url,{req_data},function(data2){
				data2 = JSON.parse(data2);
				if(data2.status=="success"){
					$("#"+img_data[1]).html('<img src="'+window.location.origin+'/'+data2.paths[data2.paths.length - 1]+data.coll_data[2]+'">');
				}
				else{
					alert("Something went wrong");
				}
			});
			$("#edit_coll_btn").attr("onclick","edit_coll("+coll_id+");");
			$("#edit_coll").modal('show');
		}
	});
}

function edit_coll(coll_id){
	var count = get_all_img_by_id('edit_coll_banner_output');
	var coll_name = $("#edit_coll_name").val();
	if(count[0]==0){
		$("#upload_error_msg").html('Atleast one image must be there.');
		$("#upload_error").modal("show");
	}
	else if(coll_name==""){
		$("#upload_error_msg").html('Coll name can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		count.shift(); // remove images count
		var req_data = JSON.stringify({module:"catalog",req:"edit_coll",coll_name:coll_name,images:count,coll_id:coll_id});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#edit_coll_name").val('');
				$("#edit_coll_banner_output").html('');
				$('#edit_coll_banner_uploader').show();
				$("#edit_coll").modal('hide');
				req_data = JSON.stringify({module:"catalog","req":"get_coll_by_id",coll_id:coll_id});
				$.post(api_url,{req_data},function(data2){
					data2 = JSON.parse(data2);
					if(data2.status=="success"){
						$("#coll-"+coll_id).html(data2.coll_data[1]);
						$("#collstatus-"+coll_id).html(get_active_text(data2.coll_data[3]));
					}
				});
				$("#update-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function deactivate_coll(coll_id){
	req_data = JSON.stringify({module:"catalog",req:"deactivate_coll",coll_id:coll_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#colldeact-"+coll_id).html('Activate');
			$("#colldeact-"+coll_id).attr("onclick",'activate_coll('+coll_id+');');
			$("#collstatus-"+coll_id).html("Deactive");
		}
	});
}

function activate_coll(coll_id){
	req_data = JSON.stringify({module:"catalog",req:"activate_coll",coll_id:coll_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#colldeact-"+coll_id).html('Dectivate');
			$("#colldeact-"+coll_id).attr("onclick",'deactivate_brand('+coll_id+');');
			$("#collstatus-"+coll_id).html("Active");
		}
	});
}

function get_coll_paging(query,status,limit){
	var req_data = JSON.stringify({module:"catalog",req:"coll_paging",query:query,status:status});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#coll_paging").html('');
			var offset = 0;
			for(i=1;i<=data.page_count;i++){
				if(i==1){
					$("#coll_paging").append('<li> <a class="pagination__link pagination__link--active" onclick="coll_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);" href="#">'+i+'</a> </li>');
				}
				else{
				$("#coll_paging").append('<li> <a class="pagination__link" href="#" onclick="coll_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);">'+i+'</a> </li>');
				}
				offset = parseInt(offset)+limit;
			}
		}
	});
}

function search_coll(){
	var query = $("#coll_query").val();
	var status = $("#coll_status").val();
	var offset = 0;
	var limit = 2;
	coll_page_data(query,status,offset,limit);
	get_coll_paging(query,status,limit);
}

function add_coll_product(coll_id){
	$("#coll_id").val(coll_id);
	$("#product_table").html('');
	$("#search_product_brand").val('');
	$("#search_product_category").val('');
	$("#search_product_subcat").val('');
	$("#product_query").val('');
	$("#product_status").val('');
	var req_data = JSON.stringify({module:"catalog",req:"productByCollection",coll_id:coll_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#collproduct_table .added").remove();
			for(var i=0;i<=data.product_data.length-1;i++){
				if(data.product_data[i][4]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="productdeact-'+data.product_data[i][0]+'" onclick="deactivate_product('+data.product_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Deactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="productdeact-'+data.product_data[i][0]+'" onclick="activate_product('+data.product_data[i][0]+')"> Activate </a>';
				}
				
				
				if(data.product_data[i][8]=='Added'){
					var add_btn = '<a class="flex items-center mr-3" href="javascript:;" id="add_coll-'+data.product_data[i][0]+'" onclick="removeFromCollection('+data.product_data[i][0]+')"> &nbsp;&nbsp;Remove</a></div>';
				}
				else{
					var add_btn = '<a class="flex items-center mr-3" href="javascript:;" id="add_coll-'+data.product_data[i][0]+'" onclick="addToCollection('+data.product_data[i][0]+')"> &nbsp;&nbsp;Add to Collection</a></div>';
				}
				
				
				
				$("#collproduct_table").append('<tr class="added" id="rem-'+data.product_data[i][0]+'">'+
										'<td id="product-'+data.product_data[i][0]+'">'
											+data.product_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_brand-'+data.product_data[i][0]+'">'+data.product_data[i][5]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_cat-'+data.product_data[i][0]+'">'+data.product_data[i][6]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_subcat-'+data.product_data[i][0]+'">'+data.product_data[i][7]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="productstatus-'+data.product_data[i][0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
										'<a class="flex items-center mr-3" href="javascript:;" id="add_coll-'+data.product_data[i][0]+'" onclick="removeFromCollection('+data.product_data[i][0]+')"> &nbsp;&nbsp;Remove</a></div>'+
                                    '</td>'+
								'</tr>');
			}
			
			$("#add_collection_product").modal("show");
		}
		else{
			alert("Something Went Wrong");
		}
	});
	
	
}

function search_product_collection(){
	var brand = $("#search_product_brand").val();
	var category = $("#search_product_category").val();
	var subcat = $("#search_product_subcat").val();
	var query = $("#product_query").val();
	var status = $("#product_status").val();
	var coll_id = $("#coll_id").val();
	var offset = 0;
	var limit = 2;
	coll_product_page_data(query,status,offset,limit,brand,category,subcat,coll_id);
	get_coll_product_paging(query,status,limit,brand,category,subcat);
}

function coll_product_page_data(query,status,offset,limit,brand,category,subcat,coll_id){
	var req_data = JSON.stringify({module:"catalog",req:"get_all_coll_products",query:query,status:status,brand:brand,category:category,subcat:subcat,offset:offset,limit:limit,coll_id:coll_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#product_table .added").remove();
			for(var i=0;i<=data.product_data.length-1;i++){
				if(data.product_data[i][4]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="productdeact-'+data.product_data[i][0]+'" onclick="deactivate_product('+data.product_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Deactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="productdeact-'+data.product_data[i][0]+'" onclick="activate_product('+data.product_data[i][0]+')"> Activate </a>';
				}
				
				
				if(data.product_data[i][8]=='Added'){
					var add_btn = '<a class="flex items-center mr-3" href="javascript:;" id="add_coll-'+data.product_data[i][0]+'" onclick="removeFromCollection('+data.product_data[i][0]+')"> &nbsp;&nbsp;Remove</a></div>';
				}
				else{
					var add_btn = '<a class="flex items-center mr-3" href="javascript:;" id="add_coll-'+data.product_data[i][0]+'" onclick="addToCollection('+data.product_data[i][0]+')"> &nbsp;&nbsp;Add to Collection</a></div>';
				}
				
				
				
				$("#product_table").append('<tr class="added">'+
										'<td id="product-'+data.product_data[i][0]+'">'
											+data.product_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_brand-'+data.product_data[i][0]+'">'+data.product_data[i][5]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_cat-'+data.product_data[i][0]+'">'+data.product_data[i][6]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_subcat-'+data.product_data[i][0]+'">'+data.product_data[i][7]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="productstatus-'+data.product_data[i][0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+add_btn+
                                    '</td>'+
								'</tr>');
			}
			
		}
	});
}

function get_coll_product_paging(query,status,limit,brand,category,subcat){
	var coll_id = $("#coll_id").val();
	var req_data = JSON.stringify({module:"catalog",req:"product_paging",query:query,status:status,brand:brand,category:category,subcat:subcat});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#product_paging").html('');
			var offset = 0;
			for(i=1;i<=data.page_count;i++){
				if(i==1){
					$("#product_paging").append('<li> <a class="pagination__link pagination__link--active" onclick="coll_product_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+',\''+brand+'\',\''+category+'\',\''+subcat+'\',\''+coll_id+'\');activate_pagenum(this);" href="#">'+i+'</a> </li>');
				}
				else{
				$("#product_paging").append('<li> <a class="pagination__link" href="#" onclick="coll_product_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+',\''+brand+'\',\''+category+'\',\''+subcat+'\',\''+coll_id+'\');activate_pagenum(this);">'+i+'</a> </li>');
				}
				offset = parseInt(offset)+limit;
			}
		}
	});
}

function addToCollection(product_id){
	coll_id = $("#coll_id").val();
	var req_data = JSON.stringify({module:"catalog",req:"addToCollection",coll_id:coll_id,product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#coll-add-success").modal("show");
			$("#add_coll-"+product_id).html("Remove");
			$("#add_coll-"+product_id).attr("onclick","removeFromCollection("+product_id+")");
			
			//Add up to below row
			
			$("#collproduct_loading").show();
	req_data = JSON.stringify({module:"catalog",req:"get_product_by_id",product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			setTimeout(function() {
				if(data.product_data[7]==1){
					var status = "Active";
				}
				else{
					var status = "Deactive";
				}
			$("#collproduct_loading").hide();
			$("#collproduct_table").prepend('<tr class="added rem-'+data.product_data[0]+'">'+
										'<td id="product-'+data.product_data[0]+'">'
											+data.product_data[1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_brand-'+data.product_data[0]+'">'+data.product_data[4]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_cat-'+data.product_data[0]+'">'+data.product_data[5]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="product_subcat-'+data.product_data[0]+'">'+data.product_data[6]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="productstatus-'+data.product_data[0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" id="add_coll-'+data.product_data[0]+'" onclick="removeFromCollection('+data.product_data[0]+')"> &nbsp;&nbsp;Remove</a></div>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
							//timeout here	
			 //your code to be executed after 1 second
			}, 1000);				
		}
		
	});
			//Add up to below row
			
			
			
		}
		else{
			$("#coll_add_error_msg").html(data.msg);
			$("#coll_add_error").modal("show");
		}
	})
}

function removeFromCollection(product_id){
	coll_id = $("#coll_id").val();
	var req_data = JSON.stringify({module:"catalog",req:"removeFromCollection",coll_id:coll_id,product_id:product_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#coll-remove-success").modal("show");
			$("#add_coll-"+product_id).html("Add to Collection");
			$("#add_coll-"+product_id).attr("onclick","addToCollection("+product_id+")");
			$("#rem-"+product_id).remove();
		}
		else{
			$("#coll_add_error_msg").html(data.msg);
			$("#coll_add_error").modal("show");
		}
	});
	
}

function add_country(){
	var country_name = $("#country_name").val();
	var currency = $("#currency").val();
	var currency_symbol = $("#currency_symbol").val();
	if(country_name==""){
		$("#upload_error_msg").html('Country name can not be blank');
		$("#upload_error").modal("show");
	}
	else if(currency==""){
		$("#upload_error_msg").html('Currency can not be empty');
		$("#upload_error").modal("show");
	}
	else if(currency_symbol==""){
		$("#upload_error_msg").html('Currency symbol can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		var req_data = JSON.stringify({module:"territory",req:"add_country",country_name:country_name,currency:currency,currency_symbol:currency_symbol});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#country_name").val('');
				$("#currency").val('');
				$("#currency_symbol").val('');
				$("#add_country").modal('hide');
				$("#upload_success_load").attr("onclick","load_country("+data.country_id+");");
				$("#upload-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function load_country(country_id){
	$("#country_loading").show();
	req_data = JSON.stringify({module:"territory",req:"get_country_by_id",country_id:country_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			setTimeout(function() {
				if(data.country_data[4]==1){
					var status = "Active";
				}
				else{
					var status = "Inactive";
				}
			$("#country_loading").hide();
			$("#country_table").prepend('<tr>'+
										'<td id="country-'+data.country_data[0]+'">'
											+data.country_data[1]+
                                    '</td>'+
									'<td>'+
                                        '<div class="flex items-center justify-center text-theme-9" id="countrycurr-'+data.country_data[0]+'">'+data.country_data[2]+' </div>'+
                                    '</td>'+
									'<td>'+
                                        '<div class="flex items-center justify-center text-theme-9" id="countrycurrsym-'+data.country_data[0]+'">'+data.country_data[3]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="countrystatus-'+data.country_data[0]+'">'+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_country_editor('+data.country_data[0]+')">'+ 'Edit </a>'+
                                           '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="countrydeact-'+data.country_data[0]+'" onclick="deactivate_country('+data.country_data[0]+');"> Deactivate </a>'+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
							//timeout here	
			 //your code to be executed after 1 second
			}, 1000);				
		}
		
	});
}

function country_page_data(query,status,offset,limit){
	var req_data = JSON.stringify({module:"territory",req:"get_all_countries",query:query,status:status,offset:offset,limit:limit});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$(".added").remove();
			for(var i=0;i<=data.country_data.length-1;i++){
				if(data.country_data[i][4]==1){
					var status = "Active";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="countrydeact-'+data.country_data[i][0]+'" onclick="deactivate_country('+data.country_data[i][0]+')"> Deactivate </a>';
				}
				else{
					var status = "Inactive";
					var active_btn = '<a class="flex items-center text-theme-6" href="javascript:;" data-toggle="modal" data-target="#delete-confirmation-modal" id="countrydeact-'+data.country_data[i][0]+'" onclick="activate_country('+data.country_data[i][0]+')"> Activate </a>';
				}
				$("#country_table").append('<tr class="added">'+
										'<td id="country-'+data.country_data[i][0]+'">'+data.country_data[i][1]+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="countrycurr-'+data.country_data[i][0]+'"> '+data.country_data[i][2]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="countrycurrsym-'+data.country_data[i][0]+'"> '+data.country_data[i][3]+' </div>'+
                                    '</td>'+
									'<td class="w-40">'+
                                        '<div class="flex items-center justify-center text-theme-9" id="countrystatus-'+data.country_data[i][0]+'"> '+status+' </div>'+
                                    '</td>'+
									'<td class="table-report__action w-56">'+
                                        '<div class="flex justify-center items-center">'+
                                            '<a class="flex items-center mr-3" href="javascript:;" onclick="load_country_editor('+data.country_data[i][0]+')">'+ 'Edit </a>'+
                                           active_btn+
                                        '</div>'+
                                    '</td>'+
								'</tr>');
			}
			
		}
	});
}

function load_country_editor(country_id){
	req_data = JSON.stringify({module:"territory",req:"get_country_by_id",country_id:country_id});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#edit_country_name:text").val(data.country_data[1]);
			$("#edit_currency:text").val(data.country_data[2]);
			$("#edit_currency_symbol:text").val(data.country_data[3]);
			
			$("#edit_country_btn").attr("onclick","edit_country("+country_id+");");
			$("#edit_country").modal('show');
		}
	});
}

function edit_country(country_id){
	var country_name = $("#edit_country_name").val();
	var currency = $("#edit_currency").val();
	var currency_symbol = $("#edit_currency_symbol").val();
	if(country_name==""){
		$("#upload_error_msg").html('Country name can not be empty');
		$("#upload_error").modal("show");
	}
	else if(currency==""){
		$("#upload_error_msg").html('Currency can not be empty');
		$("#upload_error").modal("show");
	}
	else if(currency_symbol==""){
		$("#upload_error_msg").html('Currency Symbol can not be empty');
		$("#upload_error").modal("show");
	}
	else{
		var req_data = JSON.stringify({module:"territory",req:"edit_country",country_name:country_name,currency:currency,currency_symbol:currency_symbol,country_id:country_id});
		$.post(api_url,{req_data},function(data){
			data = JSON.parse(data);
			if(data.status=="success"){
				$("#edit_country_name").val('');
				$("#edit_currency").val('');
				$("#edit_currency_symbol").val('');
				$("#edit_country").modal('hide');
				req_data = JSON.stringify({module:"territory","req":"get_country_by_id",country_id:country_id});
				$.post(api_url,{req_data},function(data2){
					data2 = JSON.parse(data2);
					if(data2.status=="success"){
						$("#country-"+country_id).html(data2.country_data[1]);
						$("#countrycurr-"+country_id).html(data2.country_data[2]);
						$("#countrycurrsym-"+country_id).html(data2.country_data[3]);
						$("#countrystatus-"+country_id).html(get_active_text(data2.country_data[4]));
					}
				});
				$("#update-success").modal('show');
			}
			else{
				$("#upload_error_msg").html(data.msg);
				$("#upload_error").modal("show");
			}
		})
	}
}

function deactivate_country(country_id){
	req_data = JSON.stringify({module:"territory",req:"deactivate_country",country_id:country_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#countrydeact-"+country_id).html('Activate');
			$("#countrydeact-"+country_id).attr("onclick",'activate_country('+country_id+');');
			$("#countrystatus-"+country_id).html("Inactive");
		}
	});
}

function activate_country(country_id){
	req_data = JSON.stringify({module:"territory",req:"activate_country",country_id:country_id});
	$.post(api_url,{req_data},function(data){
		data  = JSON.parse(data);
		if(data.status=="success"){
			$("#countrydeact-"+country_id).html('Dectivate');
			$("#countrydeact-"+country_id).attr("onclick",'deactivate_country('+country_id+');');
			$("#countrystatus-"+country_id).html("Active");
		}
	});
}

function get_country_paging(query,status,limit){
	var req_data = JSON.stringify({module:"territory",req:"country_paging",query:query,status:status});
	$.post(api_url,{req_data},function(data){
		data = JSON.parse(data);
		if(data.status=="success"){
			$("#country_paging").html('');
			var offset = 0;
			for(i=1;i<=data.page_count;i++){
				if(i==1){
					$("#country_paging").append('<li> <a class="pagination__link pagination__link--active" onclick="country_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);" href="#">'+i+'</a> </li>');
				}
				else{
				$("#country_paging").append('<li> <a class="pagination__link" href="#" onclick="country_page_data(\''+query+'\',\''+status+'\','+offset+','+limit+');activate_pagenum(this);">'+i+'</a> </li>');
				}
				offset = parseInt(offset)+limit;
			}
		}
	});
}

function search_country(){
	var query = $("#country_query").val();
	var status = $("#country_status").val();
	var offset = 0;
	var limit = 2;
	country_page_data(query,status,offset,limit);
	get_country_paging(query,status,limit);
}
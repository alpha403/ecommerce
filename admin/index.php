<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <title>Rtaze | Admin Area </title>
		<?php include 'includes/head.php'; ?>
    </head>
    <!-- END: Head -->
    <body class="login" style="display:none;">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                        <img class="w-6" src="images/logo-white.png" style="width:150px;">
                    </a>
                    <div class="my-auto">
                        <img class="-intro-x w-1/2 -mt-16" src="images/illustration.svg">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Admin Area 
                            
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white dark:text-gray-500">Manage your e-commerce store in one place</div>
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Sign In
                        </h2>
                        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A Manage your e-commerce store in one place</div>
                        <div class="intro-x mt-8">
                            <input type="text" class="intro-x login__input input input--lg border border-gray-300 block" placeholder="Email" id="email">
                            <input type="password" class="intro-x login__input input input--lg border border-gray-300 block mt-4" placeholder="Password" id="password">
                        </div>
                        <div class="intro-x flex text-gray-700 dark:text-gray-600 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input type="checkbox" class="input border mr-2" id="remember-me">
                                <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                            </div>
                            <a href="">Forgot Password?</a> 
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3" onclick="admin_login();">Login</button>
                        </div>
						<div class="normal-case text-theme-6 mt-5" id="error-space"></div>
                        <div class="intro-x mt-10 xl:mt-24 text-gray-700 dark:text-gray-600 text-center xl:text-left">
                            Developed & Maintained By 
                            <br>
                            <a class="text-theme-1 dark:text-theme-10" href="https://www.magnetreach.com"><a class="text-theme-1 dark:text-theme-10" href="">Magnet Reach</a> 
                        </div>
                    </div>
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
        <!-- BEGIN: Dark Mode Switcher-->
        <div class="dark-mode-switcher shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10">
            <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
            <input class="input input--switch border" type="checkbox" value="1">
        </div>
        <!-- END: Dark Mode Switcher-->
        <?php
			include 'includes/js.php';
		?>
		<script>
		function check_session(){
			var module = 'authenticate';
			var req = 'check_session';
			var req_data = JSON.stringify({module:module,req:req});
			$.post(api_url,{req_data},function(data){
				data = JSON.parse(data);
				if(data.status=="fail"){
					$("body").show();
				}
				else{
					window.location.href = 'dashboard';
				}
			});
		}
		check_session();
		</script>
    </body>
</html>
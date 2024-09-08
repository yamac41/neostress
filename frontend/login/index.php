<?php
    require '../../backend/configuration/database.php';
    require '../../backend/configuration/funcsinit.php';

    

    if ($user -> UserLoggedIn()){
        header('Location: /dash/home');
        exit;
    }

?>

<!DOCTYPE html>
<html>
<head>
  	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">

  	<title>stresse.guru &mdash; Sign In</title>

  	<link rel="icon" href="/dash/assets/img/logo.png" type="image/x-icon">

	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
	<script type="text/javascript" src="/assets/js/settings.js"></script>
   	<link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.4/dist/flowbite.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/styles.css?v=<?php echo time(); ?>">
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon" />
</head>
<body>
   	
    <div>
        <div class="flex justify-center h-screen">
            <div class="hidden bg-cover lg:block lg:w-1/2">
                <div class="flex justify-center items-center align-center h-full bg-gray-900 bg-opacity-40" style="border-radius: 30px;">
                    <div class="flex items-center" style="flex-direction: column;">
                        <img src="/dash/assets/img/logo.png" style="height: 180px;" class="mb-2" />
                        <h2 class="text-4xl font-bold text-white">stresse.guru</h2>
                        <p class="max-w-xl mt-3 text-gray-100 text-center">Use stresse.guru to test the protection of your website, server or network against real DDoS attacks. Stress them all with our instant stresser.</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
                <div class="flex-1 rounded-lg p-6" style="background-color: rgb(17 24 39 / 0.4);">
                    <div class="text-center">
                        <h2 class="text-4xl font-bold text-center text-gray-700 dark:text-white">Sign In</h2>
    
                        <p class="mt-3 text-gray-500 dark:text-gray-300">Sign in to access the customer area.</p>
                        <div id="alertBox" class="mt-3"></div>    
                    </div>
                    <div class="mt-8">
                        <div>
                            <label for="username" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Username</label>
                            <input type="text" name="username" id="username" placeholder="Your username" required class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40 transition-colors duration-500" />
                        </div>

                        <div class="mt-6">
                            <div class="flex justify-between mb-2">
                                <label for="password" class="text-sm text-gray-600 dark:text-gray-200">Password</label>
                                <a class="text-sm text-gray-400 focus:text-blue-500 hover:text-blue-500 hover:underline" data-modal-toggle="resetpw-modal">Forgot password?</a>
                            </div>

                            <input type="password" name="password" id="password" placeholder="Your password" required class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40 transition-colors duration-500" />
                        </div>
                        <input type="hidden" name="captcha" id="captcha"/>
                        <div id="captcha_container" class="flex justify-center mt-3"></div>

                        <div class="flex items-center mt-6">
                            <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="default-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
                        </div>
                        <input type="text" name="csrf" id="csrf" hidden value="<?php echo $aWAF->getCSRF(); ?>">
                        

                        
                        <div class="mt-6">
                            <button
                                id="signinButton" onclick="SignIn()" class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-[#8B78EA] rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                Sign In
                            </button>
                        </div>

                        

                        <p class="mt-6 text-sm text-center text-gray-400">Don&#x27;t have an account yet? <a href="/register" class="text-blue-500 focus:outline-none focus:underline hover:underline">Sign up</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="resetpw-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-toggle="resetpw-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                </button>
                <div class="py-6 px-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Reset your password</h3>
                    
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your email</label>
                            <input type="email" name="email" id="email123" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Your email address" required>
                        </div>
                        <div>
                            <label for="secret" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your secret key</label>
                            <input type="password" name="secret" id="secret" placeholder="••••••••••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">New password</label>
                            <input type="password" name="password" id="password123" placeholder="New password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <div>
                            <label for="rpassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Repeat new password</label>
                            <input type="password" name="rpassword" id="rpassword123" placeholder="Repeat new password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                       
                        <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Reset password</button>
                        
                   
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/flowbite@1.4.4/dist/flowbite.js"></script>
    <script src="/assets/js/main.js?v=<?php echo time(); ?>"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit"></script>
    <script>
        // const turnstileKeys = {
        //     'king.cfxsecurity.ru': "0x4AAAAAAAKOu15WoFYnR3Jh",
        // }

        // turnstile.ready(function () {
        //     turnstile.render('#captcha_container', {
        //         retry: 'auto',
        //         sitekey: turnstileKeys[window.location.host],
        //         callback: function(token) {
        //             $("#signinButton").attr("disabled", false)
        //             $("#signinButton").html("Sign in")
        //             $("#captcha").val(token)
        //         },
        //     });
        // })
    </script>
</body>
</html>
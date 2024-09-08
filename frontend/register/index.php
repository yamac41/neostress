<?php
    require '../../backend/configuration/database.php';
    require '../../backend/configuration/funcsinit.php';

    if ($user -> UserLoggedIn()){
        header('Location: /dash/home');
        exit;
    }
?>

<!DOCTYPE html>
<html class="dark">
<head>
  	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">

  	<title>stresse.guru &mdash; Sign Up</title>

  	<link rel="icon" href="/dash/assets/img/logo.png" type="image/x-icon">
    
	<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/settings.js"></script>
    <script src='https://js.hcaptcha.com/1/api.js' async defer></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.4.4/dist/flowbite.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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
                        <h2 class="text-4xl font-bold text-center text-gray-700 dark:text-white">Sign Up</h2>
                        
                        <p class="mt-3 text-gray-500 dark:text-gray-300">Sign up to access the customer area.</p>
                        <div id="alertBox" class="mt-3"></div>    
                    </div>

                    <form class="mt-3" method="POST" id="signup-form">
                        
                            <div>
                                <label for="username" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Username</label>
                                <input type="text" name="username" id="username" placeholder="Your username"  class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            </div>
                            <div class="mt-6">
                                <label for="email" class="block mb-2 text-sm text-gray-600 dark:text-gray-200">Email Address</label>
                                <input type="email" name="email" id="email" placeholder="Your Email Address"  class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            </div>    
                            <div class="mt-6">
                                <div class="flex justify-between mb-2">
                                    <label for="password" class="text-sm text-gray-600 dark:text-gray-200">Password</label>
                                    
                                </div>

                                <input type="password" name="password" id="password" placeholder="Your Password"  class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            </div>
                            <div class="mt-6">
                                <div class="flex justify-between mb-2">
                                    <label for="rpassword" class="text-sm text-gray-600 dark:text-gray-200">Repeat Password</label>
                                    
                                </div>

                                <input type="password" name="rpassword" id="rpassword" placeholder="Repeat Your Password"  class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40" />
                            </div>
                            <input type="text" name="csrf" id="csrf" hidden value="<?php echo $aWAF->getCSRF(); ?>">
                            <input type="hidden" name="captcha" id="captcha"/>
                            <div id="captcha_container" class="flex justify-center mt-3"></div>
                            <div class="flex items-center mt-6">
                                <input id="checkbox" type="checkbox" value="yes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I agree with the <a href="/tos/" class="text-blue-600 dark:text-blue-500 hover:underline">terms and conditions</a>.</label>
                            </div>
                            <div class="mt-6">
                                <input type="submit" class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-[#8B78EA] rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                    Sign Up
                                </input>
                            </div>

                        

                        <p class="mt-6 text-sm text-center text-gray-400">Already have created account? <a href="/login" class="text-blue-500 focus:outline-none focus:underline hover:underline">Sign In</a>.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/flowbite@1.4.4/dist/flowbite.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="/assets/js/main.js?v=<?php echo time(); ?>"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit"></script>
    <script>
        const turnstileKeys = {
            'king.cfxsecurity.ru': "0x4AAAAAAAKOu15WoFYnR3Jh",
        }

        // turnstile.ready(function () {
        //     turnstile.render('#captcha_container', {
        //         retry: 'auto',
        //         sitekey: turnstileKeys['king.cfxsecurity.ru'],
        //         callback: function(token) {
        //             $("#signupButton").attr("disabled", false)
        //             $("#signupButton").html("Sign up")
        //             $("#captcha").val(token)
        //         },
        //     });
        // })
    </script>
</body>
</html>
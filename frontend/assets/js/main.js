function SignUp() {
  console.log("here");
  var _0x6053ef = $("#username").val();
  var _0x3eb602 = $("#email").val();
  var _0x54bc3f = $("#password").val();
  var _0x1b3889 = $("#rpassword").val();
  var _0x63f440 = document.getElementById("checkbox");
  var _0x514550 = $("#csrf").val();
  var _0x135e0d = $("#captcha").val();
  if (_0x6053ef == "" || _0x3eb602 == "" || _0x54bc3f == "" || _0x1b3889 == "") {
    $("#alertBox").html("<div id=\"alert-2\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\"> Please fill all required fields to create account! </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
    return;
  } else if (_0x6053ef.length < 6 || _0x6053ef.length > 20) {
    $("#alertBox").html("<div id=\"alert-2\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\"> Username length must be greater than 6 characters!  </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
    return;
  } else if (_0x54bc3f.length < 5) {
    $("#alertBox").html("<div id=\"alert-2\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\">Password length must be atleast 6 characters! </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
    return;
  // } else if (_0x135e0d == "") {
  //   $("#alertBox").html("<div id=\"alert-2\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\">Please solve captcha challenge </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
  //   return;
  } else if (_0x54bc3f != _0x1b3889) {
    $("#alertBox").html("<div id=\"alert-3\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\"> The passwords you entered do not match! </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
    return;
  } else if (!_0x63f440.checked) {
    $("#alertBox").html("<div id=\"alert-4\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\"> Please check terms and conditions! </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
    return;
  } else {
    $.post("/rest/auth/register/",
      {
        username: _0x6053ef,
        email: _0x3eb602,
        password: _0x54bc3f,
        rpassword: _0x1b3889,
        csrf: _0x514550,
        captcha: _0x135e0d
      },
      function (_0x912e68, status) {
        const _0x2637f3 = JSON.parse(_0x912e68);
        if (_0x2637f3.status == "error") {
          $("#alertBox").html("<div id=\"alert-4\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\">" + _0x2637f3.message + "</div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
        } else {
          $("#alertBox").html("<div id=\"alert-3\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-green-100 rounded-lg dark:bg-green-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-green-700 dark:text-green-800\">" + _0x2637f3.message + "</div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300\" data-dismiss-target=\"#alert-3\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button></div>");
          setTimeout(function () {
            window.location.replace("/dash/home");
          }, 3000);
        }
      }
    );
  }
}
function SignIn() {
  var _0x3927a1 = $("#username").val();
  var _0x242761 = $("#password").val();
  var _0x43881f = $("#csrf").val();
  var _0x456e10 = document.getElementById("checkbox");
  var _0x5bc24f = $("#captcha").val();
  if (_0x3927a1 == "" || _0x242761 == "") {
    $("#alertBox").html("<div id=\"alert-2\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\"> Please fill all required fields to create account! </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
    return;
  // } else if (_0x5bc24f == "") {
  //   $("#alertBox").html("<div id=\"alert-2\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\">Please solve captcha challenge </div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
  //   return;
  } else {
    $.ajax({
      url: "/rest/auth/login/",
      type: "POST",
      data: {
        username: _0x3927a1,
        password: _0x242761,
        csrf: _0x43881f,
        captcha: _0x5bc24f
      },
      success: function (_0x181cc5) {
        const _0x4066f9 = JSON.parse(_0x181cc5);
        if (_0x4066f9.status == "error") {
          $("#alertBox").html("<div id=\"alert-4\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-red-700 dark:text-red-800\">" + _0x4066f9.message + "</div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300\" data-dismiss-target=\"#alert-box\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button> </div>");
        } else {
          $("#alertBox").html("<div id=\"alert-3\" x-data=\"{ show: true }\" x-show=\"show\" class=\"flex p-4 mb-4 bg-green-100 rounded-lg dark:bg-green-200\" role=\"alert\"> <svg class=\"flex-shrink-0 w-5 h-5 text-green-700 dark:text-green-800\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z\" clip-rule=\"evenodd\"></path></svg> <div class=\"ml-3 text-sm font-medium text-green-700 dark:text-green-800\">" + _0x4066f9.message + "</div> <button type=\"button\" class=\"ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-green-200 dark:text-green-600 dark:hover:bg-green-300\" data-dismiss-target=\"#alert-3\" aria-label=\"Close\" @click=\"show = false\"> <span class=\"sr-only\">Close</span> <svg class=\"w-5 h-5\" fill=\"currentColor\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z\" clip-rule=\"evenodd\"></path></svg> </button></div>");
          setTimeout(function () {
            window.location.replace("/dash/home");
          }, 3000);
        }
      }
    });
  }
}
if (localStorage.getItem("color-theme") === "dark" || !("color-theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches) {
  document.documentElement.classList.add("dark");
} else {
  document.documentElement.classList.remove("dark");
}

$('#signup-form').submit(function(event) {
  event.preventDefault();
  SignUp();
});
// var themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
// var themeToggleLightIcon = document.getElementById("theme-toggle-light-icon");
// if (localStorage.getItem("color-theme") === "dark" || !("color-theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches) {
//   themeToggleLightIcon.classList.remove("hidden");
// } else {
//   themeToggleDarkIcon.classList.remove("hidden");
// }
// var themeToggleBtn = document.getElementById("theme-toggle");
// themeToggleBtn.addEventListener("click", function () {
//   themeToggleDarkIcon.classList.toggle("hidden");
//   themeToggleLightIcon.classList.toggle("hidden");
//   if (localStorage.getItem("color-theme")) {
//     if (localStorage.getItem("color-theme") === "light") {
//       document.documentElement.classList.add("dark");
//       localStorage.setItem("color-theme", "dark");
//     } else {
//       document.documentElement.classList.remove("dark");
//       localStorage.setItem("color-theme", "light");
//     }
//   } else if (document.documentElement.classList.contains("dark")) {
//     document.documentElement.classList.remove("dark");
//     localStorage.setItem("color-theme", "light");
//   } else {
//     document.documentElement.classList.add("dark");
//     localStorage.setItem("color-theme", "dark");
//   }
// });
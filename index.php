<?php
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$get_library = file_get_contents('libraries.json');
$libraries = json_decode($get_library, true);
$xs = substr($request_uri[0], 1);

    switch ($request_uri[0]) {
    // Home page
    case '/':
        require 'default.php';
        break;
    case '':
        require 'default.php';
        break;
    case '/'.$xs:
        // echo 'trestog';
        // echo $xs;
        // echo $libnameapi;
    $libnameapi = isset($libraries[$xs]['name']) ? $libraries[$xs]['name'] : 'boo';
    $libemalapi = isset($libraries[$xs]['email']) ? $libraries[$xs]['email'] : 'boo';
    $libtelapi = isset($libraries[$xs]['telephone']) ? $libraries[$xs]['telephone'] : 'boo';
        require 'form.php';
        break;
    // About page
    case '/about':
        require 'about.php';
        break;
    case '/examples':
        require 'examples.php';
        break;
    // Everything else
    default:
        require 'default.php';
        // echo '$library';
        // echo $xs;
        // echo $request_uri[0];
        
        break;
}


?>

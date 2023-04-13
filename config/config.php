<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST,GET,DELETE,OPTIONS,PUT');
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");  

    define("CONNECT",new PDO("mysql:dbname=eglise;port=3308","root",""));
?>
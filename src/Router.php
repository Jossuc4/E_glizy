<?php
namespace App;

class Router{

    /**
     * render: displays the interface
     *
     * @param string $route
     * @param string $url
     * @return void
     */
    public function render($route,$url){
        if($_GET['route']==$route){
            require "Views/".$url.".php";
        }
    }

    /**
     * fetch: fetch the needed data from the database
     *
     * @param string $route
     * @param string $url
     * @return void
     */
    public function fetch($route,$url){
        if($_GET['route']==$route){
            require "data/".$url.".php";
        }
    }

    /**
     * service: requires the needed functions or library
     *
     * @param string $route
     * @param string $url
     * @return void
     */
    public function service($route,$url){
        if($_GET['route']==$route){
            require "src/".$url.".php";
        }
    }
}
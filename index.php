<?php 
use App\Router;
require "functions/functions.php";
require "src/Router.php";

$router=new Router();

//Interfaces
$router->render("eglise","Accueil");
$router->render("entree","entree");
$router->render("sortie","sortie");
$router->render("details","Details");
$router->render("rapport","pdf");
$router->render("histo","historique");


//Data
$router->fetch("listeEglise","Eglises");
$router->fetch("listeEntree","Entrees");
$router->fetch("info","Info");
$router->fetch("listeSortie","Sortie");
$router->fetch("stat","history");


//Services
$router->service("suppr","suppr");
$router->service("modif","modif");
$router->service("find","find");
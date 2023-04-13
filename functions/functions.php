<?php
require dirname(__DIR__)."/config/config.php";


function createID($lastIndex){
    $lastId=$lastIndex + 1;
    $id="E".$lastId;
    return $id;
}

function listAll($table,$condition=null){
    if(is_null($condition)){
        $query=CONNECT->query("SELECT * FROM ".$table);
        $liste=$query->fetchAll(PDO::FETCH_ASSOC);
    }else{
        $query=CONNECT->prepare("SELECT * FROM ".$table." WHERE idEglise=?");
        $exec=$query->execute([$condition]);
        $liste=$query->fetchAll(PDO::FETCH_ASSOC);
    }
    
   
    return $liste;
}

function verifyName($name){
    foreach (listAll("eglise") as $eglise){
        if(strtolower($eglise['design'])==strtolower($name)){
            return false;
        }
    }
    return true;
}

function Find($table,$option){
    return count(listAll($table,$option));
}

function formatDate($date){
    $date=array_reverse(explode("-",$date));
    return implode("/",$date);
}
function Ariary($value){
    return number_format($value,0,""," ")." Ar";
}
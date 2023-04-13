<?php
    require dirname(__DIR__)."/functions/functions.php";
    
    //Changer le nom d'une Eglise

    if(isset($_GET['idEglise']) ){

        $id=$_GET['idEglise'];
        $name=$_GET['new_name'];

        $query=CONNECT->prepare("UPDATE eglise SET design=? WHERE idEglise=?");
        $exec=$query->execute([$name,$id]);

        header("location:?route=details&id=".$id);

    }
       
    if(isset($_POST['idEntree'])){

        $idSortie=$_POST['idEntree'];

        $q=CONNECT->query("SELECT idEglise FROM entree WHERE idEntree='$idEntree'");
        $idEglise=$q->fetch(PDO::FETCH_ASSOC)['idEglise'];

        var_dump($_POST);
    }

?>

    
<?php

    if(isset($_GET['idEntree']) && !empty($_GET['idEntree'])){
        $id=$_GET['idEntree'];
        //var_dump($_GET);
        $idEglise=CONNECT->query("SELECT idEglise FROM eglise JOIN entree USING(idEglise) WHERE idEntree=".$id)->fetch(PDO::FETCH_ASSOC)['idEglise'];

        $q=CONNECT->query("SELECT montantEntree FROM  entree WHERE idEntree=".$id);
        $montant=$q->fetch(PDO::FETCH_ASSOC)["montantEntree"];


        $suppr=CONNECT->query("DELETE FROM entree WHERE idEntree=".$id);
        
        $reduction_solde=CONNECT->prepare("UPDATE eglise SET solde= solde - ? WHERE idEglise=?");
        try{$exec=$reduction_solde->execute([$montant,$idEglise]);}catch(PDOException $e){die($e->getMessage());}

        header("location:?route=entree&id=".$_GET['idEglise']);
        
    }
    if(isset($_GET['idSortie']) && !empty($_GET['idSortie'])){
        $id=$_GET['idSortie'];
        //var_dump($_GET);
        $idEglise=CONNECT->query("SELECT idEglise FROM eglise JOIN sortie USING(idEglise) WHERE idSortie=".$id)->fetch(PDO::FETCH_ASSOC)['idEglise'];

        $q=CONNECT->query("SELECT montantSortie FROM  sortie WHERE idSortie=".$id);
        $montant=$q->fetch(PDO::FETCH_ASSOC)["montantSortie"];


        $suppr=CONNECT->query("DELETE FROM sortie WHERE idSortie=".$id);
        
        $reduction_solde=CONNECT->prepare("UPDATE eglise SET solde= solde + ? WHERE idEglise=?");
        try{$exec=$reduction_solde->execute([$montant,$idEglise]);}catch(PDOException $e){die($e->getMessage());}

        header("location:?route=sortie&id=".$_GET['idEglise']);
        
    }

?>

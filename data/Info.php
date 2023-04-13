<?php

    if(isset($_GET['design'])){

        
        $design=$_GET['design'];
        $design=str_replace("_"," ",$design);


        $id=CONNECT->prepare("SELECT idEglise FROM eglise WHERE design=?");
        $exec=$id->execute([$design]);
        $idEglise=$id->fetch(PDO::FETCH_ASSOC)['idEglise'];

        
        if(count(listAll("entree",$idEglise))===0 && count(listAll("sortie",$idEglise))===0){

            echo "<pre>";
            echo json_encode([]);
            echo "</pre>";

        }else{

            //============>Entrées

            $entree=CONNECT->prepare("SELECT * FROM entree LEFT JOIN eglise USING(idEglise) WHERE design=? ORDER BY dateEntree");
            $exec=$entree->execute([$design]);
            $entree=$entree->fetchAll(PDO::FETCH_ASSOC);


            //Date de commencement entrée

            $q=CONNECT->prepare("SELECT MIN(dateEntree) dateEntree FROM entree WHERE idEglise= ?");
            $exec=$q->execute([$idEglise]);
            $dateInitial=$q->fetch(PDO::FETCH_ASSOC)['dateEntree'];
            $dateInitial=formatDate($dateInitial);


            //Dernière date d'entrée

            $q=CONNECT->prepare("SELECT MAX(dateEntree) dateEntree FROM entree  WHERE idEglise= ?");
            $exec=$q->execute([$idEglise]);
            $dateFinal=$q->fetch(PDO::FETCH_ASSOC)['dateEntree'];
            $dateFinal=formatDate($dateFinal);

            //Total entrant

            $total=CONNECT->prepare("SELECT SUM(montantEntree) totalEntree FROM entree GROUP BY idEglise HAVING idEglise=?");
            $exec=$total->execute([$idEglise]);
            $total_entrant=$total->fetch(PDO::FETCH_ASSOC)['totalEntree'];


            //============>Sortie
            $sortie=CONNECT->prepare("SELECT * FROM sortie LEFT JOIN eglise USING(idEglise) WHERE design=? ORDER BY dateSortie");
            $exec=$sortie->execute([$design]);
            $sortie=$sortie->fetchAll(PDO::FETCH_ASSOC);

            //Date de commencement Sortie

            $q=CONNECT->prepare("SELECT MIN(dateSortie) dateSortie FROM sortie WHERE idEglise= ?");
            $exec=$q->execute([$idEglise]);
            $dateSInitial=$q->fetch(PDO::FETCH_ASSOC)['dateSortie'];
            $dateSInitial= is_null($dateSInitial) ? null : formatDate($dateSInitial);
            

            //Dernière date de sortie

            $q=CONNECT->prepare("SELECT MAX(dateSortie) dateSortie FROM sortie  WHERE idEglise= ?");
            $exec=$q->execute([$idEglise]);
            $dateSFinal=$q->fetch(PDO::FETCH_ASSOC)['dateSortie'];
            $dateSFinal= is_null($dateSFinal) ? null : formatDate($dateSFinal);

            //Total sortant

            if(count(listAll("sortie"))==0){
                $total_sortant=0;
            }else{
                $total=CONNECT->prepare("SELECT SUM(montantSortie) totalSortie FROM sortie GROUP BY idEglise HAVING idEglise=?");
                $exec=$total->execute([$idEglise]);
                $total_sortant=$total->fetch(PDO::FETCH_ASSOC);
                $total_sortant= ($total_sortant==false) ? 0 : $total_sortant['totalSortie'];
            }

            if(count($entree)!==0){
                $data["entree"]=$entree;
            }
            if(count($sortie)!==0){
                $data["sortie"]=$sortie;
            }
            if(!is_null($dateInitial) && !is_null($dateFinal) && !is_null($total_entrant)){
                $data["detailEntree"]=[
                    "dateInitial"=>$dateInitial,
                    "dateFinal"=>$dateFinal,
                    "totalEntree"=>$total_entrant
                ];
            }
            if(!is_null($dateSInitial) && !is_null($dateSFinal) && $total_sortant!=0){
                $data["detailSortie"]=[
                    "dateSInitial"=>$dateSInitial,
                    "dateSFinal"=>$dateSFinal,
                    "totalSortie"=>$total_sortant
                ];
            }

            
        }
        echo "<pre>";
            echo json_encode($data);
        echo "</pre>";
    }
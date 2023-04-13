<?php

if(isset($_GET['id'])){

    $idEglise=$_GET['id'];

    // Requête SQL pour récupérer les données
    $sql_entree = "SELECT montantEntree, dateEntree FROM entree WHERE idEglise = :idEglise ORDER BY dateEntree";
    $sql_sortie = "SELECT montantSortie, dateSortie FROM sortie WHERE idEglise = :idEglise ORDER BY dateSortie";
    $stmt_entree = CONNECT->prepare($sql_entree);
    $stmt_sortie = CONNECT->prepare($sql_sortie);
    $stmt_entree->bindParam(':idEglise', $idEglise);
    $stmt_sortie->bindParam(':idEglise', $idEglise);

    // Exécution de la requête pour les entrées
    $stmt_entree->execute();
    $data_entree = $stmt_entree->fetchAll(PDO::FETCH_ASSOC);

    // Exécution de la requête pour les sorties
    $stmt_sortie->execute();
    $data_sortie = $stmt_sortie->fetchAll(PDO::FETCH_ASSOC);

    // Fermeture de la connexion à la base de données
    $dbh = null;

    // Préparation des données pour le graphique
    $data = array(
        'entree' => array(),
        'sortie' => array()
    );
    foreach ($data_entree as $d) {
        $data['entree'][] = array(
            'x' => $d['dateEntree'],
            'y' => $d['montantEntree']
        );
    }
    if(!empty($data_sortie)){
        foreach ($data_sortie as $d) {
            $data['sortie'][] = array(
                'x' => $d['dateSortie'],
                'y' => $d['montantSortie']
            );
        }
    }else{
        unset($data['sortie']);
    }
    

    // Encodage des données au format JSON
    $json_data = json_encode($data);

    // Envoi des données
    echo $json_data;
}
?>

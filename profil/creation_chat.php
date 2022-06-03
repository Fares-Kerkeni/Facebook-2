<?php
    
    include '../connexion_bdd.php';
    //On démarre une nouvelle session
    session_start();

    //récupération des varianles
    $ami_id = $_POST['ami_id'];
    $ami_pseudo = $_POST['ami_pseudo'];
    $user_id = $_POST['user_id'];
    $user_pseudo = $_SESSION['pseudo'];
    $name = $user_pseudo.$ami_pseudo;

    //on insert le chat dans la table chat
    date_default_timezone_set('Europe/Paris');
    $date = date('d-m-y h:i:s');
    $insert_chat = $pdo->prepare("INSERT INTO chat (date_creation_chat, nom_chat) VALUES ('".$date."', '".$name."')");
    $insert_chat->execute();
    //On selectionne le chat créé
    $select_chat = $pdo->prepare("SELECT id_chat FROM chat WHERE nom_chat='".$name."'");
    $select_chat->execute();
    $donnees_select_chat = $select_chat->fetchAll(PDO::FETCH_COLUMN);
    echo $user_pseudo;
    //On insert les variables correspondante à la session
    $session_user_chat = $pdo->prepare("INSERT INTO user_chat (id_user, id_chat) VALUES ('".$user_id."', '".$donnees_select_chat[0]."')");
    $session_user_chat->execute();
    //On insert les variables correspondante à l'ami
    $ami_user_chat = $pdo->prepare("INSERT INTO user_chat (id_user, id_chat) VALUES ('".$ami_id."', '".$donnees_select_chat[0]."')");
    $ami_user_chat->execute();



?>
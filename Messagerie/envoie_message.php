<?php
    include '../connexion_bdd.php';
    //On démarre une nouvelle session
    session_start();
    
    // echo 'Bonjour ' .$_SESSION['prenom']. ' , ' .$_SESSION['nom']. ' , ' .$_SESSION['id_user']. '';

    //Si l'input text est vide
    if (!isset($_POST['message'])){
        echo json_encode("error");
    } else if($_POST['message'] == '') {
        echo json_encode("error");
    }
    else{//Sinon on récupère dans des varibles nos données envoyer par le fetch
        $message = $_POST['message'];
        $id_user = $_POST['id_user'];
        $pseudo = $_POST['pseudo'];
        $id_chat = $_POST['id_chat'];
        date_default_timezone_set('Europe/Paris');
        $date = date('d-m-y h:i:s');
        // On insert le message dans notre db
        $insert_message = $pdo->prepare("INSERT INTO messages (id_chat, id_user, texte_message, date_message) VALUES ('".$id_chat."','".$id_user."','".$message."','".$date."')");
        $insert_message->execute();
        // qu'on va enssuite reselectionner
        $list_message = $pdo->prepare("SELECT texte_message, id_user FROM messages WHERE id_chat='".$id_chat."' ORDER BY date_message;");
        $list_message->execute();
        $donnees_list_message = $list_message->fetchAll(PDO::FETCH_ASSOC);
        // On va récupérer les id_user dans la table user_chat
        $user_in_chat = $pdo->prepare("SELECT id_user FROM user_chat WHERE id_chat = '".$id_chat."'");
        $user_in_chat->execute();
        $donnees_user_in_chat = $user_in_chat->fetchAll(PDO::FETCH_COLUMN);
        // Et aussi le pseudo dans user
        $pseudo_id = $pdo->prepare("SELECT pseudo, id_user FROM user WHERE id_user IN('".join("', '", $donnees_user_in_chat)."')");
        $pseudo_id->execute();
        $donnees_pseudo_id = $pseudo_id->fetchAll(PDO::FETCH_ASSOC);
        //On les envoie vers notre JS sous format json
        echo json_encode(["data"=>$donnees_list_message, "chat"=>$id_chat, "user"=>$id_user, "pseudo"=>$donnees_pseudo_id, "session_pseudo"=>$_SESSION['pseudo']]);
        
    
    }

?>
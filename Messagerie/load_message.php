<?php
    include '../connexion_bdd.php';
    //On démarre une nouvelle session
    session_start();
    //Récupération des id_chat de la session
    $user_in_chat = $pdo->prepare("SELECT id_chat FROM user_chat WHERE id_user = :id_user");
    $user_in_chat->execute([
        ":id_user" => $_SESSION['id_user']
    ]);
    $donnees_user_in_chat = $user_in_chat->fetchAll(PDO::FETCH_COLUMN);
    //On va selectionner tous les messages ayant le même id_chat
    $all_list_message = $pdo->prepare("SELECT texte_message, id_user, id_chat FROM messages WHERE id_chat IN('".join("', '", $donnees_user_in_chat)."') ORDER BY date_message;");
    $all_list_message->execute();
    //VARIANTE:
    // $all_list_message = $pdo->prepare("
    //     SELECT messages.texte_message, messages.id_user, messages.id_chat FROM messages 
    //     JOIN user_chat ON user_chat.id_chat = messages.id_chat
    //     WHERE user_chat.id_user = :id_user
    //     ORDER BY date_message;
    // ");
    // $all_list_message->execute([
    //     ":id_user" => $_SESSION['id_user']
    // ]);
    $donnees_all_list_message = $all_list_message->fetchAll(PDO::FETCH_ASSOC);
    //On selectionne les id_user présent dans le chat de la session
    $id_user_in_chat = $pdo->prepare("SELECT id_user FROM user_chat WHERE id_chat IN('".join("', '", $donnees_user_in_chat)."')");
    $id_user_in_chat->execute();
    $donnees_id_user_in_chat = $id_user_in_chat->fetchAll(PDO::FETCH_COLUMN);
    //On selectionne tous les pseudos en fonction des id_user selectionnés préalablement
    $pseudo_message = $pdo->prepare("SELECT pseudo, id_user FROM user WHERE id_user IN('".join("', '", $donnees_id_user_in_chat)."')");
    $pseudo_message->execute();
    $donnees_pseudo_message= $pseudo_message->fetchAll(PDO::FETCH_ASSOC);
    //On envoie sous forme json avec d'un coté la liste de messages et de l'autre la liste des pseudos présent dans le chat
    echo json_encode(["mess"=>$donnees_all_list_message, "pseudo"=>$donnees_pseudo_message, "session_pseudo"=>$_SESSION['pseudo']]);

?>
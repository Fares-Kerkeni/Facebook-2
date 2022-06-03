<?php
    include '../connexion_bdd.php';
    //On démarre une nouvelle session
    session_start();

    //récupération de la variable id_chat
    $id_chat = $_POST['id_chat'];
    //supression des lignes ayant le meme id_chat present dans la table messages
    $delete_chat_message = $pdo->prepare("DELETE FROM messages WHERE id_chat = '".$id_chat."'");
    $delete_chat_message->execute();
    //supression des lignes ayant le meme id_chat present dans la table user_chat
    $delete_user_chat = $pdo->prepare("DELETE FROM user_chat WHERE id_chat = '".$id_chat."'");
    $delete_user_chat->execute();
    //supression des lignes ayant le meme id_chat present dans la table chat
    $delete_chat = $pdo->prepare("DELETE FROM chat WHERE id_chat = '".$id_chat."'");
    $delete_chat->execute();
    
    echo "Le chat a été supprimé";
?>


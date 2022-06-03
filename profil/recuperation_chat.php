<?php
    
    include '../connexion_bdd.php';
    //On démarre une nouvelle session
    session_start();
    
    // echo 'Bonjour ' .$_SESSION['prenom']. ' , ' .$_SESSION['nom']. ' , ' .$_SESSION['id_user']. '';

    if (!isset($_POST['ami'])){
        echo json_encode("error");
    } else if($_POST['ami'] == '') {
        echo json_encode("error");
    }else{
        $ami = $_POST['ami'];
        $id_user = $_POST['id_user'];
        $ami_pseudo = $pdo->prepare("SELECT pseudo FROM user WHERE id_user='".$ami."'");
        $ami_pseudo->execute();
        $donnees_ami_pseudo = $ami_pseudo->fetchAll(PDO::FETCH_ASSOC);
        $list_chat = $pdo->prepare("SELECT * FROM user_chat");
        $list_chat->execute();
        $donnees_list_chat = $list_chat->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["data"=>$donnees_list_chat, "user"=>$id_user, "ami_id"=>$ami, "pseudo_ami"=>$donnees_ami_pseudo]);
    }
?>
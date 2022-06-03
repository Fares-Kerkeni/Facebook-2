<?php
include '../connexion_bdd.php';
include '../deco.php';
//On démarre une nouvelle session
session_start();
deconnexion();
date_default_timezone_set('Europe/Paris');
$date_actuelle = date('y-m-d h:i:s');
// // formulaire pour envoyer un poste sur une page de groupe
if (isset($_POST['submit_post'])){
    if ($_POST['text_poste'] == ""){
        header('Location: profil.php');
    }
    else{
        $tmpName = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];
    
        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));
    
        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $maxSize = 4000000000;
        
        if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){
    
            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $file = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg
    
            move_uploaded_file($tmpName, '../upload/'.$file);
        }
        $text_poste = $_POST['text_poste'];
        $id_group_page = $_POST['id_group_page'];
        $id_user = $_POST['id_user'];
        $insert_post = $pdo->prepare("INSERT INTO group_page_postes (id_group_page, id_user, texte_poste, image_poste, date_poste) VALUES ('".$id_group_page."','".$id_user."','".$text_poste."','".$file."','".$date_actuelle."');");
        $insert_post->execute();
        header('Location: group_page.php?id_group_page='.$id_group_page);
    }
}

if (isset($_POST['add_contact_page_group'])){
    $id_user = $_POST['id_user'];
    $id_group_page = $_POST['id_group_page'];
    $verif_add_contact_page_group = $pdo->prepare('SELECT * from group_page_user WHERE id_user = "'.$id_user.'" AND id_group_page = "'.$id_group_page.'" ');
    $verif_add_contact_page_group->execute();
    $count_verif_add_contact_page_group = $verif_add_contact_page_group->rowCount();
    if($count_verif_add_contact_page_group == 0){
        $insert_user_group_page = $pdo->prepare("INSERT INTO group_page_user (id_user, id_group_page) VALUES ('".$id_user."','".$id_group_page."');");
        $insert_user_group_page->execute();
    }
    else{
        $delete_user_group_page_poste = $pdo->prepare("DELETE FROM group_page_postes WHERE id_user = '".$id_user."' AND id_group_page = '".$id_group_page."'");
        $delete_user_group_page_poste->execute();
        $delete_user_group_page = $pdo->prepare("DELETE FROM group_page_user WHERE id_user = '".$id_user."' AND id_group_page = '".$id_group_page."'");
        $delete_user_group_page->execute();
    }
    header('Location: group_page.php?id_group_page='.$id_group_page);
}

?>
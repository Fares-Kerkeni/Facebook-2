<?php
    include '../connexion_bdd.php';
    include '../deco.php';
    //On démarre une nouvelle session
    session_start();
    deconnexion();
    date_default_timezone_set('Europe/Paris');
    $date_actuelle = date('y-m-d h:i:s');
    // // formulaire pour envoyer un poste
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
            $insert_post = $pdo->prepare("INSERT INTO postes (id_user, texte_poste, image_poste, date_poste) VALUES ('".$_SESSION['id_user']."','".$text_poste."','".$file."','".$date_actuelle."');");
            $insert_post->execute();
            header('Location: profil.php');
        }
    }

    // // formulaire pour supprimer un poste
    if (isset($_POST['submit_delete_post'])){
        $id_poste = $_POST['id_poste'];

        $verif_like_post = $pdo->prepare("SELECT * FROM aime_poste WHERE id_poste = '".$id_poste."'");
        $verif_like_post->execute();
        $count_verif_like_post = $verif_like_post->rowCount();
        if($count_verif_like_post > 0){
            $delete_post = $pdo->prepare("DELETE FROM aime_poste WHERE id_poste = '".$id_poste."'");
            $delete_post->execute();
        }

        $verif_com_post = $pdo->prepare("SELECT * FROM commentaire WHERE id_poste = '".$id_poste."'");
        $verif_com_post->execute();
        $count_verif_com_post = $verif_com_post->rowCount();
        if($count_verif_com_post > 0){
            $delete_post = $pdo->prepare("DELETE FROM commentaire WHERE id_poste = '".$id_poste."'");
            $delete_post->execute();
        }

        $delete_post = $pdo->prepare("DELETE FROM postes WHERE id_poste = '".$id_poste."'");
        $delete_post->execute();
        header('Location: profil.php');        
    }

    // // formulaire pour modifier un poste
    if (isset($_POST['submit_update_post'])){
        if ($_POST['new_texte_poste'] == ""){
            header('Location: profil.php');
        }
        else{
            $new_texte_poste = $_POST['new_texte_poste'];
            $id_poste = $_POST['id_poste'];
            $update_post = $pdo->prepare("UPDATE postes SET texte_poste = '".$new_texte_poste."' WHERE id_poste = '".$id_poste."'");
            $update_post->execute();
            header('Location: profil.php');
        }
    }

    // // formulaire pour modifier l'image d'un poste
    if (isset($_POST['submit_update_img_post'])){
            $tmpName = $_FILES['banniere']['tmp_name'];
            $name = $_FILES['banniere']['name'];
            $size = $_FILES['banniere']['size'];
            $error = $_FILES['banniere']['error'];
            $id_poste = $_POST['id_poste'];
            $tabExtension = explode('.', $name);
            $extension = strtolower(end($tabExtension));
            $extensions = ['jpg', 'png', 'jpeg', 'gif'];
            $maxSize = 4000000000;

            if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){
        
                $uniqueName = uniqid('', true);
                //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                $banniere = $uniqueName.".".$extension;
                //$file = 5f586bf96dcd38.73540086.jpg
        
                move_uploaded_file($tmpName, '../upload/'.$banniere);
        
                $req = $pdo->prepare("UPDATE postes SET image_poste = :file  WHERE id_poste = '".$id_poste."'");
                $req->execute([
                    ":file" => $banniere,
                ]);
                
            }
            header('Location: profil.php');
    }

    // // formulaire pour commenter un poste
    if (isset($_POST['submit_commentaire'])){
        if ($_POST['texte_commentaire'] == ""){
            header('Location: profil.php');
        }
        else{
            $texte_commentaire = $_POST['texte_commentaire'];
            $id_poste = $_POST['id_poste'];
            $insert_commentaire = $pdo->prepare("INSERT INTO commentaire (id_user, id_poste, texte_commentaire, image_commentaire, date_commentaire) VALUES ('".$_SESSION['id_user']."','".$id_poste."','".$texte_commentaire."','image','".$date_actuelle."');");
            $insert_commentaire->execute();
            header('Location: profil.php');
        }
    }

    // // formulaire pour commenter le poste d'un utilisateur sur son profil
    if (isset($_POST['submit_commentaire_other_user'])){
        $id_other_user = $_POST['id_other_user'];
        if ($_POST['texte_commentaire'] == ""){
            header('Location: profil_other_user.php?id_other_user='.$id_other_user);       
        }
        else{
            $texte_commentaire = $_POST['texte_commentaire'];
            $id_poste = $_POST['id_poste'];
            $insert_commentaire = $pdo->prepare("INSERT INTO commentaire (id_user, id_poste, texte_commentaire, image_commentaire, date_commentaire) VALUES ('".$_SESSION['id_user']."','".$id_poste."','".$texte_commentaire."','image','".$date_actuelle."');");
            $insert_commentaire->execute();
            header('Location: profil_other_user.php?id_other_user='.$id_other_user);       
        }
    }
    
    // // formulaire pour aimer un poste
    if (isset($_POST['submit_like'])){
        $id_poste = $_POST['id_poste'];
        $verif_like = $pdo->prepare("SELECT * FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."' AND id_poste = '".$id_poste."'");
        $verif_like->execute();
        $count_verif_like = $verif_like->rowCount();
        if($count_verif_like == 0){
            $insert_like = $pdo->prepare("INSERT INTO aime_poste (id_user, id_poste) VALUES ('".$_SESSION['id_user']."','".$id_poste."')");
            $insert_like->execute();
            header('Location: profil.php');
        }
        else{
            $delete_like = $pdo->prepare("DELETE FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."' AND id_poste = '".$id_poste."'");
            $delete_like->execute();
            header('Location: profil.php');
        }
    }

    // formulaire pour aimer le poste d'un utilisateur sur son profil
    if (isset($_POST['submit_like_other_user'])){
        $id_other_user = $_POST['id_other_user'];
        $id_poste = $_POST['id_poste'];
        $verif_like = $pdo->prepare("SELECT * FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."' AND id_poste = '".$id_poste."'");
        $verif_like->execute();
        $count_verif_like = $verif_like->rowCount();
        if($count_verif_like == 0){
            $insert_like = $pdo->prepare("INSERT INTO aime_poste (id_user, id_poste) VALUES ('".$_SESSION['id_user']."','".$id_poste."')");
            $insert_like->execute();
            header('Location: profil_other_user.php?id_other_user='.$id_other_user);       
        }
        else{
            $delete_like = $pdo->prepare("DELETE FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."' AND id_poste = '".$id_poste."'");
            $delete_like->execute();
            header('Location: profil_other_user.php?id_other_user='.$id_other_user);       
        }
        
    }

    // formulaire pour aimer un commentaire
    if (isset($_POST['submit_like_com'])){
        $id_com = $_POST['id_com'];
        $verif_like_com = $pdo->prepare("SELECT * FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."' AND id_commentaire = '".$id_com."'");
        $verif_like_com->execute();
        $count_verif_like_com = $verif_like_com->rowCount();
        if($count_verif_like_com == 0){
            $insert_like_com = $pdo->prepare("INSERT INTO aime_commentaire (id_user, id_commentaire) VALUES ('".$_SESSION['id_user']."','".$id_com."')");
            $insert_like_com->execute();
            header('Location: profil.php');
        }
        else{
            $delete_like_com = $pdo->prepare("DELETE FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."' AND id_commentaire = '".$id_com."'");
            $delete_like_com->execute();
            header('Location: profil.php');
        }
    }

    // // formulaire pour aimer le commentaire sur un poste sur le profil d'un utilisateur
    if (isset($_POST['submit_like_com_other_user'])){
        $id_com = $_POST['id_com'];
        $id_other_user = $_POST['id_other_user'];
        $verif_like_com = $pdo->prepare("SELECT * FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."' AND id_commentaire = '".$id_com."'");
        $verif_like_com->execute();
        $count_verif_like_com = $verif_like_com->rowCount();
        if($count_verif_like_com == 0){
            $insert_like_com = $pdo->prepare("INSERT INTO aime_commentaire (id_user, id_commentaire) VALUES ('".$_SESSION['id_user']."','".$id_com."')");
            $insert_like_com->execute();
            header('Location: profil_other_user.php?id_other_user='.$id_other_user);  
        }
        else{
            $delete_like_com = $pdo->prepare("DELETE FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."' AND id_commentaire = '".$id_com."'");
            $delete_like_com->execute();
            header('Location: profil_other_user.php?id_other_user='.$id_other_user);  
        }
    }

    // formulaire pour ajouter quelqu'un en ami
    if (isset($_POST['add_ami'])){
        $id_other_user = $_POST['id_other_user'];
        $verif_friend = $pdo->prepare("SELECT * FROM contacts WHERE id_contact = '".$id_other_user."' AND id_user = '".$_SESSION['id_user']."'");
        $verif_friend->execute();
        $count_verif_friend = $verif_friend->rowCount();
        if($count_verif_friend > 0){
            $delete_friend = $pdo->prepare("DELETE FROM contacts WHERE id_contact = '".$id_other_user."' AND id_user = '".$_SESSION['id_user']."'");
            $delete_friend->execute();
            $delete_friend2 = $pdo->prepare("DELETE FROM contacts WHERE id_contact = '".$_SESSION['id_user']."' AND id_user = '".$id_other_user."'");
            $delete_friend2->execute();
        }
        else{
            $insert_ami = $pdo->prepare("INSERT INTO contacts (id_contact, id_user) VALUES ('".$id_other_user."','".$_SESSION['id_user']."');");
            $insert_ami->execute();
            $insert_ami2 = $pdo->prepare("INSERT INTO contacts (id_contact, id_user) VALUES ('".$_SESSION['id_user']."','".$id_other_user."');");
            $insert_ami2->execute();
        }
        header('Location: profil_other_user.php?id_other_user='.$id_other_user);        
    }

    // formulaire pour supprimer son compte
    if (isset($_POST['delete_user'])){
        // description
        $delete_description = $pdo->prepare("DELETE FROM description WHERE id_user = '".$_SESSION['id_user']."'");
        $delete_description->execute();
        // amis
        $verif_friend = $pdo->prepare("SELECT * FROM contacts WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_friend->execute();
        $count_verif_friend = $verif_friend->rowCount();
        if($count_verif_friend > 0){
            $delete_friend = $pdo->prepare("DELETE FROM contacts WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_friend->execute();
        }
        // aime
        $verif_like = $pdo->prepare("SELECT * FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_like->execute();
        $count_verif_like = $verif_like->rowCount();
        if($count_verif_like > 0){
            $delete_like = $pdo->prepare("DELETE FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_like->execute();
        }
        // commentaire
        $verif_com = $pdo->prepare("SELECT * FROM commentaire WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_com->execute();
        $count_verif_com = $verif_com->rowCount();
        if($count_verif_com > 0){
            $delete_com = $pdo->prepare("DELETE FROM commentaire WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_com->execute();
        }
        // aime_commentaire
        $verif_aime_commentaire = $pdo->prepare("SELECT * FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_aime_commentaire->execute();
        $count_verif_aime_commentaire = $verif_aime_commentaire->rowCount();
        if($count_verif_aime_commentaire > 0){
            $delete_aime_commentaire = $pdo->prepare("DELETE FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_aime_commentaire->execute();
        }
        // postes
        $verif_poste = $pdo->prepare("SELECT * FROM postes WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_poste->execute();
        $count_verif_poste = $verif_poste->rowCount();
        if($count_verif_poste > 0){
            $delete_poste = $pdo->prepare("DELETE FROM postes WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_poste->execute();
        }
        // messages
        $verif_msg = $pdo->prepare("SELECT * FROM messages WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_msg->execute();
        $count_verif_msg = $verif_msg->rowCount();
        if($count_verif_msg > 0){
            $delete_msg = $pdo->prepare("DELETE FROM messages WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_msg->execute();
        }
        // user_chat
        $verif_user_chat = $pdo->prepare("SELECT * FROM user_chat WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_user_chat->execute();
        $count_verif_user_chat = $verif_user_chat->rowCount();
        if($count_verif_user_chat > 0){
            $delete_user_chat = $pdo->prepare("DELETE FROM user_chat WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_user_chat->execute();
        }

        // groupe_page_postes
        $verif_group_page_postes = $pdo->prepare("SELECT * FROM group_page_postes WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_group_page_postes->execute();
        $count_verif_group_page_postes = $verif_group_page_postes->rowCount();
        if($count_verif_group_page_postes > 0){
            $delete_group_page_postes = $pdo->prepare("DELETE FROM group_page_postes WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_group_page_postes->execute();
        }

        // groupe_page_user
        $verif_group_page_user = $pdo->prepare("SELECT * FROM group_page_user WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_group_page_user->execute();
        $count_verif_group_page_user = $verif_group_page_user->rowCount();
        if($count_verif_group_page_user > 0){
            $delete_group_page_user = $pdo->prepare("DELETE FROM group_page_user WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_group_page_user->execute();
        }


        // groupe_page
        $verif_group_page = $pdo->prepare("SELECT * FROM group_page WHERE id_user = '".$_SESSION['id_user']."'");
        $verif_group_page->execute();
        $count_verif_group_page = $verif_group_page->rowCount();
        if($count_verif_group_page > 0){
            $delete_group_page = $pdo->prepare("DELETE FROM group_page WHERE id_user = '".$_SESSION['id_user']."'");
            $delete_group_page->execute();
        }
        
        // user
        $delete_user = $pdo->prepare("DELETE FROM user WHERE id_user = '".$_SESSION['id_user']."'");
        $delete_user->execute();
        $_SESSION = array();
        session_destroy();
        unset($_SESSION);
        header('Location: ../landing_page/landing_page.php');    
    }

    // formulaire pour creer une page de groupe
    if (isset($_POST['submit_create_group_page'])){
        if ($_POST['group_page_name'] == "" || $_POST['group_page_description'] == ""){
            header('Location: profil.php');
        }
        else{
            $id_user = $_POST['id_user'];
            $group_page_name = $_POST['group_page_name'];
            $group_page_description = $_POST['group_page_description'];
            $insert_group_page = $pdo->prepare("INSERT INTO group_page (id_user, nom, image, banniere, description) VALUES ('".$id_user."','".$group_page_name."','default_profil.jpg', 'default_banner.jpg','".$group_page_description."');");
            $insert_group_page->execute();
            
            $select_last_group_page = $pdo->prepare("SELECT max(id_group_page) AS last_id_group_page FROM group_page");
            $select_last_group_page->execute();
            $donnee_select_last_group_page = $select_last_group_page->fetch(PDO::FETCH_ASSOC);
            echo $donnee_select_last_group_page['last_id_group_page'];

            $insert_group_page_user = $pdo->prepare("INSERT INTO group_page_user (id_user, id_group_page) VALUES ('".$id_user."','".$donnee_select_last_group_page['last_id_group_page']."');");
            $insert_group_page_user->execute();
            header('Location: profil.php');
        }     
    }
    
?>
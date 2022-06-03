<?php
    include '../connexion_bdd.php';
    session_start();
    include '../deco.php';
    deconnexion();

    $id_group_page = $_GET['id_group_page'];
    // selectionne les infos de la page de groupe
    $select_group_page = $pdo->prepare('SELECT * from group_page WHERE id_group_page = "'.$id_group_page.'"');
    $select_group_page->execute();
    $donnee_select_group_page= $select_group_page->fetch(PDO::FETCH_ASSOC);

    // selectionne les postes de la page de groupe
    $select_postes_group_page = $pdo->prepare('SELECT * FROM group_page_postes INNER JOIN user on group_page_postes.id_user = user.id_user INNER JOIN description on user.id_user = description.id_user  WHERE id_group_page = "'.$id_group_page.'" ORDER BY date_poste DESC');
    $select_postes_group_page->execute();
    $donnees_select_postes_group_page = $select_postes_group_page->fetchAll(PDO::FETCH_ASSOC);  
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>group_page</title>
    <link rel="stylesheet" href="../root.css">

    <link rel="stylesheet" href="profil.css">


</head>

<body>
    <div id="header">
      <div id="navbar">
        <div id="logo">
          <p>FaceBook2</p>
        </div>
        <div id="boutons">
          <div style="display:inline-block; height:20px; witdh:20px;" id="theme_white" class="theme_change"> ⚪ </div>
          <div style="display:inline-block; height:20px; witdh:20px;" id="theme_black" class="theme_change"> ⚫ </div>
          <button class="bouton"><a href="../HomePage/homepage.php"><img src="../icone/home.svg" alt="homepage" height="20px" witdh="20px"></a></button>
          <button class="bouton"><a href="../Messagerie/messagerie.php"><img src="../icone/mail.svg" alt="messages" height="20px" witdh="20px"></a></button>
          <button class="bouton"><a href="../profil/profil.php"><img src="../icone/user.svg" alt="profil" height="20px" witdh="20px"></a></button>
          <form class="formulaire" method="POST">
            <button class="bouton" name="submit_deconnexion"><img src="../icone/power.svg" alt="logout" height="20px" witdh="20px"></button>
          </form>
        </div>
      </div>
    </div>  
    <div id="profil_container">
        <div id="profil">
        <div id="banner">
            <div >  
                <img src='../upload/<?= $donnee_select_group_page["banniere"]?>' class="img_banniere"><br>
            </div>     
            <div id="user_pdp">
                <button class="bouton"> <img src='../upload/<?= $donnee_select_group_page["image"]?>'  alt="profil" height="50px" witdh="50px" ></button>
            </div>
        </div>
        <div id="user_profil">
            <div id="first_line">
            <div id="nom"><p><?= $donnee_select_group_page["nom"]?></p></div>
            <!-- <button class="bouton"><a href="change.php"><img src="../icone/edit-2.svg" alt="edit" height="20px" witdh="20px"></a></button> -->
            </div>
            <div id="infos">
            <p>description</p> 
            </div>
            <div id="description"><?= $donnee_select_group_page["description"]?></div>
        </div>
        </div>
        </div>
    </div>
    <?php
        if($donnee_select_group_page["id_user"] == $_SESSION['id_user']){
        $select_contact_lead_page_group = $pdo->prepare('SELECT * from contacts WHERE id_user = "'.$donnee_select_group_page["id_user"].'"');
        $select_contact_lead_page_group->execute();
        $donnees_select_contact_lead_page_group = $select_contact_lead_page_group->fetchAll(PDO::FETCH_ASSOC);  
        ?> 
        <div id="add_member">
            <h2>Ajouter un membre</h2>
            <?php foreach($donnees_select_contact_lead_page_group as $donnee_select_contact_lead_page_group): 
                if($donnee_select_contact_lead_page_group['id_contact'] != $_SESSION['id_user']){
                    $select_infos_contacts = $pdo->prepare('SELECT * from user INNER JOIN description ON user.id_user = description.id_user WHERE user.id_user = "'.$donnee_select_contact_lead_page_group['id_contact'].'"');
                    $select_infos_contacts->execute();
                    $donnee_select_infos_contacts = $select_infos_contacts->fetch(PDO::FETCH_ASSOC);  
                ?>
                <div style="display:flex; align-items:center; margin-bottom:20px;" >
                    <img style="height:30px; width:30px; border-radius:50%; margin-right:10px;" src="../upload/<?= $donnee_select_infos_contacts["image"]?>" alt="">
                    <?= $donnee_select_infos_contacts["nom"]?>
                    <?= $donnee_select_infos_contacts["prenom"]?>
                    <?php
                        $verif_contact_already_group_page = $pdo->prepare('SELECT * from group_page_user WHERE id_user = "'.$donnee_select_infos_contacts["id_user"].'" AND id_group_page = "'.$id_group_page.'" ');
                        $verif_contact_already_group_page->execute();
                        $count_verif_contact_already_group_page = $verif_contact_already_group_page->rowCount();
                        if($count_verif_contact_already_group_page == 0){
                        ?>
                            <form style="margin-left:10px;" action="formulaires_group_page.php" method="post">
                                <input type="hidden" name="id_group_page" value="<?= $id_group_page?>">
                                <input type="hidden" name="id_user" value="<?= $donnee_select_infos_contacts["id_user"]?>">
                                <button class="bouton" name="add_contact_page_group">+</button>
                            </form>
                        <?php
                        }
                        else{
                        ?>
                            <form style="margin-left:10px;" action="formulaires_group_page.php" method="post">
                                <input type="hidden" name="id_group_page" value="<?= $id_group_page?>">
                                <input type="hidden" name="id_user" value="<?= $donnee_select_infos_contacts["id_user"]?>">
                                <button class="bouton" name="add_contact_page_group">-</button>
                            </form>
                        <?php
                        }
                    ?>
                </div>
                
                <?php
                }
                ?>    
            <?php endforeach; ?>
        </div>
        <?php
        }
    ?>
    <div id="page">
        <div id="publications">
        <!-- new post -->
            <div id="new_post">
                <div id="new">
                <h2>Nouveau poste</h2>
                    <form action="formulaires_group_page.php" method="POST" enctype="multipart/form-data">
                        <input type="text" name="text_poste" placeholder="Que voulez-vous dire au monde ?">
                        <input type="hidden" name="id_group_page" value="<?= $id_group_page?>">
                        <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']?>">
                        <label for="file">Fichier</label>
                        <input type="file" name="file">
                        <button type="submit"  name="submit_post">Enregistrer</button>
                    </form>
                </div>
            </div>
            <?php 
            // affiche les postes de la page de groupe
            foreach($donnees_select_postes_group_page as $donnee_select_postes_group_page):
            ?>
                <div class="publication">
                    <div class="head">
                    <div class="user">
                        <a href="../profil/profil_other_user.php?id_other_user=<?= $donnee_select_postes_group_page["id_user"]?>" class="pp"><img style="height:100%; width:100%; border-radius:50%;" src="../upload/<?= $donnee_select_postes_group_page["image"]?>" alt=""></a>
                        <p class="name"><?= $donnee_select_postes_group_page["prenom"]?> <?= $donnee_select_postes_group_page["nom"]?></p>
                        <div class="datetime"><li><?= $donnee_select_postes_group_page["date_poste"]?></li></div>
                    </div>
                    </div>  
                        <div class="content">
                        <p class="texte"><?= $donnee_select_postes_group_page["texte_poste"]?></p>         
                        <img src='../upload/<?= $donnee_select_postes_group_page["image_poste"]?>'>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>





        <?php
        // recupere les amis de l'utilisateur 
        $select_friends = $pdo->prepare("SELECT * FROM contacts WHERE id_user = '".$_SESSION['id_user']."'");
        $select_friends->execute();
        $donnees_select_friends = $select_friends->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div id="suggestions">
            <?php
                $select_list_group_page = $pdo->prepare("SELECT * FROM group_page INNER JOIN group_page_user ON group_page.id_group_page = group_page_user.id_group_page WHERE group_page_user.id_user = '".$_SESSION['id_user']."'");
                $select_list_group_page->execute();
                $donnees_select_list_group_page = $select_list_group_page->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <p>
            <h2>Groupes</h2>
            </p>
            <?php foreach($donnees_select_list_group_page as $donnee_select_list_group_page):?>
                <div style="display:flex; align-items:center;" >
                <img style="height:30px; width:30px; border-radius:50%; margin-right:10px;" src="../upload/<?= $donnee_select_list_group_page["image"]?>" alt="">
                <a style="color:black;" href="../profil/group_page.php?id_group_page=<?= $donnee_select_list_group_page["id_group_page"]?>"><?= $donnee_select_list_group_page["nom"]?></a>
                </div>
            <?php endforeach; ?>
            <p>
            <h2>Liste d'amis</h2>
            </p>
            <?php 
            foreach($donnees_select_friends as $donnee_select_friends):
            $select_info_friends = $pdo->prepare("SELECT * FROM user INNER JOIN description ON user.id_user = description.id_user WHERE user.id_user = '".$donnee_select_friends["id_contact"]."'");
            $select_info_friends->execute();
            $donnees_select_info_friends = $select_info_friends->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php 
            // affiche ses amis
            foreach($donnees_select_info_friends as $donnee_select_info_friends):
              if($donnee_select_info_friends["id_user"] != $_SESSION['id_user']){
              ?>
              <div style="display:flex; align-items:center;" >
                <img style="height:30px; width:30px; border-radius:50%; margin-right:10px;" src="../upload/<?= $donnee_select_info_friends["image"]?>" alt="">
                <a style="color:black;" href="../profil/profil_other_user.php?id_other_user=<?= $donnee_select_info_friends["id_user"]?>"><?= $donnee_select_info_friends["prenom"]?> <?= $donnee_select_info_friends["nom"]?></p>
              </div>
              <?php 
              }
            endforeach; ?>
          <?php 
          endforeach; ?>
        </div>
    </div>

   
    <script src="profil.js"></script>
    <script src="../theme.js"></script>
    <script src="../HomePage/homepage.js"></script>
    
</body>
</html>

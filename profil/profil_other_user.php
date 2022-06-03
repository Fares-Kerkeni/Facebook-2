<?php
    
    include '../connexion_bdd.php';
    session_start();

    include '../deco.php';
    deconnexion();

    // recupere l'id de l'utilisateur sur le quel on a cliqué grace a la variable passé dans le lien
    $id_other_user = $_GET['id_other_user'];

    // recupere les données de l'utilisateur
    $select_profil = $pdo->prepare('SELECT * from user WHERE id_user="'.$id_other_user.'"');
    $select_profil->execute();
    $donnees_select_profil= $select_profil->fetchAll(PDO::FETCH_ASSOC);

    // recupere les postes de l'utilisateur
    $select_postes_user = $pdo->prepare('SELECT * FROM `postes` INNER JOIN user ON postes.id_user = user.id_user INNER JOIN description ON user.id_user = description.id_user WHERE user.id_user="'.$id_other_user.'" ORDER BY postes.date_poste DESC');
    $select_postes_user->execute();
    $donnees_select_postes_user = $select_postes_user->fetchAll(PDO::FETCH_ASSOC);   
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil autre user</title>

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
      
        <?php
            // affiche la banniere de l'utilisateur
            $affiche_bannière = $pdo->prepare("SELECT banniere FROM description WHERE id_user=:id_user");
            $affiche_bannière->execute([
            
                ":id_user" => $id_other_user
            ]);

            while($data = $affiche_bannière->fetch()){
            ?>
            <div >  
                <img src='../upload/<?= $data['banniere'] ?>' class="img_banniere" ><br>
            </div>
            <?php
            }
            ?>
                   
     
      
      
      <?php 
        // affiche la banniere de l'utilisateur
        $affiche = $pdo->prepare("SELECT image FROM description WHERE id_user=:id_user");
        $affiche->execute([
           
            ":id_user" => $id_other_user
        ]);
        
        while($data = $affiche->fetch()){
        ?>
          <div id="user_pdp">
        <button class="bouton"> <img src='../upload/<?= $data['image'] ?>'  alt="profil" height="50px" witdh="50px" >
       
      </button>
        </div>

        <?php
        }
        ?>
      

      </div>

      

      <div id="user_profil">
        <?php 
          // affiche les infos de l'utilisateurs
          foreach($donnees_select_profil as $donnee_select_profil): ?>
            <div id="first_line">
            <div id="nom"><p><?= $donnee_select_profil["pseudo"]?></p></div>
            </div>

            <div id="infos">
            <p><?= $donnee_select_profil["prenom"]?> <?= $donnee_select_profil["nom"]?> 
            <img src="../icone/mail.svg" alt="mail" height="10px" witdh="10px"> <?= $donnee_select_profil["adresse_mail"] ?> 
            <img src="../icone/map-marker-2.svg" alt="location" height="10px" witdh="10px"><?= $donnee_select_profil["pays"]?><p>
            </div>
        <?php endforeach; ?>
        <div id="description"><?= $donnee_select_profil["description"]?></div>
        <form action="formulaires_profils.php" method="post">
          <input type="hidden" name="id_other_user" value="<?= $id_other_user ?>">
          <?php
            // verifie si on est ami avec l'utilisateur, si on est deja ami on le supprime de la liste d'ami et si il n'est pas ami on l'ajoute
            $verif_friend = $pdo->prepare("SELECT * FROM contacts WHERE id_contact = '".$id_other_user."' AND id_user = '".$_SESSION['id_user']."'");
            $verif_friend->execute();
            $count_verif_friend = $verif_friend->rowCount();
            if($count_verif_friend > 0){
          ?>
              <button class="bouton" name="add_ami">-</button>
          <?php
            }
            else{
          ?>
              <button class="bouton" name="add_ami">+</button>
          <?php
            }
          ?>
        </form>
        <!-- Formulaire création de chat -->
        <form class="creation_chat" method="post">
          <input type="hidden" name="id_other_user" value="<?= $id_other_user ?>">
          <input type="hidden" name="id_user" value="<?= $_SESSION["id_user"] ?>"> 
          <input type="submit" name="add_ami" value="Discutez">
        </form>
      </div>
      
      
    </div>




    </div>

  </div>


    <div id="page">

      <div id="publications">
        <!-- Une publication -->
        <?php 
          // affiche les postes de l'utilisateur
          foreach($donnees_select_postes_user as $donnee_select_postes_user): 
          // affiche le nombre de like par poste
          $select_like = $pdo->prepare("SELECT * FROM aime_poste WHERE id_poste = '".$donnee_select_postes_user['id_poste']."'");
          $select_like->execute();
          $count_select_like = $select_like->rowCount();
          // affiche le nombre de commentaire par poste
          $nb_com = $pdo->prepare("SELECT * FROM commentaire WHERE id_poste = '".$donnee_select_postes_user['id_poste']."'");
          $nb_com->execute();
          $count_nb_com = $nb_com->rowCount();
          ?>
          <div class="publication">
            <div class="head">
              <div class="user">
                  <a href="../profil/profil_other_user.php?id_other_user=<?= $donnee_select_postes_user["id_user"]?>" class="pp"><img style="height:100%; width:100%; border-radius:50%;" src="../upload/<?= $donnee_select_postes_user["image"]?>" alt=""></a>
                  <p class="name"><?= $donnee_select_postes_user["prenom"]?> <?= $donnee_select_postes_user["nom"]?></p>
                  <div class="datetime"><li><?= $donnee_select_postes_user["date_poste"]?></li></div>
              </div>
            </div>  

            <div class="content">
              <p class="texte"><?= $donnee_select_postes_user["texte_poste"]?></p>   
              <img style="width:200px;" src='../upload/<?= $donnee_select_postes_user["image_poste"]?>'>           
            </div>

            <div class="reaction">
              <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="hidden" name="id_other_user" value="<?= $id_other_user ?>">
                <button class="bouton" name="submit_like_other_user"><img src="../icone/heart.svg" alt="like" height="20px" witdh="20px"></button>
              </form>
              <!-- affiche nombre like -->
              <p style="margin-right:20px;"><?= $count_select_like ?></p>
              <button onclick="open_com('commentaire_poste_<?= $donnee_select_postes_user['id_poste']?>');" class="bouton"><img src="../icone/message-square.svg" alt="like" height="20px" witdh="20px"></button>
              <!-- affiche nombre com -->
              <p style="margin-right:20px;"><?= $count_nb_com ?></p>
            </div>
            <div class="commentaires" id="commentaire_poste_<?= $donnee_select_postes_user['id_poste']?>">
              <?php 
              // selectionne tous les commentaires du poste
              $select_commentaire = $pdo->prepare('SELECT * FROM commentaire INNER JOIN user ON commentaire.id_user = user.id_user INNER JOIN description ON user.id_user = description.id_user WHERE id_poste = "'.$donnee_select_postes_user['id_poste'].'" ORDER BY commentaire.date_commentaire DESC');
              $select_commentaire->execute();
              $donnees_select_commentaire = $select_commentaire->fetchAll(PDO::FETCH_ASSOC);  
              // affiche tous les commentaires du postes
              foreach($donnees_select_commentaire as $donnee_select_commentaire): ?>
              <div class="com">
                  <div class="first">
                    <!-- j'arrive pas a aligner les boutons avec le texte, flemme -->
                    <div class="first_txt" style="margin-top:20px; margin-bottom:20px;"><p><?= $donnee_select_commentaire["prenom"]?> <?= $donnee_select_commentaire["nom"]?> - <?= $donnee_select_commentaire["date_commentaire"]?></p></div>
                    <!-- <div class="first_button"><button class="bouton"><img src="../icone/more-horizontal.svg" alt="more_info" height="20px" witdh="20px"></button></div> -->
                  </div>
                  <p> <?= $donnee_select_commentaire["texte_commentaire"]?></p>
                  <?php
                    // selectionne les likes du commentaire
                    $select_like_com = $pdo->prepare("SELECT * FROM aime_commentaire WHERE id_commentaire = '".$donnee_select_commentaire['id_commentaire']."'");
                    $select_like_com->execute();
                    $count_select_like_com = $select_like_com->rowCount();
                  ?>
                  <div class="second">
                    <form action="formulaires_profils.php" method="post">
                      <input type="hidden" name="id_com" value="<?= $donnee_select_commentaire["id_commentaire"]?>">
                      <input type="hidden" name="id_other_user" value="<?= $id_other_user ?>">
                      <button class="bouton" name="submit_like_com_other_user"><img src="../icone/heart.svg" alt="like" height="20px" witdh="20px"></button>
                    </form>
                    <!-- affiche nombre like par com -->
                    <span><?= $count_select_like_com?></span>
                  </div>
                </div>
              <?php 
              endforeach; ?>
              <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="hidden" name="id_other_user" value="<?= $id_other_user ?>">
                <input type="text" name="texte_commentaire">
                <input type="submit" name="submit_commentaire_other_user" value="commenter">
              </form>
            </div>
          </div>
          <div id="poste_<?= $donnee_select_postes_user['id_poste']?>" class="popup_poste">
            <div class="modification_publication">
              <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="submit" name="submit_delete_post" value="delete">
              </form>
              <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="text" name="new_texte_poste">
                <input type="submit" name="submit_update_post" value="update">
              </form>
            </div>
          </div>
        <?php endforeach; ?>

      </div>




      <?php
      // recupere notre liste d'ami
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
            // affiche notre liste d'amis
            foreach($donnees_select_friends as $donnee_select_friends):
            $select_info_friends = $pdo->prepare("SELECT * FROM user INNER JOIN description ON user.id_user = description.id_user WHERE user.id_user = '".$donnee_select_friends["id_contact"]."'");
            $select_info_friends->execute();
            $donnees_select_info_friends = $select_info_friends->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach($donnees_select_info_friends as $donnee_select_info_friends):
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
    <script>
      // Selection du formulaire creation_chat
      const formCCs = document.querySelectorAll('.creation_chat');
            formCCs.forEach(formCC => {
                // La fonction s'enclenche à l'evenement submit
                formCC.addEventListener('submit', (event) => {
                event.preventDefault();
                // récupération des post
                const ami = formCC.querySelector('[name=id_other_user]').value;
                const id_user = formCC.querySelector('[name=id_user]').value;
                // qu'on va stocker dans un FormData
                const formData = new FormData();
                formData.append('ami', ami);
                formData.append('id_user', id_user);
                // On envoie nos données dans recuperation_chat.php par method post
                fetch('recuperation_chat.php', {
                    method: 'POST',
                    body: formData
                }).then(resp => resp.json())//Récupération sous format json
                .then(list => {//Récupération de la liste
                    user_chat=[]//liste données id_chat de la session
                    ami_chat=[]//liste données id_chat de l'interlocuteur
                    //Deux boucles qui va trier list en fonction des id_chat
                    for(let i=0; i<list.data.length; i++){
                        if(list.user == list.data[i].id_user){
                            user_chat.push(list.data[i].id_chat)
                        }
                    }
                    for(let y=0; y<list.data.length; y++){
                        if(list.ami_id == list.data[y].id_user){
                            ami_chat.push(list.data[y].id_chat)
                        }
                    }
                    //fonction qui va comparer les deux listes pour voir si l'id_chat de la session est
                    //dans l'id_chat de l'interlocuteur
                    function chat_commun(chat_a, chat_b){
                        for(let j=0; j<chat_a.length; j++){
                            for(let k=0; k<chat_b.length; k++){
                                if(chat_a[j]==[] || chat_b[k]==[]){
                                    return false
                                }else if(chat_a[j]==chat_b[k]){
                                    return true // Si l'id_chat de la session est présent return true
                                }else{
                                }
                            }
                        }
                        return false//sinon false
                    }
                    let etat = chat_commun(user_chat, ami_chat)
                    //si l'état est false
                    if (etat == false){
                      //récupération des variables
                        let ami_id = list.ami_id
                        let ami_pseudo = list.pseudo_ami[0].pseudo
                        let user_id = list.user
                        // qu'on va stocker dans un FormData
                        const formDataAmi = new FormData();
                        formDataAmi.append('ami_id', ami_id);
                        formDataAmi.append('ami_pseudo', ami_pseudo);
                        formDataAmi.append('user_id', user_id)
                        fetch('creation_chat.php', {//On se dirige vers creation_chat.php
                            method: 'POST',
                            body: formDataAmi
                        }).then(resp => resp.text())// Récupération sous format json
                        .then(chat => {// Récupération de la liste
                          //redirection vers la page messagerie
                            document.location.href = 'http://localhost:8888/Projet-FB/Messagerie/messagerie.php'
                        })
                    
                    }else{
                      //redirection vers la page messagerie
                      document.location.href = 'http://localhost:8888/Projet-FB/Messagerie/messagerie.php'
                     }
            
                })

            });
        });
      </script>
</body>
</html>

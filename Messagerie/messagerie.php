<?php
    include '../connexion_bdd.php';
    include '../deco.php';
    //On démarre une nouvelle session
    session_start();
    deconnexion();

    //On récupère tous les chats correspondant à la session actuelle
    $affiche_chat = $pdo->prepare("SELECT * FROM user_chat INNER JOIN chat ON user_chat.id_chat = chat.id_chat WHERE id_user = '".$_SESSION['id_user']."'");
    $affiche_chat->execute();
    $donnees_affiche_chat = $affiche_chat->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link rel="stylesheet" href="../root.css">
    <link rel="stylesheet" href="messagerie.css">
</head>
<body>
    <div id="header">

    <div id="navbar">
        <div id="logo">
            <p>FaceBook2</p>
        </div>

        <div id="boutons">
            <button class="bouton"><a href="../HomePage/homepage.php"><img src="../icone/home.svg" alt="homepage" height="20px" witdh="20px"></a></button>
            <button class="bouton"><a href="../Messagerie/messagerie.php"><img src="../icone/mail.svg" alt="messages" height="20px" witdh="20px"></a></button>
            <button class="bouton"><a href="../profil/profil.php"><img src="../icone/user.svg" alt="profil" height="20px" witdh="20px"></a></button>
            <form class="formulaire" method="POST">
            <button class="bouton" name="submit_deconnexion"><img src="../icone/power.svg" alt="logout" height="20px" witdh="20px"></button>
            </form>

        </div>

        </div>

    </div>  

        <div style="margin-top:160px;"></div>
        <!-- Pour chaque chat on créer une div chat -->
        <?php foreach($donnees_affiche_chat as $donnee_affiche_chat): ?>
        <div id="chat_<?= $donnee_affiche_chat["id_chat"] ?>" class="right_message">

            <!-- Récupération du pseudo de l'interlocuteur -->
            <?php
                $user_selectionne = $pdo->prepare("SELECT id_user FROM user_chat WHERE id_user != '".$_SESSION['id_user']."' AND id_chat='".$donnee_affiche_chat["id_chat"]."'");
                $user_selectionne->execute();
                $donnee_user_selectionne = $user_selectionne->fetchAll(PDO::FETCH_ASSOC);

                $nom_chat = $pdo->prepare("SELECT pseudo FROM user WHERE id_user='".$donnee_user_selectionne[0]["id_user"]."'");
                $nom_chat->execute();
                $donnee_nom_chat = $nom_chat->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <h1>Conversation avec : <?= $donnee_nom_chat[0]["pseudo"]?></h1>

            <!-- Pour chaque chat on doit créer des divs contenant les messages de l'utilisateur ainsi que de l'interlocuteur -->
            <div id="messages_<?= $donnee_affiche_chat["id_chat"] ?>" class="design_message">
                <!-- On selectionne tous les messages correspondant à l'id°chat -->
                <?php
                    $affiche_message = $pdo->prepare("SELECT * FROM messages WHERE id_chat = '".$donnee_affiche_chat["id_chat"]."'");
                    $affiche_message->execute();
                    $donnees_affiche_message = $affiche_message->fetchAll(PDO::FETCH_ASSOC);
                    // Pour chaque message on doit les afficher chronologiquement en fonction du pseudo de celui qui a envoyer le message
                    foreach($donnees_affiche_message as $donnee_affiche_message): 
                        $affiche_pseudo = $pdo->prepare("SELECT pseudo FROM user WHERE id_user='".$donnee_affiche_message["id_user"]."'");
                        $affiche_pseudo->execute();
                        $donnees_affiche_pseudo = $affiche_pseudo->fetchAll(PDO::FETCH_ASSOC);?>
                    <?php 
                    if($donnee_affiche_message["id_user"] == $_SESSION['id_user']){
                    ?>
                        <div class="message_right"><?= $donnees_affiche_pseudo[0]["pseudo"] ?> : <?= $donnee_affiche_message["texte_message"] ?></div>
                    <?php 
                    }
                    else{
                    ?>
                        <div class="message_left"><?= $donnees_affiche_pseudo[0]["pseudo"] ?> : <?= $donnee_affiche_message["texte_message"] ?></div>
                    <?php
                    }
                    ?>
                    <?php 
                    endforeach; 
                ?>
                </div>
            </div>
            <!-- La partie Formulaire: chaque chat possède 2 formulaires dont 1 input text et 2 submit -->
            <div id="bottom_<?= $donnee_affiche_chat["id_chat"] ?>" class="form">
                <!-- Formulaire permettant d'envoyer un message -->
                <form style="margin-bottom:20px;" class="envoie_message" method="post">
                    <input type="text" id="myInput_<?= $donnee_affiche_chat["id_chat"] ?>" name="message" placeholder="Message">
                    <input type="hidden" name="id_user" value="<?= $_SESSION["id_user"] ?>">
                    <input type="hidden" name="pseudo" value="<?= $_SESSION["pseudo"] ?>">
                    <input type="hidden" name="id_chat" value="<?= $donnee_affiche_chat["id_chat"] ?>">
                    <input type="submit" name="submit_message" value="Envoyer">
                </form>
                <!-- Formulaire permettant de delete un chat -->
                <form class="delete_chat" method="post">
                    <input type="hidden" name="id_chat" value="<?= $donnee_affiche_chat["id_chat"] ?>">
                    <input type="submit" name="submit_message" value="Supprimer">
                </form>

            </div>
            
        </div>
    <?php endforeach; ?>
    <div style="margin-bottom:160px;"></div>
    <script>
        // Selection du formulaire envoie_message
        const formEMs = document.querySelectorAll('.envoie_message');
            formEMs.forEach(formEM => {
                // La fonction s'enclenche à l'evenement submit
                formEM.addEventListener('submit', (event) => {
                event.preventDefault();
                // récupération des post
                const message = formEM.querySelector('[name=message]').value;
                const id_user = formEM.querySelector('[name=id_user]').value;
                const pseudo = formEM.querySelector('[name=pseudo]').value;
                const id_chat = formEM.querySelector('[name=id_chat]').value;
                // qu'on va stocker dans un FormData
                const formData = new FormData();
                formData.append('message', message);
                formData.append('id_user', id_user);
                formData.append('pseudo', pseudo);
                formData.append('id_chat', id_chat);
                // On envoie nos données dans envoie_message.php par method post
                fetch('envoie_message.php', {
                    method: 'POST',
                    body: formData
                }).then(resp => resp.json())//Récupération sous format json
                .then(list => {//Récupération de la liste
                    if(list == ["error"]){
                        alert("error")
                    }else{
                        console.log(list.pseudo)
                        //On sélectionne la div message correspondant au chat et on l'actualise avec les nouveaux message
                        let mess = document.querySelector('#messages_'.concat(list.chat));
                        mess.innerHTML = '';
                        // on recupere les data dans la liste ainsi que les pseudo correspondant pour les afficher
                        for(let i=0; i<list.data.length; i++){
                            let pseudo_message = '';
                            for(let j=0; j<list.pseudo.length; j++){
                                if(list.data[i].id_user == list.pseudo[j].id_user){
                                    pseudo_message = list.pseudo[j].pseudo
                                    break
                                }
                            }
                            let newDiv = document.createElement("div");
                            newDiv.textContent = pseudo_message + " : " + list.data[i].texte_message;
                            if (pseudo_message == list.session_pseudo){
                                newDiv.classList.add("message_right");
                            }else{
                                newDiv.classList.add("message_left");
                            }
                            mess.appendChild(newDiv);
                        }
                        document.getElementById('myInput_'.concat(list.chat)).value = ''//Reset de l'input
                    }
                })
            });
        });
        // Sélection du formualaire supprimer chat
        const formDMs = document.querySelectorAll('.delete_chat');
            formDMs.forEach(formDM => {
                // La fonction s'enclenche à l'evenement submit
                formDM.addEventListener('submit', (event) => {
                event.preventDefault();
                // récupération des post
                const id_chat = formDM.querySelector('[name=id_chat]').value;
                // qu'on va stocker dans un FormData
                const formData = new FormData();
                formData.append('id_chat', id_chat);
                // On envoie nos données dans delete_chat.php par method post
                fetch('delete_chat.php', {
                    method: 'POST',
                    body: formData
                }).then(resp => resp.text())//Récupération sous format text
                .then(text => {//Récupération du text
                    alert(text)//On alert comme quoi le chat a bien été supprimé
                    // Remove du chat
                    let chat = document.getElementById("chat_".concat(id_chat));
                    chat.remove();
                    // Remove des formulaires
                    let bot = document.getElementById("bottom_".concat(id_chat));
                    bot.remove();
                })
            });
        });

        //Toutes les 3 sec on va reload la page pour que les nouveaux messages s'affichent
        setInterval(function load() {
            fetch('load_message.php')//On se dirige vers load_message.php
                .then(resp => resp.json())// Récupération sous format json
                .then(list => {// Récupération de la liste
                    //On détermine les id_chat dans la liste 
                    let temp = []
                    for(let i=0; i<list.mess.length; i++){
                        temp.push(list.mess[i].id_chat)
                    }
                    let uniqueArr = [...new Set(temp)]//Supréssion des doublons
                    //On va trier les données en fonction des id_chat
                    let chat_array = []
                    function verif(array){
                        chat=[]
                        for(let y=0; y<list.mess.length; y++){
                            if(list.mess[y].id_chat == array){
                                chat.push(list.mess[y])
                            }
                        }
                        chat_array.push(chat)//On obtiendra une liste chat_array contenant des listes ayant le même id_chat
                    }

                    //On boucle pour tous les id_chat
                    for(let z=0; z<uniqueArr.length; z++){
                        verif(uniqueArr[z])
                    }
                    //Dans chaque id_chat
                    for(let j=0; j<uniqueArr.length; j++){
                        //On sélectionne la div correspondantes
                        let mess = document.querySelector('#messages_'.concat(uniqueArr[j]));
                        mess.innerHTML = '';//on la vide
                        for(let i=0; i<chat_array[j].length; i++){
                            let pseudo_message = '';
                            //On selectionne le pseudo de celui qui a envoyé le message
                            for(let k=0; k<list.pseudo.length; k++){
                                if(chat_array[j][i].id_user == list.pseudo[k].id_user){
                                    pseudo_message = list.pseudo[k].pseudo
                                    break
                                }
                            }
                            
                            //Et on créé de nouvelles divs avec le pseudo et le message
                            let newDiv = document.createElement("div");    
                            newDiv.textContent = pseudo_message + " : " + chat_array[j][i].texte_message;
                            if (pseudo_message == list.session_pseudo){
                                newDiv.classList.add("message_right");
                            }else{
                                newDiv.classList.add("message_left");
                            }
                            mess.appendChild(newDiv);
                        }
                    }
                        
            })
        }, 3000);


    </script>

</body>
</html>
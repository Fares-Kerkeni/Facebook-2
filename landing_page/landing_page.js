const connexion = document.getElementById("connexion");
    const open_connexion = document.getElementById("open_connexion");
    const inscription = document.getElementById("inscription");
    const open_inscription = document.getElementById("open_inscription");
        
open_inscription.onclick = function(){
    inscription.style.display = "block"
    connexion.style.display = "none"

}

open_connexion.onclick = function(){
    connexion.style.display = "block"
    inscription.style.display = "none"
}

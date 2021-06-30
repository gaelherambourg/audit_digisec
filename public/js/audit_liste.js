window.onload = function () {

    cliquePage();
    cliqueTd();
}
//Lors d'un clique sur la page (hors bouton Modifier et la liste des entreprises)
//On déselectionne la ligne selectionnée (si elle existe) et on bloque le bouton Modifier
function cliquePage() {
    document.addEventListener("click", function (e) {
        var rows = document.getElementsByTagName("tr");
        /* On parcours les lignes du tableau */
        for (var i = 1; i < rows.length; i++) {
            /* On déselectionne les lignes en leurs enlevant la classe is-selected */
            rows[i].className = "";
        }
    });
}
//Lors d'un clique sur une ligne du tableau, on sélectionne celle-ci en lui affectant 
//la classe css is-selected, on débloque le bouton modifier et on ajoute au href du lien la route
//pour modifier l'élément séléctionné
function cliqueTd() {
    var btnModifier = document.getElementById('modifier');
    /* On récupère toutes les lignes du tableau */
    var rows = document.getElementsByTagName("tr");
    /* On parcours les lignes du tableau */
    for (var i = 1; i < rows.length; i++) {
        rows[i].onclick = function (evt) {
            evt.stopPropagation();
            /* On récupère les lignes qui sont actuellement séléctionnées */
            var elem = document.getElementsByClassName('is-selected');
            /* Boucle pour parcourir chaque element trouvé */
            for (var i = 0; i < elem.length; i++) {
                /* On déselectionne les lignes en leurs enlevant la classe is-selected */
                elem[i].className = "";
            }
            /* On met la classe css is-selected à la ligne sur laquelle on vient de cliquer */
            this.className = "is-selected";
        };
    }
}




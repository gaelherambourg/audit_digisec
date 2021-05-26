window.onload = function () {

    cliquePage();
    cliqueTd();
    stopPropagationCliqueBtnModifier();
    focusInputRecherche();

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

        disable();
        //var btnModifier = document.getElementById("btnModifier");
        //if (btnModifier != null) {
        //    var btnAjouter = document.getElementById("btnAjouter");
        //    btnModifier.parentNode.removeChild(btnModifier);
        //}

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
            enable();
            let a = document.getElementById("lienModifier");
            a.setAttribute("href", 'modifier/' + this.id);
            let aCreer = document.getElementById("lienCreerAudit");
            aCreer.setAttribute("href", '../audit/creation/' + this.id);
            //var divModifier = document.getElementById("divModifier");
            //var btnModifier = document.createElement("button");
            //btnModifier.className = "button is-info is-fullwidth";
            //btnModifier.textContent = "Modifier le référentiel";
            //btnModifier.setAttribute('id', 'btnModifier');
            //divModifier.append(btnModifier);
        };
    }
}
//fonction pour rendre enable le bouton modifier
function enable() {
    document.getElementById('btnModifier').disabled = false;
    document.getElementById('btnCreer').disabled = false;
}
//fonction pour rendre disable le bouton modifier
function disable() {
    document.getElementById('btnModifier').disabled = true;
    document.getElementById('btnCreer').disabled = true;
}
//fonction pour arrêter la propagation de l'évènement "clique sur la page" sur le bouton modifier
function stopPropagationCliqueBtnModifier() {
    let divModifier = document.getElementById("divModifier");
    divModifier.addEventListener("click", stopPropgationCliqueModifier);
    function stopPropgationCliqueModifier(e) {
        e.stopPropagation();
    }
}
//fonction pour donner le focus au chargement au champs de recherche
function focusInputRecherche() {
    let inputRecherche = document.getElementById('recherche_simple_recherche');
    inputRecherche.focus();
    inputRecherche.setSelectionRange(inputRecherche.value.length, inputRecherche.value.length);
}




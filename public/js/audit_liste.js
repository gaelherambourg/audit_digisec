window.onload = function () {
    /* On récupère toutes les lignes du tableau */
    var rows = document.getElementsByTagName("tr");
    /* On parcours les lignes du tableau */
    for (var i = 1; i < rows.length; i++) {
        rows[i].onclick = function () {

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


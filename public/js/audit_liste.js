window.onload = function () {

    //btnModifier.style.visibility = 'hidden';
    cliquePage();
    cliqueTd();

    let divModifier = document.getElementById("divModifier");
    divModifier.addEventListener("click", stopPropgationCliqueModifier);
    function stopPropgationCliqueModifier(e) {
        e.stopPropagation();
    }
}

function cliquePage() {
    document.addEventListener("click", function (e) {
        var rows = document.getElementsByTagName("tr");
        /* On parcours les lignes du tableau */
        for (var i = 1; i < rows.length; i++) {
            /* On déselectionne les lignes en leurs enlevant la classe is-selected */
            rows[i].className = "";
        }

        var btnModifier = document.getElementById("btnModifier");
        if (btnModifier != null) {
            var btnAjouter = document.getElementById("btnAjouter");
            btnModifier.parentNode.removeChild(btnModifier);
        }

    });
}

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
            var divModifier = document.getElementById("divModifier");
            var btnModifier = document.createElement("button");
            btnModifier.className = "button is-info is-fullwidth";
            btnModifier.textContent = "Modifier le référentiel";
            btnModifier.setAttribute('id', 'btnModifier');
            divModifier.append(btnModifier);
        };
    }
}





window.onload = function () {

    cliquePage();
    cliqueTd();
    stopPropagationCliqueBtnModifier();
    focusInputRecherche();
    modal();

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
            let ref = document.getElementById("lienModifierReferentiel");
            ref.setAttribute("href", '../modifier/' + this.id);
        };
    }
}
//fonction pour rendre enable le bouton modifier
function enable() {
    document.getElementById('btnModifier').disabled = false;
}
//fonction pour rendre disable le bouton modifier
function disable() {
    document.getElementById('btnModifier').disabled = true;
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

function modal() {
    // Constantes liées à l'import csv
    const ajouterCsv = document.querySelector('#btnAjouter');
    const csv = document.querySelector('#modalCsv');
    const modalBgA = document.querySelector('#modalCsvBg');
    const fermerCsv = document.querySelector('#fermerCsv');
    const formCsv = document.querySelector("#nouveau_csv");
    // Affiche la fenêtre modal
    ajouterCsv.addEventListener('click', function () {
        csv.className = "modal is-active";
    });
    // Ferme la fenêtre modal si on clique à côté
    modalBgA.addEventListener('click', function () {
        csv.className = "modal";
    });
    // Ferme la fenêtre modal si on clique sur le bouton
    fermerCsv.addEventListener('click', function () {
        csv.className = "modal";
    });
    formCsv.addEventListener("submit", function (e) {
        // On stop le comportement normal
        e.preventDefault();
        //On instancie un nouveau formData pour y insérer nos données à passer dans le body du fetch
        let data = new FormData();
        data.append('referentielCsv', document.getElementById('csv_form_referentielCsv').files[0]);
        data.append('chapitreCsv', document.getElementById('csv_form_chapitreCsv').files[0]);
        data.append('recommandationCsv', document.getElementById('csv_form_recommandationCsv').files[0]);
        data.append('pointControleCsv', document.getElementById('csv_form_pointControleCsv').files[0]);
        data.append('remediationCsv', document.getElementById('csv_form_remediationCsv').files[0]);
        data.append('token', document.getElementById('csv_form__token').value);
        // Création de l'url
        var url = "csv/";
        // Requête Ajax
        fetch(url, { method: 'POST', body: data })
            .then(function (response) {
                return response.json();
            }).then(function (data) {
                //Si le formulaire a été soumis et valider, on ferme la modal et on recharge la page en faisant un clear des inputs de la preuve
                if (data.resultat == 'success') {
                    fermerModal();
                    location.reload();
                    clearInput();
                } else {
                    //Si le formulaire n'est pas valide, on supprime les éventuels messages d'erreurs restant
                    removeAllSpan(formCsv);

                    // Traitement si le formulaire retourne une erreur
                    const referentielParent = document.getElementById('csv_form_referentielCsv').parentNode;
                    const referentielEnfant = document.getElementById('csv_form_referentielCsv');
                    const chapitreParent = document.getElementById('csv_form_chapitreCsv').parentNode;
                    const chapitreEnfant = document.getElementById('csv_form_chapitreCsv');
                    const recommandationParent = document.getElementById('csv_form_recommandationCsv').parentNode;
                    const recommandationEnfant = document.getElementById('csv_form_recommandationCsv');
                    const controleParent = document.getElementById('csv_form_pointControleCsv').parentNode;
                    const controleEnfant = document.getElementById('csv_form_pointControleCsv');
                    const remediationParent = document.getElementById('csv_form_remediationCsv').parentNode;
                    const remediationEnfant = document.getElementById('csv_form_remediationCsv');

                    // Créer un élément span avec la classe help is-danger
                    const classe = 'help is-danger spanMessageCsv'
                    const spanReferentiel = document.createElement("span");
                    const spanChapitre = document.createElement("span");
                    const spanRecommandation = document.createElement("span");
                    const spanControle = document.createElement("span");
                    const spanRemediation = document.createElement("span");

                    if (data.erreur != null) {
                        // S'il y a une erreur sur le referentiel
                        verifErreur(data.erreur.referentielCsv, spanReferentiel, classe, referentielParent, referentielEnfant);
                        // S'il y a une erreur sur le chapitre
                        verifErreur(data.erreur.chapitreCsv, spanChapitre, classe, chapitreParent, chapitreEnfant);
                        // S'il y a une erreur sur la recommandation
                        verifErreur(data.erreur.recommandationCsv, spanRecommandation, classe, recommandationParent, recommandationEnfant);
                        // S'il y a une erreur sur le point de contrôle
                        verifErreur(data.erreur.pointControleCsv, spanControle, classe, controleParent, controleEnfant);
                        // S'il y a une erreur sur la remediation
                        verifErreur(data.erreur.remediationCsv, spanRemediation, classe, remediationParent, remediationEnfant);
                    }
                }
            })
    });

    /**
     * Méthode permettant de récupérer les erreurs sur un champs et de les afficher
     * @param erreur - Nom de l'erreur à récupérer
     * @param span - Nom du span
     * @param classe - Nom de la classe
     * @param parent - Nom de l'élément HTML parent
     * @param enfant - Nom de lélément HTML enfant
     */
    function verifErreur(erreur, span, classe, parent, enfant) {
        if (erreur != "") {
            span.textContent = erreur;
            span.className = classe;
            parent.insertBefore(span, enfant.nextSibling);
        }
    };

    /**
     * Méthode permettant de fermer la fenêtre modal
     */
    function fermerModal() {
        csv.className = "modal";
    }

    //Fonction permettant de fermer la modal en cours
    /*function fermerModal() {

        const modalBgP = document.getElementById('modalPreuveBg');
        const fermerPreuve = document.getElementById('fermerPreuve');
        const preuve = document.getElementById('modalPreuve');

        document.getElementById('modalPreuve').className = "modal";

        modalBgP.addEventListener('click', function () {
            clearInput();
            removeAllSpan();
            preuve.className = "modal";
        });
        fermerPreuve.addEventListener('click', function () {
            clearInput();
            removeAllSpan();
            preuve.className = "modal";
        });
    };*/

    /**
    * Méthode permettant de supprimer les balises span afin d'éviter que le message d'erreur
    * ne s'affiche plusieurs fois
    * @param nomFormulaire - C'est le nom du formulaire où les balises span doivent être supprimées
    */
         function removeAllSpan(nomFormulaire) {
            var childs = nomFormulaire.querySelectorAll('span');
            for (var child of childs) {
                child.remove();
            }
        };

    //Fonction permettant un clear des values dans les champs de l'ajout de preuve
    function clearInput() {
        document.getElementById('csv_form_referentielCsv').value = "";
        document.getElementById('csv_form_chapitreCsv').value = "";
        document.getElementById('csv_form_recommandationCsv').value = "";
        document.getElementById('csv_form_pointControleCsv').value = "";
        document.getElementById('csv_form_remediationCsv').value = "";
    };
}




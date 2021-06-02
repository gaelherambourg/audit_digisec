window.onload = function () {
    // Pour ajouter une preuve
    var ajouterPreuve = document.getElementsByClassName('ajouterPreuve');
    var inputHidden = document.getElementById('id_auditControle');
    const preuve = document.querySelector('#modalPreuve');
    const modalBgP = document.querySelector('#modalPreuveBg');
    const fermerPreuve = document.querySelector('#fermerPreuve');

    for (var i = 0; i < ajouterPreuve.length; i++) {
        ajouterPreuve[i].addEventListener("click", function () {
            preuve.className = "modal is-active";
            inputHidden.value = this.id;
            
        });
    }

    modalBgP.addEventListener('click', function () {
        preuve.className = "modal";
    });
    fermerPreuve.addEventListener('click', function () {
        preuve.className = "modal";
    });

    
    showTypePreuve();
    ajoutPreuve();
    
}

function showTypePreuve() {
    var typePreuve = document.getElementById("preuve_form_preuve");
    var textePreuve = document.getElementById('texte-preuve');
    var fichierPreuve = document.getElementById('fichier-preuve');
    var imagePreuve = document.getElementById('image-preuve');

    
    typePreuve.selectedIndex = 0;

    fichierPreuve.style.display = "none";
    imagePreuve.style.display = "none";
    textePreuve.style.display = "flex";

    typePreuve.onchange = function () {

        if (typePreuve.selectedIndex == 0) {
            fichierPreuve.style.display = "none";
            imagePreuve.style.display = "none";
            textePreuve.style.display = "flex";
        }
        if (typePreuve.selectedIndex == 1) {
            fichierPreuve.style.display = "flex";
            imagePreuve.style.display = "none";
            textePreuve.style.display = "none";
        }
        if (typePreuve.selectedIndex == 2) {
            fichierPreuve.style.display = "none";
            imagePreuve.style.display = "flex";
            textePreuve.style.display = "none";
        }

    };

    

}

function ajoutPreuve() {
    const formPreuve = document.getElementById('preuveForm');

    formPreuve.addEventListener("submit", function (e) {
        e.preventDefault();

        //On récupère les champs
        var texte = document.getElementById('preuve_form_texte').value;
        var fichier = document.getElementById('preuve_form_fichier').files[0];
        var image = document.getElementById('preuve_form_image').value;
        var idAuditControle = document.getElementById('id_auditControle').value;
        var token = document.getElementById('preuve_form__token').value;

        var data = new FormData(formPreuve);
        data.append('auditControleId', idAuditControle);
        data.append('fichier', document.getElementById('preuve_form_fichier').files[0]);
        data.append('image', document.getElementById('preuve_form_image').files[0]);
        var ancienneUrl = window.location.pathname;
        var url = window.location.toString().replace(ancienneUrl, ("/audit_digisec/public/preuve/"));

        // Affiche les valeurs
        for (var value of data.values()) {
            console.log(value);
        }
        for (var key of data.keys()) {
            console.log(key);
        }
        fetch(url, {
            method: 'POST',
            body: data
        })
            .then(function (response) {
                return response.json();
            }).then(function (data) {
                console.log(data.resultat);
                if (data.resultat == 'success') {
                    console.log("hehehehehe");
                    console.log(data.preuveForm);
                    console.log(data.adc);
                    fermerModal();
                    //location.reload();
                    clearInput();
                } else {
                    console.log('on est dans AJAX');
                    console.log(data.erreur);
                    console.log(data.adc);
                    console.log("après erreur");
                    console.log(data.test);
                    console.log(data.preuveForm);
                    removeAllSpan();

                    // Traitement si le formulaire retourne une erreur
                    const texteParent = document.getElementById('preuve_form_texte').parentNode;
                    const texteEnfant = document.getElementById('preuve_form_texte');
                    const fichierParent = document.getElementById('preuve_form_fichier').parentNode;
                    const fichierEnfant = document.getElementById('preuve_form_fichier');
                    const imageParent = document.getElementById('preuve_form_image').parentNode;
                    const imageEnfant = document.getElementById('preuve_form_image');

                    // Créer un élément span avec la classe help is-danger
                    const span = document.getElementsByClassName("control");
                    const classe = 'help is-danger'
                    const spanTexte = document.createElement("span");
                    spanTexte.className = classe;
                    const spanFichier = document.createElement("span");
                    spanFichier.className = classe;
                    const spanImage = document.createElement("span");
                    spanImage.className = classe;

                    if (data.erreur != null) {
                        
                        if (data.erreur.texte != "") {
                            spanTexte.textContent = data.erreur.texte;
                            texteParent.insertBefore(spanTexte, texteEnfant.nextSibling);
                        }
                        if (data.erreur.fichier != "") {
                            spanFichier.textContent = data.erreur.fichier;
                            fichierParent.insertBefore(spanFichier, fichierEnfant.nextSibling);
                        }
                        if (data.erreur.image != "") {
                            spanImage.textContent = data.erreur.image;
                            imageParent.insertBefore(spanImage, imageEnfant.nextSibling);
                        }
                    }
                }
            })
    })
}

function fermerModal() {
    document.getElementById('modalPreuve').className = "modal";
}

function removeAllSpan() {
    var childs = document.getElementById('preuveForm').querySelectorAll('span');
    for (var child of childs) {
        child.remove();
    }
}

function clearInput() {
    document.getElementById('preuve_form_texte').value = "";
    document.getElementById('preuve_form_fichier').value = "";
    document.getElementById('preuve_form_image').value = "";
}


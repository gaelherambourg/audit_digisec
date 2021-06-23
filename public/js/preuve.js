window.onload = function () {
    
    // Pour ajouter une preuve
    ouvrirModalPreuve();
    fermerModal();
    showTypePreuve();
    ajoutPreuve();
    couleurRecomandationSelectionnee();
    progressBar();
}

//Fonction permettant d'ouvrir la modal d'ajout de preuve au click sur le bouton ajouter preuve
function ouvrirModalPreuve() {

    let ajouterPreuve = document.getElementsByClassName('ajouterPreuve');
    let inputHidden = document.getElementById('id_auditControle');
    const preuve = document.getElementById('modalPreuve');

    for (let i = 0; i < ajouterPreuve.length; i++) {
        ajouterPreuve[i].addEventListener("click", function () {
            preuve.className = "modal is-active";
            inputHidden.value = this.id;
            
        });
    }
}

//Fonction permettant de naviguer entre les différents type de preuve en cachant/montrant les inputs associés
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

        clearInput();
        removeAllSpan();

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

//Fonction d'ajout de preuve utilisant la méthode Fetch de l'API Fetch permettant l'échange de données avec le serveur (en l'occurence les données de preuve)
function ajoutPreuve() {
    const formPreuve = document.getElementById('preuveForm');

    formPreuve.addEventListener("submit", function (e) {
        e.preventDefault();

        //On instancie un nouveau formData pour y insérer nos données à passer dans le body du fetch
        let data = new FormData();
        data.append('auditControleId', document.getElementById('id_auditControle').value);
        data.append('texte', document.getElementById('preuve_form_texte').value)
        data.append('fichier', document.getElementById('preuve_form_fichier').files[0]);
        data.append('image', document.getElementById('preuve_form_image').files[0]);
        data.append('token', document.getElementById('preuve_form__token').value);

        //On créé l'url à passer au fetch
        let ancienneUrl = window.location.pathname;
        let url = window.location.toString().replace(ancienneUrl, ("/audit_digisec/public/preuve/"));

        fetch(url, {
            method: 'POST',
            body: data
        })
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
                    const classe = 'help is-danger spanMessagePreuve'
                    const spanTexte = document.createElement("span");
                    spanTexte.className = classe;
                    const spanFichier = document.createElement("span");
                    spanFichier.className = classe;
                    const spanImage = document.createElement("span");
                    spanImage.className = classe;

                    if (data.erreur != null) {
                        //Si ereur sur le texte
                        if (data.erreur.texte != "") {
                            spanTexte.textContent = data.erreur.texte;
                            texteParent.insertBefore(spanTexte, texteEnfant.nextSibling);
                        }
                        //Si erreur sur le téléchargement du fichier
                        if (data.erreur.fichier != "") {
                            spanFichier.textContent = data.erreur.fichier;
                            fichierParent.insertBefore(spanFichier, fichierEnfant.nextSibling);
                        }
                        //Si erreur sur le téléchargement de l'image
                        if (data.erreur.image != "") {
                            spanImage.textContent = data.erreur.image;
                            imageParent.insertBefore(spanImage, imageEnfant.nextSibling);
                        }
                    }
                }
            })
    })
}

//Fonction permettant de fermer la modal en cours
function fermerModal() {

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
}

//Fonction permettant de supprimer les éventuels messages d'erreurs situés dans des spans
function removeAllSpan() {
    var childs = document.getElementById('preuveForm').getElementsByClassName('spanMessagePreuve');
    for (var child of childs) {
        child.remove();
    }
}

//Fonction permettant un clear des values dans les champs de l'ajout de preuve
function clearInput() {
    document.getElementById('preuve_form_texte').value = "";
    document.getElementById('preuve_form_fichier').value = "";
    document.getElementById('preuve_form_image').value = "";
}

//Fonction permettant de différencier la couleur de fond du lien dans le menu latéral de la recommandation en cours d'audit
function couleurRecomandationSelectionnee() {

    let elem = document.getElementsByClassName('aSelected');
    let recoEnCours = document.getElementById('idReco').dataset.id;
    for (let i = 0; i < elem.length; i++) {
        if (elem[i].id == recoEnCours) {
            elem[i].style.backgroundColor = "white";
        } else {
            elem[i].style.backgroundColor = "rgb(207, 207, 230)";
        }
            
            
        ;
    }
}

function progressBar(){
    let progressBar = document.getElementById('progressBar');
    if (progressBar.value <= 49) {
        progressBar.className = "progress is-danger is-small";
    }
    if (progressBar.value > 49 && progressBar.value <= 99) {
        progressBar.className = "progress is-warning is-small";
    }
    if (progressBar.value == 100) {
        progressBar.className = "progress is-success is-small";
    }
}

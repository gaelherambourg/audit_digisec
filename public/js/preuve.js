window.onload = function () {
    // Pour ajouter une preuve
    var ajouterPreuve = document.getElementsByClassName('ajouterPreuve');
    var inputHidden = document.getElementById('id_auditControle');
    const preuve = document.querySelector('#modalPreuve');
    const modalBgP = document.querySelector('#modalPreuveBg');
    const fermerPreuve = document.querySelector('#fermerPreuve');

    preuve.className = "modal is-active";

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
        var fichier = document.getElementById('preuve_form_fichier').value;
        var image = document.getElementById('preuve_form_image').value;
        var idAuditControle = document.getElementById('id_auditControle').value;

        // On créer un objet contact
        var preuveObject = new Object();
        preuveObject.texte = texte;
        preuveObject.fichier = fichier;
        preuveObject.image = image;
        preuveObject.auditControleId = idAuditControle;
        console.log(preuveObject);
        // On le transforme en JSON
        json = JSON.stringify(preuveObject);

        var json = 0;
        var url = "preuve/" + json;

        fetch(url, { method: 'POST' })
            .then(function (response) {
                return response.json();
            })
    })

}


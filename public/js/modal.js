window.onload = function () {

    // Cache les éléments adresse
    cacherElement(/adresse*/, 'adresse');
    // Cache les éléments contact
    cacherElement(/contact*/, 'contact');

    // Constantes liées à l'adresse
    const ajouterAdresse = document.querySelector('#ajouterAdresse');
    const adresse = document.querySelector('#modalAdresse');
    const modalBgA = document.querySelector('#modalAdresseBg');
    const fermerAdresse = document.querySelector('#fermerAdresse');
    const formAdresse = document.querySelector("#nouvelle_adresse");
    // Affiche la fenêtre modal
    ajouterAdresse.addEventListener('click', function () {
        adresse.className = "modal is-active";
    });
    // Ferme la fenêtre modal si on clique à côté
    modalBgA.addEventListener('click', function () {
        adresse.className = "modal";
    });
    // Ferme la fenêtre modal si on clique sur le bouton
    fermerAdresse.addEventListener('click', function () {
        adresse.className = "modal";
    });

    formAdresse.addEventListener("submit", function (e) {
        // On stop le comportement normal
        e.preventDefault();
        // On récupère les différents champs
        var libelle = document.getElementById('adresse_form_libelle').value;
        var rue = document.getElementById('adresse_form_rue').value;
        var codePostal = document.getElementById('adresse_form_code_postal').value;
        var ville = document.getElementById('adresse_form_ville').value;
        var formAdresseToken = document.querySelector('#adresse_form__token').value;
        var societeId = urlId();
        // On créer un objet adresse
        var adresseObject = new Object();
        adresseObject.libelle = libelle;
        adresseObject.rue = rue;
        adresseObject.codePostal = codePostal;
        adresseObject.ville = ville;
        adresseObject.societeId = societeId;
        adresseObject.formAdresseToken = formAdresseToken;
        // On le transforme en JSON
        json = JSON.stringify(adresseObject);
        // On l'injecte dans la requête
        var url = "adresse/" + json;
        // Requête Ajax
        fetch(url, { method: 'POST' })
            .then(function (response) {
                return response.json();
            }).then(function (data) {
                if (data.resultat == 'success') {
                    fermerModal();
                    location.reload();
                } else {
                    // On supprime les span
                    removeAllSpan(formAdresse);
                    // Traitement si le formulaire retourne une erreur
                    const libelleParent = document.getElementById('adresse_form_libelle').parentNode;
                    const libelleEnfant = document.getElementById("adresse_form_libelle");
                    const rueParent = document.getElementById('adresse_form_rue').parentNode;
                    const rueEnfant = document.getElementById('adresse_form_rue');
                    const codePostalParent = document.getElementById('adresse_form_code_postal').parentNode;
                    const codePostalEnfant = document.getElementById('adresse_form_code_postal');
                    const villeParent = document.getElementById('adresse_form_ville').parentNode;
                    const villeEnfant = document.getElementById('adresse_form_ville');
                    // Créer un élément span avec la classe help is-danger
                    const classe = 'help is-danger';
                    const spanLibelle = document.createElement("span");
                    const spanRue = document.createElement("span");
                    const spanCp = document.createElement("span");
                    const spanVille = document.createElement("span");
                    // Si le data.erreur n'est pas vide
                    if (data.erreur != null) {
                        // S'il y a une erreur sur le libelle
                        verifErreur(data.erreur.libelle, spanLibelle, classe, libelleParent, libelleEnfant);
                        // S'il y a une erreur sur la rue
                        verifErreur(data.erreur.rue, spanRue, classe, rueParent, rueEnfant);
                        // S'il y a une erreur sur le code postal
                        verifErreur(data.erreur.code_postal, spanCp, classe, codePostalParent, codePostalEnfant);
                        // S'il y a une erreur sur la ville
                        verifErreur(data.erreur.ville, spanVille, classe, villeParent, villeEnfant);
                    }
                }
            })
    });

    // Constantes liées au contact
    const ajouterContact = document.querySelector('#ajouterContact');
    const contact = document.querySelector('#modalContact');
    const modalBgC = document.querySelector('#modalContactBg');
    const fermerContact = document.querySelector('#fermerContact');
    const formContact = document.querySelector('#nouveau_contact');
    // Affiche la fenêtre modal
    ajouterContact.addEventListener('click', function () {
        contact.className = "modal is-active";
    });
    // Ferme la fenêtre modal si on clique à côté
    modalBgC.addEventListener('click', function () {
        contact.className = "modal";
    });
    // Ferme la fenêtre modal si on clique sur le bouton
    fermerContact.addEventListener('click', function () {
        contact.className = "modal";
    });

    formContact.addEventListener("submit", function (e) {
        // On stop le comportement normal
        e.preventDefault();
        // On récupère les différents champs
        var nom = document.getElementById('contact_form_nom_contact').value;
        var prenom = document.getElementById('contact_form_prenom_contact').value;
        var telephone = document.getElementById('contact_form_tel_contact').value;
        var mail = document.getElementById('contact_form_email_contact').value;
        var poste = document.getElementById('contact_form_poste_contact').value;
        var formContactToken = document.querySelector('#contact_form__token').value;
        var societeId = urlId();
        // On créer un objet contact
        var contactObject = new Object();
        contactObject.nom = nom;
        contactObject.prenom = prenom;
        contactObject.telephone = telephone;
        contactObject.mail = mail;
        contactObject.poste = poste;
        contactObject.societeId = societeId;
        contactObject.formContactToken = formContactToken;
        // On le transforme en JSON
        json = JSON.stringify(contactObject);
        // On l'injecte dans la requête
        var url = "contact/" + json;
        // Requête Ajax
        fetch(url, { method: 'POST' })
            .then(function (response) {
                return response.json();
            }).then(function (data) {
                if (data.resultat == 'success') {
                    fermerModal();
                    location.reload();
                } else {
                    // On supprime les span
                    removeAllSpan(formContact);
                    // Traitement si le formulaire retourne une erreur
                    const nomParent = document.getElementById('contact_form_nom_contact').parentNode;
                    const nomEnfant = document.getElementById("contact_form_nom_contact");
                    const prenomParent = document.getElementById('contact_form_prenom_contact').parentNode;
                    const prenomEnfant = document.getElementById("contact_form_prenom_contact");
                    const telephoneParent = document.getElementById('contact_form_tel_contact').parentNode;
                    const telephoneEnfant = document.getElementById("contact_form_tel_contact");
                    const mailParent = document.getElementById('contact_form_email_contact').parentNode;
                    const mailEnfant = document.getElementById("contact_form_email_contact");
                    const posteParent = document.getElementById('contact_form_poste_contact').parentNode;
                    const posteEnfant = document.getElementById("contact_form_poste_contact");
                    // Créer un élément span avec la classe help is-danger
                    const classe = 'help is-danger';
                    const spanNom = document.createElement("span");
                    const spanPrenom = document.createElement("span");
                    const spanTelephone = document.createElement("span");
                    const spanMail = document.createElement("span");
                    const spanPoste = document.createElement("span");
                    // Si le data.erreur n'est pas vide
                    if (data.erreur != null) {
                        // S'il y a une erreur sur le nom
                        verifErreur(data.erreur.nom_contact, spanNom, classe, nomParent, nomEnfant);
                        // S'il y a une erreur sur le prenom
                        verifErreur(data.erreur.prenom_contact, spanPrenom, classe, prenomParent, prenomEnfant);
                        // S'il y a une erreur sur le telephone
                        verifErreur(data.erreur.tel_contact, spanTelephone, classe, telephoneParent, telephoneEnfant);
                        // S'il y a une erreur sur le mail
                        verifErreur(data.erreur.email_contact, spanMail, classe, mailParent, mailEnfant);
                        // S'il y a une erreur sur le poste
                        verifErreur(data.erreur.poste_contact, spanPoste, classe, posteParent, posteEnfant);
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
        contact.className = "modal";
    }

    /**
     * Méthode permettant de récupérer l'id de la page
     * @returns - L'id de la page
     */
    function urlId() {
        // On récupère l'url
        // Supprimons l'éventuel dernier slash de l'URL
        var urlcourante = document.location.href;
        var urlcourante = urlcourante.replace(/\/$/, "");
        // On garde uniquement la dernière partie de l'url qui est l'id
        return urlcourante.substring(urlcourante.lastIndexOf("/") + 1);
    };

    /**
     * Méthode permettant de trouver toutes les balises passée sur le tagParam, et dont l'id contient
     * le regexParam
     * @param regexpParam - C'est le mot à rechercher
     * @param tagParam - C'est la balise à rechercher
     * @returns - Un tableau avec les éléments trouvés
     */
    function getElementsByRegexId(regexpParam, tagParam) {
        // Si aucun nom de balise n'est spécifié, on cherche sur toutes les balises  
        tagParam = (tagParam === undefined) ? '*' : tagParam;
        var elementsTable = new Array();
        for (var i = 0; i < document.getElementsByTagName(tagParam).length; i++) {
            if (document.getElementsByTagName(tagParam)[i].id && document.getElementsByTagName(tagParam)[i].id.match(regexpParam)) {
                elementsTable.push(document.getElementsByTagName(tagParam)[i]);
            }
        }
        return elementsTable;
    };

    /**
     * Méthode permettant de cacher les éléments commençant par le paramètre et ayant le nomAction
     * Enuite un écouteur est ajouté sur le bouton lié à l'action via la méthode 'ajouterListener'
     * @param {} parametre - C'est le paramètre recherché dans la page. (span commençant par ce mot)
     * @param {*} nomAction - C'est le nom du span qu'il faut cacher
     */
    function cacherElement(parametre, nomAction) {
        var nombreSpanAvecParametre = getElementsByRegexId(parametre, "span");
        for (var i = 2; i <= nombreSpanAvecParametre.length; i++) {
            document.getElementById(nomAction + i).style.display = "none";
            ajouterListener(i, nomAction);
        }
    };

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

    /**
     * Méthode permettant d'ajouter un listener sur le nom de bouton passé en paramètre
     * @param i - C'est l'itération sur laquelle l'action doit être effectuée
     * @param nomAction - C'est le nom du bouton à écouter (contact ou adresse) 
     */
    function ajouterListener(i, nomAction) {
        document.getElementById('bouton_detail_' + nomAction + i).addEventListener("click", function (e) {
            e.preventDefault();
            if (document.getElementById(nomAction + i).style.display == "none") {
                document.getElementById(nomAction + i).style.display = "block";
            } else {
                document.getElementById(nomAction + i).style.display = "none";
            }
        })
    };
}

// A TESTER

/*fetch(url, { method: 'POST' })
    .then(function (response) {
        if (response.ok) {
                console.log('Bonne réponse du réseau');
                return response.json();

        } else {
            console.log('Mauvaise réponse du réseau');
        }
    })
    .then(function (data) {
        console.log('On dans la requete AJAX');
    })
    .catch(function (error) {
        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
    });*/


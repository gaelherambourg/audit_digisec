window.onload = function () {

    cacherElement();
    cacherElementContact();

    // Pour ajouter une adresse
    const ajouterAdresse = document.querySelector('#ajouterAdresse');
    const adresse = document.querySelector('#modalAdresse');
    const modalBgA = document.querySelector('#modalAdresseBg');
    const fermerAdresse = document.querySelector('#fermerAdresse');
    const formAdresse = document.querySelector("#nouvelle_adresse");

    ajouterAdresse.addEventListener('click', function () {
        adresse.className = "modal is-active";
    });
    modalBgA.addEventListener('click', function () {
        adresse.className = "modal";
    });
    fermerAdresse.addEventListener('click', function () {
        adresse.className = "modal";
    });

    formAdresse.addEventListener("submit", function (e) {
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
        console.log(adresseObject);
        // On le transforme en JSON
        json = JSON.stringify(adresseObject);
        console.log(json);
        // On l'injecte dans la requête
        var url = "adresse/" + json;
        //var url = "adresse/" + 1;
        console.log(url);
        // Requête Ajax
        fetch(url, { method: 'POST' })
            .then(function (response) {
                return response.json();
            }).then(function (data) {
                if (data.resultat == 'success') {
                    fermerModal();
                    location.reload();
                } else {
                    console.log('token' + data.verifToken);
                    removeAllSpan();
                    // Traitement si le formulaire retourne une erreur
                    const libelleParent = document.getElementById('adresse_form_libelle').parentNode;
                    const libelleEnfant = document.getElementById("adresse_form_libelle")
                    const rueParent = document.getElementById('adresse_form_rue').parentNode;
                    const rueEnfant = document.getElementById("adresse_form_rue")
                    const codePostalParent = document.getElementById('adresse_form_code_postal').parentNode;
                    const codePostalEnfant = document.getElementById("adresse_form_code_postal")
                    const villeParent = document.getElementById('adresse_form_ville').parentNode;
                    const villeEnfant = document.getElementById("adresse_form_ville")

                    // Créer un élément span avec la classe help is-danger
                    const span = document.getElementsByClassName("control");
                    const classe = 'help is-danger'
                    const spanLibelle = document.createElement("span");
                    spanLibelle.className = classe;
                    const spanCp = document.createElement("span");
                    spanCp.className = classe;
                    const spanVille = document.createElement("span");
                    spanVille.className = classe;
                    const spanRue = document.createElement("span");
                    spanRue.className = classe;

                    if (data.erreur != null) {
                        // S'il y a une erreur sur le libelle
                        if (data.erreur.libelle != "") {
                            spanLibelle.textContent = data.erreur.libelle;
                            libelleParent.insertBefore(spanLibelle, libelleEnfant.nextSibling);
                        }
                        // S'il y a une erreur sur la rue
                        if (data.erreur.rue != "") {
                            spanRue.textContent = data.erreur.rue;
                            rueParent.insertBefore(spanRue, rueEnfant.nextSibling);
                        }
                        // S'il y a une erreur sur le code postal
                        if (data.erreur.code_postal != "") {
                            spanCp.textContent = data.erreur.code_postal;
                            codePostalParent.insertBefore(spanCp, codePostalEnfant.nextSibling);
                        }
                        // S'il y a une erreur sur la ville
                        if (data.erreur.ville != "") {
                            spanVille.textContent = data.erreur.ville;
                            villeParent.insertBefore(spanVille, villeEnfant.nextSibling);
                        }
                    }
                }
            })

        function urlId() {
            // On récupère l'url
            // Supprimons l'éventuel dernier slash de l'URL
            var urlcourante = document.location.href;
            var urlcourante = urlcourante.replace(/\/$/, "");
            // On garde uniquement la dernière partie de l'url qui est l'id
            return urlcourante.substring(urlcourante.lastIndexOf("/") + 1);
        }

        function removeAllSpan() {
            var childs = formAdresse.querySelectorAll('span');
            for (var child of childs) {
                child.remove();
            }
        }

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
                console.log(data.id);
                console.log(data.verif);
                console.log(data.test);
                console.log(data.donnee);
                if (data.verif == 'KO') {
                    //console.log('on est dans le if');
                    fermerModal();
                    //location.reload();
                    //alert('erreur sur le form');
                }
            })
            .catch(function (error) {
                console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
            });*/

    });

    function fermerModal() {
        contact.className = "modal";
    }

    // Pour ajouter un contact
    const ajouterContact = document.querySelector('#ajouterContact');
    const contact = document.querySelector('#modalContact');
    const modalBgC = document.querySelector('#modalContactBg');
    const fermerContact = document.querySelector('#fermerContact');
    const formContact = document.querySelector('#nouveau_contact');

    ajouterContact.addEventListener('click', function () {
        contact.className = "modal is-active";
    });

    modalBgC.addEventListener('click', function () {
        contact.className = "modal";
    });
    fermerContact.addEventListener('click', function () {
        contact.className = "modal";
    });

    formContact.addEventListener("submit", function (e) {
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
        console.log(contactObject);
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
                    console.log('on est dans AJAX');
                    console.log(data.erreur);
                    removeAllSpan();
                    // Traitement si le formulaire retourne une erreur
                    const nomParent = document.getElementById('contact_form_nom_contact').parentNode;
                    const nomEnfant = document.getElementById("contact_form_nom_contact")
                    const prenomParent = document.getElementById('contact_form_prenom_contact').parentNode;
                    const prenomEnfant = document.getElementById("contact_form_prenom_contact")
                    const telephoneParent = document.getElementById('contact_form_tel_contact').parentNode;
                    const telephoneEnfant = document.getElementById("contact_form_tel_contact")
                    const mailParent = document.getElementById('contact_form_email_contact').parentNode;
                    const mailEnfant = document.getElementById("contact_form_email_contact")
                    const posteParent = document.getElementById('contact_form_poste_contact').parentNode;
                    const posteEnfant = document.getElementById("contact_form_poste_contact")

                    // Créer un élément span avec la classe help is-danger
                    const span = document.getElementsByClassName("control");
                    const classe = 'help is-danger'
                    const spanNom = document.createElement("span");
                    spanNom.className = classe;
                    const spanPrenom = document.createElement("span");
                    spanPrenom.className = classe;
                    const spanTelephone = document.createElement("span");
                    spanTelephone.className = classe;
                    const spanMail = document.createElement("span");
                    spanMail.className = classe;
                    const spanPoste = document.createElement("span");
                    spanPoste.className = classe;

                    if (data.erreur != null) {
                        // S'il y a une erreur sur le Nom
                        if (data.erreur.nom_contact != "") {
                            spanNom.textContent = data.erreur.nom_contact;
                            nomParent.insertBefore(spanNom, nomEnfant.nextSibling);
                        }
                        if (data.erreur.prenom_contact != "") {
                            spanPrenom.textContent = data.erreur.prenom_contact;
                            prenomParent.insertBefore(spanPrenom, prenomEnfant.nextSibling);
                        }
                        if (data.erreur.tel_contact != "") {
                            spanTelephone.textContent = data.erreur.tel_contact;
                            telephoneParent.insertBefore(spanTelephone, telephoneEnfant.nextSibling);
                        }
                        if (data.erreur.email_contact != "") {
                            spanMail.textContent = data.erreur.email_contact;
                            mailParent.insertBefore(spanMail, mailEnfant.nextSibling);
                        }
                        if (data.erreur.poste_contact != "") {
                            spanPoste.textContent = data.erreur.poste_contact;
                            posteParent.insertBefore(spanPoste, posteEnfant.nextSibling);
                        }
                    }
                }
            })

        function urlId() {
            // On récupère l'url
            // Supprimons l'éventuel dernier slash de l'URL
            var urlcourante = document.location.href;
            var urlcourante = urlcourante.replace(/\/$/, "");
            // On garde uniquement la dernière partie de l'url qui est l'id
            return urlcourante.substring(urlcourante.lastIndexOf("/") + 1);
        }

        function removeAllSpan() {
            var childs = formContact.querySelectorAll('span');
            for (var child of childs) {
                child.remove();
            }
        }

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
                console.log(data.id);
                console.log(data.verif);
                console.log(data.test);
                console.log(data.donnee);
                if (data.verif == 'KO') {
                    //console.log('on est dans le if');
                    fermerModal();
                    //location.reload();
                    //alert('erreur sur le form');
                }
            })
            .catch(function (error) {
                console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
            });*/

    });

    function afficher(obj) {
        document.getElementById(obj).style.display = "block";
    }

    function cacher(obj) {
        document.getElementById(obj).style.display = "none";
    }

    //var childs = formAdresse.querySelectorAll('span');
    //for (var child of childs) {
    //     child.remove();
    //}

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
    }

    function cacherElement() {
        var divCarres = getElementsByRegexId(/adresse*/, "span");
        for (var i = 2; i <= divCarres.length; i++) {
            document.getElementById('adresse' + i).style.display = "none";
            console.log(i);
            console.log('bouton_detail_adresse' + i);
            ajouterListener(i);
        }
    }

    function cacherElementContact() {
        var divCarres = getElementsByRegexId(/contact*/, "span");
        for (var i = 2; i <= divCarres.length; i++) {
            document.getElementById('contact' + i).style.display = "none";
            console.log(i);
            console.log('bouton_detail_contact' + i);
            ajouterListenerContact(i);
        }
    }

    function ajouterListener (i) {
        document.getElementById('bouton_detail_adresse' + i).addEventListener("click", function (e) {
            e.preventDefault();
            console.log(i);
            console.log('adresse' + i);
            if (document.getElementById('adresse' + i).style.display == "none") {
                afficher('adresse' + i);
            } else {
                cacher('adresse' + i);
            }
        })
    }

    function ajouterListenerContact (i) {
        document.getElementById('bouton_detail_contact' + i).addEventListener("click", function (e) {
            e.preventDefault();
            console.log(i);
            console.log('contact' + i);
            if (document.getElementById('contact' + i).style.display == "none") {
                afficher('contact' + i);
            } else {
                cacher('contact' + i);
            }
        })
    }
    /*btnDetail.addEventListener("click", function (e) {
      e.preventDefault();
      if (document.getElementById('adresse' + i).style.display == "none") {
          afficher('adresse' + i);
      } else {
          cacher('adresse' + i);
      }
  })*/

}

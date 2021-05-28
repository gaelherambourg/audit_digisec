window.onload = function () {
    // Pour ajouter une adresse
    const ajouterAdresse = document.querySelector('#ajouterAdresse');
    const adresse = document.querySelector('#modalAdresse');
    const modalBgA = document.querySelector('#modalAdresseBg');
    const fermerAdresse = document.querySelector('#fermerAdresse');
    const btAdresse = document.querySelector('#boutonAdresse');

    ajouterAdresse.addEventListener('click', function () {
        adresse.className = "modal is-active";
    });
    modalBgA.addEventListener('click', function () {
        adresse.className = "modal";
    });
    fermerAdresse.addEventListener('click', function () {
        adresse.className = "modal";
    });

    // Pour ajouter un contact
    const ajouterContact = document.querySelector('#ajouterContact');
    const contact = document.querySelector('#modalContact');
    const modalBgC = document.querySelector('#modalContactBg');
    const fermerContact = document.querySelector('#fermerContact');

    ajouterContact.addEventListener('click', function () {
        contact.className = "modal is-active";
        myFunction();
    });

    modalBgC.addEventListener('click', function () {
        contact.className = "modal";
    });
    fermerContact.addEventListener('click', function () {
        contact.className = "modal";
    });

    // INP

    const form = document.querySelector('#nouvelle_adresse');

    if (form.addEventListener) {
        form.addEventListener("submit", stop(), false);  //Modern browsers
    } else if (form.attachEvent) {
        form.attachEvent('onsubmit', stop());            //Old IE
    }

    function stop() {
        document.querySelector("#nouvelle_adresse").addEventListener("submit", function (e) {
            e.preventDefault();
            // On récupère les différents champs
            var libelle = document.getElementById('adresse_form_libelle').value;
            var rue = document.getElementById('adresse_form_rue').value;
            var codePostal = document.getElementById('adresse_form_code_postal').value;
            var ville = document.getElementById('adresse_form_ville').value;
            // On créer un objet adresse
            var adresseObject = new Object();
            adresseObject.libelle = libelle;
            adresseObject.rue = rue;
            adresseObject.codePostal = codePostal;
            adresseObject.ville = ville;
            console.log(adresseObject);
            // On le transforme en JSON
            json = JSON.stringify(adresseObject);
            console.log(json);
            // On l'injecte dans la requête
            var url = "adresse/" + json;
            console.log(url);
            // Requête Ajax
            fetch(url, { method: 'POST' })
                .then(function (response) {
                    return response.json();
                }).then(function (data) {
                    console.log('On dans la requete AJAX');
                    console.log(data.id);
                    console.log(data.verif);
                    console.log(data.test);
                    console.log(data.avant);
                    console.log(data.apres);
                    console.log(data.erreur);
                    if (data.verif == 'OK') {
                        console.log('on est dans le if');
                        fermerModal();
                        location.reload();
                    } else {
                        
                    }
                })

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
            adresse.className = "modal";
        }
    }
}

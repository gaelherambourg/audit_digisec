window.onload = function () {
    // Pour ajouter une adresse
    const ajouterAdresse = document.querySelector('#ajouterAdresse');
    const adresse = document.querySelector('#modalAdresse');
    const modalBgA = document.querySelector('#modalAdresseBg');
    const fermerAdresse = document.querySelector('#fermerAdresse');

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
    });

    modalBgC.addEventListener('click', function () {
        contact.className = "modal";
    });
    fermerContact.addEventListener('click', function () {
        contact.className = "modal";
    });
}
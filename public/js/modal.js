window.onload = function () {
    const ajouterContact = document.querySelector('#ajouterContact');
    const ajouterAdresse = document.querySelector('#ajouterAdresse');
    const contact = document.querySelector('#modalContact');
    const adresse = document.querySelector('#modalAdresse');
    const modalBg = document.querySelector('.modal-background');
    const modal = document.querySelector('.modal');
    const close = document.querySelector('.delete');
    //const annuler = document.querySelector('#annuler');

    console.log(ajouterContact);

    /*ajouterContact.addEventListener('click', function () {
        console.log(modal.id);
        if(modal.id == 'ajoutContact') {
            modal.classList.add('is-active');
        };
    });*/

    ajouterContact.addEventListener('click', function () {
        contact.className = "modal is-active";
    });


    modalBg.addEventListener('click', function () {
        contact.className = "modal";
        adresse.className = "modal";
    });

    close.addEventListener('click', function () {
        contact.className = "modal";
        adresse.className = "modal";
    });
    /*
    annuler.addEventListener('click', function () {
        modal.classList.remove('is-active');
    });
    */
    ajouterAdresse.addEventListener('click', function () {
            adresse.className = "modal is-active";
    });
}
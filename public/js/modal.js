window.onload = function () {
    const ajouterContact = document.querySelector('#ajouterContact');
    const modalBg = document.querySelector('.modal-background');
    const modal = document.querySelector('.modal');
    const close = document.querySelector('.delete');
    const annuler = document.querySelector('#annuler');

    ajouterContact.addEventListener('click', function () {
        modal.classList.add('is-active');
    });

    modalBg.addEventListener('click', function () {
        modal.classList.remove('is-active');
    });

    close.addEventListener('click', function () {
        modal.classList.remove('is-active');
    });

    annuler.addEventListener('click', function () {
        modal.classList.remove('is-active');
    });
}
/* const editarPerfil = document.getElementById('editar-perfil');
const modal = document.getElementById('modal');
const cerrarModal = document.getElementById('close-modal');
const modalInfo = document.getElementById('modal-info'); // Referencia al modal-info

editarPerfil.addEventListener('click', function () {
    // Verifica si modal-info está visible (NO tiene la clase 'hide')
    if (!modalInfo.classList.contains('hide')) {
        modalInfo.classList.add('hide'); // Oculta modal-info si está abierto
    }
    
    modal.classList.toggle('hide'); // Abre modal de edición de perfil
});

cerrarModal.addEventListener('click', function () {
    modal.classList.add('hide'); // Cierra modal de edición de perfil
}); */


$(document).ready(function () {
    $("#editar-perfil").click(function () {
        // Verifica si modal-info está visible (NO tiene la clase 'hide')
        if (!$("#modal-info").hasClass("hide")) {
            $("#modal-info").addClass("hide"); // Oculta modal-info si está abierto
        }
        
        $("#modal").toggleClass("hide"); // Abre o cierra el modal de edición de perfil
    });

    $("#close-modal").click(function () {
        $("#modal").addClass("hide"); // Cierra el modal de edición de perfil
    });
});

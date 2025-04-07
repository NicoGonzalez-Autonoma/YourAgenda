$(document).ready(function () {
    // Modal de confirmación para eliminar
    let deleteModal = `
    <div class="modal hide" id="delete-contact-modal">
        <div class="container-modal-info">
            <div class="title">
                <h1>Eliminar Contacto</h1>
                <img src="../assets/close.png" alt="Close Modal" id="close-delete-modal" style="cursor:pointer">
            </div>
            <div class="img-user">
                <img src="../assets/alert-closed.png" alt="">
            </div>
            <h3>¿Estás seguro que quieres eliminar este contacto?</h3>
            <h4>Esta acción no se puede deshacer</h4>
            
            <input type="hidden" id="delete-contact-id">
            
            <div class="botones-eliminar">
                <button id="confirm-delete">Sí, eliminar</button>
                <button id="cancel-delete">Cancelar</button>
            </div>
        </div>
    </div>`;

    // Agregar el modal al documento
    $('body').append(deleteModal);

    // Mostrar modal al hacer clic en "Eliminar"
    $(document).on('click', '.delete-contact', function () {
        const contactId = $(this).data('id');
        $('#delete-contact-id').val(contactId);
        $('#delete-contact-modal').removeClass('hide');
    });

    // Cerrar modal
    $(document).on('click', '#close-delete-modal, #cancel-delete', function () {
        $('#delete-contact-modal').addClass('hide');
    });

    // Confirmar eliminación
    $(document).on('click', '#confirm-delete', function () {
        const contactId = $('#delete-contact-id').val();

        $.ajax({
            url: '../Backend/delete_contact.php',
            type: 'POST',
            data: {
                contact_id: contactId
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Cerrar modal
                    $('#delete-contact-modal').addClass('hide');

                    

                    // Recargar la página para actualizar la lista de contactos
                    location.reload();
                } else {
                    alert(response.message || 'Error al eliminar el contacto');
                }
            },
            error: function () {
                alert('Error de conexión. Inténtalo de nuevo más tarde.');
            }
        });
    });
});
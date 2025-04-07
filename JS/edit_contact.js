$(document).ready(function() {
    // Modal para editar contacto
    let editModal = `
    <div class="modal hide" id="edit-contact-modal" style="width: 220vh; margin-top: 40px; margin-bottom: 80px">
        <form id="edit-contact-form" class="container-modal" enctype="multipart/form-data">
            <div class="title">
                <h1>Editar Contacto</h1>
                <img src="../assets/close.png" alt="Close Modal" id="close-edit-modal" style="cursor:pointer">
            </div>
            
            <input type="hidden" name="contact_id" id="edit-contact-id">
            <input type="hidden" name="action" value="update_contact">
            
            <label for="edit-nombre">Nombre:</label>
            <input type="text" name="nombre" id="edit-nombre" required>
            
            <label for="edit-celular">Teléfono:</label>
            <input type="text" name="celular" id="edit-celular">
            
            <label for="edit-direccion">Dirección:</label>
            <input type="text" name="direccion" id="edit-direccion">
            
            <label for="edit-etiqueta">Etiqueta:</label>
            <input type="text" name="etiqueta" id="edit-etiqueta">
            
            <div class="favorite-container">
                <label for="edit-is-favorite">Favorito:</label>
                <input type="checkbox" name="is_favorite" id="edit-is-favorite">
            </div>
            
            <label for="edit-profile-image">Foto de perfil:</label>
            <div class="container-input">
                <input type="file" name="profile_image" id="edit-profile-image" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados"/>
                <label for="edit-profile-image">
                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span class="iborrainputfile">Archivo</span>
                </label>
            </div>

            
            <button type="submit">Actualizar contacto</button>
        </form>
    </div>`;
    
    // Agregar el modal al documento
    $('body').append(editModal);
    
    // Mostrar modal al hacer clic en "Editar"
    $(document).on('click', '.edit-contact', function() {
        const contactId = $(this).data('id');
        
        // Limpiar formulario
        $('#edit-contact-form')[0].reset();
        $('#current-profile-image').empty();
        
        // Mostrar modal
        $('#edit-contact-modal').removeClass('hide');
        
        // Cargar datos del contacto
        $.ajax({
            url: '../backend/update_contact.php',
            type: 'POST',
            data: {
                action: 'get_contact',
                contact_id: contactId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const contact = response.contact;
                    
                    // Rellenar el formulario con los datos del contacto
                    $('#edit-contact-id').val(contact.id);
                    $('#edit-nombre').val(contact.name);
                    $('#edit-celular').val(contact.phone);
                    $('#edit-direccion').val(contact.address);
                    $('#edit-etiqueta').val(contact.label);
                    
                    // Checkbox para favoritos
                    if (parseInt(contact.is_favorite) === 1) {
                        $('#edit-is-favorite').prop('checked', true);
                    }
                    
                    // Mostrar imagen actual si existe
                    /* if (contact.profile_image) {
                        $('#current-profile-image').html(`
                            <img src="../uploads/contacts/${contact.profile_image}" alt="${contact.name}" style="max-width: 100px; max-height: 100px;">
                        `);
                    } else {
                        $('#current-profile-image').html(`
                            <img src="../assets/contacts.svg" alt="${contact.name}" style="max-width: 100px; max-height: 100px;">
                        `);
                    } */
                } else {
                    alert(response.message || 'Error al cargar los datos del contacto');
                }
            },
            error: function() {
                alert('Error de conexión. Inténtalo de nuevo más tarde.');
            }
        });
    });
    
    // Cerrar modal
    $(document).on('click', '#close-edit-modal', function() {
        $('#edit-contact-modal').addClass('hide');
    });
    
    // Manejar envío del formulario
    $('#edit-contact-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '../backend/update_contact.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    
                    $('#edit-contact-modal').addClass('hide');
                    // Recargar la página para mostrar los cambios
                    location.reload();
                } else {
                    alert(response.message || 'Error al actualizar el contacto');
                }
            },
            error: function() {
                alert('Error de conexión. Inténtalo de nuevo más tarde.');
            }
        });
    });
    
    // Para el cambio de texto en el input de archivos
    $('input[type="file"]').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('label').find('.iborrainputfile').html(fileName ? fileName : 'Archivo');
    });
});
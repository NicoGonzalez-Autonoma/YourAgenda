
document.addEventListener('DOMContentLoaded', function () {
  // Seleccionar el botón "Crear Contacto" que tiene id="contacts"
  const crearContactoBtn = document.getElementById('contacts');

  // Verificar si el botón existe
  if (crearContactoBtn) {
    crearContactoBtn.addEventListener('click', function () {
      // Modificar los estilos del contenedor principal
      const contactsContainer = document.querySelector('.contacts');
      const searchContainer = document.querySelector('.search-container');
      const body = document.body;

      body.classList.toggle('creating-contact-mode');

      // Si estamos en modo de creación de contacto
      if (body.classList.contains('creating-contact-mode')) {
        // Cambiar el título del contenedor de contactos
        if (contactsContainer) {
          const title = contactsContainer.querySelector('h1');
          if (title) {
            title.textContent = 'Crear Contacto';
          }

          // Obtener el contenedor de la lista de contactos
          const contactsList = contactsContainer.querySelector('.contacts-list');
          if (contactsList) {
            // Guardar el contenido original para restaurarlo después
            contactsList.setAttribute('data-original-content', contactsList.innerHTML);
            contactsList.innerHTML = `
                <div class="contact-form-container">
                <div class="contact-form">
                    <form action="../Backend/create_contact..php" method="POST" enctype="multipart/form-data">
                    <div class="form-header">
                        <div class="back-button">
                            <button type="button" id="cancel-create-contact">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 18l-6-6 6-6" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="actions">
                            <button type="button" class="star-btn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" stroke="#FFD700" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button type="submit" class="save-btn">Guardar</button>
                        </div>
                    </div>
                    <div class="profile-section">
                        <div class="profile-pic">
                            <img src="../assets/contacts.svg" alt="Foto de perfil" id="profile-preview">
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;">
                            <button type="button" class="add-photo" onclick="document.getElementById('profile_image').click()">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" fill="#4285F4"/>
                                    <path d="M12 8v8M8 12h8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" placeholder="" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="tel" id="celular" name="celular" placeholder="">
                        </div>
                        
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion" name="direccion" placeholder="">
                        </div>
                        
                        <div class="form-group">
                            <label for="etiqueta">Etiqueta</label>
                            <input type="text" id="etiqueta" name="etiqueta" placeholder="">
                        </div>
                    </div>
                    </form>
                </div>
                </div>
                `;

            // Añadir evento para el botón de cancelar
            const cancelBtn = contactsList.querySelector('#cancel-create-contact');
            if (cancelBtn) {
              cancelBtn.addEventListener('click', function () {
                // Restaurar el estado original
                body.classList.remove('creating-contact-mode');
                // Recuperar el contenido original
                contactsList.innerHTML = contactsList.getAttribute('data-original-content');
                // Restaurar el título original
                if (title) {
                  title.textContent = `Hola, ${document.querySelector('.contacts h1').textContent.replace('Hola, ', 'h1')}`;
                }
              });
            }

            // Añadir evento para previsualizar la imagen de perfil
            const profileInput = contactsList.querySelector('#profile_image');
            const profilePreview = contactsList.querySelector('#profile-preview');

            if (profileInput && profilePreview) {
              profileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                  const reader = new FileReader();
                  reader.onload = function (e) {
                    profilePreview.src = e.target.result;
                  }
                  reader.readAsDataURL(file);
                }
              });
            }
          }
        }

      }
      // Si no estamos en modo de creación, restaurar todo
      else {
        // Obtener el contenedor de la lista de contactos
        const contactsList = contactsContainer.querySelector('.contacts-list');
        if (contactsList && contactsList.hasAttribute('data-original-content')) {
          // Recuperar el contenido original
          contactsList.innerHTML = contactsList.getAttribute('data-original-content');
          // Restaurar el título original
          const title = contactsContainer.querySelector('h1');
          if (title) {
            title.textContent = `Hola, ${document.querySelector('.contacts h1').textContent.replace('Crear Contacto', '')}`;
          }
        }

        // Restaurar el estilo de la barra de búsqueda
        if (searchContainer) {
          searchContainer.style.backgroundColor = '';
          searchContainer.style.boxShadow = '';
        }
      }
    });
  }

  // Agregar estilos CSS dinámicamente para el formulario
  const style = document.createElement('style');
  style.textContent = `
      .contact-form-container {
        width: 100%;
        max-width: 100%;
        padding: 0;
        background: #fff;
      }
      
      .contact-form {
        display: flex;
        flex-direction: column;
        width: 100%;
      }
      
      .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        width: 100%;
      }
      
      .back-button button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
      }
      
      .actions {
        display: flex;
        align-items: center;
        gap: 15px;
      }
      
      .star-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
      }
      
      .save-btn {
        background: #4285F4;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 20px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
      }
      
      .profile-section {
        display: flex;
        justify-content: center;
        padding: 20px 0 30px;
      }
      
      .profile-pic {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        background-color: #e0e0e0;
      }

      #profile-preview{
      margin-top:-30px;
      }
      
      .profile-pic img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      
      .add-photo {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: transparent;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .form-body {
        display: flex;
        flex-direction: column;
        padding: 0 20px;
        gap: 20px;
      }
      
      .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
      }
      
      .form-group label {
        font-size: 14px;
        color: #666;
        font-weight: normal;
      }
      
      .form-group input[type="text"],
      .form-group input[type="tel"],
      .form-group input[type="email"] {
        padding: 12px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 16px;
        background-color: #f0f0f0;
      }
    `;
  document.head.appendChild(style);
});
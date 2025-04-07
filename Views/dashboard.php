<?php
session_start();
require_once '../backend/config.php';
require_once '../Backend/list_contacs.php'; 


if (!isset($_SESSION['id'])) {
    header("Location: ../views/login.php");
    exit();
}

$database = new DbConfig();
$conn = $database->getConnection();

$user_id = $_SESSION['id'];
$contacts = getUserContacts($conn, $user_id);
$contactCount = countUserContacts($conn, $user_id);
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Agenda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../assets/imgs/Logo_Final-removebg-preview.2.png" type="image/x-icon">
    <link rel="icon" type="image/png" href=".././assets/logo.png">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <did="result">
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
    </div>

    <div class="modal-info hide" id="modal-info">
        <div class="container-modal-info">
            <div class="title">
                <h1>Mi perfil</h1>
                <img src="../assets/close.png" alt="Close Modal" id="close-modal-info" style="cursor:pointer">
            </div>
            <div class="img-user">
                <img src="../assets/contacts.svg" alt="">
            </div>

            <label for="edad">Nombre :</label>
                <h3><?php echo htmlspecialchars($_SESSION['name']); ?></h3>

            <label for="edad">email : </label>
                <h3><?php echo htmlspecialchars($_SESSION['email']); ?></h3>
            
            
            <button type="submit" class="btn-update" id="editar-perfil">Actualizar datos</button>
            <button type="submit" class="btn-close" id="mostrar-eliminar">Eliminar cuenta</button>
        </div>
    </div>
    <div class="eliminar hide" id="eliminar">
            <div class="contenedor-eliminar">
                <img src="../assets/alert-closed.png" alt="" srcset="">
                <div>
                    <h3>¿Estás seguro que quieres eliminar tu cuenta?</h3>
                    <h4>Una vez eliminada su cuenta<br> ya no podra acceder a sus contactos y a su información</h4>
                </div>    
                <div class="botones-eliminar">
                    <form action="../backend/eliminar_user.php" method="POST">
                        <button name="eliminar-usuario" type="submit" id="eliminar-user" >Estoy seguro.</button>
                    </form>
                    <button id="cerrar-eliminar">No estoy seguro.</button>
                </div>

            </div>
    </div>

    <div class="modal-info-developers hide" id="modal-info">
        <div class="container-modal-info">
            <div class="title">
                <h1>Desarrolladores</h1>
                <img src="../assets/close.png" alt="Close Modal" id="close-modal-info" style="cursor:pointer">
            </div>
            <div class="img-user">
                <img src="../assets/contacts.svg" alt="">
            </div>

            <label for="edad">Nombre :</label>
                <h3><?php echo htmlspecialchars($_SESSION['name']); ?></h3>

            <label for="edad">email : </label>
                <h3><?php echo htmlspecialchars($_SESSION['email']); ?></h3>
            
            
            <button type="submit" class="btn-update" id="editar-perfil">Actualizar datos</button>
            <button type="submit" class="btn-close" id="mostrar-eliminar">Eliminar cuenta</button>
        </div>
    </div>
    
    <div class="modal hide" id="modal">
        <form action="../backend/update-user.php" method="POST" class="container-modal"
            enctype="multipart/form-data">
            <div class="title">
                <h1>Actualizar Información</h1>
                <img src="../assets/close.png" alt="Close Modal" id="close-modal" style="cursor:pointer">
            </div>

            <h3>Usuario:
                <h3><?php echo htmlspecialchars($_SESSION['name']); ?></h3>
            </h3>


            <label for="name">Nuevo Nombre: </label>
            <input type="text" name="name" id="name">

            <label for="contraseña">Nueva contraseña: </label>
            <input type="password" name="contrasena" id="contraseña">

            <label for="edad">Foto de perfil: </label>
            <div class="container-input">
                <input type="file" name="imagen-perfil" id="file-1" class="inputfile inputfile-1"
                    data-multiple-caption="{count} archivos seleccionados"/>
                <label for="file-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17"
                        viewBox="0 0 20 17">
                        <path
                            d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                        </path>
                    </svg>
                    <span class="iborrainputfile">Archivo</span>
                </label>
            </div>

            <button type="submit">Actualizar</button>

        </form>
    </div>

    <div class="sidebar">
        <div class="logo-details">
            <img class="opcions" src="../assets/options.svg" alt="opcions" id="btn" >
            <div class="logo_name">Agenda</div>
        </div>
        <ul class="nav-list">
            <li>
                <div class="editar" id="contacts">
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="links_name" >Crear Contacto</span>
                </div>
                <span class="tooltip">Crear Contacto</span>
            </li>

            <li>
                <div class="editar" id="x">
                    <i class="fa-regular fa-star"></i>
                    <span class="links_name">Favoritos</span>
                </div>
                <span class="tooltip">Favoritos</span>
            </li>

            <li>
                <div class="calc" id="calculadora-inp">
                    <i class="fa-solid fa-tag"></i>
                    <span class="links_name">Etiquetas</span>
                </div>
                <span class="tooltip">Etiquetas</span>
            </li>
            <li class="direccions">
                <a href="./registroDatos.php" class="direccions">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span class="links_name">Direcciones</span>
                </a>                            
                <span class="tooltip">Direcciones</span>
            </li>
            <li class="cerrar-sesion">
                <a href="../backend/logout.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="links_name">Cerrar sesion</span>
                </a>
                <span class="tooltip">Cerrar sesion</span>
            </li>



        </ul>
    </div>

    


    <div class="modal-info hide" id="modal-developers">
        <div class="container-modal-info-ing">
            <div class="tittle-ing">
                <h1>Desarrolladores</h1>
                <img class="close-img" src="../assets/close.png"  alt="Close Modal" id="close-modal-developers" style="cursor:pointer">
            </div>
            <div class="ings">
                
                <div class="ing1">
                    <div class="img-user">
                        <img src="../assets/Nico.jpg" alt="">
                    </div>
                    <h3>Nicolás González García</h3>
                    <h4>Desarrollado encargado en Frontend, Documentación y backend.</h4>
                </div>
                
                <div class="ing1">
                    <div class="img-user">
                        <img src="../assets/Blanco.jpeg" alt="">
                    </div>
                    <h3>Santiago Blanco Agudelo</h3>
                    <h4>Desarrollado encargado en Frontend, Documentación y backend.</h4>
                </div>          
            </div>
            
            
            <div class="extra">
                <p>Para mayor información, contacte a soporte:</p>
                <p>nicolas.gonzalez@autonoma.edu.com</p> 
                <p>santiago.blancoa@autonoma.edu.com</p>
                
                <p>+57 3225168814</p> 
                <p>+57 312 8517630</p> 
                <button type="submit" class="btn-close" id="close-developers">Cerrar</button>
            </div>
        </div>
    </div>


    <div class="search-container">
        <img src="../assets/search.svg" alt="Buscar" class="lupa">
        <input type="text" placeholder="Buscar">
        <img src="../assets/admin-user.png" alt="" class="img1" id="mostrar-info" style="cursor:pointer;" >

        <div class="opcions-search" >
            <img src="../assets/help.svg" id="developers"alt="">
            <img src="../assets/admin-user.png"  id="developers" alt="">
        </div>
    </div>
    
    <div class="contacts">
        <h1>Hola, <?= htmlspecialchars($_SESSION['name']) ?></h1>
        <h2>Total Contactos: <?= $contactCount ?></h2>

        <div class="contacts-list">
            <?php if (empty($contacts)): ?>
                <p>No tienes contactos aún.</p>
            <?php else: ?>
                <?php foreach ($contacts as $contact): ?>
                    <div class="contact-card">
                        <div class="contact-avatar">
                            <?php if (!empty($contact['profile_image'])): ?>
                                <img src="../uploads/contacts/<?= htmlspecialchars($contact['profile_image']) ?>" alt="<?= htmlspecialchars($contact['name']) ?>">
                            <?php else: ?>
                                <img src="../assets/contacts.svg" alt="<?= htmlspecialchars($contact['name']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="contact-info">
                            <h3><?= htmlspecialchars($contact['name']) ?></h3>
                            <p><?= htmlspecialchars($contact['phone']) ?></p>
                            <?php if (!empty($contact['label'])): ?>
                                <span class="contact-label"><?= htmlspecialchars($contact['label']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="contact-actions">
                            <?php if (!empty($contact['is_favorite'])): ?>
                                <i class="fa-solid fa-star favorite-icon"></i>
                            <?php endif; ?>
                            <button class="edit-contact" data-id="<?= $contact['id'] ?>">Editar</button>
                            <button class="delete-contact" data-id="<?= $contact['id'] ?>">Eliminar</button>
                        
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
    </div>
    </div>
    


    
    <script src="../js/sidebar.js"></script>
    <script src="../js/modal_update.js"></script>
    <script src="../js/modal-info.js"></script>
    <script src="../js/modal-help.js"></script>
    <script src="../js/modal-eliminar.js"></script>
    <script src="../js/styles-contacs.js"></script>
    <script src="../js/edit_contact.js"></script>
    <script src="../js/delete_contact.js"></script>

    <!-- NO ELIMINAR -->
    <script>
        document.getElementById("mostrar-info").addEventListener("click", function() {
            let modal = document.getElementById("modal-info"); // Definir correctamente el modal
            modal.classList.toggle('hide');
        });
        document.querySelector('.menu-btn').addEventListener('click', () => document.querySelector('.sidebar').classList.toggle('open'));
        
        document.getElementById("developers").addEventListener("click", function() {
            let modal = document.getElementById("modal-info-ing"); // Definir correctamente el modal
            modal.classList.toggle('hide');
        });
    </script>



</body>
</html>
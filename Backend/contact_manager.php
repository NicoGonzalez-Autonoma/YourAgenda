<?php
class ContactManager
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /* Obtiene un contacto por su id*/
    public function getContactById($contactId, $userId)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM contacts WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $contactId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /* Actualizo el contacto  */
    public function updateContact($contactId, $userId, $data, $profileImage = null)
    {
        try {
            // Primero se verificaria que el contacto pertenezca al usuario
            $stmt = $this->conn->prepare("SELECT id FROM contacts WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $contactId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return ["success" => false, "message" => "No tienes permiso para editar este contacto"];
            }

            // Si se quiere agregar una imagen de perfil nueva 
            $imageUpdate = "";
            $params = [
                ':id' => $contactId,
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':address' => $data['address'],
                ':label' => $data['label'],
                ':is_favorite' => isset($data['is_favorite']) ? 1 : 0
            ];

            if ($profileImage !== null) {
                $imageUpdate = ", profile_image = :profile_image";
                $params[':profile_image'] = $profileImage;
            }

            $sql = "UPDATE contacts SET 
                    name = :name, 
                    phone = :phone, 
                    address = :address, 
                    label = :label, 
                    is_favorite = :is_favorite" .
                $imageUpdate .
                " WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute($params);

            return [
                "success" => true,
                "message" => "Contacto actualizado correctamente"
            ];
        } catch (PDOException $e) {
            return [
                "success" => false,
                "message" => "Error al actualizar el contacto: " . $e->getMessage()
            ];
        }
    }

    
    /* Almacena la imagen de perfil si se actualiza */
    public function processProfileImage($file)
    {
        if (isset($file) && $file['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif']; /* Formatos que se pueden permitir */
            $filename = $file['name'];
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            // Verificar extensión
            if (in_array($file_ext, $allowed)) {
                // Crear un nombre de archivo único
                $new_filename = uniqid('contact_') . '.' . $file_ext;
                $upload_path = '../uploads/contacts/' . $new_filename;

                // Crear la carpeta de las imagenes de contactos si no existe o no está disponible 
                if (!file_exists('../uploads/contacts/')) {
                    mkdir('../uploads/contacts/', 0777, true);
                }

                // Subir la imagen
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    return $new_filename;
                }
            }
        }

        return null;
    }




    /* Elimina un contacto */
    public function deleteContact($contactId, $userId)
    {
        try {
            // Primero verificamos que el contacto pertenezca al usuario
            $stmt = $this->conn->prepare("SELECT id, profile_image FROM contacts WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $contactId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $contact = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$contact) {
                return ["success" => false, "message" => "No tienes permiso para eliminar este contacto"];
            }

            // Eliminar la imagen del contacto si existe
            if (!empty($contact['profile_image'])) {
                $imagePath = '../uploads/contacts/' . $contact['profile_image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Eliminar el contacto
            $stmt = $this->conn->prepare("DELETE FROM contacts WHERE id = :id");
            $stmt->bindParam(':id', $contactId, PDO::PARAM_INT);
            $stmt->execute();

            return [
                "success" => true,
                "message" => "Contacto eliminado correctamente"
            ];
        } catch (PDOException $e) {
            return [
                "success" => false,
                "message" => "Error al eliminar el contacto: " . $e->getMessage()
            ];
        }
    }
}

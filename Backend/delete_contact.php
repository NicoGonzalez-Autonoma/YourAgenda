<?php
session_start();
require_once './config.php';
require_once './contact_manager.php';

// Añade esto al principio del archivo para ver errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica que estés recibiendo los datos

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['contact_id']) || empty($_POST['contact_id'])) {
        echo json_encode(['success' => false, 'message' => 'ID de contacto no válido']);
        exit();
    }
    
    $database = new DbConfig();
    $conn = $database->getConnection();
    $contactManager = new ContactManager($conn);
    
    $contact_id = intval($_POST['contact_id']);
    $user_id = $_SESSION['id'];
    
    // Eliminar el contacto
    $result = $contactManager->deleteContact($contact_id, $user_id);
    
    echo json_encode($result);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Solicitud inválida']);
exit();
?>
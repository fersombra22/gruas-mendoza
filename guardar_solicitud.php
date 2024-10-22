<?php
// Configuración de la base de datos
$servername = "localhost"; 
$username = "root"; 
$password = "root"; 
$dbname = "gruas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $email = $_POST['email'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $camion_id = $_POST['camion_seleccionado'] ?? null; // ID del camión seleccionado

    // Verificar campos obligatorios
    if (empty($nombre) || empty($apellido) || empty($telefono) || empty($email) || empty($descripcion)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Preparar y vincular la declaración para insertar la solicitud
    $stmt = $conn->prepare("INSERT INTO solicitudes (nombre, apellido, telefono, email, descripcion, camion_id) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error en la preparación: " . $conn->error);
    }

    $stmt->bind_param("sssssi", $nombre, $apellido, $telefono, $email, $descripcion, $camion_id);

    // Ejecutar la declaración para insertar la solicitud
    if ($stmt->execute()) {
        // Actualizar el estado del camión solo si se ha seleccionado un camión
        if ($camion_id) {
            $stmt_camion = $conn->prepare("UPDATE camiones SET estado = 'ocupado', tiempo_ocupacion = 60 WHERE id = ?");
            $stmt_camion->bind_param("i", $camion_id);
            $stmt_camion->execute();
            $stmt_camion->close();
        }
        echo "Solicitud guardada correctamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la declaración y conexión
    $stmt->close();
} else {
    die("Error: El formulario no se envió.");
}

$conn->close();
?>

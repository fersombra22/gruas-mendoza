<?php
// Mostrar datos recibidos para depuración
echo "<h1>Datos del Formulario:</h1>";
var_dump($_POST);

$conn = new PDO("mysql:host=localhost;dbname=gruas", "root", "root");

// Verificar si los datos están disponibles en el array $_POST
if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['telefono']) && isset($_POST['email']) && isset($_POST['descripcion'])) {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $descripcion = $_POST['descripcion'];

    try {
        // Preparar la consulta SQL
        $sql = "INSERT INTO solicitudes (nombre, apellido, telefono, email, descripcion) VALUES (:nombre, :apellido, :telefono, :email, :descripcion)";
        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':descripcion', $descripcion);

        // Ejecutar la consulta
        $stmt->execute();

        echo "Solicitud guardada con éxito.";
    } catch (PDOException $e) {
        echo "Error al guardar la solicitud: " . $e->getMessage();
    }

    // Cerrar la conexión (no es necesario con PDO, se cierra automáticamente al destruir el objeto)
    $conn = null;
} else {
    echo "Faltan datos en el formulario.";
}
?>


    
   



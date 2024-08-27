<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";  // Ajusta según tu configuración
$password = "";  // Ajusta según tu configuración
$dbname = "users_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar PDO para lanzar excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener y sanitizar los datos del formulario
        $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pass = $_POST['password']; // La contraseña no se sanitiza aquí para no alterar su contenido.
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        // Validación de datos
        if (empty($user) || strlen($user) > 30) {
            die("Nombre de usuario inválido. Debe tener menos de 30 caracteres.");
        }

        if (empty($pass) || strlen($pass) < 6) {
            die("La contraseña debe tener al menos 6 caracteres.");
        }

        if (empty($id) || !is_numeric($id)) {
            die("ID inválido.");
        }

        // Verificar si el ID ya existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            echo "<script>alert('El ID ya está en uso.');</script>";
            echo "<script>setTimeout(function(){ window.location.href = 'index.html'; }, 2000);</script>";
            //echo "<script>clearForm();</script>"; // Limpia el formulario después del registro 
            //header("Location: index.html");
        } else {
            // Verificar si el nombre de usuario ya existe
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->bindParam(':username', $user);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                echo "<script>alert('El nombre de usuario ya está en uso.');</script>";
                echo "<script>setTimeout(function(){ window.location.href = 'index.html'; }, 2000);</script>";
                // echo "<script>clearForm();</script>"; // Limpia el formulario después del registro 
               // header("Location: index.html");
            } else {
                // Cifrar la contraseña utilizando password_hash con BCRYPT
                $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

                // Insertar el nuevo usuario en la base de datos
                $stmt = $conn->prepare("INSERT INTO users (id, username, password) VALUES (:id, :username, :password)");
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':username', $user);
                $stmt->bindParam(':password', $hashed_password);

                $stmt->execute();
                echo "<script>alert('Usuario registrado exitosamente.');</script>";
                echo "<script>setTimeout(function(){ window.location.href = 'index.html'; }, 2000);</script>";
                //echo "<script>clearForm();</script>"; // Limpia el formulario después del registro exitoso...
                // Redirigir a index.html después del registro exitoso
                //header("Location: index.html");
                exit();
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    echo "<script>setTimeout(function(){ window.location.href = 'index.html'; }, 3000);</script>";
}

$conn = null;
?>

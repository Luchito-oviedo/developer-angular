<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$servidor = "localhost";
$usuario = "root";
$contrasenia = "";
$nombreBaseDatos = "api_php";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);
mysqli_set_charset($conexionBD, "utf8");

if (isset($_GET["consultar_usuario"])){
    $sqlUsuarios = mysqli_query($conexionBD, "SELECT * FROM usuarios WHERE id=".$_GET["consultar_usuario"]);
    if(mysqli_num_rows($sqlUsuarios) > 0){
        $usuarios = mysqli_fetch_all($sqlUsuarios, MYSQLI_ASSOC);
        echo json_encode($usuarios);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["borrar_usuario"])){
    $sqlUsuarios = mysqli_query($conexionBD, "DELETE FROM usuarios WHERE id=".$_GET["borrar_usuario"]);
    if($sqlUsuarios){
        echo json_encode(["success" => 1]);
        exit();
    } else {
        echo json_encode(["success" => 0]);
    }
}

if (isset($_GET["insertar_usuario"])){
    $data = json_decode(file_get_contents("php://input"));
    $nombre = $data->nombre;
    $apellido = $data->apellido;
    $correo = $data->correo;
    $contrasenia = password_hash($data->contrasenia, PASSWORD_BCRYPT); // Encripta la contraseña
    $mensaje = "Usuario registrado con éxito";

    $sqlUsuarios = mysqli_query($conexionBD, "INSERT INTO usuarios(nombre, apellido, correo, contrasenia, mensaje) VALUES('$nombre', '$apellido', '$correo', '$contrasenia', '$mensaje')");
    if ($sqlUsuarios) {
        echo json_encode(["success" => 1, "message" => "Usuario registrado con éxito"]);
    } else {
        echo json_encode(["success" => 0, "message" => "Error al registrar el usuario"]);
    }
    exit();
}

if (isset($_GET["actualizar_usuario"])){
    $data = json_decode(file_get_contents("php://input"));

    $id = (isset($data->id)) ? $data->id : $_GET["actualizar_usuario"];
    $nombre = $data->nombre;
    $apellido = $data->apellido;
    $correo = $data->correo;

    $sqlUsuarios = mysqli_query($conexionBD, "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', correo='$correo' WHERE id='$id'");
    echo json_encode(["success" => 1]);
    exit();
}

$sqlUsuarios = mysqli_query($conexionBD, "SELECT * FROM usuarios ");
if(mysqli_num_rows($sqlUsuarios) > 0){
    $usuarios = mysqli_fetch_all($sqlUsuarios, MYSQLI_ASSOC);
    echo json_encode($usuarios);
} else {
    echo json_encode([["success" => 0]]);
}
?>

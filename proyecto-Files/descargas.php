<?php
//variables globales
$directorioUp1 = '/var/www/proyecto-Files/upload/otros/';
$directorioUp2 = '/var/www/proyecto-Files/upload/musica/';
$directorioUp3 = '/var/www/proyecto-Files/upload/imagenes/';
$arrayCosas = ["otros" => array(), "musica" => array(), "imagenes" => array()];
$array = ["otros" => array(), "musica" => array(), "imagenes" => array()];
$admin;
$msj;

//Esta funcion separa los archivos 'checkeados' por el administrador dependiendo de que tipo son
function separaArchivosSubir($array1, $array2, $array3) {
    global $array;

    if ($array1 != 0) {
        foreach ($array1 as $archivo) {
            array_push($array['otros'], $archivo);
        }
    }
    if ($array2 != 0) {
        foreach ($array2 as $archivo) {
            array_push($array['musica'], $archivo);
        }
    }
    if ($array3 != 0) {
        foreach ($array3 as $archivo) {
            array_push($array['imagenes'], $archivo);
        }
    }
}

//se encarga de coger del array global el fichero y subirlo a la url correspmdiemte.
function subirArchivo() {
    global $array;
    global $directorioUp1;
    global $directorioUp2;
    global $directorioUp3;

    if (sizeof($array['musica']) != 0) {
        foreach ($array['musica'] as $valor) {
            $fichero[] = $valor;
        }
        $dir_destino = "./download/musica/";
        $origen = $directorioUp2;
    }
    if (sizeof($array['imagenes']) != 0) {
        foreach ($array['imagenes'] as $valor) {
            $fichero[] = $valor;
        }
        $dir_destino = "./download/imagenes/";
        $origen = $directorioUp3;
    }
    if (sizeof($array['otros']) != 0) {
        foreach ($array['otros'] as $valor) {
            $fichero[] = $valor;
        }
        $dir_destino = "./download/otros/";
        $origen = $directorioUp1;
    }

    foreach ($fichero as $ficher) {
        $destino = $dir_destino . $ficher;
        $estado = rename($origen . $ficher, $destino);
    }

    if ($estado) {
        echo "Fichero subido correctamente a $destino";
    } else {
        echo "Fichero $fichero no se a podido subir";
    }
}

//muestra el contenido de la carpeta Download
function download() {
    echo"<h1>Ficheros publicos</h1>";
    $directorio1 = dir('/var/www/proyecto-Files/download/imagenes/');
    echo "<h2>Contenido de la carpeta Imagenes</h2>";
    while ($f = $directorio1->read()) {
        if (!($f == "." || $f == "..")) {
            echo "<a href='./download/imagenes/$f'> hola $f<a/><br>";
        }
    }
    echo "<hr/>";

    $directorio2 = dir('/var/www/proyecto-Files/download/musica/');
    echo "<h2>Contenido de la carpeta Musica</h2>";
    while ($f = $directorio2->read()) {
        if (!($f == "." || $f == "..")) {
            echo "<a href='./download/musica/$f'>$f<a/><br>";
        }
    }
    echo "<hr/>";

    $directorio3 = dir('/var/www/proyecto-Files/download/otros/');
    echo "<h2>Contenido de la carpeta Otros</h2>";
    while ($f = $directorio3->read()) {
        if (!($f == "." || $f == "..")) {
            echo "<a href='./download/otros/$f'>$f<a/><br>";
        }
    }
    echo "<hr/>";
}

//muestra el contenido de la carpeta Upload
function upload() {

    global $arrayCosas;

    $directorioUp1 = dir('/var/www/proyecto-Files/upload/otros/');


    while ($f = $directorioUp1->read()) {
        if (!($f == "." || $f == "..")) {

            array_push($arrayCosas['otros'], $f);
        }
    }
    echo "</hr>";

    $directorioUp2 = dir('/var/www/proyecto-Files/upload/musica/');



    while ($f = $directorioUp2->read()) {
        if (!($f == "." || $f == "..")) {

            array_push($arrayCosas['musica'], $f);
        }
    }
    echo "</hr>";

    $directorioUp3 = dir('/var/www/proyecto-Files/upload/imagenes/');



    while ($f = $directorioUp3->read()) {
        if (!($f == "." || $f == "..")) {

            array_push($arrayCosas['imagenes'], $f);
        }
    }
    echo "</hr>";
}

function subirFichero() {
    $origen = $_FILES['fichero']['tmp_name'];
    $nombre_fichero = $_FILES['fichero']['name'];
    $tipo = $_FILES['fichero']['type'];
    $tipo_fichero = explode('/', $tipo);

    switch ($tipo_fichero[0]) {
        case'audio':
            $dir_destino = "./upload/musica";

            break;
        case'image':
            $dir_destino = "./upload/imagenes";

            break;
        default :
            $dir_destino = "./upload/otros";
    }
    $destino = $dir_destino . '/' . $nombre_fichero;
    $estado = move_uploaded_file($origen, $destino);

    if ($estado) {
        echo "Fichero subido correctamente a $destino";
    } else {
        echo "Fichero $nombre_fichero no se a podido subir";
    }
}

//si el usuario es correcto pero no es admin devuelve 1 y si es admin devuelve 2
function compruebaUsuario() {
    global $admin;
    global $msj;
    $nombre = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    //si el usuario es ADMIN
    if (($nombre === 'admin') && ($contraseña == 'admin')) {

        $admin = true;
        $msj = "<input type='submit' name='subir' value='Subir' >";
        return 2;
    }
    if (($nombre != "") && ($nombre === $contraseña)) {

        return 1;
    } else {
        return 0;
    }
}

if (isset($_POST['entrar'])) {
    switch (compruebaUsuario()) {
        //sube el fichero si lo hemos seleccionado a la carpeta correspondiente
        case 1:
            if ($_FILES['fichero']['name'] != "") {
                
                subirFichero();
            } else {
                echo "nada seleccionado!";
            }

            download();
            break;

        case 2:
            //sube el fichero si lo hemos seleccionado a la carpeta correspondiente
            if ($_FILES['fichero']['name'] != "") {
                
                subirFichero();
            } else {
                echo "nada seleccionado!";
            }

            download();
            upload();
            break;
        default:
            echo "Por favor introduzca sus credenciales";
            header('refresh:3, url=index.php');
    }
}



//boton volver
if (isset($_POST['volver'])) {

    $nombre = "";
    $contraseña = "";

    header('refresh:2; url=index.php');
}

//accion de subir archivo, recoge los seleccionados y llama a las funciones para realizar la subida
if (isset($_POST['subir'])) {
    $array1 = $_POST['array1'];
    $array2 = $_POST['array2'];
    $array3 = $_POST['array3'];
    separaArchivosSubir($array1, $array2, $array3);
    subirArchivo();

    header("refresh:1; url='index.php'");
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Descargas</title>
    </head>
    <body>
        <form action="descargas.php" method="POST" enctype="multipart/form-data">
            <?php
            //si es admin añado los input con el checkbox de los archivos que pueden subirse
            if ($admin) :
                global $arrayCosas;
                ?>
                <hr/>
                <h1>Administracion de ficheros</h1>
                <h4>Contenido por subir en otros</h4>

                <?php
                foreach ($arrayCosas['otros'] as $fichero) {
                    echo "<input type='checkbox' value='$fichero' name='array1[]'>$fichero";
                }
                ?>
                <hr/>
                <h4>Contenido por subir en Musica</h4>
                <?php
                foreach ($arrayCosas['musica'] as $fichero) {
                    echo "<input type='checkbox' value='$fichero' name='array2[]'>$fichero";
                }
                ?>
                <hr/>
                <h4>Contenido por subir en imagenes</h4>
                <?php
                foreach ($arrayCosas['imagenes'] as $fichero) {
                    echo "<input type='checkbox' value='$fichero' name='array3[]'>$fichero";
                }

                echo $msj;

            endif;
            ?>



        </form>
        <form action="index.php">
            <input type="submit" value="Volver">

        </form>
    </body>
</html>


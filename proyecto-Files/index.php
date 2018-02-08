<!DOCTYPE html>
<!--EJERCICIO FICHEROS DEYVID UZUNOV-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <!--Formulario donde se piden los datos-->
        <form action="descargas.php" method="POST" enctype="multipart/form-data">
            <div>
                <h2>Datos del usuario</h2>
                <input type="text" name="usuario" >
                <input type="password" name="contraseña">
                <input type="submit" name="entrar" value="Entrar">
            </div>
            <div>
                <h3>Seleccione un fichero</h3>
                <input type="file" name="fichero" value="null">
                <input type="hidden" name="MAX_SIZE_FILE" value="1000000000" >
                <input type="hidden" name="MIN_SIZE_FILE" value="10000000" >
            </div>
        </form>

    </body>
</html>

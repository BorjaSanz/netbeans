<!DOCTYPE html>
<!--comentario-->
<!--otro comentario-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        //compruebo si se ha llamado a la pagina desde el formulario que esta en la propia pagina
        //en caso de que no el valor sera true y guardara el fichero proveniente de index.php,
        //en caso de que si comprobara los fichero que tiene que mover
        if (filter_input(INPUT_POST, 'publicar') == "") {

            guardarArchivo();
            comprobarUsuario();
        } else {

            //obtiene los nombres de los ficheros a mover que se han enviado por medio de un formulario
            $imagenes = $_POST['imagen'];
            $audio = $_POST['audio'];
            $otros = $_POST['otros'];

            moverFichero($imagenes, "./upload/image/", "./download/imagenes/");
            moverFichero($audio, "./upload/audio/", "./download/musica/");
            moverFichero($otros, "./upload/otros/", "./download/otros/");
            //llamo a comprobar usuario para mostrar todos los datos de la pagina actualizados
            comprobarUsuario();
        }

        //mueve los ficheros que se encuentran en un array de la ruta de origen a la de destino
        function moverFichero($arrayFicheros, $origen, $destino) {
            foreach ($arrayFicheros as $fichero) {

                if (rename($origen . "" . $fichero, $destino . "" . $fichero)) {
                    //fichero movido correctamente
                } else {
                    //fallo al mover el fichero
                }
            }
        }

        
        //guarda el fichero en la ubicacion correspondiente
        function guardarArchivo() {
            /*
             *Para permitir archivos de 10 mb he cambiado el valor de las propiedades 
                 de php.ini upload_max_filesize = 10M y post_max_size=10M ya que por defecto estaban
                 en 2M y 8M respectivamente
             */
            
            $origen = $_FILES['fichero']['tmp_name'];

            $nombreFichero = $_FILES['fichero']['name'];

            $tipo = $_FILES['fichero']['type'];
            
            $size = $_FILES['fichero']['size'];
            
           

            //si el tamaño del fichero es mayor de 10kb lo guarda, si no lo es no
            if ($size > 10000){
            $tipo_fichero = explode('/', $tipo);
            //comprueba el tipo del fichero y guarda la ruta correspondiente en una variable
            switch ($tipo_fichero[0]) {
                case 'audio':
                    $dir_destino = "./upload/audio/";
                    break;
                case 'image':
                    $dir_destino = "./upload/image/";
                    break;
                default:
                    $dir_destino = "./upload/otros/";
            }


            $destino = $dir_destino . "" . $nombreFichero;
            
            //guarda el fichero en la ruta obtenida anteriormente
            if (move_uploaded_file($origen, $destino)) {
                //fichero subido correctamente
            } else {
                //error subiendo el fichero
            }
            }
            else{
                echo('no se aceptar ficheros de menos de 10kb por lo tanto no se ha subido');
                //el tamaño del fichero es menor de 10kb y por lo tanto no se sube
            }
        }

        //comprueba el usuario y la contraseña que se han introducido en index.php
        //en caso de que usuario y contraseña sean admin mostrara tanto la parte publica como la privada
        //en caso de que no sean admin mostrará solamente la parte publica
        function comprobarUsuario() {

            $usuario = filter_input(INPUT_POST, 'usuario');
            $pass = filter_input(INPUT_POST, 'pass');


            if ($usuario == "admin" && $pass == "admin") {
                partePublica();
                partePrivada();
            } else {
                partePublica();
            }
        }

        
        //muestra la parte privada, corresponde a un formulario con los distintos archivos
        //ordenados por categorias y con un checkboxpara marcarlos
        function partePrivada() {
            
           
            echo('<h1>Ficheros privados:</h1>
                
                    <fieldset>
                     <legend>Publicacion de ficheros:</legend>
                      <form action="" method="POST">
                      <fieldset>
                        <legend>Canciones subidas:</legend>
                        ');
            //muestra los archivos de audio
            mostrarArchivos("./upload/audio/", "audio");
            echo('
                       </fieldset>
                       
                       
                        <fieldset>
                        <legend>Imagenes subidas:</legend>
                        ');
            //muestra los archivos de imagen
            mostrarArchivos("./upload/image/", "imagen");
            echo ('
                        </fieldset>
                        
                        <fieldset>
                        <legend>Otros archivos subidos:</legend>');
            //muestra los archivos en otros
            mostrarArchivos("./upload/otros/", "otros");
            //dos de los imput del formulario envian el usuario y la contraseña para que al enviar el formulario
            //se mantenga el usuario admin y se muestre la parte privada
            echo('
                        </fieldset>
                        
                        <input type="text" name="usuario" value="admin" hidden=""/>
                        <input type="text" name="pass" value="admin" hidden=""/>
                        <input type="submit" value="Publicar" name="publicar">
                    </form>
                    </fieldset>');
        }

        //muestra la parte publica ordenando por categorias los archivos
        //cada nombre de archivo es un enlace al archivo correspondiente.
        
        function partePublica() {
            echo('<h1>Ficheros públicos:</h1>
                    <fieldset> 
                    <legend>Publicación de ficheros</legend>
                    <fieldset>
                        <legend>Canciones subidas</legend>');
            
            //muestra los archivos de musica
            mostrarArchivosLink("./download/musica/");
            echo('
                    </fieldset>
                    <fieldset>
                        <legend>Imagenes subidas</legend>');
            //muestra los archivos de imagenes
            mostrarArchivosLink("./download/imagenes/");
            echo ('
                    </fieldset>
                    <fieldset>
                        <legend>Otros ficheros subidos</legend>');
            //muestra los archivos en otros
            mostrarArchivosLink("./download/otros/");
            echo('
                    </fieldset>
                    
                    </fieldset>');
        }

        
        //revisa los archivos en una ruta y para cada uno crea un checkbox
        function mostrarArchivos($ruta, $nombre) {

            $archivos = scandir($ruta);

            foreach ($archivos as $archivo) {
                //con el if se omiten el directorio actual y el directorio padre que muestra scandir()
                if ($archivo != "." && $archivo != "..") {
                    echo('<input type="checkbox" name="' . $nombre . '[]" value="' . $archivo . '">' . $archivo . '<br>');
                } else {
                    
                }
            }
        }

        //revisa los archivos en cada ruta y para cada uno muestra un enlace
        function mostrarArchivosLink($ruta) {

            $archivos = scandir($ruta);

            foreach ($archivos as $archivo) {
                if ($archivo != "." && $archivo != "..") {
                    echo('<a href ="' . $ruta . "" . $archivo . '">' . $archivo . ' </a><br/>');
                } else {
                    
                }
            }
        }
        ?>
    </body>
</html>

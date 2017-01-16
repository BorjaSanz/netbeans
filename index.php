<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Descarga de ficheros</title>
    </head>
    <body>
        <h1>Descarga de ficheros</h1>
        <fieldset>
            <legend><h3>Datos de usuario:</h3></legend>
            <!-- el formulario se envia a descargas.php, 
            uso enctype="multipart/form-data" porque voy a enviar ficheros-->
            <form action="descargas.php" method="POST" enctype='multipart/form-data'>

                 <!--Establece el tamaño maximo del fichero a 10mb -->
                 <!--Para permitir archivos de 10 mb he cambiado el valor de las propiedades 
                 de php.ini upload_max_filesize = 10M y post_max_size=10M ya que por defecto estaban
                 en 2M y 8M respectivamente-->
                 
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                
                Usuario: <input type="text" name="usuario">

                Password: <input type="password" name="pass"><br>

                <h2>Selecciona fichero:</h2>

                <input type="file" name="fichero" id="" >
                <br />
                <br/>
                <input type="submit" value="Acceder" name="acceder">



            </form>


        </fieldset>
        
       
    </body>
</html>

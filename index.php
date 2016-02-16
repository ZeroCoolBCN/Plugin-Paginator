<!DOCTYPE html>
<html>
    <head>
        <title>ZeroCool Paginate</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- css carga del estilo bootstrap -->
        <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
        
        <!-- carga de los plugins JQUERY y BOOTSTRAP -->
        <script src="bower_components/jquery/dist/jquery.js"></script>
        <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="bower_components/bootbox.js/bootbox.js"></script>
        <!-- carga el plugin paginator -->
        <script src="js/paginator.js"></script>
        <script>
            $(document).ready(function () {
                $("#paginar").PaginateZeroCoolCrud({
                    path: 'test.php',
                    pathDelete: 'delete.php',
                    content:'#paginar'                    
                })
            });
        </script>
    </head>
    <body>
        <div id="paginar"></div>

    </body>
</html>

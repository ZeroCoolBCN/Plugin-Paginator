<!DOCTYPE html>
<html>
    <head>
        <title>ZeroCool Paginate</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="js/paginator.js"></script>
        <script>
            $(document).ready(function () {
                $("#paginar").PaginateZeroCool({
                    path: 'test.php',
                    content:'#paginar'
                    
                })
            });
        </script>
    </head>
    <body>
        <div id="paginar"></div>

    </body>
</html>

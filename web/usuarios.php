<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lista de Usuarios</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        var urlWs = "http://" + window.location.hostname + "/api/index.php/";
        $.ajax({
                type: "GET",
                url: urlWs + "usuario",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function (data, status, jqXHR) {
                    $.each(data, function(i, object) {
                        $("body").append("<div><b>Usuario: </b><span>"+object.Nombre+"</span><br><b>Contraseña: </b><span>"+object.DNI+"</span><br><b>Gerencia: </b><span>"+object.ID_Gerencia+"</span><br><br><hr></div>")
                    });
                },
                error: function (jqXHR, status) {
                    console.log(jqXHR);
                }
            });
    });
    </script>
</head>
<body>

</body>
</html>
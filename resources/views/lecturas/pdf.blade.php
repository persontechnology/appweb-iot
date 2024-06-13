<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $lectura->dev_eui }}</title>
    <style>
        table, td, th {
            border: 1px solid;
            padding-left: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        } 

        .bg-primary {
            background-color: #007bff; /* Color primario */
            
        }
        
        .bg-primary td {
            color: black; /* Color de texto negro */
        }

   </style>
</head>
<body>
    <br>
    @include('lecturas.detalle',['lectura'=>$lectura])
</body>
</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturas de Nivel de Agua</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .header, .footer {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
        }

        .content strong {
            color: #4CAF50;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .highlight {
            font-size: 18px;
            color: #e74c3c;
            font-weight: bold;
        }

        .rango {
            background-color: #f0f8f5;
            border-left: 5px solid #4CAF50;
            padding: 10px;
            margin: 20px 0;
        }

        .rango strong {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        @include('email.header', ['title' => 'Notificación de Distancia'])

        <div class="content">
            <p>Estimado/a <strong>{{ $user->apellidos }} {{ $user->nombres }}</strong>,</p>

            <p>Se le comunica que el nivel está al 
                <span class="highlight">{{ $porcentajeLlenado }}%</span>.</p>

            @if ($rangoLlenado)
            <div class="rango">
                <p>El estado del nivel es: <strong>{{ $rangoLlenado['descripcion'] }}</strong> 
                (<strong>{{ $rangoLlenado['valor'] }}%</strong>).</p>
            </div>
            @else
            <p>No se pudo determinar el estado del nivel.</p>
            @endif

        </div>

        @include('email.footer')
    </div>
</body>

</html>

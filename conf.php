<?php
// Definir el directorio padre
$directorio = realpath(dirname(__FILE__) . '/../'); // Usar el directorio padre

// Obtener la fecha desde la URL si está definida
$fecha_param = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d'); // Valor por defecto
$fecha_actual = new DateTime($fecha_param); // Crea un objeto DateTime con la fecha proporcionada

// Obtener el mes y el día
$mes = $fecha_actual->format('m');
$dia = $fecha_actual->format('d');

// Determinar la estación del año
$estacion = '';
if (($mes == 12 && $dia >= 21) || ($mes <= 2) || ($mes == 3 && $dia < 21)) {
    $estacion = 'invierno';
} elseif (($mes == 3 && $dia >= 21) || ($mes <= 5) || ($mes == 6 && $dia < 21)) {
    $estacion = 'primavera';
} elseif (($mes == 6 && $dia >= 21) || ($mes <= 8) || ($mes == 9 && $dia < 21)) {
    $estacion = 'verano';
} else {
    $estacion = 'otoño';
}

// Asignar imagen de cabecera según la estación
$imagenes_estacion = [
    'invierno' => '../UD3/Actividad1/images/invierno.jpg',
    'primavera' => '../UD3/Actividad1/images/primavera.jpg',
    'verano' => '../UD3/Actividad1/images/verano.jpg',
    'otoño' => '../UD3/Actividad1/images/otono.jpg'
];

$imagen_estacion = $imagenes_estacion[$estacion];

// Obtener la hora actual
$hora = $fecha_actual->format('H');

// Determinar el color de fondo según la hora del día
$color_fondo = '';
if ($hora >= 6 && $hora < 12) {
    $color_fondo = '#FFFAE5'; // Mañana - Color claro
} elseif ($hora >= 12 && $hora < 18) {
    $color_fondo = '#FFF8DC'; // Tarde - Color más cálido
} elseif ($hora >= 18 && $hora < 21) {
    $color_fondo = '#FFD700'; // Atardecer - Dorado
} else {
    $color_fondo = '#2F4F4F'; // Noche - Oscuro
}

// Comprobar si estamos en el directorio DWES y establecer la ruta de retroceso
$ruta_retroceso = (basename($directorio) === 'DWES') 
    ? '../UD3/Actividad1/index.php' // Ajusta esta ruta según tu estructura
    : dirname($directorio); // Usar el directorio padre correctamente
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mi Portafolio - Contenido del Directorio</title>
    <link rel="stylesheet" href="../UD3/Actividad1/style.css"> <!-- Ruta al archivo CSS -->
    <style>
        html, body {
            background-color: <?php echo $color_fondo; ?>; /* Color de fondo según la hora */
            margin: 0; /* Elimina el margen por defecto */
            padding: 0; /* Elimina el padding por defecto */
            display: flex; /* Utiliza Flexbox para el cuerpo */
            flex-direction: column; /* Coloca los elementos en una columna */
        }
        .cabecera {
            width: 100%;
            height: 300px;
            background: url('<?php echo $imagen_estacion; ?>') no-repeat center center;
            background-size: cover;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluir jQuery -->
    <script>
        $(document).ready(function() {
            function cargarDirectorio(directorio) {
                $.get('listar.php', { dir: directorio }, function(data) {
                    $('#elementos').empty(); // Vaciar la lista actual
                    data.forEach(function(elemento) {
                        var icono = elemento.includes('.') ? '📄' : '📁';
                        var enlace = elemento.includes('.') 
                            ? '<a href="' + encodeURI(directorio + '/' + elemento) + '" target="_blank">' + elemento + '</a>'
                            : '<a href="#" class="directorio" data-dir="' + encodeURI(directorio + '/' + elemento) + '">' + elemento + '</a>';
                        $('#elementos').append('<li>' + icono + ' ' + enlace + '</li>');
                    });
                }, 'json');
            }

            // Cargar el directorio inicial
            cargarDirectorio('<?php echo htmlspecialchars($directorio); ?>');

            // Manejar clics en los enlaces de directorio
            $('#elementos').on('click', '.directorio', function(event) {
                event.preventDefault(); // Prevenir el comportamiento por defecto
                var nuevoDirectorio = $(this).data('dir');
                cargarDirectorio(nuevoDirectorio); // Cargar el nuevo directorio
            });
        });
    </script>
</head>
<body>

<header>
    <div class="cabecera"></div> <!-- Aquí se aplicará la imagen de la estación -->
    <h1>Contenido del Directorio Actual</h1>
</header>

<section class="flex" id="units">
    <div class="unitmenu">
        <ul id="elementos">
            <!-- Aquí se llenarán los elementos por AJAX -->
        </ul>
    </div>
</section>

<!-- Enlace de retroceso con texto y clase -->
<a href="<?php echo htmlspecialchars($ruta_retroceso); ?>" class="volver-inicio">
    Volver al Inicio
</a>


<footer>
    <p>© 2024 Alejandro Carrasco Castellano. Todos los derechos reservados.</p>
</footer>

</body>
</html>

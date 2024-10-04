<?php
// Obtener el directorio desde la solicitud AJAX
$directorio = isset($_GET['dir']) ? realpath($_GET['dir']) : realpath(dirname(__FILE__) . '/../');

// Verificar si el directorio es válido
if ($directorio === false || !is_dir($directorio)) {
    $directorio = realpath(dirname(__FILE__) . '/../'); // Regresar al directorio padre si no es válido
}

// Función para listar archivos y directorios
function listar_directorios($directorio) {
    $resultados = [];
    if (is_dir($directorio)) {
        // Obtener los archivos y directorios
        $elementos = scandir($directorio);
        foreach ($elementos as $elemento) {
            // Ignorar los elementos especiales '.' y '..'
            if ($elemento != '.' && $elemento != '..') {
                $resultados[] = $elemento;
            }
        }
    }
    return $resultados;
}

// Lista de archivos y carpetas en el directorio especificado
$elementos = listar_directorios($directorio);

// Devolver la lista en formato JSON
header('Content-Type: application/json');
echo json_encode($elementos);
?>

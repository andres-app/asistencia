<?php

// Detectar si estamos en un entorno de desarrollo o producción
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    // === AMBIENTE DE DESARROLLO ===
    
    // ip de la pc servidor base de datos
    define("DB_HOST", "localhost");

    // nombre de la base de datos
    define("DB_NAME", "control_asistencia");

    // nombre de usuario de base de datos
    define("DB_USERNAME", "root");

    // contraseña del usuario de base de datos
    define("DB_PASSWORD", "");

    // codificación de caracteres
    define("DB_ENCODE", "utf8");

    // nombre del proyecto
    define("PRO_NOMBRE", "Appsauri");
} else {
    // === AMBIENTE DE PRODUCCIÓN ===
    
    // ip o dominio del servidor de base de datos
    define("DB_HOST", "localhost");  // Cambia si el host en producción es diferente

    // nombre de la base de datos
    define("DB_NAME", "u274409976_asistencia");

    // nombre de usuario de base de datos
    define("DB_USERNAME", "u274409976_asistencia");

    // contraseña del usuario de base de datos
    define("DB_PASSWORD", "Redes2804751$$$");

    // codificación de caracteres
    define("DB_ENCODE", "utf8");

    // nombre del proyecto
    define("PRO_NOMBRE", "Appsauri_Produccion");
}

?>

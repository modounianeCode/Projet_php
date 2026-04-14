<?php
// ─── Base de données ──────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'marketplace_agricole');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ─── Application ─────────────────────────────────────────
define('APP_NAME',   'AgroMarket');

// ─── Déterminer BASE_URL dynamiquement ────────────────────
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$path     = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
define('BASE_URL', $protocol . '://' . $host . $path . '/');

// ─── Chemins ─────────────────────────────────────────────
// define('ROOT_PATH',   dirname(__DIR__));
define('APP_PATH',    ROOT_PATH . '/app');
define('UPLOAD_DIR',  ROOT_PATH . '/public/assets/images/produits/');
define('UPLOAD_URL',  BASE_URL  . 'assets/images/produits/');
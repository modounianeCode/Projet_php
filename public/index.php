<?php
/**
 * ╔══════════════════════════════════════════════════════╗
 * ║   AGROMARKET — Point d'entrée unique (Front Controller)
 * ║   Toutes les requêtes passent par ce fichier.
 * ╚══════════════════════════════════════════════════════╝
 */

// ── 1. Constante racine du projet ─────────────────────
define('ROOT_PATH', dirname(__DIR__));

// ── 2. Configuration ──────────────────────────────────
require_once ROOT_PATH . '/config/config.php';

// ── 3. Classes du framework (Core) ───────────────────
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Router.php';

// ── 4. Session ────────────────────────────────────────
session_start();

// ── 5. Démarrage du routeur ──────────────────────────
new Router();
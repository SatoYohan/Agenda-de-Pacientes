<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/app/Controllers/PacienteController.php';

session_start();
$_SESSION['user'] = ['id' => 1, 'username' => 'admin', 'role' => 'admin'];

$controller = new PacienteController();
$controller->index();
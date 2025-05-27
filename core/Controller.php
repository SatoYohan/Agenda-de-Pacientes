<?php

namespace Core; // Add Core namespace

class Controller {
    public function view(string $view, array $data = []) {
        extract($data);

        ob_start();
        // Assuming APP_ROOT is defined in your config.php and accessible
        $path = APP_ROOT . '/app/Views/' . $view . '.php';
        if (file_exists($path)) {
            require $path;
        } else {
            die("Erro: view '{$view}' não encontrada em: {$path}");
        }
        $content = ob_get_clean();

        $layoutPath = APP_ROOT . '/app/Views/layouts/main.php';
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            die("Erro: layout 'main.php' não encontrado em: {$layoutPath}");
        }
    }

    public function model(string $modelName) {
        // Construct the fully qualified model class name
        $modelClass = 'App\\Models\\' . ucfirst($modelName);

        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            // The old path-based loading is no longer needed if Composer autoload is working
            // and Models are correctly namespaced.
            die("Erro: model '{$modelClass}' não encontrado. Verifique o namespace e o autoloader.");
        }
    }

    protected function setFlashMessage(string $message, string $type = 'success') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Ensure session is started
        }
        $_SESSION['flash_message'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    protected function redirect(string $url) {
        // Ensure BASE_URL is defined and correctly configured in config.php
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}
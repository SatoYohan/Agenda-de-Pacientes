<?php

class Controller {
    public function view(string $view, array $data = []) {
        extract($data); // Make $data variables available in the view and layout

        // Capture the specific view's content
        ob_start();
        $path = APP_ROOT . '/app/views/' . $view . '.php'; // Use APP_ROOT defined in config.php
        if (file_exists($path)) {
            require $path;
        } else {
            die("Erro: view '{$view}' não encontrada em: {$path}");
        }
        $content = ob_get_clean(); // Get the buffered content

        // Now load the main layout, which will use $content
        $layoutPath = APP_ROOT . '/app/views/layouts/main.php';
        if (file_exists($layoutPath)) {
            require $layoutPath;
        } else {
            die("Erro: layout 'main.php' não encontrado em: {$layoutPath}");
        }
    }

    public function model(string $model) {
        $path = APP_ROOT . '/app/models/' . $model . '.php'; // Use APP_ROOT
        if (file_exists($path)) {
            require_once $path; // Use require_once for class definitions
            return new $model();
        } else {
            die("Erro: model '{$model}' não encontrado em: {$path}");
        }
    }

    // Helper for setting flash messages (feedback)
    protected function setFlashMessage(string $message, string $type = 'success') {
        $_SESSION['flash_message'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    // Helper for redirects
    protected function redirect(string $url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}
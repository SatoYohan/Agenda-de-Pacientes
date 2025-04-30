<?php

class Controller {
    public function view(string $view, array $data = []) {
        extract($data);
        $path = __DIR__ . '/../app/views/' . $view . '.php';
        if (file_exists($path)) {
            require $path;
        } else {
            die("Erro: view '{$view}' não encontrada em: {$path}");
        }
    }

    public function model(string $model) {
        $path = __DIR__ . '/../app/models/' . $model . '.php';
        if (file_exists($path)) {
            require $path;
            return new $model();
        } else {
            die("Erro: model '{$model}' não encontrado em: {$path}");
        }
    }
}
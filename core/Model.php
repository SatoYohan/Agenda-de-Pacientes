<?php

namespace Core; // <--- ADICIONE ESTA LINHA

use PDO; // Adicione esta linha se você não tiver adicionado antes, para a classe PDO
use PDOException; // Adicione esta linha para a classe PDOException

class Model { //
    protected PDO $db;

    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            // É melhor logar o erro ou lançar uma exceção mais genérica aqui
            // em vez de usar die() em um model.
            // Por agora, manteremos o die para consistência com o código original.
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
}
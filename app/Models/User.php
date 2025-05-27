<?php

namespace App\Models; // <--- ADICIONE ESTA LINHA

use Core\Model; // <--- ADICIONE ESTA LINHA (para que 'Model' seja reconhecido como 'Core\Model')
use PDO; // Adicione esta linha para PDO::FETCH_ASSOC, se ainda não estiver

class User extends Model // Agora o PHP sabe que 'Model' aqui se refere a 'Core\Model'
{
    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Use PDO::FETCH_ASSOC
        return $result ?: null; // Retorna null se fetch retornar false
    }
}
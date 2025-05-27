<?php

namespace App\Models;

use Core\Model; // Import the base Model class
use PDO; // Import PDO for database operations

class Consulta extends Model
{
    public function findAllWithPaciente(): array
    {
        $sql = "SELECT c.*, p.nome AS paciente_nome
                FROM consultas c
                JOIN pacientes p ON p.id = c.paciente_id
                ORDER BY c.data DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM consultas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(int $pacienteId, string $data, string $hora, string $obs): void {
        $stmt = $this->db->prepare("INSERT INTO consultas (paciente_id, data, hora, observacoes) VALUES (:paciente_id, :data, :hora, :obs)");
        $stmt->execute([
            'paciente_id' => $pacienteId,
            'data' => $data,
            'hora' => $hora,
            'obs' => $obs
        ]);
    }    

    public function update(int $id, int $pacienteId, string $data, string $hora, string $obs): void {
        $stmt = $this->db->prepare("UPDATE consultas SET paciente_id = :paciente_id, data = :data, hora = :hora, observacoes = :obs WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'paciente_id' => $pacienteId,
            'data' => $data,
            'hora' => $hora,
            'obs' => $obs
        ]);
    }    

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM consultas WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
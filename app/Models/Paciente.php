<?php

class Paciente extends Model
{
    public function findAll(int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->db->prepare("SELECT * FROM pacientes ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $nome, string $email, string $telefone): void
    {
        $stmt = $this->db->prepare("INSERT INTO pacientes (nome, email, telefone) VALUES (:nome, :email, :telefone)");
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone
        ]);
    }

    public function update(int $id, string $nome, string $email, string $telefone): void
    {
        $stmt = $this->db->prepare("UPDATE pacientes SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM pacientes WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

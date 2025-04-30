<?php
$currentPage = $_GET['page'] ?? 1;
$currentPage = max(1, (int) $currentPage);

$total = count($pacientes);
$perPage = 10;
$offset = ($currentPage - 1) * $perPage;
$totalPages = ceil($total / $perPage);

$displayedPacientes = array_slice($pacientes, $offset, $perPage);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pacientes</h2>
        <a href="<?= BASE_URL ?>/paciente/create" class="btn btn-primary">Novo Paciente</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($displayedPacientes as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= htmlspecialchars($p['email']) ?></td>
                    <td><?= htmlspecialchars($p['telefone']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/paciente/edit/<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="<?= BASE_URL ?>/paciente/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
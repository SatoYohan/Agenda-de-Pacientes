<?php
// Assuming $title is set in the controller for the <title> tag in main.php
// Example: $this->view('paciente/index', ['pacientes' => $pacientes, 'title' => 'Pacientes']);

// Pagination logic remains here as it's specific to preparing data for this view
$currentPage = $_GET['page'] ?? 1; //
$currentPage = max(1, (int) $currentPage); //

$total = $totalPacientes ?? count($pacientes); // Assuming $totalPacientes is passed from controller for accuracy
$perPage = 10; //
$offset = ($currentPage - 1) * $perPage; //
$totalPages = ceil($total / $perPage); //

// If $pacientes passed from controller is already paginated, this array_slice might be redundant
// or should be handled in the controller. For now, keeping it as per original.
$displayedPacientes = isset($pacientesFromController) ? $pacientesFromController : array_slice($pacientes, $offset, $perPage); //

?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pacientes</h2>
        <a href="<?= BASE_URL ?>/paciente/create" class="btn btn-primary">Novo Paciente</a>
    </div>

    <?php if (empty($displayedPacientes)): ?>
        <div class="alert alert-info">Nenhum paciente cadastrado.</div>
    <?php else: ?>
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

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= BASE_URL ?>/paciente/index?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php
// $this->view('paciente/index', ['pacientes' => $pacientes, 'title' => 'Pacientes']);

$currentPage = $_GET['page'] ?? 1;
$currentPage = max(1, (int) $currentPage);

$total = $totalPacientes ?? count($pacientes ?? []);
$perPage = 10;
$offset = ($currentPage - 1) * $perPage;
$totalPages = ($total > 0) ? ceil($total / $perPage) : 0; // Evita divisão por zero se $total for 0

$displayedPacientes = $pacientes ?? []; // Usa $pacientes diretamente, assumindo que já está paginado pelo controller
// Se $pacientesFromController fosse usado, a lógica original seria:
// $displayedPacientes = isset($pacientesFromController) ? $pacientesFromController : array_slice(($pacientes ?? []), $offset, $perPage);

?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-people"></i> Pacientes</h2>
        <a href="<?= BASE_URL ?>/paciente/create" class="btn btn-primary"><i class="bi bi-person-plus-fill"></i> Novo Paciente</a>
    </div>

    <?php if (empty($displayedPacientes)): ?>
        <div class="alert alert-info">Nenhum paciente cadastrado.</div>
    <?php else: ?>
        <table class="table table-hover table-bordered table-striped"> <thead class="table-dark">
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
                        <a href="<?= BASE_URL ?>/paciente/edit/<?= $p['id'] ?>" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil-fill"></i> Editar</a>
                        <a href="<?= BASE_URL ?>/paciente/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')"><i class="bi bi-person-x-fill"></i> Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= BASE_URL ?>/paciente/index?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= BASE_URL ?>/paciente/index?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= BASE_URL ?>/paciente/index?page=<?= $currentPage + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
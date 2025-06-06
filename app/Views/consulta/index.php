<?php
// $this->view('consulta/index', ['consultas' => $consultas, 'title' => 'Consultas Agendadas']);
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-clipboard2-pulse"></i> Consultas Agendadas</h2>
        <a href="<?= BASE_URL ?>/consulta/create" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i> Nova Consulta</a>
    </div>

    <?php if (empty($consultas)): ?>
        <div class="alert alert-info">Nenhuma consulta agendada no momento.</div>
    <?php else: ?>
        <table class="table table-hover table-striped table-bordered"> <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Data</th>
                <th>Observações</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($consultas as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['paciente_nome']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($c['data']))) . ' ' . htmlspecialchars(substr($c['hora'], 0, 5)) ?></td>
                    <td><?= nl2br(htmlspecialchars($c['observacoes'])) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/consulta/edit/<?= $c['id'] ?>" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil-square"></i> Editar</a>
                        <a href="<?= BASE_URL ?>/consulta/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja remover esta consulta?')"><i class="bi bi-trash3-fill"></i> Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
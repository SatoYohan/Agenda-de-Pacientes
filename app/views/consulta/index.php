<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Consultas Agendadas</h2>
        <a href="<?= BASE_URL ?>/consulta/create" class="btn btn-primary">Nova Consulta</a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
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
                    <td><?= date('d/m/Y', strtotime($c['data'])) . ' ' . substr($c['hora'], 0, 5) ?></td>
                    <td><?= nl2br(htmlspecialchars($c['observacoes'])) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/consulta/edit/<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="<?= BASE_URL ?>/consulta/delete/<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja remover esta consulta?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="container mt-5">
    <h2><?= isset($consulta) ? 'Editar Consulta' : 'Nova Consulta' ?></h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= isset($consulta) 
        ? BASE_URL . '/consulta/update/' . $consulta['id'] 
        : BASE_URL . '/consulta/store' ?>">

        <div class="mb-3">
            <label for="paciente_id" class="form-label">Paciente</label>
            <select name="paciente_id" id="paciente_id" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($pacientes as $p): ?>
                    <option value="<?= $p['id'] ?>"
                        <?= (isset($paciente_id) && $paciente_id == $p['id']) || 
                            (isset($consulta) && $consulta['paciente_id'] == $p['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="data" class="form-label">Data e Hora</label>
            <?php
                $inputDate = $data ?? (is_string($consulta['data'] ?? null) ? $consulta['data'] : null);
                $inputHora = $hora ?? (is_string($consulta['hora'] ?? null) ? $consulta['hora'] : null);
                $datetimeValue = ($inputDate && $inputHora) ? date('Y-m-d\TH:i', strtotime("$inputDate $inputHora")) : '';
            ?>
            <input type="datetime-local" name="data" id="data" class="form-control"
                value="<?= $datetimeValue ?>" required>
        </div>

        <div class="mb-3">
            <label for="observacoes" class="form-label">Observações</label>
            <textarea name="observacoes" id="observacoes" rows="4" class="form-control"><?= $obs ?? $consulta['observacoes'] ?? '' ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="<?= BASE_URL ?>/consulta/index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
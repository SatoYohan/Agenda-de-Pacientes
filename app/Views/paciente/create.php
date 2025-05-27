<?php
$isEditMode = isset($paciente) && !empty($paciente['id']);
$formAction = $isEditMode ? BASE_URL . '/paciente/update/' . $paciente['id'] : BASE_URL . '/paciente/store';
$pageTitle = $isEditMode ? 'Editar Paciente' : 'Novo Paciente';
// O controller já define $title, mas podemos ter um título mais específico aqui se necessário.
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi <?= $isEditMode ? 'bi-person-lines-fill' : 'bi-person-plus-fill' ?>"></i> <?= htmlspecialchars($pageTitle) ?></h2>
        <a href="<?= BASE_URL ?>/paciente/index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle"></i> Voltar para Lista</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show auto-close-alert" role="alert">
            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Erro de Validação!</h5>
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="<?= $formAction ?>">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= !empty($errors['nome']) ? 'is-invalid' : '' ?>" name="nome" id="nome" value="<?= htmlspecialchars($nome ?? ($isEditMode ? $paciente['nome'] : '')) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" name="email" id="email" value="<?= htmlspecialchars($email ?? ($isEditMode ? $paciente['email'] : '')) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= !empty($errors['telefone']) ? 'is-invalid' : '' ?>" name="telefone" id="telefone" value="<?= htmlspecialchars($telefone ?? ($isEditMode ? $paciente['telefone'] : '')) ?>" required>
                </div>
                <hr>
                <div class="d-flex justify-content-end">
                    <a href="<?= BASE_URL ?>/paciente/index" class="btn btn-outline-secondary me-2"><i class="bi bi-x-circle"></i> Cancelar</a>
                    <button type="submit" class="btn <?= $isEditMode ? 'btn-primary' : 'btn-success' ?>">
                        <i class="bi <?= $isEditMode ? 'bi-check-lg' : 'bi-plus-lg' ?>"></i> <?= $isEditMode ? 'Atualizar Paciente' : 'Salvar Paciente' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
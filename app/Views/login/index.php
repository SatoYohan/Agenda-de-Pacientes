<?php
// Assuming $title is set in the controller for the <title> tag in main.php
// Example: $this->view('login/index', ['error' => $error, 'title' => 'Login']);
?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow-lg">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Login</h4>
                <?php if (!empty($logout_message)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($logout_message) ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form action="<?= BASE_URL ?>/login/authenticate" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
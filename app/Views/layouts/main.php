<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Agenda de Pacientes' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <style>
        /* Estilo para garantir que o body ocupe pelo menos a altura da viewport */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1; /* Faz o conteúdo principal crescer e empurrar o footer para baixo */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/login/index"><i class="bi bi-calendar-heart-fill"></i> Agenda de Pacientes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/paciente/index"><i class="bi bi-people-fill"></i> Pacientes</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/consulta/index"><i class="bi bi-clipboard2-pulse-fill"></i> Consultas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/login/logout"><i class="bi bi-box-arrow-right"></i> Logout (<?= htmlspecialchars($_SESSION['user']['username']) ?>)</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/login/index"><i class="bi bi-box-arrow-in-left"></i> Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="container">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash_message']['type'] ?? 'info') ?> alert-dismissible fade show auto-close-alert" role="alert">
                <?= htmlspecialchars($_SESSION['flash_message']['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </div>
</div>

<footer class="bg-dark text-white text-center mt-auto py-3"> <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> Agenda de Pacientes. Todos os direitos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script para fechar alertas automaticamente
    document.addEventListener('DOMContentLoaded', (event) => {
        const autoCloseAlerts = document.querySelectorAll('.auto-close-alert');
        autoCloseAlerts.forEach(alert => {
            setTimeout(() => {
                // Usa o componente Alert do Bootstrap para fechar suavemente, se disponível
                const bootstrapAlert = bootstrap.Alert.getInstance(alert);
                if (bootstrapAlert) {
                    bootstrapAlert.close();
                } else {
                    // Fallback se o componente não for encontrado (remove diretamente)
                    alert.style.display = 'none';
                }
            }, 3000); // Fecha após 3000 milissegundos = 3 segundos
        });
    });
</script>
</body>
</html>
<?php

namespace App\Controllers; // Add App\Controllers namespace

use Core\Controller; // Import the base Controller class

class LoginController extends Controller
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            $this->redirect(($_SESSION['user']['role'] === 'admin') ? '/paciente/index' : '/consulta/index');
            return;
        }

        $data = ['title' => 'Login'];
        if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
            // Não use setFlashMessage aqui porque a sessão antiga foi destruída.
            // Em vez disso, passe uma mensagem diretamente para a view.
            $data['logout_message'] = 'Logout realizado com sucesso!';
        }

        $this->view('login/index', $data);
    }

    public function authenticate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Prevent already logged in users from re-authenticating
        if (isset($_SESSION['user'])) {
            $this->redirect(($_SESSION['user']['role'] === 'admin') ? '/paciente/index' : '/consulta/index');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? ''; // Password should not be trimmed

        if (empty($username) || empty($password)) {
            $this->view('login/index', [
                'error' => 'Usuário e senha são obrigatórios.',
                'title' => 'Login',
                'username' => $username // Send username back to prefill form
            ]);
            return;
        }

        $userModel = $this->model('User');
        $user = $userModel->findByUsername($username);

        if ($user && hash('sha256', $password) === $user['password']) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            $this->setFlashMessage('Login realizado com sucesso!', 'success');
            if ($user['role'] === 'admin') {
                $this->redirect('/paciente/index');
            } else {
                $this->redirect('/consulta/index');
            }
            return; // Exit after redirect
        }

        $this->view('login/index', [
            'error' => 'Usuário ou senha inválidos.',
            'title' => 'Login',
            'username' => $username // Send username back to prefill form
        ]);
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        // setFlashMessage won't work here as session is destroyed.
        // For a logout message, you might need to set it before destroying or handle on login page.
        header('Location:' . BASE_URL . '/login/index?logout=true'); // Redirect with a param
        exit;
    }
}
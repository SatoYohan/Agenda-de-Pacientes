<?php

class LoginController extends Controller
{
    public function index()
    {
        $this->view('login/index');
    }

    public function authenticate()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = $this->model('User');
        $user = $userModel->findByUsername($username);

        if ($user && hash('sha256', $password) === $user['password']) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            if ($user['role'] === 'admin') {
                header('Location:' . BASE_URL . '/paciente/index');
            } else {
                header('Location:' . BASE_URL . '/consulta/index');
            }
            exit;
        }

        $this->view('login/index', ['error' => 'Usuário ou senha inválidos']);
    }

    public function logout()
    {
        session_destroy();
        header('Location:' . BASE_URL . '/login/index');
        exit;
    }
}
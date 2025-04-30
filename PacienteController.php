<?php

class PacienteController extends Controller
{
    public function index()
    {
        $this->authorize();
        $pacienteModel = $this->model('Paciente');
        $pacientes = $pacienteModel->findAll();
        $this->view('paciente/index', ['pacientes' => $pacientes]);
    }

    public function create()
    {
        $this->authorize();
        $this->view('paciente/create');
    }

    public function store()
    {
        $this->authorize();

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');

        $errors = [];
        if (!$nome) $errors[] = 'Nome é obrigatório';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
        if (!$telefone) $errors[] = 'Telefone é obrigatório';

        if ($errors) {
            $this->view('paciente/create', compact('errors', 'nome', 'email', 'telefone'));
            return;
        }

        $pacienteModel = $this->model('Paciente');
        $pacienteModel->create($nome, $email, $telefone);

        header('Location: ' . BASE_URL . '/paciente/index');
        exit;
    }

    public function edit($id)
    {
        $this->authorize();
        $pacienteModel = $this->model('Paciente');
        $paciente = $pacienteModel->findById($id);
        $this->view('paciente/edit', ['paciente' => $paciente]);
    }

    public function update($id)
    {
        $this->authorize();

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');

        $errors = [];
        if (!$nome) $errors[] = 'Nome é obrigatório';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
        if (!$telefone) $errors[] = 'Telefone é obrigatório';

        if ($errors) {
            $paciente = compact('nome', 'email', 'telefone');
            $paciente['id'] = $id;
            $this->view('paciente/edit', compact('errors', 'paciente'));
            return;
        }

        $pacienteModel = $this->model('Paciente');
        $pacienteModel->update($id, $nome, $email, $telefone);

        header('Location: ' . BASE_URL . '/paciente/index');
        exit;
    }

    public function delete($id)
    {
        $this->authorize();
        $this->model('Paciente')->delete($id);
        header('Location: ' . BASE_URL . '/paciente/index');
        exit;
    }

    private function authorize()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login/index');
            exit;
        }
    }
}
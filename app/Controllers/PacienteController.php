<?php

namespace App\Controllers; // Add App\Controllers namespace

use Core\Controller; // Import the base Controller class

class PacienteController extends Controller
{
    private function authorizeAdmin() // Renamed for clarity
    {
        if (session_status() == PHP_SESSION_NONE) { // Ensure session is started
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            $this->setFlashMessage('Você precisa estar logado para acessar esta página.', 'warning');
            $this->redirect('/login/index');
        } elseif ($_SESSION['user']['role'] !== 'admin') {
            $this->setFlashMessage('Acesso negado. Somente administradores.', 'danger');
            // Redirect to a generic page for non-admins or their specific dashboard
            $this->redirect('/consulta/index'); // Or a dedicated "access denied" page
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
        $pacienteModel = $this->model('Paciente');

        // Pagination Logic - Moved to controller
        $currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
        $perPage = 10; // Or from a config file

        $totalPacientes = $pacienteModel->countAll(); // Assuming this method exists in your Paciente model
        $totalPages = ceil($totalPacientes / $perPage);
        $offset = ($currentPage - 1) * $perPage;

        $pacientes = $pacienteModel->findAll($perPage, $offset); // Assuming findAll accepts limit and offset

        $this->view('paciente/index', [
            'pacientes' => $pacientes,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalPacientes' => $totalPacientes,
            'title' => 'Pacientes'
        ]);
    }

    public function create()
    {
        $this->authorizeAdmin();
        $this->view('paciente/create', ['title' => 'Novo Paciente']);
    }

    public function store()
    {
        $this->authorizeAdmin();

        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '');
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING) ?? '');

        $errors = [];
        if (empty($nome)) $errors[] = 'Nome é obrigatório.';
        if (empty($email)) {
            $errors[] = 'Email é obrigatório.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }
        if (empty($telefone)) $errors[] = 'Telefone é obrigatório.';
        // Add more specific validation for telefone if needed (e.g., format)

        if (!empty($errors)) {
            $this->view('paciente/create', [
                'errors' => $errors,
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'title' => 'Novo Paciente'
            ]);
            return;
        }

        $pacienteModel = $this->model('Paciente');
        $pacienteModel->create($nome, $email, $telefone);

        $this->setFlashMessage('Paciente criado com sucesso!', 'success');
        $this->redirect('/paciente/index');
    }

    // PacienteController.php
    public function edit($id)
    {
        $this->authorizeAdmin();
        $id = (int)$id;
        $pacienteModel = $this->model('Paciente');
        $paciente = $pacienteModel->findById($id);

        if (!$paciente) {
            $this->setFlashMessage('Paciente não encontrado.', 'danger');
            $this->redirect('/paciente/index');
            return;
        }

        // Em vez de 'paciente/edit', chamamos 'paciente/create'
        // Passamos os dados do paciente para preencher o formulário
        $this->view('paciente/create', [
            'paciente' => $paciente, // Dados para preencher o formulário
            'title' => 'Editar Paciente',
            // Passar os valores individuais também para facilitar o preenchimento do formulário
            'nome' => $paciente['nome'],
            'email' => $paciente['email'],
            'telefone' => $paciente['telefone']
        ]);
    }

    public function update($id)
    {
        $this->authorizeAdmin();
        $id = (int)$id;

        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING) ?? '');
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        $telefone = trim(filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING) ?? '');

        $errors = [];
        if (empty($nome)) $errors[] = 'Nome é obrigatório.';
        if (empty($email)) {
            $errors[] = 'Email é obrigatório.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }
        if (empty($telefone)) $errors[] = 'Telefone é obrigatório.';

        if (!empty($errors)) {
            // Fetch the original paciente data to avoid losing the ID in the view
            $pacienteOriginal = $this->model('Paciente')->findById($id);
            $pacienteDataForView = [
                'id' => $id, // Ensure ID is present
                'nome' => $nome, // Use submitted value
                'email' => $email, // Use submitted value
                'telefone' => $telefone, // Use submitted value
            ];

            $this->view('paciente/edit', [
                'errors' => $errors,
                'paciente' => array_merge($pacienteOriginal ?: [], $pacienteDataForView),
                'title' => 'Editar Paciente'
            ]);
            return;
        }

        $pacienteModel = $this->model('Paciente');
        $pacienteModel->update($id, $nome, $email, $telefone);

        $this->setFlashMessage('Paciente atualizado com sucesso!', 'success');
        $this->redirect('/paciente/index');
    }

    public function delete($id)
    {
        $this->authorizeAdmin();
        $id = (int)$id;
        // Optional: Add a check to see if the paciente exists before attempting to delete
        $pacienteModel = $this->model('Paciente');
        if (!$pacienteModel->findById($id)) {
            $this->setFlashMessage('Paciente não encontrado ou já excluído.', 'warning');
            $this->redirect('/paciente/index');
            return;
        }

        $pacienteModel->delete($id);
        $this->setFlashMessage('Paciente excluído com sucesso!', 'success');
        $this->redirect('/paciente/index');
    }
}
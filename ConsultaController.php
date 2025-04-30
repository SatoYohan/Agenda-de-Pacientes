<?php

class ConsultaController extends Controller
{
    public function index()
    {
        $this->authorize();
        $consultas = $this->model('Consulta')->findAllWithPaciente();
        $this->view('consulta/index', compact('consultas'));
    }

    public function create()
    {
        $this->authorize();
        $pacientes = $this->model('Paciente')->findAll(1000);
        $this->view('consulta/create', compact('pacientes'));
    }

    public function store()
    {
        $this->authorize();
        $paciente_id = $_POST['paciente_id'] ?? '';
        $raw = $_POST['data'] ?? '';
        $data = date('Y-m-d', strtotime($raw));
        $hora = date('H:i:s', strtotime($raw));
        $obs = trim($_POST['observacoes'] ?? '');

        $errors = [];
        if (!$paciente_id) $errors[] = 'Paciente obrigatório';
        if (!$data || !strtotime($data)) $errors[] = 'Data inválida';

        if ($errors) {
            $pacientes = $this->model('Paciente')->findAll(1000);
            $this->view('consulta/create', compact('errors', 'paciente_id', 'data', 'obs', 'pacientes'));
            return;
        }

        $this->model('Consulta')->create($paciente_id, $data, $hora, $obs);
        header('Location: ' . BASE_URL . '/consulta/index');
        exit;
    }

    public function edit($id)
    {
        $this->authorize();
        $consulta = $this->model('Consulta')->findById($id);
        $pacientes = $this->model('Paciente')->findAll(1000);
        $data = $consulta['data'] ?? '';
        $hora = $consulta['hora'] ?? '';
        $this->view('consulta/create', compact('consulta', 'pacientes', 'data', 'hora'));
    }

    public function update($id)
    {
        $this->authorize();

        $paciente_id = $_POST['paciente_id'] ?? '';
        $raw = $_POST['data'] ?? '';
        $data = date('Y-m-d', strtotime($raw));
        $hora = date('H:i:s', strtotime($raw));
        $obs = trim($_POST['observacoes'] ?? '');

        $errors = [];
        if (!$paciente_id) $errors[] = 'Paciente obrigatório';
        if (!$data || !strtotime($data)) $errors[] = 'Data inválida';

        if ($errors) {
            $consulta = ['id' => $id, 'paciente_id' => $paciente_id, 'data' => $data, 'observacoes' => $obs];
            $pacientes = $this->model('Paciente')->findAll(1000);
            $this->view('consulta/create', compact('errors', 'consulta', 'pacientes'));
            return;
        }

        $this->model('Consulta')->update($id, $paciente_id, $data, $hora, $obs);
        header('Location: ' . BASE_URL . '/consulta/index');
        exit;
    }

    public function delete($id)
    {
        $this->authorize();
        $this->model('Consulta')->delete($id);
        header('Location: ' . BASE_URL . '/consulta/index');
        exit;
    }

    private function authorize()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login/index');
            exit;
        }
    }
}
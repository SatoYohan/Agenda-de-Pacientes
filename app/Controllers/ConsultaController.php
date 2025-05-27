<?php

namespace App\Controllers; // Add App\Controllers namespace

use Core\Controller; // Import the base Controller class

class ConsultaController extends Controller
{
    public function index()
    {
        $this->authorize();
        $consultaModel = $this->model('Consulta'); // Use the model name directly
        $consultas = $consultaModel->findAllWithPaciente();
        $this->view('consulta/index', [
            'consultas' => $consultas,
            'title' => 'Consultas Agendadas'
        ]);
    }

    public function create()
    {
        $this->authorize();
        $pacienteModel = $this->model('Paciente');
        $pacientes = $pacienteModel->findAll(1000); // Consider if fetching all is always needed
        $this->view('consulta/create', [
            'pacientes' => $pacientes,
            'title' => 'Nova Consulta'
        ]);
    }

    public function store()
    {
        $this->authorize();
        // Sanitize and validate input
        $paciente_id = filter_input(INPUT_POST, 'paciente_id', FILTER_VALIDATE_INT);
        $raw_datetime = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING); // Assuming 'data' is datetime-local
        $observacoes = trim(filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING) ?? '');

        $errors = [];
        if (empty($paciente_id)) {
            $errors[] = 'Paciente é obrigatório.';
        }

        $data = null;
        $hora = null;
        if (empty($raw_datetime)) {
            $errors[] = 'Data e Hora são obrigatórios.';
        } else {
            $dateTimeObj = date_create_from_format('Y-m-d\TH:i', $raw_datetime);
            if ($dateTimeObj === false) {
                $errors[] = 'Formato de Data e Hora inválido.';
            } else {
                $data = $dateTimeObj->format('Y-m-d');
                $hora = $dateTimeObj->format('H:i:s');
            }
        }


        if (!empty($errors)) {
            $pacienteModel = $this->model('Paciente');
            $pacientes = $pacienteModel->findAll(1000);
            $this->view('consulta/create', [
                'errors' => $errors,
                'paciente_id' => $paciente_id,
                'data' => $raw_datetime, // Send raw datetime back to prefill form
                'obs' => $observacoes, // Corrected variable name from 'obs' to 'observacoes' for consistency
                'pacientes' => $pacientes,
                'title' => 'Nova Consulta'
            ]);
            return;
        }

        $consultaModel = $this->model('Consulta');
        $consultaModel->create((int)$paciente_id, $data, $hora, $observacoes);

        $this->setFlashMessage('Consulta agendada com sucesso!', 'success');
        $this->redirect('/consulta/index');
    }

    public function edit($id)
    {
        $this->authorize();
        $consultaModel = $this->model('Consulta');
        $consulta = $consultaModel->findById((int)$id);

        if (!$consulta) {
            $this->setFlashMessage('Consulta não encontrada.', 'danger');
            $this->redirect('/consulta/index');
            return;
        }

        $pacienteModel = $this->model('Paciente');
        $pacientes = $pacienteModel->findAll(1000);

        // Prepare datetime-local value
        $datetimeValue = '';
        if (!empty($consulta['data']) && !empty($consulta['hora'])) {
            $datetimeValue = date('Y-m-d\TH:i', strtotime("{$consulta['data']} {$consulta['hora']}"));
        }

        $this->view('consulta/create', [ // Re-using create view for editing
            'consulta' => $consulta,
            'pacientes' => $pacientes,
            'datetimeValue' => $datetimeValue, // For prefilling datetime-local input
            'title' => 'Editar Consulta'
        ]);
    }

    public function update($id)
    {
        $this->authorize();
        $id = (int)$id;

        $paciente_id = filter_input(INPUT_POST, 'paciente_id', FILTER_VALIDATE_INT);
        $raw_datetime = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
        $observacoes = trim(filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING) ?? '');

        $errors = [];
        if (empty($paciente_id)) {
            $errors[] = 'Paciente é obrigatório.';
        }

        $data = null;
        $hora = null;
        if (empty($raw_datetime)) {
            $errors[] = 'Data e Hora são obrigatórios.';
        } else {
            $dateTimeObj = date_create_from_format('Y-m-d\TH:i', $raw_datetime);
            if ($dateTimeObj === false) {
                $errors[] = 'Formato de Data e Hora inválido.';
            } else {
                $data = $dateTimeObj->format('Y-m-d');
                $hora = $dateTimeObj->format('H:i:s');
            }
        }

        if (!empty($errors)) {
            $consultaModel = $this->model('Consulta'); // Added for consistency
            $consulta = $consultaModel->findById($id); // Fetch existing data to repopulate form
            $pacienteModel = $this->model('Paciente');
            $pacientes = $pacienteModel->findAll(1000);
            $this->view('consulta/create', [ // Re-using create view for editing
                'errors' => $errors,
                'consulta' => array_merge($consulta ?? [], ['id' => $id, 'paciente_id' => $paciente_id, 'observacoes' => $observacoes]),
                'datetimeValue' => $raw_datetime, // Send raw datetime back
                'pacientes' => $pacientes,
                'title' => 'Editar Consulta'
            ]);
            return;
        }

        $consultaModel = $this->model('Consulta');
        $consultaModel->update($id, (int)$paciente_id, $data, $hora, $observacoes);

        $this->setFlashMessage('Consulta atualizada com sucesso!', 'success');
        $this->redirect('/consulta/index');
    }

    public function delete($id)
    {
        $this->authorize();
        $this->model('Consulta')->delete((int)$id);
        $this->setFlashMessage('Consulta excluída com sucesso!', 'success');
        $this->redirect('/consulta/index');
    }

    private function authorize()
    {
        if (session_status() == PHP_SESSION_NONE) { // Ensure session is started
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            $this->setFlashMessage('Você precisa estar logado para acessar esta página.', 'warning');
            $this->redirect('/login/index');
        }
        // Add role-specific authorization if needed, e.g.
        // if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'normal') {
        //     $this->setFlashMessage('Você não tem permissão para acessar esta página.', 'danger');
        //     $this->redirect('/some/other/page');
        // }
    }
}
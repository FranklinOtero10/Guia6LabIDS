<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MateriasController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        if (!isset($this->session->userdata['logged_in'])) {
            redirect("/");
        }
    }

    // FUNCIONES QUE CARGAN VISTAS /////////////////////////////////////////////////////////
    public function index()
    {
        $this->load->model('MateriasModel');
        $data = array(
            "records" => $this->MateriasModel->getAll(),
            "title" => "Materias",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("materias/index", $data);
        $this->load->view("shared/footer");
    }

    public function insertar()
    {
        $this->load->model('MateriasModel');
        $data = array(
            "materias" => $this->MateriasModel->getAll(),
            "title" => "Insertar materia",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("materias/add_edit", $data);
        $this->load->view("shared/footer");
    }

    public function modificar($id)
    {
        $this->load->model('MateriasModel');
        $materia = $this->MateriasModel->getById($id);
        $data = array(
            "materias" => $this->MateriasModel->getAll(),
            "materia" => $materia,
            "title" => "Modificar materias",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("materias/add_edit", $data);
        $this->load->view("shared/footer");
    }
    // FIN - FUNCIONES QUE CARGAN VISTAS /////////////////////////////////////////////////////////

    // FUNCIONES QUE REALIZAN OPERACIONES /////////////////////////////////////////////////////////
    public function add()
    {

        // Reglas de validación del formulario
        /*
        required: indica que el campo es obligatorio.
        min_length: indica que la cadena debe tener al menos una cantidad determinada de caracteres.
        max_length: indica que la cadena debe tener como máximo una cantidad determinada de caracteres.
        valid_email: indica que el valor debe ser un correo con formato válido.
         */
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules("idmateria", "Id Materia", "required|min_length[10]|max_length[12]|is_unique[materias.idmateria]");
        $this->form_validation->set_rules("materia", "Materia", "required|max_length[100]");

        // Modificando el mensaje de validación para los errores
        $this->form_validation->set_message('required', 'El campo %s es requerido.');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s caracteres.');
        $this->form_validation->set_message('max_length', 'El campo %s debe tener como máximo %s caracteres.');
        $this->form_validation->set_message('valid_email', 'El campo %s no es un correo válido.');
        $this->form_validation->set_message('is_unique', 'El campo %s ya existe.');

        // Parámetros de respuesta
        header('Content-type: application/json');
        $statusCode = 200;
        $msg = "";

        // Se ejecuta la validación de los campos
        if ($this->form_validation->run()) {
            // Si la validación es correcta entra acá
            try {
                $this->load->model('MateriasModel');
                $data = array(
                    "idmateria" => $this->input->post("idmateria"),
                    "materia" => $this->input->post("materia")
                );
                $rows = $this->MateriasModel->insert($data);
                if ($rows > 0) {
                    $msg = "Información guardada correctamente.";
                } else {
                    $statusCode = 500;
                    $msg = "No se pudo guardar la información.";
                }
            } catch (Exception $ex) {
                $statusCode = 500;
                $msg = "Ocurrió un error." . $ex->getMessage();
            }
        } else {
            // Si la validación da error, entonces se ejecuta acá
            $statusCode = 400;
            $msg = "Ocurrieron errores de validación.";
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $this->data['errors'] = $errors;
        }
        // Se asigna el mensaje que llevará la respuesta
        $this->data['msg'] = $msg;
        // Se asigna el código de Estado HTTP
        $this->output->set_status_header($statusCode);
        // Se envía la respuesta en formato JSON
        echo json_encode($this->data);
    }

    public function update()
    {

        // Reglas de validación del formulario
        $this->form_validation->set_error_delimiters('', '');
        /*
        required: indica que el campo es obligatorio.
        min_length: indica que la cadena debe tener al menos una cantidad determinada de caracteres.
        max_length: indica que la cadena debe tener como máximo una cantidad determinada de caracteres.
        valid_email: indica que el valor debe ser un correo con formato válido.
         */
        $this->form_validation->set_rules("idmateria", "Id Materia", "required|min_length[11]|max_length[12]|is_unique[materias.idmateria]");
        $this->form_validation->set_rules("materia", "Materia", "required|max_length[100]");

        // Modificando el mensaje de validación para los errores, en este caso para
        // la regla required, min_length, max_length
        $this->form_validation->set_message('required', 'El campo %s es requerido.');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s caracteres.');
        $this->form_validation->set_message('max_length', 'El campo %s debe tener como máximo %s caracteres.');
        $this->form_validation->set_message('is_unique', 'El campo %s ya existe.');

        // Parámetros de respuesta
        header('Content-type: application/json');
        $statusCode = 200;
        $msg = "";

        // Se ejecuta la validación de los campos
        if ($this->form_validation->run()) {
            // Si la validación es correcta entra
            try {
                $this->load->model('MateriasModel');
                $data = array(
                    "idmateria" => $this->input->post("idmateria"),
                    "materia" => $this->input->post("materia")
                );
                $rows = $this->MateriasModel->update($data, $this->input->post("PK_materia"));
                $msg = "Información guardada correctamente.";
            } catch (Exception $ex) {
                $statusCode = 500;
                $msg = "Ocurrió un error." . $ex->getMessage();
            }
        } else {
            // Si la validación da error, entonces se ejecuta acá
            $statusCode = 400;
            $msg = "Ocurrieron errores de validación.";
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $this->data['errors'] = $errors;
        }
        // Se asigna el mensaje que llevará la respuesta
        $this->data['msg'] = $msg;
        // Se asigna el código de Estado HTTP
        $this->output->set_status_header($statusCode);
        // Se envía la respuesta en formato JSON
        echo json_encode($this->data);
    }

    public function eliminar($id)
    {
        $this->load->model('MateriasModel');
        $result = $this->MateriasModel->delete($id);
        if ($result) {
            $this->session->set_flashdata('success', "Registro borrado correctamente.");
        } else {
            $this->session->set_flashdata('error', "No se pudo borrar el registro.");
        }
        redirect("MateriasController");
    }
    // FIN - FUNCIONES QUE REALIZAN OPERACIONES /////////////////////////////////////////////////////////

}

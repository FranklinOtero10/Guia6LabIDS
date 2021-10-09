<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GruposController extends CI_Controller
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
        $this->load->model('GruposModel');
        $data = array(
            "records" => $this->GruposModel->getAll(),
            "title" => "Grupos",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("grupos/index", $data);
        $this->load->view("shared/footer");
    }

    public function insertar()
    {
        $this->load->model('MateriasModel');
        $this->load->model('ProfesoresModel');
        $data = array(
            "materias" => $this->MateriasModel->getAll(),
            "profesores" => $this->ProfesoresModel->getAll(),
            "title" => "Insertar grupo",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("grupos/add_edit", $data);
        $this->load->view("shared/footer");
    }

    public function modificar($id)
    {
        $this->load->model('MateriasModel');
        $this->load->model('ProfesoresModel');
        $this->load->model('GruposModel');
        $grupo = $this->GruposModel->getById($id);
        $data = array(
            "materias" => $this->MateriasModel->getAll(),
            "profesores" => $this->ProfesoresModel->getAll(),
            "grupo" => $grupo,
            "title" => "Modificar grupo",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("grupos/add_edit", $data);
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
        $this->form_validation->set_rules("idgrupo", "Id Grupo", "required|min_length[12]|max_length[12]|is_unique[grupos.idgrupo]");
        $this->form_validation->set_rules("num_grupo", "Num Grupo", "required|max_length[3]|min_length[3]|alpha_numeric_spaces");
        $this->form_validation->set_rules("anio", "Año", "required|max_length[4]");
        $this->form_validation->set_rules("ciclo", "Ciclo", "required|max_length[2]|min_length[2]");
        $this->form_validation->set_rules("idmateria", "Materia", "required|max_length[100]");
        $this->form_validation->set_rules("idprofesor", "Profesor", "required|max_length[100]");

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
                $this->load->model('GruposModel');
                $data = array(
                    "idgrupo" => $this->input->post("idgrupo"),
                    "num_grupo" => $this->input->post("num_grupo"),
                    "anio" => $this->input->post("anio"),
                    "ciclo" => $this->input->post("ciclo"),
                    "idmateria" => $this->input->post("idmateria"),
                    "idprofesor" => $this->input->post("idprofesor"),
                );
                $rows = $this->GruposModel->insert($data);
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
        $this->form_validation->set_rules("idgrupo", "Id Grupo", "required|min_length[12]|max_length[12]|is_unique[grupos.idgrupo]");
        $this->form_validation->set_rules("num_grupo", "Num Grupo", "required|max_length[1]|min_length[3]");
        $this->form_validation->set_rules("anio", "Año", "required|max_length[4]");
        $this->form_validation->set_rules("ciclo", "Ciclo", "required|max_length[2]|min_length[2]");
        $this->form_validation->set_rules("idmateria", "Materia", "required|max_length[100]");
        $this->form_validation->set_rules("idprofesor", "Profesor", "required|max_length[100]");

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
                $this->load->model('GruposModel');
                $data = array(
                    "idgrupo" => $this->input->post("idgrupo"),
                    "num_grupo" => $this->input->post("num_grupo"),
                    "anio" => $this->input->post("anio"),
                    "ciclo" => $this->input->post("ciclo"),
                    "idmateria" => $this->input->post("idmateria"),
                    "idprofesor" => $this->input->post("idprofesor"),
                );
                $rows = $this->GruposModel->update($data, $this->input->post("PK_grupo"));
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
        $this->load->model('GruposModel');
        $result = $this->GruposModel->delete($id);
        if ($result) {
            $this->session->set_flashdata('success', "Registro borrado correctamente.");
        } else {
            $this->session->set_flashdata('error', "No se pudo borrar el registro.");
        }
        redirect("GruposController");
    }
    // FIN - FUNCIONES QUE REALIZAN OPERACIONES /////////////////////////////////////////////////////////

}

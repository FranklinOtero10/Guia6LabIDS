<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfesoresController extends CI_Controller
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
        $this->load->model('ProfesoresModel');
        $data = array(
            "records" => $this->ProfesoresModel->getAll(),
            "title" => "Profesores",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("profesores/index", $data);
        $this->load->view("shared/footer");
    }

    public function insertar()
    {
        $data = array(
            "title" => "Insertar profesor",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("profesores/add_edit", $data);
        $this->load->view("shared/footer");
    }

    public function modificar($id)
    {
        $this->load->model('ProfesoresModel');
        $profesor = $this->ProfesoresModel->getById($id);
        $data = array(
            "profesor" => $profesor,
            "title" => "Modificar profesor",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("profesores/add_edit", $data);
        $this->load->view("shared/footer");
    }
    // FIN - FUNCIONES QUE CARGAN VISTAS /////////////////////////////////////////////////////////

    public function validate_empty($valor)
    {
        if (isset($valor)) {
            $this->form_validation->set_message('validate_empty', 'El campo {field} es requerido.');
            return false;
        } else {
            return true;
        }
    }

    // FUNCIONES QUE REALIZAN OPERACIONES /////////////////////////////////////////////////////////
    public function add()
    {

        // Reglas de validaci??n del formulario
        /*
        required: indica que el campo es obligatorio.
        min_length: indica que la cadena debe tener al menos una cantidad determinada de caracteres.
        max_length: indica que la cadena debe tener como m??ximo una cantidad determinada de caracteres.
        valid_email: indica que el valor debe ser un correo con formato v??lido.
         */
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules("nombre", "Nombre", "required|max_length[100]");
        $this->form_validation->set_rules("apellido", "Apellido", "required|max_length[100]");
        $this->form_validation->set_rules("email", "Email", "required|valid_email|max_length[150]|is_unique[profesores.email]");
        $this->form_validation->set_rules("fecha_nacimiento", "Fecha de Nacimiento", "required");
        $this->form_validation->set_rules("profesion", "Profesion", "required|max_length[100]");

        // Modificando el mensaje de validaci??n para los errores
        $this->form_validation->set_message('required', 'El campo %s es requerido.');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s caracteres.');
        $this->form_validation->set_message('max_length', 'El campo %s debe tener como m??ximo %s caracteres.');
        $this->form_validation->set_message('valid_email', 'El campo %s no es un correo v??lido.');
        $this->form_validation->set_message('is_unique', 'El campo %s ya existe.');

        //echo "Genero => " . $this->input->post("genero");

        // Par??metros de respuesta
        header('Content-type: application/json');
        $statusCode = 200;
        $msg = "";

        // Se ejecuta la validaci??n de los campos
        if ($this->form_validation->run()) {
            // Si la validaci??n es correcta entra ac??
            try {
                $this->load->model('ProfesoresModel');
                $data = array(
                    "nombre" => $this->input->post("nombre"),
                    "apellido" => $this->input->post("apellido"),
                    "email" => $this->input->post("email"),
                    "profesion" => $this->input->post("profesion"),
                    "genero" => $this->input->post("genero"),
                    "fecha_nacimiento" => $this->input->post("fecha_nacimiento"),
                );
                $rows = $this->ProfesoresModel->insert($data);
                if ($resMo > 0) {
                    $msg = "Informaci??n guardada correctamente.";
                } else {
                    $statusCode = 500;
                    $msg = "No se pudo guardar la informaci??n.";
                }
            } catch (Exception $ex) {
                $statusCode = 500;
                $msg = "Ocurri?? un error." . $ex->getMessage();
            }
        } else {
            // Si la validaci??n da error, entonces se ejecuta ac??
            $statusCode = 400;
            $msg = "Ocurrieron errores de validaci??n.";
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $this->data['errors'] = $errors;
        }
        // Se asigna el mensaje que llevar?? la respuesta
        $this->data['msg'] = $msg;
        // Se asigna el c??digo de Estado HTTP
        $this->output->set_status_header($statusCode);
        // Se env??a la respuesta en formato JSON
        echo json_encode($this->data);
    }

    public function update()
    {

        // Reglas de validaci??n del formulario
        $this->form_validation->set_error_delimiters('', '');
        /*
        required: indica que el campo es obligatorio.
        min_length: indica que la cadena debe tener al menos una cantidad determinada de caracteres.
        max_length: indica que la cadena debe tener como m??ximo una cantidad determinada de caracteres.
        valid_email: indica que el valor debe ser un correo con formato v??lido.
         */
        $this->form_validation->set_rules("nombre", "Nombre", "required|max_length[100]");
        $this->form_validation->set_rules("apellido", "Apellido", "required|max_length[100]");
        $this->form_validation->set_rules("email", "Email", "required|valid_email|max_length[150]");
        $this->form_validation->set_rules("fecha_nacimiento", "Fecha de Nacimiento", "required");
        $this->form_validation->set_rules("profesion", "Profesion", "required|max_length[100]");

        // Modificando el mensaje de validaci??n para los errores, en este caso para
        // la regla required, min_length, max_length
        $this->form_validation->set_message('required', 'El campo %s es requerido.');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s caracteres.');
        $this->form_validation->set_message('max_length', 'El campo %s debe tener como m??ximo %s caracteres.');

        // Par??metros de respuesta
        header('Content-type: application/json');
        $statusCode = 200;
        $msg = "";

        // Se ejecuta la validaci??n de los campos
        if ($this->form_validation->run()) {
            // Si la validaci??n es correcta entra
            try {
                $this->load->model('ProfesoresModel');
                $data = array(
                    "idprofesor" => $this->input->post("idprofesor"),
                    "nombre" => $this->input->post("nombre"),
                    "apellido" => $this->input->post("apellido"),
                    "email" => $this->input->post("email"),
                    "profesion" => $this->input->post("profesion"),
                    "genero" => $this->input->post("genero"),
                    "fecha_nacimiento" => $this->input->post("fecha_nacimiento"),
                );
                $rows = $this->ProfesoresModel->update($data, $this->input->post("PK_profesor"));
                $msg = "Informaci??n modificada correctamente.";
            } catch (Exception $ex) {
                $statusCode = 500;
                $msg = "Ocurri?? un error." . $ex->getMessage();
            }
        } else {
            // Si la validaci??n da error, entonces se ejecuta ac??
            $statusCode = 400;
            $msg = "Ocurrieron errores de validaci??n.";
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $this->data['errors'] = $errors;
        }
        // Se asigna el mensaje que llevar?? la respuesta
        $this->data['msg'] = $msg;
        // Se asigna el c??digo de Estado HTTP
        $this->output->set_status_header($statusCode);
        // Se env??a la respuesta en formato JSON
        echo json_encode($this->data);
    }

    public function eliminar($id)
    {
        $this->load->model('ProfesoresModel');
        $result = $this->ProfesoresModel->delete($id);
        if ($result) {
            $this->session->set_flashdata('success', "Registro borrado correctamente.");
        } else {
            $this->session->set_flashdata('error', "No se pudo borrar el registro.");
        }
        redirect("profesoresController");
    }
    // FIN - FUNCIONES QUE REALIZAN OPERACIONES /////////////////////////////////////////////////////////

}

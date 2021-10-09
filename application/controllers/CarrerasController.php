<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CarrerasController extends CI_Controller
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
        $this->load->model('CarrerasModel');
        $data = array(
            "records" => $this->CarrerasModel->getAll(),
            "title" => "Carreras",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("carreras/index", $data);
        $this->load->view("shared/footer");
    }

    public function insertar()
    {
        $data = array(
            "title" => "Insertar carrera",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("carreras/add_edit", $data);
        $this->load->view("shared/footer");
    }

    public function modificar($id)
    {
        $this->load->model('CarrerasModel');
        $carrera = $this->CarrerasModel->getById($id);
        $data = array(
            "carrera" => $carrera,
            "title" => "Modificar carrera",
        );
        $this->load->view("shared/header", $data);
        $this->load->view("carreras/add_edit", $data);
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
         */
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules(
            "idcarrera",
            "Id Carrera",
            "required|min_length[3]|max_length[3]|is_unique[carreras.idcarrera]"
        );
        $this->form_validation->set_rules(
            "carrera",
            "Carrera",
            "required|min_length[3]|max_length[100]"
        );

        // Modificando el mensaje de validación para los errores
        $this->form_validation->set_message(
            'required',
            'El campo %s es requerido.'
        );
        $this->form_validation->set_message(
            'min_length',
            'El campo %s debe tener al menos %s caracteres.'
        );
        $this->form_validation->set_message(
            'max_length',
            'El campo %s debe tener como máximo %s caracteres.'
        );
        $this->form_validation->set_message('is_unique', 'El campo %s ya existe.');

        // Parámetros de respuesta
        header('Content-type: application/json');
        $statusCode = 200;
        $msg = "";

        // Se ejecuta la validación del formulario
        if ($this->form_validation->run()) {
            // Si la validación fue exitosa, entonces entra acá
            try {
                $this->load->model('CarrerasModel');
                // Se crea el objeto con los campos de la tabla de carreras
                $data = array(
                    "idcarrera" => $this->input->post("idcarrera"),
                    "carrera" => $this->input->post("carrera"),
                );
                // Se pasan los valores al método insert del modelo
                $rows = $this->CarrerasModel->insert($data);
                // Si $rows devuelve un valor mayor a 1, la inserción fue exitosa
                if ($rows > 0) {
                    $msg = "Información guardada correctamente.";
                } else {
                    // Si $rows entra acá es porque hubo un error al insertar
                    $statusCode = 500;
                    $msg = "No se pudo guardar la información.";
                }
            } catch (Exception $ex) {
                // Si entra acá es porque hubo un error al momento de ejecutar este método
                $statusCode = 500;
                $msg = "Ocurrió un error.";
            }
        } else {
            // Si hubo errores de validación entra acá
            $statusCode = 400;
            $msg = "Ocurrieron errores de validación.";
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $this->data['errors'] = $errors;
        }
        $this->data['msg'] = $msg;
        $this->output->set_status_header($statusCode);
        // Se devuelve el objeto json con la información
        echo json_encode($this->data);
    }

    public function update()
    {
        // Reglas de validación del formulario
        /*
        required: indica que el campo es obligatorio.
        min_length: indica que la cadena debe tener al menos una cantidad determinada de caracteres.
        max_length: indica que la cadena debe tener como máximo una cantidad determinada de caracteres.
         */
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules("idcarrera", "Id Carrera", "required|min_length[3]|max_length[3]");
        $this->form_validation->set_rules("carrera", "Carrera", "required|min_length[3]|max_length[100]");

        // Modificando el mensaje de validación para los errores, en este caso para
        // la regla required
        $this->form_validation->set_message('required', 'El campo %s es requerido.');
        $this->form_validation->set_message('min_length', 'El campo %s debe tener al menos %s caracteres.');
        $this->form_validation->set_message('max_length', 'El campo %s debe tener como máximo %s caracteres.');

        // Parámetros de respuesta
        header('Content-type: application/json');
        $statusCode = 200;
        $msg = "";

        // Se ejecuta la validación del formulario
        if ($this->form_validation->run()) {
            // Si la validación fue exitosa, entonces entra acá
            try {
                $this->load->model('CarrerasModel');
                // Se crea el objeto con los campos de la tabla de carreras
                $data = array(
                    "idcarrera" => $this->input->post("idcarrera"),
                    "carrera" => $this->input->post("carrera"),
                );
                // Se pasan los valores al método update del modelo, junto con la llave primaria
                $rows = $this->CarrerasModel->update($data, $this->input->post("PK_carrera"));
                $msg = "Información guardada correctamente.";
            } catch (Exception $ex) {
                $statusCode = 500;
                $msg = "Ocurrió un error.";
            }
        } else {
            $statusCode = 400;
            $msg = "Ocurrieron errores de validación.";
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $this->data['errors'] = $errors;
        }
        $this->data['msg'] = $msg;
        $this->output->set_status_header($statusCode);
        // Se devuelve el objeto json con la información
        echo json_encode($this->data);
    }

    public function eliminar($id)
    {
        $this->load->model('CarrerasModel');
        $result = $this->CarrerasModel->delete($id);
        if ($result) {
            $this->session->set_flashdata('success', "Registro borrado correctamente.");
        } else {
            $this->session->set_flashdata('error', "No se pudo borrar el registro.");
        }
        redirect("carrerasController");
    }
    // FIN - FUNCIONES QUE REALIZAN OPERACIONES /////////////////////////////////////////////////////////

}

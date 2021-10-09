<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfesoresModel extends CI_Model
{
    public function construct()
    {
        parent::__construct();
    }
    public function getAll()
    {
        $query = $this->db->query("SELECT a.idprofesor, CONCAT(a.nombre, ' ', a.apellido) AS 'nombreCompleto',
                                   a.fecha_nacimiento, a.profesion, a.genero, a.email FROM profesores a;");
        $records = $query->result();
        return $records;
    }

    //Funcion que permite agregar elementos a la tabla carreras
    public function insert($data)
    {
        $this->db->insert("profesores", $data);
        $rows = $this->db->affected_rows();
        return $rows;
    }
    //Funcion que permite eliminar carreras
    public function delete($id)
    {
        if ($this->db->delete("profesores", "idprofesor='" . $id . "'")) {
            return true;
        }
    }
    ///Funcion que permite consultar carreras por ID
    public function getById($id)
    {
        return $this->db->get_where("profesores", array("idprofesor" => $id))->row();
    }

    ///Funcio que permite modificar.
    public function update($data, $id)
    {
        try {
            $this->db->where("idprofesor", $id);
            $this->db->update("profesores", $data);
            $rows = $this->db->affected_rows();
            return $rows;
        } catch (Exception $ex) {
            return -1;
        }
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CarrerasModel extends CI_Model
{
    public function construct()
    {
        parent::__construct();
    }
    public function getAll()
    {
        $query = $this->db->get("carreras");
        $records = $query->result();
        return $records;
    }

    //Funcion que permite agregar elementos a la tabla carreras
    public function insert($data)
    {
        $this->db->insert("carreras", $data);
        $rows = $this->db->affected_rows();
        return $rows;
    }
    //Funcion que permite eliminar carreras
    public function delete($id)
    {
        if ($this->db->delete("carreras", "idcarrera='" . $id . "'")) {
            return true;
        }
    }
    ///Funcion que permite consultar carreras por ID
    public function getById($id)
    {
        return $this->db->get_where("carreras", array("idcarrera" => $id))->row();
    }

    ///Funcio que permite modificar.
    public function update($data, $id)
    {
        try {
            $this->db->where("idcarrera", $id);
            $this->db->update("carreras", $data);
            $rows = $this->db->affected_rows();
            return $rows;
        } catch (Exception $ex) {
            return -1;
        }
    }
}

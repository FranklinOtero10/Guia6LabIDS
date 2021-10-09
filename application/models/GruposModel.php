<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GruposModel extends CI_Model
{
    public function construct()
    {
        parent::__construct();
    }
    public function getAll()
    {
        $query = $this->db->query("SELECT a.idgrupo, a.num_grupo, a.anio, a.ciclo,
        b.materia, CONCAT(c.nombre, ' ', c.apellido) AS 'nombreCompleto' 
        FROM grupos a, materias b, profesores c WHERE a.idmateria = b.idmateria and a.idprofesor = c.idprofesor;");
        $records = $query->result();
        return $records;
    }

    public function insert($data)
    {
        $this->db->insert("grupos", $data);
        $rows = $this->db->affected_rows();
        return $rows;
    }

    public function delete($id)
    {
        if ($this->db->delete("grupos", "idgrupo='" . $id . "'")) {
            return true;
        }
    }

    public function getById($id)
    {
        return $this->db->get_where("grupos", array("idgrupo" => $id))->row();
    }

    ///Funcio que permite modificar.
    public function update($data, $id)
    {
        try {
            $this->db->where("idgrupo", $id);
            $this->db->update("grupos", $data);
            $rows = $this->db->affected_rows();
            return $rows;
        } catch (Exception $ex) {
            return -1;
        }
    }
}

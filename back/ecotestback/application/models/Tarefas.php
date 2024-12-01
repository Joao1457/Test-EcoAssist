<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarefas extends CI_Model {

    public function get_all($pagenum = 0, $pagesize = 10)
    {
        $this->db->limit($pagesize, $pagenum * $pagesize);
        $tarefas = $this->db->get('tarefas');

        return $tarefas->result();  
    }

    public function get_by_id($id)
    {
        $tarefas = $this->db->get_where('tarefas', ['id' => $id]);

        return $tarefas->row(); 
    }

    public function save($data)
    {
        return $this->db->insert('tarefas', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tarefas', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tarefas');  
    }
}

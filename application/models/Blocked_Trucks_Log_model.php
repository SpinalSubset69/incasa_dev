<?php

class Blocked_Trucks_Log_model extends CI_Model
{
    // constructor of this class
    public function __construct()
    {
        $this->load->database();
    }

    // obtain the blocked log of a specific truck by the given plate
    public function getLog($plate)
    {
        $this->db->select("*");
        $this->db->from("blocked_trucks_log");
        $this->db->where("idTruck = \"$plate\"");

        $query = $this->db->get();
        return $query->result_array();
    }

    // insert a blocked log of a truck
    public function insertLog($plate, $end_date, $reason)
    {
        $this->db->set('idTruck', $plate);
        $this->db->set('dateStart', 'NOW()', false);
        $this->db->set('dateEnd', $end_date);
        $this->db->set('reason', $reason);

        $this->db->insert('blocked_trucks_log');
        
        return $this->db->insert_id();  // return the last generated id
    }
}

?>
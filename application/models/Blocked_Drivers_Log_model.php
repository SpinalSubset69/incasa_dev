<?php

class Blocked_Drivers_Log_model extends CI_Model
{
    // constructor of this class
    public function __construct()
    {
        $this->load->database();
    }

    // obtain the blocked log of a specific driver by the given id
    public function getLog($driver_id)
    {
        $this->db->select("*");
        $this->db->from("blocked_drivers_log");
        $this->db->where("idDriver = $driver_id");

        $query = $this->db->get();
        return $query->result_array();
    }

    // insert a blocked log of a driver
    public function insertLog($driver_id, $end_date, $reason)
    {
        $this->db->set('idDriver', $driver_id);
        $this->db->set('dateStart', 'NOW()', false);
        $this->db->set('dateEnd', $end_date);
        $this->db->set('reason', $reason);

        $this->db->insert('blocked_drivers_log');
        
        return $this->db->insert_id();  // return the last generated id
    }
}

?>
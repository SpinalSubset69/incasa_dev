<?php

class Drivers_model extends CI_Model
{

    // constructer of this class
    public function __construct()
    {
        $this->load->database();
    }

    // get all drivers from database
    public function getDrivers()
    {
        $this->db->select('*');
        $this->db->from('drivers');

        $query = $this->db->get();
        return $query->result_array();
    }

    // get id of a driver from the database by its name
    public function getIdDriverFromName($driver_name)
    {
        $this->db->select('*');
        $this->db->from('drivers');
        $this->db->where('nameDriver', $driver_name);

        $query = $this->db->get();

        if($query->num_rows() > 0)
            return $query->row()->idDriver; // the driver has been found

        return FALSE;                       // the driver has NOT been found
    }

    // insert a driver to the database with a given name
    public function insertDriver($driver_name)
    { 
        $this->db->set('nameDriver', $driver_name);
        $this->db->insert('drivers');

        $id = $this->db->insert_id();
        return $id;
    }

    // update a driver's name from the database by its id
    public function updateDriver($id, $driver_name)
    {
        $this->db->set('nameDriver', $driver_name);
        $this->db->where('idDriver', $id);

        return $this->db->update('drivers');
    }

    // remove a driver from the database from its id (hard delete)
    public function removeDriver($id)
    {
        $sqlCommand = "DELETE FROM drivers WHERE idDriver = $id";
        return $this->db->query($sqlCommand);
    }
}

?>
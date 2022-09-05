<?php

class Trucks_model extends CI_Model
{
    // constructor of this class
    public function __construct()
    {
        $this->load->database();
    }

    // get all trucks from the database
    public function getTrucks()
    {
        $this->db->select('*');
        $this->db->from('trucks');

        $query = $this->db->get();
        return $query->result_array();
    }

    // insert a truck to the database with the given plate and truck type
    public function insertTruck($plate, $truck_type)
    {
        $this->db->set('idTruck', $plate);
        $this->db->set('idType', $truck_type);

        $this->db->insert('trucks');

        return $this->db->insert_id();  // return the last inserted id
    }

    // update a truck's type from the database by its plate
    public function updateTruck($plate, $truck_type)
    {
        $this->db->set('idType', $truck_type);
        $this->db->where('idTruck', $plate);
        
        return $this->db->update('trucks');
    }

    // remove a truck from the database by its plate (hard delete)
    public function removeTruck($plate)
    {
        $sqlCommand = "DELETE FROM trucks WHERE idTruck = \"$plate\"";
        return $this->db->query($sqlCommand);
    }

    // --- THE METHODS BELLOW ARE NOT MINE --- Alan F.

    // get the truck by the specified plate from the database
    public function getTruck($plate)
    {
        $this->db->select('*');
        $this->db->from("trucks, truckTypes");
        $this->db->where('idTruck', $plate);
        $this->db->where('trucks.idType = truckTypes.idType');

        $query = $this->db->get();

        if ( $query->num_rows() == 1 )
            return $query->row();

        return FALSE;
    }

    // adds a truck with the given data to the database
    public function addTruck($data)
    {
        $this->db->insert('trucks', $data);
    }

    // why is this even here??? don't touch it; might break something - Alan F.
    public function existGPS($gps)
    {
        $this->db->select('*');
        $this->db->from("gps");
        $this->db->where('idGPS', $gps);

        $query = $this->db->get();

        if ( $query->num_rows() == 1 )
            return TRUE;

        return FALSE;
    }
}

?>
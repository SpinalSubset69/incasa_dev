<?php
class Trucks_model extends CI_Model{

    public function __construct(){
		$this->load->database();
	}

    public function getTruck($plate){
		$this->db->select('*');
		$this->db->from("trucks, truckTypes");
		$this->db->where('idTruck',$plate);
		$this->db->where('trucks.idType=truckTypes.idType');
		$query=$this->db->get();
		if($query->num_rows()==1)
			return $query->row();
		return FALSE;
	}

	public function existGPS($gps){
		$this->db->select('*');
		$this->db->from("gps");
		$this->db->where('idGPS',$gps);
		$query=$this->db->get();
		if($query->num_rows()==1)
			return TRUE;
		return FALSE;
	}
	
	public function addTruck($data){
        $this->db->insert('trucks', $data);
    }
    
}
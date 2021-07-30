<?php
class Users_model extends CI_Model{

    public function __construct(){
		$this->load->database();
	}

    public function login($username,$password){
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		$query=$this->db->get('users');
		if($query->num_rows()==1){
			return $query->row();
		}
		return FALSE;
	}

	public function addUser($user){
		$this->db->insert('users',$user);
	}

	public function addGPS($gps){
		$this->db->insert('gps',$gps);
	}
	
	public function getUser($username){
		$this->db->select('*');
        $this->db->where('username',$username);        
		$query=$this->db->get('users');
		if($query->num_rows()==1){
			return $query->row();
		}
		return NULL;
	}

	//select * from users, quarries where users.idQuarry=quarries.idQuarry
	public function getUsers(){
		$this->db->select('*');
		$this->db->from('users, quarries');
		$this->db->where('users.idQuarry=quarries.idQuarry'); 
		$query=$this->db->get();
		return $query->result_array();
	}

	public function getQuarries(){
		//select *,(select count(*) from log where log.idQuarry=10 and (log.arrival BETWEEN curdate() and NOW()) and log.departure is NULL) as dentro, (select count(*) from log where log.idQuarry=quarries.idQuarry and (log.arrival BETWEEN curdate() and NOW()) and log.departure is not NULL) as atendidos from quarries
		$this->db->select('*, (select count(*) from log where log.idQuarry=quarries.idQuarry and (log.arrival BETWEEN curdate() and NOW()) and log.departure is NULL) as dentro, (select count(*) from log where log.idQuarry=quarries.idQuarry and (log.arrival BETWEEN curdate() and NOW()) and log.departure is not NULL) as atendidos');
		$query=$this->db->get('quarries');
		return $query->result_array(); 
	}

	public function getNameQuarry($idQuarry){
		$this->db->select('*');
		$this->db->from('quarries');
		$this->db->where('idQuarry',$idQuarry); 
		$query=$this->db->get();
		return $query->row()->nameQuarry;
	}

	public function addQuarry($quarry){
		$this->db->insert('quarries',$quarry);
	}

	public function removeQuarry($id){
		$this->db->query("delete from quarries where idQuarry='".$id."'");
	}

	public function getBuildings(){
		$this->db->select('*');
		$this->db->from('buildings, quarries');
		$this->db->where('buildings.idQuarry=quarries.idQuarry'); 
		$query=$this->db->get();
		return $query->result_array(); 
	}	

	public function getBuildings2(){
		$this->db->select('*');
		$this->db->from('buildings, quarries');
		$this->db->where('buildings.idQuarry=quarries.idQuarry'); 
		$this->db->where('buildings.typeBuilding=2'); 
		$query=$this->db->get();
		return $query->result_array(); 
	}	

	//select *, (select count(*) from operators_buildings where operators_buildings.idBuilding=buildings.idBuilding and operators_buildings.idOperator=6) as active from buildings where buildings.idQuarry=1 and buildings.typeBuilding=2
	public function getBuildingsQuarryOperator($quarry, $idOperator){		
		$this->db->select('*, (select count(*) from operators_buildings where operators_buildings.idBuilding=buildings.idBuilding and operators_buildings.idOperator='.$idOperator.') as active');
		$this->db->from('buildings');
		$this->db->where('idQuarry',$quarry); 
		$this->db->where('typeBuilding=2'); 
		$query=$this->db->get();
		return $query->result_array(); 
	}

	public function getMaterialsBuilding($idBuilding){		
		//select *, (select count(*) from materials_buildings where materials_buildings.idMaterial=materials.idMaterial and materials_buildings.idBuilding=1) as active from materials
		$this->db->select('*, (select count(*) from materials_buildings where materials_buildings.idMaterial=materials.idMaterial and materials_buildings.idBuilding='.$idBuilding.') as active');
		$this->db->from('materials');
		$query=$this->db->get();
		return $query->result_array(); 
	}

	public function getBuildingsQuarry($quarry){		
		$this->db->select('*');
		$this->db->from('buildings');
		$this->db->where('idQuarry',$quarry); 
		$this->db->where('typeBuilding=2'); 
		$query=$this->db->get();
		return $query->result_array(); 
	}
	
	public function addMaterial($material){
		$this->db->insert('materials',$material);
	}
	
	public function addBuilding($building){
		$this->db->insert('buildings',$building);
		$id=$this->db->insert_id();
		return $id;
	}

	public function removeSite($id){
		$this->db->query("delete from buildings where idBuilding='".$id."'");
	}

	public function removeGPS($id){
		$this->db->query("delete from gps where idGPS='".$id."'");
	}

	public function getOperators(){
		$this->db->select('*');
        $this->db->where('usertype',3);        
        $query=$this->db->get('users');
		return $query->result_array(); 
	}

	public function updateOperatorBuilding($idUser, $idBuilding){
        $this->db->set('idBuilding', $idBuilding);
        $this->db->where('idUser', $idUser);
        $this->db->update('users');
    }

	public function getOperatorsGPS($idGPS){
		$this->db->select('*');
        $this->db->where('usertype',3); 
		$this->db->where('idGPS',$idGPS); 
        $query=$this->db->get('users');
		if($query->num_rows()==1){
			return $query->row();
		}
		return FALSE;
	}

	public function removePlantOperator($idOperator, $idBuilding){
		$this->db->query("delete from operators_buildings where idBuilding='".$idBuilding."' and idOperator='".$idOperator."'");
	}

	public function addPlantOperator($idOperator, $idBuilding){
		$this->db->insert('operators_buildings',array('idBuilding'=>$idBuilding, 'idOperator'=>$idOperator));
	}

	public function addGPSOperator($idOperator, $idGPS){
		$this->db->set('idGPS', $idGPS);           
        $this->db->where('idUser', $idOperator);
        $this->db->update('users');
		//$this->db->insert('operators_buildings',array('idBuilding'=>$idBuilding, 'idOperator'=>$idOperator));
	}

	public function removeMaterialPlant($idMaterial, $idBuilding){
		$this->db->query("delete from materials_buildings where idBuilding='".$idBuilding."' and idMaterial='".$idMaterial."'");
	}

	public function addMaterialPlant($idMaterial, $idBuilding){
		$this->db->insert('materials_buildings',array('idBuilding'=>$idBuilding, 'idMaterial'=>$idMaterial));
	}

	public function getOperatorsBuildings($idOperator){
        $this->db->select('operators_buildings.idOperator as idOperator, buildings.nameBuilding, operators_buildings.active as active, operators_buildings.idOB as idOB');
        $this->db->from('operators_buildings, buildings'); 
        $this->db->where("operators_buildings.idOperator",$idOperator);
        $this->db->where("operators_buildings.idBuilding=buildings.idBuilding");
        $query=$this->db->get();
		return $query->result_array();           
	}
	
	public function updateActiveBuilding($idOB, $active){
        $this->db->set('active', $active);           
        $this->db->where('idOB', $idOB);
        $this->db->update('operators_buildings');
    }
    
}
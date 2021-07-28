<?php
class Materials_model extends CI_Model{

    public function __construct(){
		$this->load->database();
    }

    public function getMaterials(){

        $this->db->select('*');
        $this->db->from('materials');
        //$this->db->from('materials, buildings, quarries');
        //$this->db->where('buildings.idQuarry=quarries.idQuarry');
        //$this->db->where('buildings.idBuilding=materials.idBuilding');
        //$this->db->where('materials.active=1');

		$query=$this->db->get();
		return $query->result_array();        		
    }

    public function updateMaterial($idMaterial, $active){
        $this->db->set('active', $active);           
        $this->db->where('idMaterial', $idMaterial);
        $this->db->update('materials');
    }

    public function updateNameMaterial($idMaterial, $name){
        $this->db->set('nameMaterial', $name);           
        $this->db->where('idMaterial', $idMaterial);
        $this->db->update('materials');
    }

    public function getAvailableMaterials($quarry){
        /* Version 1.0 */
        // $this->db->select('distinct(materials.idMaterial) as id, materials.nameMaterial');
		// $this->db->from('materials, materials_buildings');
        // $this->db->where("materials.idMaterial=materials_buildings.idMaterial");
        // $this->db->where("active=1");

        //select distinct(materials.idMaterial) as idMaterial, materials.nameMaterial from buildings, materials, materials_buildings where buildings.idQuarry = 8 and materials_buildings.idMaterial=materials.idMaterial and materials_buildings.idBuilding=buildings.idBuilding
        $this->db->select('distinct(materials.idMaterial) as idMaterial, materials.nameMaterial');
        $this->db->from('materials, buildings, materials_buildings');
        $this->db->where('buildings.idQuarry',$quarry);
        $this->db->where('materials_buildings.idMaterial=materials.idMaterial');
        $this->db->where('materials_buildings.idBuilding=buildings.idBuilding');

		$query=$this->db->get();
		return $query->result_array();        		
    }

    public function getGPS(){
        $this->db->select('*');
        $this->db->from('gps');
        $query=$this->db->get();
		return $query->result_array();
    }

    public function insertBuilding($buildingName){
        $this->db->set('nameBuilding', $buildingName);
        $this->db->insert('buildings');
    }

    public function insertMaterial($materialName){
        $this->db->set('nameMaterial', $materialName);
        $this->db->insert('materials');
    }
    
    public function getBuildings(){
        //SELECT DISTINCT(materials.idMaterial), materials.nameMaterial FROM materials, materials_buildings 
        //where materials.idMaterial=materials_buildings.idMaterial and active=1
        $this->db->select('*');
		$this->db->from('buildings');        
		$query=$this->db->get();
		return $query->result_array();        		
    }

    public function getBuilding($idBuilding){
        $this->db->select('*');
		$this->db->from('buildings'); 
        $this->db->where('idBuilding', $idBuilding);       
		$query=$this->db->get();
        return $query->row();
    }

    public function updateActiveMaterial($idMB, $active){
        $this->db->set('active', $active);           
        $this->db->where('idMB', $idMB);
        $this->db->update('materials_buildings');
    }

    public function getMaterialsBuildings($idBuilding){
        //SELECT materials.nameMaterial as nameMaterial, materials_buildings.active as active, materials_buildings.idMB as idMB 
        //FROM materials_buildings, materials 
        //where materials_buildings.idBuilding=1 
        //and materials_buildings.idMaterial=materials.idMaterial

        $this->db->select('materials_buildings.idBuilding as idBuilding, materials.nameMaterial as nameMaterial, materials_buildings.active as active, materials_buildings.idMB as idMB');
        $this->db->from('materials_buildings, materials'); 
        $this->db->where("materials_buildings.idBuilding",$idBuilding);
        $this->db->where("materials_buildings.idMaterial=materials.idMaterial");
        $query=$this->db->get();
		return $query->result_array();   
        
    }

    

    public function getAvailableBuilding($idMaterial){
        /* Se cuenta cada edificio cuantos camiones tiene en espera */
        /*
select materials_buildings.idMB, buildings.nameBuilding, cnt
from(select materials_buildings.idBuilding, count(log.idLog) as cnt
	 from materials_buildings left join log on materials_buildings.idMB = log.idMB and log.loaded is null
     group by 1 having cnt<3) p, materials_buildings, buildings
where p.idBuilding=materials_buildings.idBuilding and materials_buildings.idMaterial=2 and 
buildings.idBuilding=materials_buildings.idBuilding order by cnt desc
*/

        $this->db->select('materials_buildings.idMB, buildings.nameBuilding, materials.nameMaterial, cnt');
        $this->db->from("(select materials_buildings.idBuilding, count(log.idLog) as cnt
        from materials_buildings left join log on materials_buildings.idMB = log.idMB and log.loaded is null
        group by 1 having cnt<3) p, materials_buildings, buildings, materials");
        $this->db->where("p.idBuilding=materials_buildings.idBuilding");
        $this->db->where("materials_buildings.idMaterial=".$idMaterial);
        $this->db->where("buildings.idBuilding=materials_buildings.idBuilding");
        $this->db->where("materials.idMaterial=".$idMaterial);
        $this->db->where("materials_buildings.active=1");        
        $this->db->order_by("cnt","desc");
        $query=$this->db->get();
        $query=$query->row();
        if($query!=NULL)
            return $query;



        
        $this->db->select('materials_buildings.idMB, buildings.nameBuilding, materials.nameMaterial, cnt');
        $this->db->from("(select materials_buildings.idBuilding, count(log.idLog) as cnt
        from materials_buildings left join log on materials_buildings.idMB = log.idMB and log.loaded is null
        group by 1) p, materials_buildings, buildings, materials");
        $this->db->where("p.idBuilding=materials_buildings.idBuilding");
        $this->db->where("materials_buildings.idMaterial=".$idMaterial);
        $this->db->where("buildings.idBuilding=materials_buildings.idBuilding");
        $this->db->where("materials.idMaterial=".$idMaterial);
        $this->db->where("materials_buildings.active=1");
        $this->db->order_by("cnt","asc");
        $query=$this->db->get();
        $query=$query->row();
        return $query;

        /*
            select materials_buildings.idMB, buildings.nameBuilding, cnt
from(select materials_buildings.idBuilding, count(log.idLog) as cnt
	 from materials_buildings left join log on materials_buildings.idMB = log.idMB and log.loaded is null
     group by 1) p, materials_buildings, buildings
where p.idBuilding=materials_buildings.idBuilding and materials_buildings.idMaterial=2 and 
buildings.idBuilding=materials_buildings.idBuilding order by cnt asc
         */
        

    }
    
}
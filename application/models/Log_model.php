<?php
class Log_model extends CI_Model{

    public function __construct(){
		$this->load->database();
    }

    public function insertHistory($idLog, $desc){
        $this->db->set('idLog', $idLog);
        $this->db->set('description', $desc);        
        $this->db->set('date', 'NOW()', FALSE);
        $this->db->insert('history');
    }  

    public function getDrivers(){
        $this->db->select('nameDriver as name');
        $this->db->from('drivers');

		$query=$this->db->get();
		return $query->result_array();        		
    }

    public function getCompanies(){
        $this->db->select('nameCompany as name');
        $this->db->from('companies');

		$query=$this->db->get();
		return $query->result_array();        		
    }

    public function getIdDriver($driver){
        $this->db->select('*');
        $this->db->from('drivers');
        $this->db->where('nameDriver', $driver);
        $query=$this->db->get();
        if($query->num_rows()>0)
            return $query->row()->idDriver;
        
        $this->db->set('nameDriver', $driver);
        $this->db->insert('drivers');

        $id=$this->db->insert_id();
        return $id;

    }

    public function getIdCompany($company){
        $this->db->select('*');
        $this->db->from('companies');
        $this->db->where('nameCompany', $company);
        $query=$this->db->get();
        if($query->num_rows()>0)
            return $query->row()->idCompany;
        
        $this->db->set('nameCompany', $company);
        $this->db->insert('companies');

        $id=$this->db->insert_id();
        return $id;

    }

    public function updateTruckBuilding($idLog, $idBuilding){
        $this->db->set('idBuilding', $idBuilding);
        $this->db->where('idLog', $idLog);
        $this->db->update('log');
    }

    public function getVehicles2Plant($idBuilding){
        $this->db->select("idTruck");
        $this->db->from('log, materials_buildings');
        $this->db->where("log.idMaterial=materials_buildings.idMaterial");
        $this->db->where("materials_buildings.idBuilding=".$idBuilding);
        $this->db->where("departure is null");
        $query=$this->db->get();
        return $query->result_array();
    }

    public function getVehiclesinPlant($idBuilding){
        //select idTruck, IF(EXISTS(select * from materials_buildings where materials_buildings.idMaterial=log.idMaterial and materials_buildings.idBuilding=log.idBuilding),1,0) as good from log where log.idBuilding=48
        $this->db->select("idTruck, IF(EXISTS(select * from materials_buildings where materials_buildings.idMaterial=log.idMaterial and materials_buildings.idBuilding=log.idBuilding),1,0) as good");
        $this->db->from('log');
        $this->db->where("log.idBuilding=".$idBuilding);
        //$this->db->where("departure is null");
        $query=$this->db->get();
        return $query->result_array();
    }

    public function getAttendancePriority($idOperator){//adm - prioridad
        /*
        select *, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, (select TIME(min(log.arrival)) from log where log.idBuilding=buildings.idBuilding) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser!=13), 1, 0) as isOperator, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser=13), 1, 0) as isOnBuilding from buildings where buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idOperator=13 and operators_buildings.idBuilding=buildings.idBuilding) ORDER by isOnBuilding DESC, isOperator ASC, -time DESC, total desc
        */
        /*
        select *, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, TIMESTAMPDIFF(MINUTE, (select min(log.arrival) from log where log.idBuilding=buildings.idBuilding),now()) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser!=13), 1, 0) as isOperator, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser=13), 1, 0) as isOnBuilding from buildings where buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idOperator=35 and operators_buildings.idBuilding=buildings.idBuilding) ORDER by isOnBuilding DESC, isOperator ASC, -time DESC, total desc
         */
        $this->db->select("*, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, TIMESTAMPDIFF(MINUTE, (select min(log.arrival) from log where log.idBuilding=buildings.idBuilding),now()) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser!=".$idOperator."), 1, 0) as isOperator, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser=".$idOperator."), 1, 0) as isOnBuilding");
        $this->db->from('buildings');
        $this->db->where("buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idOperator=".$idOperator." and operators_buildings.idBuilding=buildings.idBuilding)");
        $this->db->order_by("isOnBuilding DESC, isOperator ASC, (time * -1) DESC, total DESC");
        $query=$this->db->get();
        $attendance=$query->result_array();
        if($attendance[0]['total']==0 && $attendance[0]['isOnBuilding']==1){//Le esta diciendo que se quede en la planta donde no hay ninguno ya, se vuelve a ejecutar
            $this->db->select("*, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, TIMESTAMPDIFF(MINUTE, (select min(log.arrival) from log where log.idBuilding=buildings.idBuilding),now()) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser!=".$idOperator."), 1, 0) as isOperator, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser=".$idOperator."), 1, 0) as isOnBuilding");
            $this->db->from('buildings');
            $this->db->where("buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idOperator=".$idOperator." and operators_buildings.idBuilding=buildings.idBuilding)");
            $this->db->order_by("isOperator ASC, (time * -1) DESC, total DESC");
            $query=$this->db->get();
            $attendance=$query->result_array();
        }
        /*
        date_default_timezone_set('America/Monterrey');
        $_attendance = [];
        foreach ($attendance as $att):
            if($att['time']!=null){
                $dateu = $att['time'];
                $dateu = mysql_to_unix($dateu);            
                if(date('I')==1) {
                    $dateu = gmt_to_local($dateu, "UP2", FALSE);
                }else
                    $dateu = gmt_to_local($dateu, "UP1", FALSE);
                $dateu = unix_to_human($dateu);                 
                $att['time']=explode(" ",$dateu)[1]." ".explode(" ",$dateu)[2];
            }
            $_attendance[] = $att;           
        endforeach;*/
        return $attendance;

    }

    public function getAttendance(){//adm - prioridad
        /*
        select *, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, (select TIME(min(log.arrival)) from log where log.idBuilding=buildings.idBuilding) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser!=13), 1, 0) as isOperator, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser=13), 1, 0) as isOnBuilding from buildings where buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idOperator=13 and operators_buildings.idBuilding=buildings.idBuilding) ORDER by isOnBuilding DESC, isOperator ASC, -time DESC, total desc
        */
        /*
        select *, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, TIMESTAMPDIFF(MINUTE, (select min(log.arrival) from log where log.idBuilding=buildings.idBuilding),now()) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser!=13), 1, 0) as isOperator, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser=13), 1, 0) as isOnBuilding from buildings where buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idOperator=35 and operators_buildings.idBuilding=buildings.idBuilding) ORDER by isOnBuilding DESC, isOperator ASC, -time DESC, total desc
         */
        $this->db->select("*, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, TIMESTAMPDIFF(MINUTE, (select min(log.arrival) from log where log.idBuilding=buildings.idBuilding),now()) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding), 1, 0) as isOperator");
        $this->db->from('buildings');
        $this->db->where("buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idBuilding=buildings.idBuilding)");
        $this->db->order_by("isOnBuilding DESC, isOperator ASC, (time * -1) DESC, total DESC");
        $query=$this->db->get();
        $attendance=$query->result_array();
        // if($attendance[0]['total']==0 && $attendance[0]['isOnBuilding']==1){//Le esta diciendo que se quede en la planta donde no hay ninguno ya, se vuelve a ejecutar
        //     $this->db->select("*, (select count(*) from log where log.idBuilding=buildings.idBuilding) as total, (select min(log.arrival) from log where log.idBuilding=buildings.idBuilding) as time, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser!=".$idOperator."), 1, 0) as isOperator, IF(EXISTS(select * from users where users.idBuilding=buildings.idBuilding and users.idUser=".$idOperator."), 1, 0) as isOnBuilding");
        //     $this->db->from('buildings');
        //     $this->db->where("buildings.typeBuilding=2 and EXISTS(select * from operators_buildings where operators_buildings.idOperator=".$idOperator." and operators_buildings.idBuilding=buildings.idBuilding)");
        //     $this->db->order_by("isOperator ASC, (time * -1) DESC, total DESC");
        //     $query=$this->db->get();
        //     $attendance=$query->result_array();
        // }
        return $attendance;

    }

    public function insertTimeTest(){
        $this->db->set('tiempo', 'UTC_TIMESTAMP()', FALSE);           
        $this->db->insert('test');
    }

    public function updateLog($field, $idLog, $desc){  
        $this->db->set($field, 'NOW()', FALSE);                   
        $this->db->where('idLog', $idLog);
        $this->db->update('log');

        if($field=="departure"){
            //Si es salida se pone el campo idGPS en NULL
            $this->db->set('idGPS', NULL);           
            $this->db->where('idLog', $idLog);
            $this->db->update('log');

            $this->db->set('idLog', $idLog);
            $this->db->set('description', $desc);
            $this->db->set('date', 'NOW()', FALSE);
            $this->db->insert('history');     
            /*$this->db->set('idLog', $idLog);
            $this->db->set('description', "Salida la empresa.");
            $this->db->set('time', 'NOW()', FALSE);
            $this->db->insert('incidents');*/
        }else if($field=="loaded"){        
            $this->db->set('idLog', $idLog);
            $this->db->set('description', "Vehiculo cargado.");
            $this->db->set('time', 'NOW()', FALSE);
            $this->db->insert('incidents');
        }else if($field=="weighed"){        
            $this->db->set('idLog', $idLog);
            $this->db->set('description', "Vehiculo pesado.");
            $this->db->set('time', 'NOW()', FALSE);
            $this->db->insert('incidents');
        }

    }   
    
    public function getLog(){
        $this->db->select("*, IF(departure is NULL,TIMESTAMPDIFF(MINUTE,arrival,NOW()),TIMESTAMPDIFF(MINUTE,arrival,departure)) as time");
        $this->db->from('log, materials, companies, drivers, quarries');
        $this->db->where("log.idMaterial=materials.idMaterial");
        //$this->db->where("log.idBuilding=buildings.idBuilding");
        $this->db->where("companies.idCompany=log.idCompany");
        $this->db->where("drivers.idDriver=log.idDriver");
        $this->db->where("quarries.idQuarry=log.idQuarry");

        $query=$this->db->get();
        return $query->result_array();
    }

    public function getLogId($idLog){
        $this->db->select("*, IF(departure is NULL,TIMESTAMPDIFF(MINUTE,arrival,NOW()),TIMESTAMPDIFF(MINUTE,arrival,departure)) as time");
        $this->db->from('log, materials, companies, drivers, quarries');
        $this->db->where("log.idMaterial=materials.idMaterial");
        //$this->db->where("log.idBuilding=buildings.idBuilding");
        $this->db->where("companies.idCompany=log.idCompany");
        $this->db->where("drivers.idDriver=log.idDriver");
        $this->db->where("quarries.idQuarry=log.idQuarry");
        $this->db->where("log.idLog=".$idLog);
        $query=$this->db->get();
        if($query->num_rows()>0)
            return $query->row();
        return FALSE;
    }

    public function getIncidents($idLog){
        $this->db->select("*");
        $this->db->from('history');        
        $this->db->where("idLog=".$idLog);
        $this->db->order_by("idHistory DESC");
        $query=$this->db->get();
        return $query;
    }

    //add as incidency
    public function insertLog($data){        
        //$this->db->set('arr ival', 'NOW()', FALSE);
        //UTC_TIMESTAMP()
        $this->db->set('arrival', 'NOW()', FALSE);
        $this->db->insert('log', $data);

        $idLog=$this->db->insert_id();
        return $idLog;
        /*
        
        $this->db->set('idLog', $idLog);
        $this->db->set('description', "Entrada a la empresa.");
        $this->db->set('time', 'NOW()', FALSE);
        $this->db->insert('incidents');
        */

    }

    public function isShovelSearching(){
        //SELECT * FROM shovelTrucks where idBuilding=NULL
        $this->db->where('idBuilding',NULL);
		$query=$this->db->get('shovelTrucks');
		if($query->num_rows()>0)
			return TRUE;
		return FALSE;
    }

    public function isBuildingAssigned($idUser){
        $this->db->select('*');
        $this->db->where('idShovel',$idUser);        
        $query=$this->db->get('shovelTrucks');        
        if($query->num_rows()==1)
            return $query->row();
    }

    public function insertShovelSearching($idUser){        
        $this->db->set('idShovel', $idUser);
        $this->db->insert('shovelTrucks');
    }

    public function getBuildingShovel($idBuilding){
        //select log.idTruck as plate, log.idLog as idLog, materials.nameMaterial as nameMaterial, buildings.nameBuilding as nameBuilding 
        //from log,materials,buildings,materials_buildings 
        //where attempt=(SELECT min(attempt) FROM log,materials_buildings where loaded is null and log.idMB=materials_buildings.idMB and materials_buildings.idBuilding=2) 
        //and log.idMB=materials_buildings.idMB 
        //and materials.idMaterial=materials_buildings.idMaterial 
        //and buildings.idBuilding=materials_buildings.idBuilding


        $this->db->select("log.idTruck as plate, log.idLog as idLog, materials.nameMaterial as nameMaterial, buildings.nameBuilding as nameBuilding, buildings.idBuilding as idBuilding");
        $this->db->from('log, materials, buildings, materials_buildings ');
        $this->db->where("attempt=(SELECT min(attempt) FROM log,materials_buildings where loaded is null and departure is null and log.idMB=materials_buildings.idMB and materials_buildings.idBuilding=".$idBuilding.")");
        $this->db->where("log.idMB=materials_buildings.idMB");
        $this->db->where("materials.idMaterial=materials_buildings.idMaterial");
        $this->db->where("buildings.idBuilding=materials_buildings.idBuilding");
        $query=$this->db->get();
        if($query->num_rows()==1)
            return $query->row();
        return FALSE;
    }

    public function removeShovel($idUser){
        $this->db->query("delete from shovelTrucks where idShovel='".$idUser."'");
    }

    public function getTrucksInside(){
        //select log.idTruck, log.idLog, buildings.nameBuilding 
        //from log, materials_buildings, buildings 
        //where loaded is null and departure is null 
        //and log.idMB=materials_buildings.idMB 
        //and materials_buildings.idBuilding=buildings.idBuilding 
        //order by buildings.nameBuilding
        $this->db->select("log.idTruck, log.idLog, buildings.nameBuilding");
        $this->db->from('log, materials_buildings, buildings');
        $this->db->where("loaded is null and departure is null");
        $this->db->where("log.idMB=materials_buildings.idMB");
        $this->db->where("materials_buildings.idBuilding=buildings.idBuilding");
        $this->db->order_by("buildings.nameBuilding");
        $query=$this->db->get();
        return $query->result_array(); 
    }

    public function getBuildingShovel2($idUser){
        //select log.idTruck as plate, log.idLog as idLog, materials.nameMaterial as nameMaterial, buildings.nameBuilding as nameBuilding, buildings.idBuilding as idBuilding 
        //from log,materials,buildings,materials_buildings 
        //where attempt=(SELECT min(attempt) FROM log where loaded is null) 
        //and log.idMB=materials_buildings.idMB 
        //and materials.idMaterial=materials_buildings.idMaterial 
        //and buildings.idBuilding=materials_buildings.idBuilding




        /*
            SELECT min(attempt) 
            FROM log where loaded is null and departure is null 
            and EXISTS (select operators_buildings.idBuilding from operators_buildings, materials_buildings 
                        where idOperator=2 and operators_buildings.active=1 
                        and materials_buildings.idBuilding=operators_buildings.idBuilding 
                        and materials_buildings.idMB=log.idMB)
         */

        $this->db->select("log.idTruck as plate, log.idLog as idLog, materials.nameMaterial as nameMaterial, buildings.nameBuilding as nameBuilding, buildings.idBuilding as idBuilding");
        $this->db->from('log, materials, buildings, materials_buildings ');
        $this->db->where("attempt=(SELECT min(attempt) 
        FROM log where loaded is null and departure is null 
        and EXISTS (select operators_buildings.idBuilding from operators_buildings, materials_buildings 
                    where idOperator=".$idUser." and operators_buildings.active=1 
                    and materials_buildings.idBuilding=operators_buildings.idBuilding 
                    and materials_buildings.idMB=log.idMB))");
        $this->db->where("log.idMB=materials_buildings.idMB");
        $this->db->where("materials.idMaterial=materials_buildings.idMaterial");
        $this->db->where("buildings.idBuilding=materials_buildings.idBuilding");        
        $query=$this->db->get();
        if($query->num_rows()==1)
            return $query->row();
        return FALSE;
    }

    public function updateShovelSearching($idUser, $idBuilding){    
        $value=array('idBuilding'=>$idBuilding);              
        $this->db->where('idShovel', $idUser);
        $this->db->update('shovelTrucks',$value);
    }    

    public function insertIncident($idLog, $desc){
        $this->db->set('idLog', $idLog);
        $this->db->set('description', $desc);
        $this->db->set('time', 'NOW()', FALSE);
        $this->db->insert('incidents');
    }  
    
    
    /* Obtain a truck that is inside */
    public function getTruck($plate){
        $this->db->select("*");
        $this->db->from('log, trucks, truckTypes, drivers, companies');
        $this->db->where("log.idTruck",$plate);   
        $this->db->where("log.idTruck=trucks.idTruck");   
        $this->db->where("trucks.idType=truckTypes.idType");   
        $this->db->where("log.idDriver=drivers.idDriver");   
        $this->db->where("log.idCompany=companies.idCompany");   
        $this->db->where('log.departure',NULL);        
        $query=$this->db->get();
        if($query->num_rows()>0)
            return $query->row();
        return FALSE;
    }

    /* Obtain a truck that is inside */
    public function getTruck2($gps){
        $this->db->select("*");
        $this->db->from('log, trucks, truckTypes, drivers, companies');
        $this->db->where("log.idGPS",$gps);   
        $this->db->where("log.idTruck=trucks.idTruck");   
        $this->db->where("trucks.idType=truckTypes.idType");   
        $this->db->where("log.idDriver=drivers.idDriver");   
        $this->db->where("log.idCompany=companies.idCompany");   
        $this->db->where('log.departure',NULL);        
        $query=$this->db->get();
        if($query->num_rows()>0)
            return $query->row();
        return FALSE;
    }

    public function isOccupied($gps){
        $this->db->select("*");
        $this->db->from('log');
        $this->db->where("idGPS",$gps);   
        $this->db->where('departure',NULL);        
        $query=$this->db->get();
        if($query->num_rows()>0)
            return TRUE;
        return FALSE;
    }

    public function isOccupiedOperator($gps){
        $this->db->select("*");
        $this->db->from('users');
        $this->db->where("idGPS",$gps);   
        $query=$this->db->get();
        if($query->num_rows()>0)
            return TRUE;
        return FALSE;
    }

    public function isTruckLeaving($idTruck){
        $this->db->select("*");
        $this->db->from('log');
        $this->db->where("idTruck",$idTruck);   
        $this->db->where('arrival is NOT NULL');
        $this->db->where('loaded is NOT NULL');
        $this->db->where('weighed is NOT NULL');
        $this->db->where('departure',NULL);
        $query=$this->db->get();
        if($query->num_rows()>0)
            return $query->row();
        return FALSE;
    }

    public function wasWeighed($idTruck){
        $this->db->select("*");
        $this->db->from('log');
        $this->db->where("idTruck",$idTruck);   
        $this->db->where('arrival is NOT NULL');
        $this->db->where('loaded is NOT NULL');
        $this->db->where('weighed', NULL);
        $this->db->where('departure',NULL);
        $query=$this->db->get();
        if($query->num_rows()>0)
            return $query->row();
        return FALSE;
    }    

}
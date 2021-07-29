<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//7028126751
//148.237.30.190
class Pedrera extends CI_Controller {

	public function __construct(){
		parent::__construct();

		//Helpers
		$this->load->library(array('session')); //Control de sesiones (Cookies)
		$this->load->helper('url'); //Para el base_url()
		$this->load->helper(['jwt', 'authorization']); // Loading jwt and authorization
		$this->load->helper('date');
		$this->load->helper('utils');//Utilerias para obtener el menu, permisos, etc


		//Models
		$this->load->model('Users_model'); //Users model {login}
		$this->load->model('Trucks_model'); 
		$this->load->model('Materials_model'); 	
		$this->load->model('Log_model'); 		
	}

	public function getMenu(){
		$menu=array();
		switch($this->session->userdata('usertype')){
			case 1://admin				
				array_push($menu, getMenuPedreras());
				array_push($menu, getMenuUsuarios());				
				array_push($menu, getMenuSites());
				array_push($menu, getMenuMaterials());				
				array_push($menu, getMenuLog());								
				break;
			case 5://bascula
				array_push($menu, getMenuCamiones());
			break;
		}				
		return $menu;
	  }
	
	public function index()
	{
		if($this->session->userdata('is_logued')){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';			
			if($this->session->userdata('usertype')==1){
				$data['type']='Administrador';
				redirect(base_url().'Pedrera/showQuarries','refresh');
				return;
			}
			if($this->session->userdata('usertype')==5){
				$data['type']='Báscula';
				redirect(base_url().'Pedrera/addTruck','refresh');
				return;
			}
			//$this->load->view('templates/header',$data);
      		//$this->load->view('pages/index');
      		//$this->load->view('templates/footer');			
		}else{
			redirect(base_url().'Pedrera/login','refresh');
		}		
	}

	public function showLog(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';			
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$data['mostrarModal']='';
			$data['error']='';
			//$data['log']=$this->Users_model->getUsers();
			//$data['usuarios']=array();
			$data['log']=$this->Log_model->getLog();
			$data["txt_fechainicio"]="";
    		$data["txt_fechafin"]="";

			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showLog', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function showDetails(){
		$idLog=$this->uri->segment(3);
		//Obtener log				
		$log=$this->Log_model->getLogId($idLog);
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1 && $log!=FALSE){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';			
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';
			$data['log']=$log;
			$incidents=$this->Log_model->getIncidents($idLog);
			$data['incidents']=$incidents->result_array();
			$data['total']=$incidents->num_rows();
			$this->load->view('templates/header',$data);
			$this->load->view('pages/showDetails', $data);
			$this->load->view('templates/footer');
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function removePlantOperator(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['idOperator'])){
				$idBuilding = $this->input->post("idBuilding");
				$idOperator = $this->input->post("idOperator");				
				$this->Users_model->removePlantOperator($idOperator, $idBuilding);
				$response=[];
                $response['idBuilding'] = $idBuilding;
				$response['idOperator'] = $idOperator;
                echo json_encode($response);
				//$this->session->set_userdata('success','La pedrera se ha registrado correctamente.'); 					
				//redirect(base_url().'Pedrera/showSites','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function addPlantOperator(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['idOperator'])){
				$idBuilding = $this->input->post("idBuilding");
				$idOperator = $this->input->post("idOperator");				
				$this->Users_model->addPlantOperator($idOperator, $idBuilding);
				$response=[];
                $response['idBuilding'] = $idBuilding;
				$response['idOperator'] = $idOperator;
                echo json_encode($response);
				//$this->session->set_userdata('success','La pedrera se ha registrado correctamente.'); 					
				//redirect(base_url().'Pedrera/showSites','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function addGPSOperator(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['idOperator'])){
				$idGPS = $this->input->post("idGPS");
				$idOperator = $this->input->post("idOperator");				
				$this->Users_model->addGPSOperator($idOperator, $idGPS);
				$response=[];
                $response['idGPS'] = $idGPS;
				$response['idOperator'] = $idOperator;
                echo json_encode($response);
				//$this->session->set_userdata('success','La pedrera se ha registrado correctamente.'); 					
				//redirect(base_url().'Pedrera/showSites','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function addMaterialPlant(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['idBuilding'])){
				$idBuilding = $this->input->post("idBuilding");
				$idMaterial = $this->input->post("idMaterial");				
				$this->Users_model->addMaterialPlant($idMaterial, $idBuilding);
				$response=[];
                $response['idBuilding'] = $idBuilding;
				$response['idMaterial'] = $idMaterial;
                echo json_encode($response);
				//$this->session->set_userdata('success','La pedrera se ha registrado correctamente.'); 					
				//redirect(base_url().'Pedrera/showSites','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function removeMaterialPlant(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['idBuilding'])){
				$idBuilding = $this->input->post("idBuilding");
				$idMaterial = $this->input->post("idMaterial");				
				$this->Users_model->removeMaterialPlant($idMaterial, $idBuilding);
				$response=[];
                $response['idBuilding'] = $idBuilding;
				$response['idMaterial'] = $idMaterial;
                echo json_encode($response);
				//$this->session->set_userdata('success','La pedrera se ha registrado correctamente.'); 					
				//redirect(base_url().'Pedrera/showSites','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function showOperators(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';	
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$operators = $this->Users_model->getOperators();
			//$data['buildings']=$this->Users_model->getBuildings();
			//$quarries = $this->Users_model->getQuarries();
			//$data['materials'] = $this->Materials_model->getMaterials();
			
			$_operators = [];
			$gps = $this->Materials_model->getGPS();
			foreach ($operators as $operator):
				$operator['buildings'] = $this->Users_model->getBuildingsQuarryOperator($operator['idQuarry'], $operator['idUser']);
				$_gps = [];
				foreach ($gps as $gp):
					if(!($this->Log_model->isOccupied($gp['idGPS']) || $this->Log_model->isOccupiedOperator($gp['idGPS']))){
						$_gps[] = $gp;
					}
					if($gp['idGPS']==$operator['idGPS']){
						$_gps[] = $gp;
					}
				endforeach;
				$operator['gps'] = $_gps;
				$_operators[] = $operator;
			endforeach;

			$data['operators'] = $_operators;												


			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showOperators', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function showMaterialsQuarry(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';	
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			//$operators = $this->Users_model->getOperators();
			$buildings = $this->Users_model->getBuildings2();
			//$quarries = $this->Users_model->getQuarries();
			//$data['materials'] = $this->Materials_model->getMaterials();
			
			$_buildings = [];
			foreach ($buildings as $building):
				//getBuildingsQuarryOperator
				$building['materials'] = $this->Users_model->getMaterialsBuilding($building['idBuilding']);
				$_buildings[] = $building;
			endforeach;

			$data['buildings'] = $_buildings;
			//$data['quarries'] = $_quarries;

			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showMaterialsQuarry', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function activateMaterial(){		
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['idMaterial'])){
				$idMaterial = $this->input->post("idMaterial");
				$active = $this->input->post("active");
				$this->Materials_model->updateMaterial($idMaterial, $active);
				$response=[];
                $response['status'] = $active;
                echo json_encode($response);
				//$this->session->set_userdata('success','La pedrera se ha registrado correctamente.'); 					
				//redirect(base_url().'Pedrera/showSites','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function showMaterials(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';	
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			//$data['buildings']=$this->Users_model->getBuildings();
			//$quarries = $this->Users_model->getQuarries();
			$data['materials'] = $this->Materials_model->getMaterials();
			
			/*
			$_quarries = [];
			foreach ($quarries as $quarry):
				$quarry['buildings'] = $this->Users_model->getBuildingsQuarry($quarry['idQuarry']);				
				$_quarries[] = $quarry;
			endforeach;

			$data['quarries'] = $_quarries;*/

			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showMaterials', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function editMaterial(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['txt_name_edit'])){
				$name = $this->input->post("txt_name_edit");
				$id = $this->input->post("txt_id");

				$this->Materials_model->updateNameMaterial($id, $name);
				$this->session->set_userdata('success','Se ha cambiado el nombre del material correctamente.'); 					
				redirect(base_url().'Pedrera/showMaterials','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function addMaterial(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['txt_name'])){
				$name = $this->input->post("txt_name");
				//$idPlanta = $this->input->post("txt_Planta");				
				$this->Users_model->addMaterial(array('nameMaterial' => $name));
				$this->session->set_userdata('success','El material se ha registrado correctamente.'); 					
				redirect(base_url().'Pedrera/showMaterials','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function showSites(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';	
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$data['buildings']=$this->Users_model->getBuildings();
			$data['quarries'] = $this->Users_model->getQuarries();
			

			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showSites', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function showGPS(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';	
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			//$data['buildings']=$this->Users_model->getBuildings();
			//$data['quarries'] = $this->Users_model->getQuarries();
			$data['gps'] = $this->Materials_model->getGPS();
			

			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showGPS', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function addSite(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['txt_name'])){
				$name = $this->input->post("txt_name");
				$tipoSitio = $this->input->post("txt_tipoSitio");
				$pedrera = $this->input->post("txt_Pedrera");
				$namePedrera = $this->Users_model->getNameQuarry($pedrera);
				$idBuilding = $this->Users_model->addBuilding(array('idQuarry'=>$pedrera, 'typeBuilding' => $tipoSitio, 'nameBuilding' => $name));
				//$data['mostrarModal']='mostrarModal';
				//$data['txtModal']='La pedrera se ha registrado correctamente.';
				$this->session->set_userdata('success','El sitio se ha registrado correctamente. Dar de alta en Global Track con el nombre:<br>'.$idBuilding); 					
				redirect(base_url().'Pedrera/showSites','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function addGPS(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['txt_gps'])){
				$gps = $this->input->post("txt_gps");
				if($this->Trucks_model->existGPS($gps)){
					$this->session->set_userdata('error','El GPS ya esta registrado.'); 					
					redirect(base_url().'Pedrera/showGPS','refresh');
				}else{
					$this->Users_model->addGPS(array('idGPS'=>$gps));
					//$data['mostrarModal']='mostrarModal';
					//$data['txtModal']='La pedrera se ha registrado correctamente.';
					$this->session->set_userdata('success','El GPS se ha registrado correctamente.'); 					
					redirect(base_url().'Pedrera/showGPS','refresh');
				}
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function removeSite(){
		$id = $this->uri->segment(3);
		if($id!='' && $this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$this->Users_model->removeSite($id);			
			redirect(base_url().'Pedrera/showSites','refresh');
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function removeGPS(){
		$id = $this->uri->segment(3);
		if($id!='' && $this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$this->Users_model->removeGPS($id);			
			redirect(base_url().'Pedrera/showGPS','refresh');
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function showQuarries(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';	
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$data['pedreras']=$this->Users_model->getQuarries();
			

			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showQuarries', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}	

	public function removeQuarry(){
		$id = $this->uri->segment(3);
		if($id!='' && $this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$this->Users_model->removeQuarry($id);			
			redirect(base_url().'Pedrera/showQuarries','refresh');
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function addQuarry(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){				
			if(isset($_POST['txt_name'])){
				$name = $this->input->post("txt_name");

				$this->Users_model->addQuarry(array('nameQuarry' => $name));
				//$data['mostrarModal']='mostrarModal';
				//$data['txtModal']='La pedrera se ha registrado correctamente.';
				$this->session->set_userdata('success','La pedrera se ha registrado correctamente.'); 					
				redirect(base_url().'Pedrera/showQuarries','refresh');
			}
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function showUsers(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';	
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$data['usuarios']=$this->Users_model->getUsers();
			$data['userConnected']=$this->session->userdata('user');
			

			$this->load->view('templates/header',$data);
    		$this->load->view('pages/showUsers', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}

	}

	public function addUsers(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==1){	
			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';			
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$data['mostrarModal']='';
			$data['error']='';
			$data['userName']='';
			$data['user']='';
			$data['password']='';
			$data['tipoUsuario']='';
			$data['pedrera']='';
			$data['quarries'] = $this->Users_model->getQuarries();

			if(isset($_POST['txt_userName'])){
				$userName = $this->input->post("txt_userName");
				$user = $this->input->post("txt_user");
				$password = $this->input->post("txt_password");
				$tipoUsuario = $this->input->post("txt_tipoUsuario");
				$pedrera = $this->input->post("txt_Pedrera");

				$data['userName']=$userName;
				$data['user']=$user;
				$data['password']=$password;
				$data['tipoUsuario']=$tipoUsuario;
				$data['pedrera']=$pedrera;

				//Verificar si ya existe el nombre de usuario
				if($this->Users_model->getUser($user)==NULL){
					//Agregarlo
					$newuser = array(
						'nameUser' => $userName,
						'username' => $user,
						'password' => hash('sha256', $password),
						'usertype' => $tipoUsuario,
						'idQuarry' => $pedrera
					);

					$this->Users_model->addUser($newuser);
					$data['mostrarModal']='mostrarModal';
					$data['txtModal']='El usuario se ha registrado correctamente.';

				}else{
					$data['error']='El nombre de usuario ya existe';
				}
				
			}


			$this->load->view('templates/header',$data);
    		$this->load->view('pages/addUsers', $data);
    		$this->load->view('templates/footer');

		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function addTruck(){				

		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==5){			

			$data['plate']='';
			$data['bedColor']='';
			$data['truckColor']='';
			$data['tipo']='';
			$data['driver']='';
			$data['company']='';
			$data['materialSel']=-1;	
			$data['gps2'] = '';		
			$quarry = $this->session->userdata('quarry');
			$data['materials'] = $this->Materials_model->getAvailableMaterials($quarry);
			$gps = $this->Materials_model->getGPS();//Corregir

			$gps = $this->Materials_model->getGPS();
			
			$_gps = [];
			foreach ($gps as $gp):
				if(!($this->Log_model->isOccupied($gp['idGPS']) || $this->Log_model->isOccupiedOperator($gp['idGPS']))){
					$_gps[] = $gp;
				}
			endforeach;
			$data['gps'] = $_gps;

			$data['drivers'] = $this->Log_model->getDrivers();
			$data['companies'] = $this->Log_model->getCompanies();

			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';			
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$data['mostrarModal']='';
			$data['error']='';

			if(isset($_POST['txt_plate'])){
				$plate = $this->input->post("txt_plate");
				$material = $this->input->post("txt_material");
				$driver = $this->input->post("txt_driver");
				$company = $this->input->post("txt_company");
				$gps2 = $this->input->post("txt_gps");

				$plate = strtoupper($plate);
				$driver = strtoupper($driver);
				$company = strtoupper($company);

				$data['plate']=$plate;
				$data['materialSel']=$material;
				$data['driver']=$driver;
				$data['company']=$company;
				$data['gps2'] = $gps2;

				if($this->Trucks_model->getTruck($plate)!=FALSE){
					//Error existe el vehículo
					$data["error"]="El vehículo con esa placa ya está registrado!";
				}else{
					// Insert the truck
					$truck = array(
						'idTruck' => $plate,
						'bedColor' => '',
						'truckColor' => '',
						'idType' => 1
					);

					$this->Trucks_model->addTruck($truck);

					$idDriver = $this->Log_model->getIdDriver($driver);
					$idCompany = $this->Log_model->getIdCompany($company);

					$log = array(
						'idTruck' => $plate,
						'idMaterial' => $material,
						'idQuarry' => $quarry,
						'idDriver' => $idDriver,
						'idCompany' => $idCompany,
						'idGPS' => $gps2,
						'idGPS2' => $gps2
					);
	
					//adm - Descomentar en producción
					$idLog = $this->Log_model->insertLog($log);					

					//Insertar en el historial
					$this->Log_model->insertHistory($idLog, "Registro de vehículo en báscula");


					//$material=$this->Materials_model->getAvailableBuilding($material);

					/* Insertar en la tabla log, el $material->idMB, el $plate, y un NOW() */

					/*$log = array(
						'idTruck' => $plate,
						'idMB' => $material->idMB,
						'driver' => $driver,
						'company' => $company
					);*/

					//adm - Descomentar en producción
					//$this->Log_model->insertLog($log);

					$data['mostrarModal'] = "mostrarModal";
					$data['txtModal'] = "El vehículo se ha registrado correctamente.";
				}
			}

    		$this->load->view('templates/header',$data);
    		$this->load->view('pages/addTruck', $data);
    		$this->load->view('templates/footer');
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function weighTruck(){
		if($this->session->userdata('is_logued') && $this->session->userdata('usertype')==5){
			$data['plate']='';
			$data['tipo'] = '';
			$data['company'] = '';
			$data['driver'] = '';
			$data['truckColor'] = '';
			$data['bedColor'] = '';
			$data['idLog'] = '';

			$data['menu']=$this->getMenu();
			$data['user']=$this->session->userdata('username');
			$data['type']='';
			if($this->session->userdata('usertype')==1)
				$data['type']='Administrador';
			if($this->session->userdata('usertype')==5)
				$data['type']='Báscula';

			$data['mostrarModal']='';
			$data['txtModal'] = '';
			$data['showInfo']='';
			$data['error']='';

			if(isset($_POST['idLog'])){
				$idLog = $this->input->post("idLog");
				$this->Log_model->updateLog("weighed",$idLog);
				$data['mostrarModal'] = "mostrarModal";
				$data['txtModal'] = "El vehículo fué pesado exitosamente.";
			}

			if(isset($_POST['txt_plate'])){
				$plate = $this->input->post("txt_plate");

				$data['plate']=$plate;

				if($this->Trucks_model->getTruck($plate)!=FALSE){
					//Existe el vehículo
					$log = $this->Log_model->getTruck($plate);
					if($log){
						$data['plate'] = $plate;
						$data['idLog'] = $log->idLog;
						$data['tipo'] = $log->typeName;
						$data['company'] = $log->company;
						$data['driver'] = $log->driver;
						$data['truckColor'] = $log->truckColor;
						$data['bedColor'] = $log->bedColor;
						$data['showInfo']='showInfo';
					}else{//El vehiculo no esta dentro de la empresa
						$data["error"]="El vehículo no está dentro de la empresa.";
					}
				}else{
					$data["error"]="El vehículo con esa placa no está registrado.";
				}
			}

    		$this->load->view('templates/header',$data);
    		$this->load->view('pages/weighTruck', $data);
    		$this->load->view('templates/footer');
		}else{
			$data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no puede tener acceso a la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url().'Pedrera/login','refresh');
	
    }
    
    public function prueba(){  
		/*$now = now("America/Monterrey");
		$datestring = '%Y-%m-%d %h:%i:%s';
		echo $now;
		echo "<br>";
		echo mdate($datestring,$now);*/
		//1627557807
		//2021-07-29 11:23:27
		$unix = mysql_to_unix('2021-07-29 11:23:27');
		echo $unix;
        return;
    }

	public function login(){
		
		$data["error"]=NULL;
		if(isset($_POST['txt_usuario'])){
			$username=$this->input->post("txt_usuario");
			$password=$this->input->post("txt_contrasena");
			$user = $this->Users_model->login($username, $password);

			// Check if valid user
			if ($user) {
				if($user->usertype==1 || $user->usertype==5){
					// Create a token from the user data and send it as reponse
					$token = AUTHORIZATION::generateToken(['username' => $user->username]);
					$data=array(
						'is_logued'=>TRUE,
						'idUser' => $user->idUser,
						'username'=>$user->nameUser,
						'user' => $user->username,
						'usertype'=>$user->usertype,
						'token'=>$token,
						'quarry' => $user->idQuarry
					);
					$this->session->set_userdata($data);
					redirect(base_url().'Pedrera/index','refresh');
				}else{
					$data["error"]='El usuario no tiene acceso al sistema.';	
				}
			} else {
				$data["error"]='Usuario y/o contraseña Incorrectos!';
			}  
		}
		$this->load->view('pages/login',$data);
	}
}

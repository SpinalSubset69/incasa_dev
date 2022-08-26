<?php
defined('BASEPATH') or exit('No direct script access allowed');

//default website
//https://incasapac.com/PedreraMobile/

use chriskacerguis\RestServer\RestController;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api extends RestController
{


	function __construct()
	{
		// Construct the parent class
		parent::__construct();

		//Load Helpers		
		$this->load->helper(['jwt', 'authorization']); // Loading jwt and authorization
		$this->load->helper('date');
		
		//Load models
		$this->load->model('Users_model'); //Users model {login}
		$this->load->model('Trucks_model'); //Trucks model {getTruck}
		$this->load->model('Materials_model'); //Materials model {getAvailableMaterials}
		$this->load->model('Log_model'); //Log model {insertLog}

	}	

	/* Version 2.0 */

	/* Login en el sistema */
	public function login_post()
	{

		// Extract user data from POST request
		$username = $this->post('username');
		$password = $this->post('password');


		$user = $this->Users_model->login($username, $password);

		// Check if valid user
		if ($user) {

			// Create a token from the user data and send it as reponse
			$token = AUTHORIZATION::generateToken(['username' => $user->username, 'quarry'=>$user->idQuarry]);
			// Prepare the response
			$status = parent::HTTP_OK;
			$response = ['status' => $status, 'quarry'=> $user->idQuarry, 'token' => $token, 'user' => $username, 'usertype' => $user->usertype];
			$this->response($response, $status);
		} else {
			$this->response(['msg' => 'Usuario y/o password Incorrectos!'], parent::HTTP_NOT_FOUND);
		}
	}

	/* Verificar si el vehiculo esta llegando o saliendo */
	public function isLeavingTruck_post()
    {

        // Get all the headers
        $headers = $this->input->request_headers();

        // Extract the token
        $token = $headers['Authorization'];
        $token = sscanf($token, 'Bearer %s')[0];

        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
                $this->response($response, $status);
                exit();
            } else {
                // Extract plate number from POST request
                $plate = $this->post('plate');                                                            

                // Obtain the truck from the model
                $truck = $this->Trucks_model->getTruck($plate);

                // Check if valid truck
                if ($truck) {                    					
					
					// Obtain if exist a row in the log (no departure, and this vehicle)
					$log = $this->Log_model->getTruck($plate);					
                    
					if($log){//Salida
						$status = parent::HTTP_OK;
					    $response = ['status' => $status, 'gps' => $log->idGPS, 'isExit'=>1, 'idLog' => $log->idLog, 'driver' => $log->nameDriver, 'company' => $log->nameCompany, 'mica' => $log->mica];
						$this->response($response, $status);					                
                    }else{//Es entrada
                        $status = parent::HTTP_OK;
					    $response = ['status' => $status, 'isExit'=>0];						
					    $this->response($response, $status);
                    }

                } else {
					//Se envio el gps en lugar de la placa, es salida
					$log = $this->Log_model->getTruck2($plate);
					if($log){//Salida
						$status = parent::HTTP_OK;
					    $response = ['status' => $status, 'plate'=>$log->idTruck,'gps' => $log->idGPS, 'isExit'=>1, 'idLog' => $log->idLog, 'driver' => $log->nameDriver, 'company' => $log->nameCompany];
						$this->response($response, $status);					                
                    }else{				
                    	$this->response(['msg' => 'El transporte no está registrado o GPS inexsitente.'], parent::HTTP_NOT_FOUND);
					}
                }
            }
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            $this->response($response, $status);
        }
    }

	/* Obtener lista conductores en la pedrera */
	public function getDrivers_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Obtain the materials
				//$quarry = $data->quarry;
				$drivers = $this->Log_model->getDrivers();
				$companies = $this->Log_model->getCompanies();
				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'drivers' => $drivers, 'companies' => $companies];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

	/* Obtener los materiales disponibles en la pedrera */
	public function getAvailableMaterials_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Obtain the materials
				$quarry = $data->quarry;
				$materials = $this->Materials_model->getAvailableMaterials($quarry);
				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'materials' => $materials];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

	/* Obtener los gps en la pedrera */
	public function getGPS_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Obtain the materials
				//$quarry = $data->quarry;
				$gps = $this->Materials_model->getGPS();
				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'gps' => $gps];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

	/* Obtener los materiales disponibles en la pedrera */
	public function getAttendancePriority_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Obtain the materials
				$quarry = $data->quarry;
				//Obtener los datos del usuario
                $user = $this->Users_model->getUser($data->username);

				$plants = $this->Log_model->getAttendancePriority($user->idUser);

				/* Obtener los vehiculos a planta */
				$_plants = [];
				foreach ($plants as $plant):
					$plant['v2plant'] = $this->Log_model->getVehicles2Plant($plant['idBuilding']);
					$plant['vInplant'] = $this->Log_model->getVehiclesinPlant($plant['idBuilding']);
					$_plants[] = $plant;
				endforeach;
				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'plants' => $_plants];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

	/* Verificar si el gps existe */
	public function existGPS_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				
				$gps = $this->post('gps');

				$exist = $this->Trucks_model->existGPS($gps);

				$status = parent::HTTP_OK;
				if($exist){
					if($this->Log_model->isOccupied($gps)){//Verificar si no esta siendo usado por un camion
						$response = ['exist' => '¡El GPS esta siendo ocupado por otro vehículo!'];
					}else{
						if($this->Log_model->isOccupiedOperator($gps)){//Verificar si no esta siendo utilizado por un palero
							$response = ['exist' => '¡El GPS esta siendo ocupado por otro vehículo!'];
						}else{
							$response = ['exist' => ''];
						}
					}
				}else{
					$response = ['exist' => '¡GPS inexistente!'];
				}
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

	/* Registrar llegada de un vehiculo */
	public function registerArraival_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				$quarry = $data->quarry;
				$plate = $this->post('plate');				
				$idMaterial = $this->post('idMaterial');
				$driver = $this->post('driverName');
				$company = $this->post('companyName');
				$gps = $this->post('GPS');
				$mica = $this->post('mica');	
				
				$plate = strtoupper($plate);
				$driver = strtoupper($driver);
				$company = strtoupper($company);

				$idDriver = $this->Log_model->getIdDriver($driver);
				$idCompany = $this->Log_model->getIdCompany($company);


				/* Insertar en la tabla log */

				$log = array(
					'idTruck' => $plate,
					'idMaterial' => $idMaterial,
					'idQuarry' => $quarry,
					'idDriver' => $idDriver,
					'idCompany' => $idCompany,
					'idGPS' => $gps,
					'idGPS2' => $gps,
					'mica' => $mica
				);

                //adm - Descomentar en producción
				$idLog = $this->Log_model->insertLog($log);

				$this->Log_model->insertHistory($idLog, "Entrada de vehículo", 1, NULL);
				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'plate'=>$plate, 'idMaterial'=>$idMaterial, 'driver'=>$driver, 'company'=>$company, 'gps'=>$gps];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

	/* Registr salida de un vehiculo */
	public function saveExit_post()
    {

        // Get all the headers
        $headers = $this->input->request_headers();

        // Extract the token
        $token = $headers['Authorization'];
        $token = sscanf($token, 'Bearer %s')[0];

        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);
            if ($data === false) {
                $status = parent::HTTP_UNAUTHORIZED;
                $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
                $this->response($response, $status);
                exit();
            } else {
                // Extract the idLog
                $idLog = $this->post('idLog');                                                        
				$remision = $this->post('remision');
				$observaciones = $this->post('observaciones');
				
				$this->Log_model->updateLog("departure",$idLog, "Salida de vehículo", $remision, $observaciones);
				$this->Log_model->updateTruckBuilding($idLog, NULL);

                $status = parent::HTTP_OK;
				$response = ['status' => $status, 'idLog' => $idLog];
				$this->response($response, $status);
                   
            }
        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
            $this->response($response, $status);
        }
    }

	/* Version 1.0 */

	public function test_get(){
		$file = "miarchivo.txt";
		$texto = "Hola que tal";
		$fp = fopen($file, "w");
		fwrite($fp, $texto);
		fclose($fp);
	}

	

	public function position_post()
	{		

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract the id of the gps
                $id = $this->post('Reference');
				$latitude = $this->post('Latitude');
				$longitude = $this->post('Longitude');
                				

				$file = "miarchivo.txt";
				$texto = "Hola que tal 2";
				$fp = fopen($file, "w");
				fwrite($fp, $texto);
				fclose($fp);

				// Obtain the truck from the model
				//$this->Materials_model->insertBuilding($name);

				              

				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'id' => $id];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}

	public function updateSite_post()
	{		

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract the id of the gps
                $nombregps = $this->post('id');
				$id = $this->post('nombregps');
				$type = $this->post('type');
				$name = $this->post('name');
				$date = $this->post('date');
				$batt = $this->post('batt');
				//$type = ucwords($type);
				//$this->Log_model->insertTimeTest();
				
				$file = "miarchivo2.txt";
				$texto = $id." ".$nombregps." ".$name." ".$type." ".$date." ".$batt."\n";
				$fp = fopen($file, "a");
				fwrite($fp, $texto);
				fclose($fp);
                
				//Actualizar bateria
				$this->Log_model->updateBattery($id, $batt);

				//Se obtiene el idLog que esta dentro
				$log = $this->Log_model->getTruck2($id);
				if($log){//Quiere decir que el gps que se esta moviendo es un gps dentro de la planta
				
					//Obtengo los datos del building
					$building = $this->Materials_model->getBuilding($name);	
					if($building!=NULL){
						$msg = '';
						if($building->typeBuilding==1){
							$msg ='Caseta: '.$building->nameBuilding;
							$this->Log_model->updateTruckBuilding($log->idLog, NULL);
						}elseif($building->typeBuilding==2){
							$msg ='Planta: '.$building->nameBuilding;
							//Update del idBuilding igual a $building->idBuilding en el $log->idLog
							if($type==1)
								$this->Log_model->updateTruckBuilding($log->idLog, $building->idBuilding);
							else{
								//Es salida de planta, hay que verificar si tardo en salir de planta mas de n minutos
								$this->Log_model->saveLoaded($log->idLog,$building->idBuilding);
								$this->Log_model->updateTruckBuilding($log->idLog, NULL);
							}
						}elseif($building->typeBuilding==3){
							$msg ='Bascula '.$building->nameBuilding;
							$this->Log_model->updateTruckBuilding($log->idLog, NULL);
						}elseif($building->typeBuilding==4){
							$msg ='Enlonado';
							$this->Log_model->updateTruckBuilding($log->idLog, NULL);
						}
						$con = 'a';
						$nom_type = 'Entrada';
						if($type==2){
							$nom_type = 'Salida';
							$con = 'de';						
						}
						
						$this->Log_model->insertHistory($log->idLog, $nom_type." de vehículo ".$con." ".$msg, $type, $building->idBuilding);
					}
				}else{//No es ningun gps del log
				//Buscamos si es un gps de algun operador
					$user = $this->Users_model->getOperatorsGPS($id);
					if($user){
						$building = $this->Materials_model->getBuilding($name);
						if($building!=NULL){
							if($building->typeBuilding==2){
								if($type==1)
									$this->Users_model->updateOperatorBuilding($user->idUser, $building->idBuilding);
								else
									$this->Users_model->updateOperatorBuilding($user->idUser, NULL);
							}
						}
					}
				}				

				// Obtain the truck from the model
				//$this->Materials_model->insertBuilding($name);

				              

				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'id' => $nombregps];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}

	public function getTruck_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract plate number from POST request
                $plate = $this->post('plate');                                            
                

				// Obtain the truck from the model
				$truck = $this->Trucks_model->getTruck($plate);

				// Check if valid truck
				if ($truck) {                    

                    //ENTRADA
					$status = parent::HTTP_OK;
					$response = ['status' => $status, 'driver' => $truck->driver, 'capacity' => $truck->capacity];
					$this->response($response, $status);
				} else {
					$this->response(['msg' => 'Transporte inexistente!'], parent::HTTP_NOT_FOUND);
				}
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}  


	public function addPlant_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract plate number from POST request
                $name = $this->post('name');                                            
                

				// Obtain the truck from the model
				$this->Materials_model->insertBuilding($name);

				              

				$status = parent::HTTP_OK;
				$response = ['status' => $status];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}

	public function addMaterial_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract plate number from POST request
                $name = $this->post('name');                                            
                

				// Obtain the truck from the model
				$this->Materials_model->insertMaterial($name);

				              

				$status = parent::HTTP_OK;
				$response = ['status' => $status];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}

	public function wasLoaded_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract plate number from POST request
                $plate = $this->post('plate');                                            
                

				// Obtain the truck from the model
				$truck = $this->Trucks_model->getTruck($plate);

				// Check if valid truck
				if ($truck) {                    


					// Obtain if exist a row in the log (no departure, and this vehicle)
					$log = $this->Log_model->getTruck($truck->idTruck);

					if($log){
						if($log->loaded!=NULL){
							if($log->weighed==NULL){
								//ENTRADA
								$status = parent::HTTP_OK;
								$response = ['status' => $status, 'driver' => $truck->driver, 'capacity' => $truck->capacity, 'idLog' =>$log->idLog];
								$this->response($response, $status);
							}else{
								$this->response(['msg' => 'El transporte ya fue pesado.'], parent::HTTP_NOT_FOUND);
							}						
						}else{
							$this->response(['msg' => 'El transporte no ha sido llenado aún.'], parent::HTTP_NOT_FOUND);
						}                    	
					}else{
						$this->response(['msg' => 'El transporte no está dentro de las instalaciones.'], parent::HTTP_NOT_FOUND);
					}
				} else {
					$this->response(['msg' => 'El transporte no está registrado.'], parent::HTTP_NOT_FOUND);
				}
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}

    public function isTruckService_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract plate number from POST request
				$plate = $this->post('plate');

				// Obtain the truck from the model
				$truck = $this->Log_model->getTruck($plate);

				// Check if valid truck
				if ($truck) {
					$status = parent::HTTP_OK;
					$response = ['status' => $status, 'driver' => $truck->driver, 'idLog'=>$truck->idLog];
					$this->response($response, $status);
				} else {
					$this->response(['msg' => 'Transporte inexistente!'], parent::HTTP_NOT_FOUND);
				}
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

    public function addTruck_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Extract plate number from POST request
                $plate = $this->post('plate');
                $driver = $this->post('driver');
                $capacity= $this->post('capacity');

				// Insert the truck
                $truck = array(
					'idTruck' => $plate,
                    'driver' => $driver,
                    'capacity' => $capacity
				);

                $this->Trucks_model->addTruck($truck);
                
				$status = parent::HTTP_OK;
				$response = ['status' => $status];
                $this->response($response, $status);
                
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }
    
    public function getBuildings_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Obtain the buildings from the model
				$buildings = $this->Materials_model->getBuildings();

				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'buildings' => $buildings];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}
	
	public function getOperators_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {
				// Obtain the buildings from the model
				$operators = $this->Users_model->getOperators();

				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'operators' => $operators];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }
    
    public function getMaterialsBuildings_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {

                $idBuilding = $this->post('idBuilding');

				// Obtain the materials of the building
				$materials = $this->Materials_model->getMaterialsBuildings($idBuilding);

				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'materials' => $materials];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }
	
	public function getOperatorsBuildings_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {

                $idOperator = $this->post('idOperator');

				// Obtain the materials of the building
				$buildings = $this->Users_model->getOperatorsBuildings($idOperator);

				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'buildings' => $buildings];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

    public function changeActiveMaterial_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {

                $idMB = $this->post('idMB');
                $active = $this->post('active');
                $idBuilding = $this->post('idBuilding');

                //Update the values
                $this->Materials_model->updateActiveMaterial($idMB, $active);

				// Obtain the materials of the building
				$materials = $this->Materials_model->getMaterialsBuildings($idBuilding);

				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'materials' => $materials];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}
	
	public function changeActiveBuilding_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {

                $idOB = $this->post('idOB');
                $active = $this->post('active');
                $idOperator = $this->post('idOperator');

                //Update the values
                $this->Users_model->updateActiveBuilding($idOB, $active);

				// Obtain the materials of the building
				$buildings = $this->Users_model->getOperatorsBuildings($idOperator);

				
				$status = parent::HTTP_OK;
				$response = ['status' => $status, 'buildings' => $buildings];
				$this->response($response, $status);
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

	
    
    public function startService_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				


                //Obtener los datos del usuario
                $user = $this->Users_model->getUser($data->username);

                //Se busca nueva nave
                if($this->Log_model->isShovelSearching()){//Si hay operador buscando regreso que no se puede buscar, reintenta
                    $status = parent::HTTP_OK;
                    $response = ['status' => $status, 'building' => 0];
                    $this->response($response, $status);
                    return;

                }else{//No hay operador buscando 
                    //Separo que voy a buscar, tipo exclusion mutua
                    $this->Log_model->insertShovelSearching($user->idUser);

                    //Busco nave
                    //select * from log where attempt=(SELECT min(attempt) FROM log where loaded is null)
                    $assigned = $this->Log_model->getBuildingShovel2($user->idUser);
                    if($assigned){
                        //Hacer update en assigned->idBuilding y user->idUser en shovelTrucks
                        $this->Log_model->updateShovelSearching($user->idUser, $assigned->idBuilding);                            
                    }else{
                        //Regresar que no hay vehiculos
                        $this->Log_model->removeShovel($user->idUser);
                        $status = parent::HTTP_OK;
                        $response = ['status' => $status, 'building' => -1];
                        $this->response($response, $status);
                        return;
                    }
                }
                                  

                $status = parent::HTTP_OK;
                $response = ['status' => $status, 'building' => $assigned->nameBuilding, 'nameMaterial' => $assigned->nameMaterial, 'plate' => $assigned->plate, 'idLog' => $assigned->idLog];
				$this->response($response, $status);
                							
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
	}
	
	public function getTrucksInside_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				


                //adm
				$trucks = $this->Log_model->getTrucksInside();

				$lastPlant = null;
				$finalTrucks = array();
				$indexes  = array();
				$idx = 0;
				foreach ($trucks as $truck){
					if($lastPlant != $truck["nameBuilding"]){
						$truck["header"] = true;
						$truck["id"] = $idx;
						$indexes[] = $idx;
						$lastPlant = $truck["nameBuilding"];
						$finalTrucks[] = $truck;
						$idx = $idx +1;
						$truck["header"] = false;
						$truck["id"] = $idx;
						$finalTrucks[] = $truck;						
						$idx = $idx +1;
					}else{
						$truck["header"] = false;
						$truck["id"] = $idx;
						$finalTrucks[] = $truck;
						$idx = $idx +1;
					}
				}
				
                $status = parent::HTTP_OK;
                $response = ['status' => $status, 'trucks' => $finalTrucks, 'indexes'=>$indexes];
				$this->response($response, $status);
                							
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

    public function stopService_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				


                //Obtener los datos del usuario
                $user = $this->Users_model->getUser($data->username);
                
                $this->Log_model->removeShovel($user->idUser);
                                  

                $status = parent::HTTP_OK;
                $response = ['status' => $status];
				$this->response($response, $status);
                							
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }


    public function nextService_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				

                $idLog = $this->post('idLog');
                //Se actualiza el log la hora de carga
                //$this->Log_model->updateLog("loaded",$idLog);

                //Obtener los datos del usuario
                $user = $this->Users_model->getUser($data->username);
                $building = $this->Log_model->isBuildingAssigned($user->idUser);

                $assigned = FALSE;

                if($building){//Tiene nave asignada, se busca el vehiculo con menor tiempo en esa nave tiempo
                    $assigned = $this->Log_model->getBuildingShovel($building->idBuilding);
                }

                if(!$assigned){//No tiene nave asignada, o no hay mas vehiculos en la nave

                    //Se elimina el operador del shovelTrucks si tenia nave asignada
                    if($building)
                        $this->Log_model->removeShovel($user->idUser);


                    //Se busca nueva nave
                    if($this->Log_model->isShovelSearching()){//Si hay operador buscando regreso que no se puede buscar, reintenta
                        $status = parent::HTTP_OK;
                        $response = ['status' => $status, 'building' => 0];
                        $this->response($response, $status);
                        return;
    
                    }else{//No hay operador buscando 
                        //Separo que voy a buscar, tipo exclusion mutua
                        $this->Log_model->insertShovelSearching($user->idUser);

                        //Busco nave
                        //select * from log where attempt=(SELECT min(attempt) FROM log where loaded is null)
                        $assigned = $this->Log_model->getBuildingShovel2($user->idUser);
                        if($assigned){
                            //Hacer update en assigned->idBuilding y user->idUser en shovelTrucks
                            $this->Log_model->updateShovelSearching($user->idUser, $assigned->idBuilding);                            
                        }else{
                            //Regresar que no hay vehiculos
                            $this->Log_model->removeShovel($user->idUser);
                            $status = parent::HTTP_OK;
                            $response = ['status' => $status, 'building' => -1];
                            $this->response($response, $status);
                            return;
                        }
                    }
                }                   

                $status = parent::HTTP_OK;
                $response = ['status' => $status, 'building' => $assigned->nameBuilding, 'nameMaterial' => $assigned->nameMaterial, 'plate' => $assigned->plate, 'idLog' => $assigned->idLog];
				$this->response($response, $status);
                							
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

    public function omitService_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				

                $idLog = $this->post('idLog');
                //Se actualiza el log la hora de carga
                //$this->Log_model->updateLog("attempt",$idLog);
                $this->Log_model->insertIncident($idLog,"El vehiculo no estuvo en el area asiganda para cargar.");

                //Obtener los datos del usuario
                $user = $this->Users_model->getUser($data->username);
                $building = $this->Log_model->isBuildingAssigned($user->idUser);

                $assigned = FALSE;

                if($building){//Tiene nave asignada, se busca el vehiculo con menor tiempo en esa nave tiempo
                    $assigned = $this->Log_model->getBuildingShovel($building->idBuilding);
                    if($assigned->idLog==$idLog){//Era el ultimo servicio de esa nave, se busca nueva nave
                        $assigned = FALSE;                        
                    }
                }

                if(!$assigned){//No tiene nave asignada, o no hay mas vehiculos en la nave

                    //Se elimina el operador del shovelTrucks si tenia nave asignada
                    if($building)
                        $this->Log_model->removeShovel($user->idUser);


                    //Se busca nueva nave
                    if($this->Log_model->isShovelSearching()){//Si hay operador buscando regreso que no se puede buscar, reintenta
                        $status = parent::HTTP_OK;
                        $response = ['status' => $status, 'building' => 0];
                        $this->response($response, $status);
                        return;
    
                    }else{//No hay operador buscando 
                        //Separo que voy a buscar, tipo exclusion mutua
                        $this->Log_model->insertShovelSearching($user->idUser);

                        //Busco nave
                        //select * from log where attempt=(SELECT min(attempt) FROM log where loaded is null)
                        $assigned = $this->Log_model->getBuildingShovel2($user->idUser);
                        if($assigned){
                            //Hacer update en assigned->idBuilding y user->idUser en shovelTrucks
                            $this->Log_model->updateShovelSearching($user->idUser, $assigned->idBuilding);                            
                        }else{
                            //Regresar que no hay vehiculos
                            $this->Log_model->removeShovel($user->idUser);
                            $status = parent::HTTP_OK;
                            $response = ['status' => $status, 'building' => -1];
                            $this->response($response, $status);
                            return;
                        }
                    }
                }                   

                $status = parent::HTTP_OK;
                $response = ['status' => $status, 'building' => $assigned->nameBuilding, 'nameMaterial' => $assigned->nameMaterial, 'plate' => $assigned->plate, 'idLog' => $assigned->idLog];
				$this->response($response, $status);
                							
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

    public function isBuildingAssigned_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				

                //Obtener los datos del usuario
                $user = $this->Users_model->getUser($data->username);
                $building = $this->Log_model->isBuildingAssigned($user->idUser);

            
                if($building){
                    $status = parent::HTTP_OK;
                    $response = ['status' => $status, 'isAssigned' => 1];
                    $this->response($response, $status);
                }else{
                    $status = parent::HTTP_OK;
                    $response = ['status' => $status, 'isAssigned' => 0];
                    $this->response($response, $status);
                }                                       
    
                    
            }                                											
			
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

    public function addIncidency_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				

                $idLog = $this->post('idLog');
                $incidency = $this->post('incidency');
                $this->Log_model->insertIncident($idLog,$incidency);                

                $status = parent::HTTP_OK;
                $response = ['status' => $status ];
				$this->response($response, $status);
                							
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

    public function addWeighed_post()
	{

		// Get all the headers
		$headers = $this->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];
		$token = sscanf($token, 'Bearer %s')[0];

		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$this->response($response, $status);
				exit();
			} else {				

                $idLog = $this->post('idLog');

                
                //$this->Log_model->updateLog('weighed', $idLog);
                $status = parent::HTTP_OK;
                $response = ['status' => $status];
                $this->response($response, $status);                                                                                                							
				
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
    }

}

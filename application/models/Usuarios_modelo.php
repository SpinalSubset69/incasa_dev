<?php
class usuarios_modelo extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	public function desactivarUsuario($idEnc){
		$data=array(
			'activo'=>0
		);
		$this->db->where('idEncriptado', $idEnc);
		$this->db->update('usuarios',$data);
	}

	public function activarUsuario($idEnc){
		$data=array(
			'activo'=>1
		);
		$this->db->where('idEncriptado', $idEnc);
		$this->db->update('usuarios',$data);
	}

	public function updateUsuario($usuario, $idEnc){
		$this->db->where('idEncriptado', $idEnc);
		$this->db->update('usuarios',$usuario);
	}

	public function getIdUsuario($idEnc){
		$this->db->select('*');
		$this->db->from('usuarios');
		$this->db->where("idEncriptado",$idEnc);
		$query=$this->db->get();
		$usuario=$query->row();
		return $usuario->idUsuario;
	}

	public function updatePmva($usuario, $id){

		$data=array(
			'display'=> $usuario["nombres"]." ".$usuario["apellidoP"]
		);

		$this->db->where('idUsuario', $id);
		$this->db->update('usuarios',$data);

		$this->db->where('idPmva', $id);
		$this->db->update('pmvas',$usuario);
	}

	public function hacerSupervisorGeneral($idEnc){
		$data=array(
			"supervisorGral" => 1
		);

		$this->db->where('idEncriptado', $idEnc);
		$this->db->update('usuarios',$data);
	}

	public function quitarSupervisorGeneral($idEnc){
		$data=array(
			"supervisorGral" => 0
		);

		$this->db->where('idEncriptado', $idEnc);
		$this->db->update('usuarios',$data);
	}

	public function updatePromovente($promovente, $usuario, $id, $ban){


		if($ban==3){
			$this->db->where('idUsuario', $id);
			$this->db->update('usuarios',$usuario);
		}

		$this->db->where('idPromovente', $id);
		$this->db->update('promoventes',$promovente);
	}

	public function updateSupervisor($usuario, $id, $admin){

		$data=array(
			'display'=> $usuario["nombres"]." ".$usuario["apellidoP"],
			'admin' => $admin
		);

		$this->db->where('idUsuario', $id);
		$this->db->update('usuarios',$data);

		$this->db->where('idSupervisor', $id);
		$this->db->update('supervisores',$usuario);
	}

	public function agregarUsuario($usuario){
		$this->db->insert('usuarios', $usuario);
		$idUsuario=$this->db->insert_id();
		$idEnc=hash ( 'sha256', $idUsuario );
		$this->db->where('idUsuario', $idUsuario);
		$data=array(
			'idEncriptado'=>$idEnc
		);
		$this->db->update('usuarios',$data);

		return $idUsuario;
	}

	public function agregarSupervisor($supervisor){
		$this->db->insert('supervisores', $supervisor);
		return $this->db->insert_id();
	}

	public function agregarPromovente($promovente){
		$this->db->insert('promoventes', $promovente);
		return $this->db->insert_id();
	}

	//SELECT * FROM usuarios a, promoventes b where a.idUsuario=49
	//and a.idUsuario=b.idPromovente and b.fechaInicio<=curdate()
	//and curdate()<=b.fechaFin;
	public function esPromoventeEspecial($usuario){
		$this->db->select('*');
		$this->db->from('usuarios as a, promoventes as b');
		$this->db->where('a.idUsuario',$usuario);
		$this->db->where('a.idUsuario=b.idPromovente');
		$this->db->where('b.fechaInicio<=curdate()');
		$this->db->where('curdate()<=b.fechaFin');
		$query=$this->db->get();
		if($query->num_rows()>=1)
			return TRUE;
		return FALSE;
	}

	public function agregarPmva($pmva){
		$this->db->insert('pmvas', $pmva);
		return $this->db->insert_id();
	}

	public function getUsuario($ticket){
		$this->db->select('*');
		$this->db->from('usuarios');
		$this->db->where("ticket",$ticket);
		$query=$this->db->get();
		return $query->row();
	}

	//select * from usuarios where creadoPor not in (select idPromovente from promoventes);
	public function getUsuarios($creadoPor){

		if($creadoPor>0){
			$this->db->select('*');
			$this->db->from('usuarios');
			$this->db->where("creadoPor",$creadoPor);
			$query=$this->db->get();
			return $query->result_array();
		}else{
			$this->db->select('*');
			$this->db->from('promoventes');
			$query=$this->db->get();
			$promos=$query->result();
			$promoventes=array();
			$entra=false;
			foreach ($promos as $prom) {
				$entra=true;
				array_push($promoventes, $prom->idPromovente);
			}
			$this->db->select('*');
			$this->db->from('usuarios');
			if($entra)
				$this->db->where_not_in('creadoPor', $promoventes);
			$query=$this->db->get();
			return $query->result_array();
		}

	}

	public function getPromoventes(){
		$this->db->select('*');
		$this->db->from('usuarios as a, promoventes as b');
		$this->db->where("a.idUsuario=b.idPromovente");
		$query=$this->db->get();
		return $query->result_array();
	}

	public function esPromovente($id){
		$this->db->select('*');
		$this->db->from('usuarios as a, promoventes as b');
		$this->db->where("a.idEncriptado",$id);
		$this->db->where("a.idUsuario=b.idPromovente");
		$query=$this->db->get();
		if($query->num_rows()>=1)
			return $query->row();
		return NULL;
	}

	public function esPmva($id){
		$this->db->select('*');
		$this->db->from('usuarios as a, pmvas as b');
		$this->db->where("a.idEncriptado",$id);
		$this->db->where("a.idUsuario=b.idPmva");
		$query=$this->db->get();
		if($query->num_rows()>=1)
			return $query->row();
		return NULL;
	}

	public function esSupervisor($id){
		$this->db->select('*');
		$this->db->from('usuarios as a, supervisores as b');
		$this->db->where("a.idEncriptado",$id);
		$this->db->where("a.idUsuario=b.idSupervisor");
		$query=$this->db->get();
		if($query->num_rows()>=1)
			return $query->row();
		return NULL;
	}

	public function esAdmin($id){
		$this->db->select('*');
		$this->db->from('usuarios');
		$this->db->where("idEncriptado",$id);
		$query=$this->db->get();
		if($query->num_rows()>=1)
			return $query->row();
		return NULL;
	}

	public function cambiarContrasena($ticket, $contrasena){
		$this->db->where('ticket', $ticket);
		$data=array(
			'ticket'=>"",
			'contrasena'=>$contrasena
		);
		$this->db->update('usuarios',$data);
	}

}

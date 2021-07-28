<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
Clear DB
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE contratistas;
TRUNCATE TABLE etapas;
TRUNCATE TABLE etapas_mmtc;
TRUNCATE TABLE factores;
TRUNCATE TABLE mmtc;
TRUNCATE TABLE obras;
TRUNCATE TABLE pmvas;
TRUNCATE TABLE promoventes;
TRUNCATE TABLE proyectos;
TRUNCATE TABLE supervisores;
TRUNCATE TABLE usuarios;
SET FOREIGN_KEY_CHECKS=1;
*/


class GECOA extends CI_Controller {

  public $titulo="GECOA";

  public function __construct(){
    parent::__construct();

    //Modelo para el login
    $this->load->model('login_modelo');
    //Modelo para los usuarios
    $this->load->model('proyectos_modelo');

    //Modelo para los proyectos
    $this->load->model('usuarios_modelo');

    //Helpers
    $this->load->library(array('session')); //Control de sesiones (Coockies)
    $this->load->helper('url'); //Para el base_url()
    $this->load->helper('utilerias');//Utilerias para obtener el menu, permisos, etc
    $this->load->helper('form');

    $config = array();
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'ssl://smtp.googlemail.com';
    $config['smtp_user'] = 'gecoainf@gmail.com';
    $config['smtp_pass'] = '123456gecoa';
    $config['smtp_port'] = 465;
    $this->load->library('email',$config);

    /*
    $this->load->library(array('session'));
    $this->load->helper('form');
    $this->load->helper('html');

    */
  }

  public function getMenu(){
    $menu=array();
    array_push($menu, getMenuUsuarios());
    array_push($menu, getMenuProyectos());
    return $menu;
  }

  public function index(){
    if($this->session->userdata('is_logued')){
      $tipos=$this->session->userdata('tipos');
      $data['tipos']=$tipos;
      $data['menu']=$this->getMenu();
      $this->load->view('templates/header',$data);
      $this->load->view('pages/index');
      $this->load->view('templates/footer');
    }else{
      redirect(base_url().'GECOA/login','refresh');
    }

  }

  public function verUsuarios(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promovente"));

    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $data['user']=$this->session->userdata('usuario');
    $this->load->view('templates/header',$data);
    if(esPromovente($tipos))
      $data['usuarios']=$this->usuarios_modelo->getUsuarios($this->session->userdata('idUsuario'));
    else
      $data['usuarios']=$this->usuarios_modelo->getUsuarios(-1);
    $this->load->view('pages/verUsuarios',$data);
    $this->load->view('templates/footer');

  }

  public function verProyectos(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promovente"));

    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $data['user']=$this->session->userdata('usuario');
    $this->load->view('templates/header',$data);
    if(esPromovente($tipos))
      $data['proyectos']=$this->proyectos_modelo->getProyectos($this->session->userdata('idUsuario'));
    else
      $data['proyectos']=$this->proyectos_modelo->getProyectos(-1);
    $this->load->view('pages/verProyectos',$data);
    $this->load->view('templates/footer');

  }

  public function agregarProyecto(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promovente"));
    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $data['user']=$this->session->userdata('usuario');
    $data['esPromovente']=true;
    $data['usuarios']=[];
    $data['idEnc']=$this->session->userdata('idEnc');
    $data['mostrarModal'] = "";
    if(isset($_POST['dir_alt'])){
      $idEnc=$this->input->post("dir_alt");
      $id=$this->usuarios_modelo->getIdUsuario($idEnc);
      $loguedUser=$this->session->userdata('idUsuario');
      $proyecto = array(
                    'nombreProyecto' => $this->input->post("txt_nombre"),
                    'giro' => $this->input->post("txt_giro"),
                    'autorizacion' => $this->input->post("txt_autorizacion"),
                    'calle' => $this->input->post("txt_calle"),
                    'numExt' => $this->input->post("txt_numExt"),
                    'numInt' => $this->input->post("txt_numInt"),
                    'colonia' => $this->input->post("txt_colonia"),
                    'cp' => $this->input->post("txt_cp"),
                    'ciudad' => $this->input->post("txt_ciudad"),
                    'estado' => $this->input->post("txt_estado"),
                    'pais' => $this->input->post("txt_pais"),
                    'latitud' => $this->input->post("txt_latitud"),
                    'longitud' => $this->input->post("txt_longitud"),
                    'dimension' => $this->input->post("txt_dimension"),
                    'descripcion' => $this->input->post("txt_descripcion"),
                    'fechaInicio' => $this->input->post("txt_fechainicio"),
                    'fechaFin' => $this->input->post("txt_fechafin"),
                    'idPromovente' => $id,
                    'creadoPor'=> $loguedUser
      );
      //
      $this->proyectos_modelo->agregarProyecto($proyecto);
      $data['mostrarModal'] = "mostrarModal";
    }else{

      $data['esPromovente']=false;


      if(esPromovente($tipos)){
        $data['idEnc']=$this->session->userdata('idEnc');
        $data['esPromovente']=true;
        $data['usuarios']=[];
      }else{
        $data['idEnc']="";
        $data['usuarios']=$this->usuarios_modelo->getPromoventes();

      }

    }
    $this->load->view('templates/header',$data);
    $this->load->view('pages/agregarProyecto',$data);
    $this->load->view('templates/footer');

  }

  public function sendEmail(){
    $this->email->from('gecoainf@gmail.com', 'GECOA');
    $this->email->to('calix35@gmail.com');
    $this->email->subject('E-mail de registro a GECOA!');
    $this->email->message('Cuerpo del mensaje');
    $this->email->send();
  }

  public function agregarAdministrador(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin"));
    $data["error"]=NULL;
    $data['mostrarModal'] = '';
    $data["txt_nombres"]="";
    $data["txt_usuario"]="";
    if(isset($_POST['txt_usuario']) && $this->login_modelo->existeUsuario($_POST['txt_usuario'])==FALSE){
      //Agregar el usuario a la BD
      $ticket=generate_string(150);
      $loguedUser=$this->session->userdata('idUsuario');
      $usuario = array(
                    'usuario' => $this->input->post("txt_usuario"),
                    'display'=>$this->input->post("txt_nombre"),
                    'admin'=>1,
                    'ticket' => $ticket,
                    'activo' => 1,
                    'creadoPor' => $loguedUser
      );
      $this->usuarios_modelo->agregarUsuario($usuario);
      $data['mostrarModal'] = 'mostrarModal';
      //
    }else{//Mostrar el formulario

      if(isset($_POST['txt_usuario'])){
        $data["txt_usuario"]=$this->input->post("txt_usuario");
        $data["txt_nombres"]=$this->input->post("txt_nombre");
        $data["error"]="El correo registrado ya existe!";
      }
    }
    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $this->load->view('templates/header',$data);

    $this->load->view('pages/agregarAdministrador', $data);
    $this->load->view('templates/footer');
  }

  public function agregarSupervisor(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promoventeEsp"));
    $data["error"]=NULL;

    $data['mostrarModal']="";
    $data["txt_nombres"]="";
    $data["txt_apellidoP"]="";
    $data["txt_apellidoM"]="";
    $data["txt_telefono"]="";
    $data["txt_usuario"]="";
    $data["esAdministrador"]="";
    if(isset($_POST['txt_usuario']) && $this->login_modelo->existeUsuario($_POST['txt_usuario'])==FALSE){
      $esAdministrador=$this->input->post('esAdministrador');
      if($esAdministrador=="on") $esAdministrador=1; else $esAdministrador=0;

      $ticket=generate_string(150);
      $loguedUser=$this->session->userdata('idUsuario');
      $usuario = array(
                    'usuario' => $this->input->post("txt_usuario"),
                    'display'=>$this->input->post("txt_nombres")." ".$this->input->post("txt_apellidoP"),
                    'supervisor'=>1,
                    'admin'=>$esAdministrador,
                    'ticket' => $ticket,
                    'activo' => 1,
                    'creadoPor' => $loguedUser
      );
      $idUsuario=$this->usuarios_modelo->agregarUsuario($usuario);

      //Agregar al usuario a la tabla de usuarios
      $supervisor = array(
                    'idSupervisor' => $idUsuario,
                    'nombres' => $this->input->post("txt_nombres"),
                    'apellidoP' => $this->input->post("txt_apellidoP"),
                    'apellidoM' => $this->input->post("txt_apellidoM"),
                    'telefono' => $this->input->post("txt_telefono")
      );

      $this->usuarios_modelo->agregarSupervisor($supervisor);
      $data['mostrarModal'] = "mostrarModal";
    }else{

      if(isset($_POST['txt_usuario'])){
        $data["txt_nombres"]=$this->input->post("txt_nombres");
        $data["txt_apellidoP"]=$this->input->post("txt_apellidoP");
        $data["txt_apellidoM"]=$this->input->post("txt_apellidoM");
        $data["txt_telefono"]=$this->input->post("txt_telefono");
        $data["txt_usuario"]=$this->input->post("txt_usuario");
        if($this->input->post('esAdministrador')=="on")
          $data["esAdministrador"]="checked";
        $data["error"]="El correo registrado ya existe!";
      }
    }
    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $this->load->view('templates/header',$data);
    $this->load->view('pages/agregarSupervisor', $data);
    $this->load->view('templates/footer');
  }

  public function agregarPromovente(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin"));

    $data['mostrarModal'] = '';
    $data["txt_nombre"]="";
    $data["txt_display"]="";
    $data["txt_rfc"]="";
    $data["txt_calle"]="";
    $data["txt_numInt"]="";
    $data["txt_numExt"]="";
    $data["txt_colonia"]="";
    $data["txt_estado"]="";
    $data["txt_pais"]="";
    $data["txt_celular"]="";
    $data["txt_teloficina"]="";
    $data["txt_ext"]="";
    $data["txt_nombreRep"]="";
    $data["txt_usuario"]="";
    $data["txt_pagweb"]="";
    $data["txt_fechainicio"]="";
    $data["txt_fechafin"]="";
    $data["esAdministrador"]="";
    $data["error"]=NULL;

    if(isset($_POST['txt_usuario']) && $this->login_modelo->existeUsuario($_POST['txt_usuario'])==FALSE){
      $ticket=generate_string(150);
      $loguedUser=$this->session->userdata('idUsuario');
      $usuario = array(
                    'usuario' => $this->input->post("txt_usuario"),
                    'display'=>$this->input->post("txt_display"),
                    'promovente'=>1,
                    'ticket' => $ticket,
                    'activo' => 1,
                    'creadoPor' => $loguedUser
      );
      $idUsuario=$this->usuarios_modelo->agregarUsuario($usuario);

      if($this->input->post("txt_fechainicio")==""){
        $fechaInicio=NULL;
        $fechaFin=NULL;
      }else{
        $fechaInicio=$this->input->post("txt_fechainicio");
        $fechaFin=$this->input->post("txt_fechafin");
      }
      $promovente = array(
                    'idPromovente' => $idUsuario,
                    'nombre' => $this->input->post("txt_nombre"),
                    'rfc' => $this->input->post("txt_rfc"),
                    'calle' => $this->input->post("txt_calle"),
                    'numExt' => $this->input->post("txt_numExt"),
                    'numInt' => $this->input->post("txt_numInt"),
                    'colonia' => $this->input->post("txt_colonia"),
                    'pais' => $this->input->post("txt_pais"),
                    'estado' => $this->input->post("txt_estado"),
                    'telcel' => $this->input->post("txt_celular"),
                    'teloficina' => $this->input->post("txt_teloficina"),
                    'ext' => $this->input->post("txt_ext"),
                    'nombreRep' => $this->input->post("txt_nombreRep"),
                    'correoRep' => $this->input->post("txt_usuario"),
                    'pagweb' => $this->input->post("txt_pagweb"),
                    'FechaInicio' => $fechaInicio,
                    'FechaFin' => $fechaFin
      );

      $this->usuarios_modelo->agregarPromovente($promovente);
      $data['mostrarModal'] = 'mostrarModal';
    }else{


      if(isset($_POST['txt_usuario'])){
        $data["txt_nombre"]=$this->input->post("txt_nombre");
        $data["txt_display"]=$this->input->post("txt_display");
        $data["txt_rfc"]=$this->input->post("txt_rfc");
        $data["txt_calle"]=$this->input->post("txt_calle");
        $data["txt_numInt"]=$this->input->post("txt_numInt");
        $data["txt_numExt"]=$this->input->post("txt_numExt");
        $data["txt_colonia"]=$this->input->post("txt_colonia");
        $data["txt_estado"]=$this->input->post("txt_estado");
        $data["txt_pais"]=$this->input->post("txt_pais");
        $data["txt_celular"]=$this->input->post("txt_celular");
        $data["txt_teloficina"]=$this->input->post("txt_teloficina");
        $data["txt_ext"]=$this->input->post("txt_ext");
        $data["txt_nombreRep"]=$this->input->post("txt_nombreRep");
        $data["txt_usuario"]=$this->input->post("txt_usuario");
        $data["txt_pagweb"]=$this->input->post("txt_pagweb");
        $data["txt_fechainicio"]=$this->input->post("txt_fechainicio");
        $data["txt_fechafin"]=$this->input->post("txt_fechafin");
        if($this->input->post('esAdministrador')=="on")
          $data["esAdministrador"]="checked";
        $data["error"]="El correo registrado ya existe!";
      }
    }
    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $this->load->view('templates/header',$data);
    $this->load->view('pages/agregarPromovente', $data);
    $this->load->view('templates/footer');
  }

  public function agregarPmva(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("promovente"));

    $data['mostrarModal'] = '';
    $data["error"]=NULL;
    $data["txt_nombres"]="";
    $data["txt_apellidoP"]="";
    $data["txt_apellidoM"]="";
    $data["txt_telefono"]="";
    $data["txt_usuario"]="";

    if(isset($_POST['txt_usuario']) && $this->login_modelo->existeUsuario($_POST['txt_usuario'])==FALSE){

      $ticket=generate_string(150);
      $loguedUser=$this->session->userdata('idUsuario');
      $usuario = array(
                    'usuario' => $this->input->post("txt_usuario"),
                    'display'=>$this->input->post("txt_nombres")." ".$this->input->post("txt_apellidoP"),
                    'pmva'=>1,
                    'ticket' => $ticket,
                    'activo' => 1,
                    'creadoPor' => $loguedUser
      );
      $idUsuario=$this->usuarios_modelo->agregarUsuario($usuario);

      //Agregar al usuario a la tabla de usuarios
      $pmva = array(
                    'idPmva' => $idUsuario,
                    'nombres' => $this->input->post("txt_nombres"),
                    'apellidoP' => $this->input->post("txt_apellidoP"),
                    'apellidoM' => $this->input->post("txt_apellidoM"),
                    'telefono' => $this->input->post("txt_telefono")
      );

      $this->usuarios_modelo->agregarPmva($pmva);
      $data['mostrarModal'] = 'mostrarModal';
    }else{
      if(isset($_POST['txt_usuario'])){
        $data["txt_nombres"]=$this->input->post("txt_nombres");
        $data["txt_apellidoP"]=$this->input->post("txt_apellidoP");
        $data["txt_apellidoM"]=$this->input->post("txt_apellidoM");
        $data["txt_telefono"]=$this->input->post("txt_telefono");
        $data["txt_usuario"]=$this->input->post("txt_usuario");
        $data["error"]="El correo registrado ya existe!";
      }
    }
    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $this->load->view('templates/header',$data);
    $this->load->view('pages/agregarPmva', $data);
    $this->load->view('templates/footer');
  }

  public function desactivarUsuario(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promoventeEsp"));

    //TODO ajustar de acuerdo al url
    $idEnc=$this->uri->segment(3);
    $usuario=$this->usuarios_modelo->desactivarUsuario($idEnc);
    redirect(base_url().'GECOA/verUsuarios','refresh');
  }

  public function activarUsuario(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promoventeEsp"));

    //TODO ajustar de acuerdo al url
    $idEnc=$this->uri->segment(3);
    $usuario=$this->usuarios_modelo->activarUsuario($idEnc);
    redirect(base_url().'GECOA/verUsuarios','refresh');
  }

  public function verPromovente(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promoventeEsp"));

    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $this->load->view('templates/header',$data);

    $idEnc=$this->uri->segment(3);
    if($idEnc==""){
      $idEnc=$this->input->post('idEnc');
    }
    $usuario=$this->usuarios_modelo->esPromovente($idEnc);
    $data["txt_nombre"]=$usuario->nombre;
    $data["txt_display"]=$usuario->display;
    $data["txt_rfc"]=$usuario->rfc;
    $data["txt_calle"]=$usuario->calle;
    $data["txt_numInt"]=$usuario->numInt;
    $data["txt_numExt"]=$usuario->numExt;
    $data["txt_colonia"]=$usuario->colonia;
    $data["txt_estado"]=$usuario->estado;
    $data["txt_pais"]=$usuario->pais;
    $data["txt_celular"]=$usuario->telcel;
    $data["txt_teloficina"]=$usuario->teloficina;
    $data["txt_ext"]=$usuario->ext;
    $data["txt_nombreRep"]=$usuario->nombreRep;
    $data["txt_usuario"]=$usuario->correoRep;
    $data["txt_pagweb"]=$usuario->pagweb;
    $data['idEnc']=$idEnc;
    if($usuario->fechaInicio!=NULL){
      $data["txt_fechainicio"]=$usuario->fechaInicio;
      $data["txt_fechafin"]=$usuario->fechaFin;
      $data["esAdministrador"]="checked";
    }else{
      $data["txt_fechainicio"]="";
      $data["txt_fechafin"]="";
      $data["esAdministrador"]="";
    }
    $this->load->view('pages/verPromovente', $data);
  }

  public function editarUsuario(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("admin","promoventeEsp"));

    $data['tipos']=$tipos;
    $data['menu']=$this->getMenu();
    $this->load->view('templates/header',$data);
    $data['mostrarModal']='';
    $data['error']='';
    $data['idEnc']='';


    //TODO ajustar de acuerdo al url
    $idEnc=$this->uri->segment(3);
    if($idEnc==""){
      $idEnc=$this->input->post('idEnc');
    }
    $usuario=$this->usuarios_modelo->esPromovente($idEnc);
    if($usuario!=NULL){
        $data["txt_nombre"]=$usuario->nombre;
        $data["txt_display"]=$usuario->display;
        $data["txt_rfc"]=$usuario->rfc;
        $data["txt_calle"]=$usuario->calle;
        $data["txt_numInt"]=$usuario->numInt;
        $data["txt_numExt"]=$usuario->numExt;
        $data["txt_colonia"]=$usuario->colonia;
        $data["txt_estado"]=$usuario->estado;
        $data["txt_pais"]=$usuario->pais;
        $data["txt_celular"]=$usuario->telcel;
        $data["txt_teloficina"]=$usuario->teloficina;
        $data["txt_ext"]=$usuario->ext;
        $data["txt_nombreRep"]=$usuario->nombreRep;
        $data["txt_usuario"]=$usuario->correoRep;
        $data["txt_pagweb"]=$usuario->pagweb;
        $data['idEnc']=$idEnc;
        if($usuario->fechaInicio!=NULL){
          $data["txt_fechainicio"]=$usuario->fechaInicio;
          $data["txt_fechafin"]=$usuario->fechaFin;
          $data["esAdministrador"]="checked";
        }else{
          $data["txt_fechainicio"]="";
          $data["txt_fechafin"]="";
          $data["esAdministrador"]="";
        }
        if(isset($_POST['idEnc'])){
          //A guardar
          $bandera=0;
          if($this->input->post("txt_usuario")==$usuario->correoRep){
            $bandera=1;
          }else{
            $bandera=3;
            //Cambio el representante legal - verificar contraseña
            if($this->login_modelo->existeUsuario($_POST['txt_usuario'])){
              $bandera=2;
            }
          }
          if($bandera!=2){
            $id=$this->usuarios_modelo->getIdUsuario($idEnc);

            $ticket=generate_string(150);
            $usuariox = array(
                          'usuario' => $this->input->post("txt_usuario"),
                          'contrasena'=>NULL,
                          'ticket' => $ticket
            );
            if($this->input->post("esAdministrador")=="on"){
              $fechaInicio=$this->input->post("txt_fechainicio");
              $fechaFin=$this->input->post("txt_fechafin");
            }else{
              $fechaInicio=NULL;
              $fechaFin=NULL;
            }
            $promovente = array(
                          'calle' => $this->input->post("txt_calle"),
                          'numExt' => $this->input->post("txt_numExt"),
                          'numInt' => $this->input->post("txt_numInt"),
                          'colonia' => $this->input->post("txt_colonia"),
                          'pais' => $this->input->post("txt_pais"),
                          'estado' => $this->input->post("txt_estado"),
                          'telcel' => $this->input->post("txt_celular"),
                          'teloficina' => $this->input->post("txt_teloficina"),
                          'ext' => $this->input->post("txt_ext"),
                          'nombreRep' => $this->input->post("txt_nombreRep"),
                          'correoRep' => $this->input->post("txt_usuario"),
                          'pagweb' => $this->input->post("txt_pagweb"),
                          'FechaInicio' => $fechaInicio,
                          'FechaFin' => $fechaFin
            );

            $data['mostrarModal']='mostrarModal';
            $this->usuarios_modelo->updatePromovente($promovente, $usuariox, $id, $bandera);
          }
          if($bandera==2){
            $data["error"]="El correo registrado ya existe!";
          }
        }
      //Es Promovente - mostrar la vista esa
      $this->load->view('pages/editarPromovente', $data);
    }else{
      $usuario=$this->usuarios_modelo->esSupervisor($idEnc);
      if($usuario!=NULL){
        $data["txt_nombres"]=$usuario->nombres;
        $data["txt_apellidoP"]=$usuario->apellidoP;
        $data["txt_apellidoM"]=$usuario->apellidoM;
        $data["txt_telefono"]=$usuario->telefono;
        $data["txt_usuario"]=$usuario->usuario;
        $data['idEnc']=$idEnc;
        $data["esAdministrador"]="";
        if($usuario->admin==1) $data["esAdministrador"]="checked";
        if(isset($_POST['idEnc'])){
          //A guardar
          $id=$this->usuarios_modelo->getIdUsuario($idEnc);
          $data['txt_nombres'] = $this->input->post("txt_nombres");
          $data['txt_apellidoP'] = $this->input->post("txt_apellidoP");
          $data['txt_apellidoM'] = $this->input->post("txt_apellidoM");
          $data['txt_telefono'] = $this->input->post("txt_telefono");
          $usuario=array(
            'nombres' => $this->input->post("txt_nombres"),
            'apellidoP' => $this->input->post("txt_apellidoP"),
            'apellidoM' => $this->input->post("txt_apellidoM"),
            'telefono' => $this->input->post("txt_telefono")
          );
          $esAdministrador=0;
          $data["esAdministrador"]="";
          if($this->input->post("esAdministrador")=="on") {
            $data['esAdministrador']="checked";
            $esAdministrador=1;
          }
          $this->usuarios_modelo->updateSupervisor($usuario, $id, $esAdministrador);
          $data['mostrarModal']='mostrarModal';
        }
        //Es Supervisor - mostrar la vista esa
        $this->load->view('pages/editarSupervisor', $data);
      }else{
        $usuario=$this->usuarios_modelo->esPmva($idEnc);
        if($usuario!=NULL){
          $data["txt_nombres"]=$usuario->nombres;
          $data["txt_apellidoP"]=$usuario->apellidoP;
          $data["txt_apellidoM"]=$usuario->apellidoM;
          $data["txt_telefono"]=$usuario->telefono;
          $data["txt_usuario"]=$usuario->usuario;
          $data['idEnc']=$idEnc;
          if(isset($_POST['idEnc'])){
            //A guardar
            $id=$this->usuarios_modelo->getIdUsuario($idEnc);
            $data['txt_nombres'] = $this->input->post("txt_nombres");
            $data['txt_apellidoP'] = $this->input->post("txt_apellidoP");
            $data['txt_apellidoM'] = $this->input->post("txt_apellidoM");
            $data['txt_telefono'] = $this->input->post("txt_telefono");
            $usuario=array(
              'nombres' => $this->input->post("txt_nombres"),
              'apellidoP' => $this->input->post("txt_apellidoP"),
              'apellidoM' => $this->input->post("txt_apellidoM"),
              'telefono' => $this->input->post("txt_telefono")
            );
            $this->usuarios_modelo->updatePmva($usuario, $id);
            $data['mostrarModal']='mostrarModal';
          }
          //Es PMVA - mostrar la vista esa
          $this->load->view('pages/editarPmva', $data);
        }else{
          $usuario=$this->usuarios_modelo->esAdmin($idEnc);
          if($usuario!=NULL){
            $data['txt_nombres']=$usuario->display;
            $data['txt_usuario']=$usuario->usuario;
            $data['idEnc']=$idEnc;
            if(isset($_POST['idEnc'])){
              $usuario = array(
                            'display'=>$this->input->post("txt_nombre")
              );
              $data['txt_nombres']=$this->input->post("txt_nombre");
              $this->usuarios_modelo->updateUsuario($usuario, $idEnc);
              $data['mostrarModal']='mostrarModal';
            }
            //Es Admin - mostrar la vista esa
            $this->load->view('pages/editarAdministrador', $data);
          }else{
            //Regresar error
            $data['heading'] = "404 Página no encotrada.";
            $data['message'] = "Lo sentimos, pero no se puede cargar la página solicitada.";
            $this->load->view('errors/cli/error_404',$data);
          }
        }
      }
    }
    $this->load->view('templates/footer');

  }

  public function hacerSupervisorGeneral(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("supervisorGral"));

    //TODO ajustar de acuerdo al url
    $idEnc=$this->uri->segment(3);
    $usuario=$this->usuarios_modelo->hacerSupervisorGeneral($idEnc);
    redirect(base_url().'GECOA/verUsuarios','refresh');
  }

  public function quitarSupervisorGeneral(){
    $tipos=$this->session->userdata('tipos');
    requiredLogin($tipos, array("supervisorGral"));

    //TODO ajustar de acuerdo al url
    $idEnc=$this->uri->segment(3);
    $usuario=$this->usuarios_modelo->quitarSupervisorGeneral($idEnc);
    redirect(base_url().'GECOA/verUsuarios','refresh');
  }

  public function cambiarContrasena(){

    //TODO ajustar de acuerdo al url
    $ticket=$this->uri->segment(3);
    $data['mostrarModal']='';
    $data['ticket']='';
    $data['usuario']='';
    if($ticket!=""){
      if(isset($_POST['txt_contrasena1'])){
        //Cambiar la contraseña de acuerdo al ticket
        //TODO verificar si es necesario un mensaje que la contraseña fue colocada correctamente
        $usuario=$this->usuarios_modelo->cambiarContrasena($ticket, $_POST['txt_contrasena1']);
        $data['mostrarModal']='mostrarModal';
        //redirect(base_url().'GECOA/index','refresh');
      }else{
        //Obtener el display
        $usuario=$this->usuarios_modelo->getUsuario($ticket);
        if($usuario==NULL){
          //TODO enviar a esta pagina no existe - la debe hacer valeria
          //echo "Ticket No Valido";
          redirect(base_url().'GECOA/index','refresh');
        }
        $data["ticket"]=$ticket;
        $data["usuario"]=$usuario->display;

      }
    }else {
      //TODO enviar a esta pagina no existe - la debe hacer valeria
      redirect(base_url().'GECOA/index','refresh');
    }
    $this->load->view('pages/cambiarContrasena',$data);
  }



  public function login(){
    $data["error"]=NULL;

    if(isset($_POST['txt_usuario'])){//Intento de incio de sesion
      $username=$this->input->post("txt_usuario");
      $password=$this->input->post("txt_contrasena");
      if($this->login_modelo->existeUsuario($username)==FALSE){//Usuario inexistente
          $data["error"]="El usuario no existe.";
      }else{
        $usuario=$this->login_modelo->loginUsuario($username, $password);
        if($usuario==FALSE){
          $data["error"]="Usuario/Contraseña incorrectos.";
        }else{
          //Login correcto - guardar sesion y
          //hacer redirect a pagina principal del sistema
          $tipos=array();
          if($usuario->admin)
            array_push($tipos,"admin");
          if($usuario->supervisor)
            array_push($tipos,"supervisor");
          if($usuario->promovente)
            array_push($tipos,"promovente");
          if($usuario->pmva)
            array_push($tipos,"pmva");
          if($usuario->supervisorGral)
            array_push($tipos,"supervisorGral");

          //TODO
          //verificar si el promovente esta en rango de fechas de renta del servicio
          //Agregar el permiso promoventeEsp - para que pueda agregar Supervisores, Proyectos, etc
          if($usuario->promovente){
            //Verificar si esta en el rango de fechas
            if($this->usuarios_modelo->esPromoventeEspecial($usuario->idUsuario)){
                array_push($tipos,"promoventeEsp");
            }
          }
          $data=array(
            'is_logued'=>TRUE,
            'idUsuario' => $usuario->idUsuario,
            'usuario'=>$username,
            'display'=>$usuario->display,
            'tipos'=>$tipos,
            'idEnc'=>$usuario->idEncriptado
          );
          $this->session->set_userdata($data);

          //Redirect index
          redirect(base_url().'GECOA/index','refresh');
        }
      }

    }


    //Todas las vistas puedes enviarles información desde aqui (El controlador)


    ////////////////////// LAS VISTAS QUE CARGAS  /////////////////////////////

    //Header
    //$this->load->view('templates/header');

    //Contenido de la pagina -  En este caso no hay header ni footer
    $this->load->view('pages/login',$data);

    //Footer
    //$this->load->view('templates/footer');
  }

  public function logout(){
    $this->session->sess_destroy();
    redirect(base_url().'GECOA/login','refresh');

  }






}

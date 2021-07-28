<?php

function getMenuCamiones(){
  $menu=array(
            "nombre"=>"Camiones",
            "icono" => "truck icon",
            "submenu"=>array(
              array(
                "nombre"=>"Agregar Camión",
                "direccion"=>"Pedrera/addTruck"
              )
            )
  );
  return $menu;
}

function getMenuUsuarios(){
  $menu=array(
            "nombre"=>"Usuarios",
            "icono" => "user icon",
            "submenu"=>array(
              array(
                "nombre"=>"Agregar Usuarios",
                "direccion"=>"Pedrera/addUsers"
              ),
              array(
                "nombre"=>"Ver Usuarios",
                "direccion"=>"Pedrera/showUsers"
              ),
              array(
                "nombre"=>"Asignar Operador a Planta",
                "direccion"=>"Pedrera/showOperators"
              )
            )
  );
  return $menu;
}

function getMenuPedreras(){
  $menu=array(
            "nombre"=>"Pedreras",
            "icono" => "building icon",
            "submenu"=>array(
              array(
                "nombre"=>"Ver Pedreras",
                "direccion"=>"Pedrera/showQuarries"
              )
            )
  );
  return $menu;
}

function getMenuSites(){
  $menu=array(
            "nombre"=>"Sitios",
            "icono" => "map marker alternate icon",
            "submenu"=>array(
              array(
                "nombre"=>"Ver Sitios",
                "direccion"=>"Pedrera/showSites"
              ),
              array(
                "nombre"=>"Ver GPS",
                "direccion"=>"Pedrera/showGPS"
              )
            )
  );
  return $menu;
}

function getMenuMaterials(){
  $menu=array(
            "nombre"=>"Plantas",
            "icono" => "warehouse icon",
            "submenu"=>array(
              array(
                "nombre"=>"Materiales",
                "direccion"=>"Pedrera/showMaterials"
              ),
              array(
                "nombre"=>"Asignar Materiales a Planta",
                "direccion"=>"Pedrera/showMaterialsQuarry"
              )
            )
  );
  return $menu;
}

function getMenuLog(){
  $menu=array(
            "nombre"=>"Historial",
            "icono" => "history icon",
            "submenu"=>array(
              array(
                "nombre"=>"Mostrar Historial",
                "direccion"=>"Pedrera/showLog"
              )
            )
  );
  return $menu;
}

?>
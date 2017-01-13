<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['salt'] = "c0nr1c17_Im$$";///SALT

$config['menu_admin'] = array('login'=>array('cerrar_session','cerrar_session_ajax'),'dashboard'=>array('*'),'profesor'=>array('*'),'registro'=>array('*'),'buscador'=>array('*'),'catalogos'=>array('*'),'pagina_no_encontrada'=>array('index'),'catalogosdistancia'=>array('*'),'calendario'=>array('*'));
$config['menu_super_admin'] = array('login'=>array('cerrar_session','cerrar_session_ajax'),'dashboard'=>array('*'),'profesor'=>array('*'),'registro'=>array('*'),'buscador'=>array('*'),'catalogos'=>array('*'),'usuarios'=>array('*'),'talleres'=>array('*'),'asistencia'=>array('*'),'pagina_no_encontrada'=>array('index'),'catalogosdistancia'=>array('*'),'calendario'=>array('*'));
$config['menu_docente'] = array('login'=>array('cerrar_session','cerrar_session_ajax'),'dashboard'=>array('*'),'registro'=>array('*'),'pagina_no_encontrada'=>array('index'),'profesor'=>array('*'),'calendario'=>array('*'));

/////Ruta de solicitudes
$config['ruta_documentacion'] = $_SERVER["DOCUMENT_ROOT"]."/becas_imss/assets/files/solicitudes/";
$config['ruta_documentacion_web'] = asset_url().'files/solicitudes/';//base_url()."assets/files/solicitudes/";

$config['tiempo_fuerza_bruta'] = 60 * 60; //3600 = 1 hora => Tiempo válido para chequeo de fuerza bruta

$config['intentos_fuerza_bruta'] = 10; ///Número de intentos válidos durante tiempo 'tiempo_fuerza_bruta'

$config['tiempo_recuperar_contrasenia'] = 60 * 60 * 24; //3600 * 24 = 86400 = 1 día => Límite de tiempo que estará activo el link

$config['meses'] = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');

$config['rol_admin'] = array('DOCENTE'=>array('id'=>1, 'text'=>'Docente'), 'ADMIN'=>array('id'=>2, 'text'=>'Administrador'), 'SUPERADMIN'=>array('id'=>3, 'text'=>'Administrador sistema'));

$config['estado_taller'] = array('ACTIVO'=>array('id'=>1, 'text'=>'Activo'), 'CANCELADO'=>array('id'=>0, 'text'=>'Cancelado'), 'REAGENDADO'=>array('id'=>2, 'text'=>'Re-agendar'));

$config['estado_agenda'] = array('ACTIVO'=>array('id'=>1, 'text'=>'Activo'), 'INACTIVO'=>array('id'=>0, 'text'=>'Inactivo'));

$config['tipo_sesion'] = array('PRESENCIAL'=>array('id'=>1, 'text'=>'Presencial'), 'DISTANCIA'=>array('id'=>2, 'text'=>'En linea'));

$config['delegacion_centro'] = array('09','15','16','35','36','37','38','39');

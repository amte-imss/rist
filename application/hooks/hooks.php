<?php   if(!defined('BASEPATH')) exit('NO DIRECT SCRIPT ACCESS ALLOWED');


class Iniciar_sesion
{

    var $CI;

    function login(){
        $CI =& get_instance();
        
        $CI->load->library('session');
        $CI->load->helper('url');
        $CI->config->load('general');

        $logeado = $CI->session->userdata('usuario_logeado'); ///Obtener datos de sesión
        $matricula = $CI->session->userdata('matricula');
        $tipo_admin = $CI->session->userdata('tipo_admin');

        $tipo_admin_config = $CI->config->item('rol_admin'); ///Tipo de perfil del usuario que inicio sesión
        
        if($tipo_admin==$tipo_admin_config['SUPERADMIN']['id']){

            $modulos_permitidos = $CI->config->item('menu_super_admin');

        }elseif($tipo_admin==$tipo_admin_config['ADMIN']['id']){

            $modulos_permitidos = $CI->config->item('menu_admin');

        }else{

            $modulos_permitidos = $CI->config->item('menu_docente');

        }

        //$modulos_permitidos = ($tipo_admin==$tipo_admin_config['ADMIN']['id']) ? $CI->config->item('menu_admin') : $CI->config->item('menu_docente');
        //pr($modulos_permitidos); pr($CI->session->userdata());

        $controlador = $CI->uri->segment(1);  //Controlador
        $accion = $CI->uri->segment(2);  //Accion
        $accion = (empty($accion) || is_null($accion)) ? 'index' : $accion;
        $accion_total = "*";

        $excepciones = array('login'=>array('index','cerrar_session','cerrar_session_ajax'),'registro'=>array('index','cancelacion','confirmacion','reagendar','registrodistancia'),'calendario'=>array('*')); //Excepciones, acceso sin sesión activa
        $no_accesos = array('login'=>array('index')); //No acceso, con sesión activa
        
        $bandera_excepcion = $bandera_no_acceso = FALSE;
        if((empty($logeado) || is_null($logeado)) || (empty($matricula) || is_null($matricula))){ //En caso de que no cuente con datos en sesión
            foreach ($excepciones as $key_excepcion => $excepcion) { //Recorremos listado de excepciones
                if(($controlador==$key_excepcion && in_array($accion, $excepcion)) || ($controlador==$key_excepcion && in_array($accion_total, $excepcion))) { //Verificamos si la ruta actual se encuentra dentro de las excepciones
                    $bandera_excepcion = TRUE;
                }
            }
            if($bandera_excepcion===FALSE){
                if($CI->input->is_ajax_request()){
                    redirect('login/cerrar_session_ajax');
                } else {
                    redirect('login');
                    exit();
                }
            }
        } else { //En caso de que existan datos en sesión
            $bandera_encontrado=FALSE;
            foreach ($modulos_permitidos as $key_mod_perm => $modulo_permitido) {
                //echo "controlador: $controlador - key_mod_perm: $key_mod_perm - accion: $accion - modulo_permitido: $modulo_permitido - accion_total: $accion_total <br>";
                if(($controlador==$key_mod_perm && in_array($accion, $modulo_permitido)) || ($controlador==$key_mod_perm && in_array($accion_total, $modulo_permitido))){
                    $bandera_encontrado=TRUE;
                }
            }
            foreach ($no_accesos as $key_excepcion_login => $no_acceso) { //Recorremos listado de no accesos
                if($controlador==$key_excepcion_login && in_array($accion, $no_acceso)){ //Verificamos si la ruta actual se encuentra dentro de los no acceso
                    $bandera_no_acceso = TRUE;
                }
            }

            if($bandera_encontrado===FALSE || $bandera_no_acceso===TRUE){
                redirect('dashboard');
                exit();
            }
        }
    }
}

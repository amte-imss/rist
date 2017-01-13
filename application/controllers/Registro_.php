<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase que contiene métodos para registro de participantes en talleres
 * @version 	: 1.0.0
 * @author      : Jesús Z. Díaz Peláez
 **/
class Registro extends MY_Controller {
	private $estado_taller;
    /***********Costructor
     * Función inicial que atrae los atributos de libreria captcha_becas, form_validation y form_complete
     */
	function __construct()
	{
		parent::__construct();                
        $this->load->helpers('captcha');
        $this->load->library('form_validation');
        $this->load->library('form_complete');
        $this->load->library('captcha_becas');
        $this->load->library('seguridad');
        $this->load->library('empleados_siap');
        $this->load->model('Registro_model','mod_registro');
        $this->load->database();
        $this->load->config('general');
        $this->estado_taller = $this->config->item('estado_taller');
	}

	private function validarUsuarioRegistrado($usuario, $estado){
		$result = array('result'=>FALSE, 'msg'=>'');
		$exist = $this->mod_registro->getTaller(array('conditions'=>'T.usr_matricula=\''.$usuario.'\' AND YEAR(NOW())=YEAR(t_fecha_registro) AND t_estado='.$estado));
		switch ($estado) {
			case $this->estado_taller['ACTIVO']['id']:
				if($exist['total']>0){ //Validar que tenga un registro activo
					////Si estado sigue como activo verificamos si tiene asistencia
					$existAsistencia = $this->mod_registro->getAsistencia(array('conditions'=>'taller_id=\''.$exist['data'][0]['taller_id'].'\''));
					//pr($exist);
					//pr($existAsistencia);
					if(isset($existAsistencia) && !empty($existAsistencia) && $existAsistencia['total']>0) //Validar que tenga asistencias
					{
						$result = array('result'=>TRUE, 'msg'=>'Usted ya ha participado en el taller de "'.$exist['data'][0]['a_nombre'].'", recuerde que sólo es posible cursar uno al año. Le agradecemos su participación.');
					} else {
						$result = array('result'=>TRUE, 'msg'=>'Sólo puede estar inscrito en un taller a la vez, por favor cancele su inscripción al taller de "'.$exist['data'][0]['a_nombre'].'" para poder registrarse.');
					}
				}
				break;
			case $this->estado_taller['REAGENDADO']['id']:
				if($exist['total']>0){ //Validar que haya reagendado
					$result = array('result'=>TRUE, 'msg'=>'Sólo puede estar inscrito en un taller a la vez, usted re-agendo anteriormente. No es posible realizar esta acción una segunda vez durante el a&ntildeo. Si no va a participar en el taller le pedimos por favor que cancele su registro.');
				}
				break;
			case $this->estado_taller['CANCELADO']['id']:
				if($exist['total']>1){ //Validar que haya cancelado más de 2 veces
					$result = array('result'=>TRUE, 'msg'=>'No es posible realizar su inscripción, ha cancelado o re-programado m&aacute;s de una vez su registro.');
				}
				break;
		}
		return $result;
	}

	private function validarCupo($agenda){ 
		$result = array('result'=>FALSE, 'msg'=>'');
		$cupo = $this->mod_registro->getCupo($agenda);
		if(!empty($cupo)){
			if(($cupo[0]['a_cupo']-$cupo[0]['ocupado'])<=0){ //Si ya no existe cupo disponible se muestra error
				$result = array('result'=>TRUE, 'msg'=>'Éste taller ya no cuenta con lugares disponibles, por favor elija otro para inscribirse.');
			}
		}
		return $result;
	}

	private function validarUsuarioCupo($usuario, $agenda){
		$exists = array('result'=>FALSE, 'msg'=>'');
        foreach ($this->estado_taller as $et) 
        {
        	$exists = $this->validarUsuarioRegistrado($usuario, $et['id']);
        	if($exists['result'])
        	{
        		break;
        	}
        }
        if(!$exists['result']){
        	$exists = $this->validarCupo($agenda);
        }
        return $exists;
	}
	
	/***********Registro de participantes
	 * Función que determina el tipo de usuario y lo dirige a su página de bienvenida
	 * @method: void index()
	 * @author: Pablo José de Jesús
	 * @modified. Miguel Ángel González Guagnelli, Cambios de seguridad.
	 * @modified. Jesús Z. Díaz Peláez.
	 */
	public function index()
	{
		$estado_agenda = $this->config->item('estado_agenda');
		$error = $msg = null;

		$sesiones_programadas = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=1', 'order'=>array('field'=>'a_inicio', 'type'=>'ASC'))); //buscamos las sesiones programadas
        $sesiones_programadas_distancia = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=2', 'order'=>array('field'=>'a_inicio', 'type'=>'ASC'))); //buscamos las sesiones programadas
		

		if($this->input->post()) //Validar que la información se haya enviado por método POST para almacenado
		{
			if($this->validarToken($this->input->post('token', true))) //se valida qué el token de la sesión sea el mismo que el del formulario solicitante
			{
				$this->config->load('form_validation'); //Cargar archivo con validaciones
				$validations = $this->config->item('form_registro'); //Obtener validaciones de archivo
                $this->form_validation->set_rules($validations);
				
				if($this->form_validation->run()) //Se ejecuta la validación de datos 
				{
					$datos_registro = $this->input->post(null, true);//cargamos el array post en una variable					
                    $exists = $this->validarUsuarioCupo($datos_registro['reg_matricula'], $datos_registro['reg_sesion']); //Validar que el usuario no este registrado

					if(!$exists['result'])
					{
						// obtenemos los datos del sistema de personal (SIAP)
                        $datos_siap = $this->empleados_siap->buscar_usuario_siap( array("reg_delegacion"=>$datos_registro['reg_delegacion'], "asp_matricula"=>$datos_registro['reg_matricula']) );
                        //pr($datos_siap);
						if(is_array($datos_siap) && !empty($datos_siap))
						{
							if($datos_siap['status'] == 1) //si el status del empleado esta activo (1)
							{								
								$usuario = $this->usuarioFactory($datos_siap, $datos_registro);
								$taller = $this->tallerFactory($datos_siap, $datos_registro);

								$guardar_taller = $this->mod_registro->guardarUsuarioTaller($usuario, $taller);
								if($guardar_taller['result'] === TRUE){ // si guardar aspirante fue verdadero
									$agendaData = $this->mod_registro->getSesion(array('conditions'=>array('agenda_id'=>$taller->agenda_id))); //Datos de la fecha programada
									$datos = array('usuario'=>$usuario, 'taller'=>$taller, 'agenda'=>$agendaData, 'agendas'=>$sesiones_programadas);
									$plantilla = $this->load->view('template/email/enviar_confirmacion.tpl.php', $datos, true);
									
									$sentMail = $this->enviar_confirmacion($datos+array('plantilla'=>$plantilla)); //Enviar correo
									
									$this->session->unset_userdata('token'); //Eliminar token
									$this->session->set_flashdata('success', $plantilla);
									if(!$sentMail["result"]){									
										$this->session->set_flashdata('error',$sentMail['error']);
									}
									redirect('/registro/confirmacion', 'refresh');
									exit();
								} else {
									$error = $guardar_taller['msg'];
								}
							} else {
								$error = "La matrícula {$datos_registro['reg_matricula']} no se encuentra en estado activo IMSS.";
							}
						} else {
							$error = "La matrícula {$datos_registro['reg_matricula']} no se encuentra registrada en la base de datos de personal IMSS ó la Delegación seleccionada no corresponde con el número de Matrícula proporcionada. Por favor verifíquelo.";
						}
					} else {
						$error = $exists['msg'];
					}					
				}
			} else {
                $this->session->unset_userdata('token'); //Eliminar token
            }		
		}
		$datos_registro['error'] = $error;
        $datos_registro['msg'] = $msg;

        $datos_registro['delegacion_centro'] = $this->config->item('delegacion_centro');
		
		//se valida que el formulario traiga información            
		$delegaciones = $this->mod_registro->getDelegacion(); //buscamos las delegaciones
		$datos_registro['delegaciones'] = dropdown_options($delegaciones, 'cve_delegacion', 'nom_delegacion'); //generamos las opciones
		
		$datos_registro['sesiones_programadas'] = $sesiones_programadas;
		$sesiones_programadas_disponibles = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND DATE(NOW()) BETWEEN DATE(a_registro) AND DATE(a_inicio - INTERVAL 1 DAY) AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=1')); //buscamos las sesiones programadas
		$datos_registro['sesiones_programadas_disponibles'] = dropdown_options($sesiones_programadas_disponibles, 'agenda_id', 'a_nombre'); //generamos las opciones
		//SELECT * FROM rist_agenda WHERE a_estado=1 AND DATE(NOW()) BETWEEN DATE(a_registro) AND DATE(a_inicio);
		$this->seguridad->token(); //Crear un token cada vez que se ingresa al formulario de inicio sesión
		
		$datos_registro['captcha'] = create_captcha($this->captcha_becas->captcha_config());
                $this->session->set_userdata('captchaWord', $datos_registro['captcha']['word']);
		

        $datos_registro['sesiones_programadas_distancia'] = $sesiones_programadas_distancia;

		$this->template->setTitle("Registro a los talleres de actualización de recursos electrónicos");
		$main_contet = $this->load->view('registro/registro',$datos_registro,true);
		$this->template->setMainContent($main_contet);
		$this->template->getTemplate();
	}

	/***********Cancelación de registro
	 * Función que cancela el registro al taller
	 * @method: void index()
	 * @author: Jesús Z. Díaz Peláez
	 */
	public function cancelacion()
	{
		$estado_agenda = $this->config->item('estado_agenda');
		$error = $msg = null;

		$sesiones_programadas = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND YEAR(a_inicio)=YEAR(NOW())', 'order'=>array('field'=>'a_inicio', 'type'=>'ASC'))); //buscamos las sesiones programadas

		if($this->input->post()) //Validar que la información se haya enviado por método POST para almacenado
		{
			if($this->validarToken($this->input->post('token', true))) //se valida qué el token de la sesión sea el mismo que el del formulario solicitante
			{
				$this->config->load('form_validation'); //Cargar archivo con validaciones
				$validations = $this->config->item('form_cancelacion'); //Obtener validaciones de archivo
                $this->form_validation->set_rules($validations);
				
				if($this->form_validation->run()) //Se ejecuta la validación de datos 
				{
					$datos_registro = $this->input->post(null, true);//cargamos el array post en una variable
					$validarRegistroExistente = $this->mod_registro->getTaller(array('conditions'=>'T.usr_matricula=\''.$datos_registro['reg_matricula'].'\' AND T.cve_delegacion=\''.$datos_registro['reg_delegacion'].'\' AND t_folio=\''.$datos_registro['reg_folio'].'\' AND YEAR(NOW())=YEAR(t_fecha_registro) AND t_estado='.$this->estado_taller['ACTIVO']['id'])); ///Validar registro existente
					
					if($validarRegistroExistente['total']>0) //Si existe un registro con los datos otorgados se procede a cancelar
					{
						$existAsistencia = $this->mod_registro->getAsistencia(array('conditions'=>'taller_id=\''.$validarRegistroExistente['data'][0]['taller_id'].'\''));
						//pr($existAsistencia);
						if(isset($existAsistencia) && !empty($existAsistencia) && $existAsistencia['total']==0) //Validar que tenga asistencias
						{
							$guardar_taller = $this->mod_registro->guardarCancelacion($validarRegistroExistente['data'][0]['taller_id'], array('t_estado'=>$this->estado_taller['CANCELADO']['id']));
							if($guardar_taller['result'] === TRUE){ // Guardado correcto
								$agendaData = $this->mod_registro->getSesion(array('conditions'=>array('agenda_id'=>$validarRegistroExistente['data'][0]['agenda_id']))); //Datos de la fecha programada
								$datos = array('usuario'=>(object)array('usr_nombre'=>$validarRegistroExistente['data'][0]['usr_nombre'],'usr_paterno'=>$validarRegistroExistente['data'][0]['usr_paterno'],'usr_materno'=>$validarRegistroExistente['data'][0]['usr_materno']), 'agenda'=>$agendaData);
								
								///Obtener número de cancelaciones para mostrar mensaje de reagenda
								$datos['validarNumeroCancelaciones'] = $this->mod_registro->getTaller(array('conditions'=>'T.usr_matricula=\''.$datos_registro['reg_matricula'].'\' AND T.cve_delegacion=\''.$datos_registro['reg_delegacion'].'\' AND YEAR(NOW())=YEAR(t_fecha_registro) AND t_estado='.$this->estado_taller['CANCELADO']['id']));

								$plantilla = $this->load->view('registro/cancelacion_exitosa.tpl.php', $datos, true);
								$this->session->unset_userdata('token'); //Eliminar token
								$this->session->set_flashdata('success', $plantilla);
								
								redirect('/registro/confirmacion', 'refresh');
								exit();
							} else {
								$error = $guardar_taller['msg'];
							}
						} else {
							$error = "No es posible eliminar el registro al taller de \"".$validarRegistroExistente['data'][0]['a_nombre']."\" ya que cuenta con asistencias registradas o no fue cancelado oportunamente.";
						}
					} else {
						$error = "No se encontró registro del usuario con esos datos.";
					}
				}
			} else {
                $this->session->unset_userdata('token'); //Eliminar token
            }
		}
		$datos_registro['error'] = $error;
        $datos_registro['msg'] = $msg;
		
		//se valida que el formulario traiga información            
		$delegaciones = $this->mod_registro->getDelegacion(); //buscamos las delegaciones
		$datos_registro['delegaciones'] = dropdown_options($delegaciones, 'cve_delegacion', 'nom_delegacion'); //generamos las opciones
		
		$datos_registro['sesiones_programadas'] = $sesiones_programadas;
		$sesiones_programadas_disponibles = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND DATE(NOW()) BETWEEN DATE(a_registro) AND DATE(a_inicio) AND YEAR(a_inicio)=YEAR(NOW())')); //buscamos las sesiones programadas
		$datos_registro['sesiones_programadas_disponibles'] = dropdown_options($sesiones_programadas_disponibles, 'agenda_id', 'a_nombre'); //generamos las opciones
		
		$this->seguridad->token(); //Crear un token cada vez que se ingresa al formulario de inicio sesión
		
		$datos_registro['captcha'] = create_captcha($this->captcha_becas->captcha_config());
                $this->session->set_userdata('captchaWord', $datos_registro['captcha']['word']);
		
		$this->template->setTitle("Cancelación de registro");
		$main_contet = $this->load->view('registro/cancelacion',$datos_registro,true);
		$this->template->setMainContent($main_contet);
		$this->template->getTemplate();
	}

	public function enviar_confirmacion($datos){
		//$this->load->config('email');
		$this->load->library('My_phpmailer');
        
        $mail = $this->my_phpmailer->phpmailerclass(); //Se cargan datos por default definidos en config/email

        $resultado = array('result'=>false, 'error'=>null);
        
       // $mail->IsSMTP(); // establecemos que utilizaremos SMTP
        // $mail->Host = "172.16.23.18";
        
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        /*$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->Host       = "smtp.gmail.com"; // sets the SMTP server
		$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
		$mail->Username   = "sied.ad.imss@gmail.com"; // SMTP account username
		$mail->Password   = "s13d.4d.1mss";*/

        $mail->addAddress($datos['usuario']->usr_correo, utf8_decode($datos['usuario']->usr_nombre.' '.$datos['usuario']->usr_paterno.' '.$datos['usuario']->usr_materno));
        $mail->Subject = utf8_decode('Confirmación de registro :: Talleres IMSS');
        $mail->msgHTML(utf8_decode($datos['plantilla']));
        //$mail->AltBody = 'This is a plain-text message body';
        
		// $resultado['result'] = true;
        if (!$mail->send()) { //send the message, check for errors
            $resultado['result'] = false;
            $resultado['error'] = $mail->ErrorInfo;
        }
        return $resultado;		
	}

	public function confirmacion(){
		$this->template->setTitle("Registro a los talleres de actualización de recursos electrónicos");
		$this->template->setMainContent('<div class="container"><div class="text-right" style="margin-right:50px;"><a href="'.site_url('/registro/registrosagenda').'" class="btn btn-primary">< Ir al registro</a></div><br></div>');
		$this->template->getTemplate();
	}

	private function validarToken($token){
        $token_session = $this->session->userdata('token'); //Obtenemos token almacenado en sesión
		
		if($token === $token_session) //se valida qué el token de la sesión sea el mismo que el del formulario solicitante
		{
			return TRUE;
		}
		return FALSE;
	}

	private function usuarioFactory($siapData = array(), $formData = array()){
		$usuario = new UsuarioEntity();
		$usuario->usr_matricula = (isset($siapData['matricula']) && !empty($siapData['matricula'])) ? $siapData['matricula'] : null;
	    $usuario->usr_nombre = (isset($siapData['nombre']) && !empty($siapData['nombre'])) ? $siapData['nombre'] : null;
	    $usuario->usr_paterno = (isset($siapData['paterno']) && !empty($siapData['paterno'])) ? $siapData['paterno'] : null;
	    $usuario->usr_materno = (isset($siapData['materno']) && !empty($siapData['materno'])) ? $siapData['materno'] : null;
	    $usuario->usr_correo = (isset($formData['reg_email']) && !empty($formData['reg_email'])) ? $formData['reg_email'] : null;
	    
		return $usuario;
	}

	private function tallerFactory($siapData = array(), $formData = array(), $customData = array()){
		$taller = new TallerEntity();
		$taller->usr_matricula = (isset($siapData['matricula']) && !empty($siapData['matricula'])) ? $siapData['matricula'] : null;
		$taller->agenda_id = (isset($formData['reg_sesion']) && !empty($formData['reg_sesion'])) ? $formData['reg_sesion'] : null;
		$taller->cve_depto_adscripcion = (isset($siapData['adscripcion']) && !empty($siapData['adscripcion'])) ? $siapData['adscripcion'] : null;
		$taller->cve_categoria = (isset($siapData['emp_keypue']) && !empty($siapData['emp_keypue'])) ? $siapData['emp_keypue'] : null;
		$taller->cve_delegacion = (isset($siapData['delegacion']) && !empty($siapData['delegacion'])) ? $siapData['delegacion'] : null;
		$taller->t_folio = $this->seguridad->folio_random();
		//$taller->t_fecha_registro;
		$taller->t_hash_constancia = $this->seguridad->folio_random(10, TRUE);
		$taller->t_estado = (isset($customData['t_estado']) && !empty($customData['t_estado'])) ? $customData['t_estado'] : $this->estado_taller['ACTIVO']['id'];
	    
		return $taller;
	}

	public function registrodistancia($agenda_id=null)
	{

		$estado_agenda = $this->config->item('estado_agenda');
		$error = $msg = null;
		//$agenda_id= $this->input->post('reg_sesion');
		//$agenda_id= $agenda_id;
		$agenda_id= is_numeric($agenda_id) ? $agenda_id : '';

		// validar que sea de tipo numerico

		//$sesiones_programadas = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=1', 'order'=>array('field'=>'a_inicio', 'type'=>'ASC'))); //buscamos las sesiones programadas
        $sesiones_programadas_distancia = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=2 and agenda_id='.$agenda_id, 'order'=>array('field'=>'a_inicio', 'type'=>'ASC'))); //buscamos las sesiones programadas
		//var_dump($sesiones_programadas_distancia);

		if($this->input->post()) //Validar que la información se haya enviado por método POST para almacenado
		{
			$agenda_id= $this->input->post('reg_sesion');
			if($this->validarToken($this->input->post('token', true))) //se valida qué el token de la sesión sea el mismo que el del formulario solicitante
			{
				$this->config->load('form_validation'); //Cargar archivo con validaciones
				$validations = $this->config->item('form_registro'); //Obtener validaciones de archivo
                $this->form_validation->set_rules($validations);
				
				if($this->form_validation->run()) //Se ejecuta la validación de datos 
				{
					$datos_registro = $this->input->post(null, true);//cargamos el array post en una variable					
                    $exists = $this->validarUsuarioDistancia($datos_registro['reg_matricula'], $datos_registro['reg_sesion']); //Validar que el usuario no este registrado

					if(!$exists['result'])
					{
						// obtenemos los datos del sistema de personal (SIAP)
                        $datos_siap = $this->empleados_siap->buscar_usuario_siap( array("reg_delegacion"=>$datos_registro['reg_delegacion'], "asp_matricula"=>$datos_registro['reg_matricula']) );
                        //pr($datos_siap);
						if(is_array($datos_siap) && !empty($datos_siap))
						{
							if($datos_siap['status'] == 1) //si el status del empleado esta activo (1)
							{								
								$usuario = $this->usuarioFactory($datos_siap, $datos_registro);
								$taller = $this->tallerFactory($datos_siap, $datos_registro);

								$guardar_taller = $this->mod_registro->guardarUsuarioTaller($usuario, $taller);
								if($guardar_taller['result'] === TRUE){ // si guardar aspirante fue verdadero
									$agendaData = $this->mod_registro->getSesion(array('conditions'=>array('agenda_id'=>$taller->agenda_id))); //Datos de la fecha programada
									$datos = array('usuario'=>$usuario, 'taller'=>$taller, 'agenda'=>$agendaData);
									$plantilla = $this->load->view('template/email/enviar_confirmacion_distancia.tpl.php', $datos, true);
									
									$sentMail = $this->enviar_confirmacion($datos+array('plantilla'=>$plantilla)); //Enviar correo
									
									$this->session->unset_userdata('token'); //Eliminar token
									$this->session->set_flashdata('success', $plantilla);
									if(!$sentMail["result"]){									
										$this->session->set_flashdata('error',$sentMail['error']);
									}
									redirect('/registro/confirmacion', 'refresh');
									exit();
								} else {
									$error = $guardar_taller['msg'];
								}
							} else {
								$error = "La matrícula {$datos_registro['reg_matricula']} no se encuentra en estado activo IMSS.";
							}
						} else {
							$error = "La matrícula {$datos_registro['reg_matricula']} no se encuentra registrada en la base de datos de personal IMSS ó la Delegación seleccionada no corresponde con el número de Matrícula proporcionada. Por favor verifíquelo.";
						}
					} else {
						$error = $exists['msg'];
					}					
				}
			} else {
                $this->session->unset_userdata('token'); //Eliminar token
            }		
		}
		$datos_registro['error'] = $error;
        $datos_registro['msg'] = $msg;


        $datos_registro['delegacion_centro'] = $this->config->item('delegacion_centro');
		
		//se valida que el formulario traiga información            
		$delegaciones = $this->mod_registro->getDelegacion(); //buscamos las delegaciones
		$datos_registro['delegaciones'] = dropdown_options($delegaciones, 'cve_delegacion', 'nom_delegacion'); //generamos las opciones
		
		//$datos_registro['sesiones_programadas'] = $sesiones_programadas;
		$sesiones_programadas_distancia_disponibles = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND DATE(NOW()) BETWEEN DATE(a_registro) AND DATE(a_registro_fin) AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=2 and agenda_id='.$agenda_id)); //buscamos las sesiones programadas
		//$datos_registro['sesiones_programadas_disponibles'] = dropdown_options($sesiones_programadas_disponibles, 'agenda_id', 'a_nombre'); //generamos las opciones
		//SELECT * FROM rist_agenda WHERE a_estado=1 AND DATE(NOW()) BETWEEN DATE(a_registro) AND DATE(a_inicio);
		$this->seguridad->token(); //Crear un token cada vez que se ingresa al formulario de inicio sesión
		//var_dump($sesiones_programadas_distancia_disponibles);
        $c_sesiones_programadas_distancia_disponibles=count($sesiones_programadas_distancia_disponibles);
        //var_dump($c_sesiones_programadas_distancia_disponibles);


		$datos_registro['captcha'] = create_captcha($this->captcha_becas->captcha_config());
                $this->session->set_userdata('captchaWord', $datos_registro['captcha']['word']);
		

        $datos_registro['sesiones_programadas_distancia'] = $sesiones_programadas_distancia;
        $datos_registro['sesiones_programadas_distancia_disponibles'] = $c_sesiones_programadas_distancia_disponibles;
		$datos_registro['agenda_id'] = $agenda_id;

		$this->template->setTitle("Registro a los talleres de actualización de recursos electrónicos");
		$main_contet = $this->load->view('registro/registro_distancia',$datos_registro,true);
		$this->template->setMainContent($main_contet);
		$this->template->getTemplate();
      
	}

	public function registrosagenda()
	{
		$estado_agenda = $this->config->item('estado_agenda');
		$error = $msg = null;

		$sesiones_programadas = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=1', 'order'=>array('field'=>'a_inicio', 'type'=>'ASC'))); //buscamos las sesiones programadas
        $sesiones_programadas_distancia = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND YEAR(a_inicio)=YEAR(NOW()) and a_tipo=2', 'order'=>array('field'=>'a_inicio', 'type'=>'ASC'))); //buscamos las sesiones programadas
		
		$datos_registro['error'] = $error;
        $datos_registro['msg'] = $msg;

        $datos_registro['delegacion_centro'] = $this->config->item('delegacion_centro');
		
		//se valida que el formulario traiga información            
		$delegaciones = $this->mod_registro->getDelegacion(); //buscamos las delegaciones
		$datos_registro['delegaciones'] = dropdown_options($delegaciones, 'cve_delegacion', 'nom_delegacion'); //generamos las opciones
		
		$datos_registro['sesiones_programadas'] = $sesiones_programadas;
		$sesiones_programadas_disponibles = $this->mod_registro->getSesion(array('conditions'=>'a_estado = '.$estado_agenda['ACTIVO']['id'].' AND DATE(NOW()) BETWEEN DATE(a_registro) AND DATE(a_inicio - INTERVAL 1 DAY) AND YEAR(a_inicio)=YEAR(NOW())')); //buscamos las sesiones programadas
		$datos_registro['sesiones_programadas_disponibles'] = dropdown_options($sesiones_programadas_disponibles, 'agenda_id', 'a_nombre'); //generamos las opciones
		//SELECT * FROM rist_agenda WHERE a_estado=1 AND DATE(NOW()) BETWEEN DATE(a_registro) AND DATE(a_inicio);
		$this->seguridad->token(); //Crear un token cada vez que se ingresa al formulario de inicio sesión
		
		$datos_registro['captcha'] = create_captcha($this->captcha_becas->captcha_config());
                $this->session->set_userdata('captchaWord', $datos_registro['captcha']['word']);
		

        $datos_registro['sesiones_programadas_distancia'] = $sesiones_programadas_distancia;

		$this->template->setTitle("Registro a los talleres de actualización de recursos electrónicos");
		$main_contet = $this->load->view('registro/registros_linea',$datos_registro,true);
		$this->template->setMainContent($main_contet);
		$this->template->getTemplate();
	}

	private function validarUsuarioDistancia($usuario, $agenda_id){
		$result = array('result'=>FALSE, 'msg'=>'');
		$exist = $this->mod_registro->getTaller(array('conditions'=>'T.usr_matricula=\''.$usuario.'\' and a_tipo=2 AND T.agenda_id='.$agenda_id));
		if($exist['total']>0)
		{
			$result = array('result'=>TRUE, 'msg'=>'No es posible realizar su inscripción dos veces al  mismos taller.');
		}	
        
        return $result;
	}







}

class UsuarioEntity
{
	public $usr_matricula;
    public $usr_nombre;
    public $usr_paterno;
    public $usr_materno;
    public $usr_correo;
}

class TallerEntity
{
	//public $taller_id;
	public $usr_matricula;
	public $agenda_id;
	public $cve_depto_adscripcion;
	public $cve_categoria;
	public $cve_delegacion;
	public $t_folio;
	//public $t_fecha_registro;
	public $t_hash_constancia;
	public $t_estado;
}

   
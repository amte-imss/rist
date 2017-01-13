<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	'inicio_sesion' => array(
		array(
			'field'=>'matricula',
			'label'=>'Matrícula',
			'rules'=>'required|max_length[18]|alpha_dash'
		),
		array(
			'field'=>'passwd',
			'label'=>'Contraseña',
			'rules'=>'required' //|callback_valid_pass
		),
		/*array(
			'field'=>'curp',
			'label'=>'CURP',
			'rules'=>'required|exact_length[18]'
		),*/
		array(
			'field'=>'userCaptcha',
			'label'=>'Código de seguridad',
			'rules'=>'required|check_captcha'
		),
	),
      'form_registro' => array(
            array(
                  'field'=>'reg_matricula',
                  'label' => 'Matricula',
                  'rules'=>'required|max_length[10]|min_length[6]|numeric'
            ),
            array(
                  'field'=>'reg_delegacion',
                  'label' => 'Delegaci&oacute;n',
                  'rules'=>'required|min_length[1]|max_length[2]|numeric'
            ),
            array(
                  'field'=>'reg_email',
                  'label' => 'Correo electr&oacute;nico',
                  'rules'=>'required|valid_email|max_length[254]'
            ),
            array(
                  'field'=>'reg_sesion',
                  'label' => 'Sesiones programadas',
                  'rules'=>'required|min_length[1]|max_length[2]|numeric'
            ),
            array(
                  'field'=>'txt_captcha',
                  'label'=>'C&oacute;digo de seguridad',
                  'rules'=>'required|exact_length[6]|check_captcha'
            )
      ),
      'form_cancelacion' => array(
            array(
                  'field'=>'reg_matricula',
                  'label' => 'Matricula',
                  'rules'=>'required|max_length[10]|min_length[6]|numeric'
            ),
            array(
                  'field'=>'reg_delegacion',
                  'label' => 'Delegaci&oacute;n',
                  'rules'=>'required|min_length[1]|max_length[2]|numeric'
            ),
            array(
                  'field'=>'reg_folio',
                  'label' => 'Folio de registro',
                  'rules'=>'required|exact_length[6]|alpha_numeric'
            ),
            array(
                  'field'=>'txt_captcha',
                  'label'=>'C&oacute;digo de seguridad',
                  'rules'=>'required|exact_length[6]|check_captcha'
            )
      ),
      'form_reagendar' => array(
            array(
                  'field'=>'reg_matricula',
                  'label' => 'Matricula',
                  'rules'=>'required|max_length[10]|min_length[6]|numeric'
            ),
            array(
                  'field'=>'reg_delegacion',
                  'label' => 'Delegaci&oacute;n',
                  'rules'=>'required|min_length[1]|max_length[2]|numeric'
            ),
            array(
                  'field'=>'reg_folio',
                  'label' => 'Folio de registro',
                  'rules'=>'required|exact_length[6]|alpha_numeric'
            ),
            array(
                  'field'=>'reg_sesion',
                  'label' => 'Sesiones programadas',
                  'rules'=>'required|min_length[1]|max_length[2]|numeric'
            ),
            array(
                  'field'=>'txt_captcha',
                  'label'=>'C&oacute;digo de seguridad',
                  'rules'=>'required|exact_length[6]|check_captcha'
            )
      ),
);





// VALIDACIONES
/* 
             * isset
             * valid_email
             * valid_url
             * min_length
             * max_length
             * exact_length
             * alpha
             * alpha_numeric
             * alpha_numeric_spaces
             * alpha_dash
             * numeric
             * is_numeric
             * integer
             * regex_match
             * matches
             * differs
             * is_unique
             * is_natural
             * is_natural_no_zero
             * decimal
             * less_than
             * less_than_equal_to
             * greater_than
             * greater_than_equal_to
             * in_list
             * 
             */
         
		 
//custom validation

/*

alpha_accent_space_dot_quot
 * 
alpha_numeric_accent_slash
 * 
alpha_numeric_accent_space_dot_parent
 * 
alpha_numeric_accent_space_dot_double_quot

*/

/*
*password_strong
*
*
*
*
*/
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email'] = Array(
	/*'protocol' => 'smtp',
	//'smtp_crypto' => 'tls',
    'smtp_host' => 'tls://smtp.gmail.com',
    'smtp_port' => 465,
    'smtp_user' => 'sied.ad.imss@gmail.com',
    'smtp_pass' => 's13d.4d.1mss',
    'mailtype'  => 'html', 
    'charset'   => 'utf-8',
    'validate'  => false*/
    
    /*'host' => 'smtp.gmail.com',
    'port' => 587,
    'crypt' => 'tls',
    'username' => "sied.ad.imss@gmail.com",
    'password' => "s13d.4d.1mss",
    'setFrom' => array('email'=>'sied.ad.imss@gmail.com', 'name'=>'Becas IMSS')*/
	
	//Correo IMSS
	'host' => '172.16.23.18',
    'port' => "",
    'crypt' => '',
    'username' => "",
    'password' => "",
    'setFrom' => array('email'=>'acceso.edumed@imss.gob.mx', 'name'=>'RIST IMSS')
	
    //correos de becas
	// 'host' => 'smtp.gmail.com',
    // 'port' => 587,
    // 'crypt' => 'tls',
    // 'username' => "gycpes.imss@gmail.com",
    // 'password' => "imss2015",
    // 'setFrom' => array('email'=>'gycpes.imss@gmail.com', 'name'=>utf8_decode('Programa Intensivo de Inglés para Profesionales de la Salud'))
);

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profesor_model extends CI_Model {
	// Call the CI_Model constructor
	public $tipo_asistencia = array("I"=>1,"F"=>2);
    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }
	
	public function getSesion($id = null){
		if(is_null($id) || $id == 0){
			return null;
		}
		$this->db->select("agenda_id,a_nombre,a_cupo,a_desc,a_inicio,a_fin,CONCAT('Sesi&oacute;n del ',DATE_FORMAT(a_inicio,'%d-%m-%Y'),' al ',DATE_FORMAT(a_fin,'%d-%m-%Y'))as fecha");
		$this->db->from("rist_agenda");
		$this->db->where("agenda_id",$id);
		$result = $this->db->get();
		if($result->num_rows() == 1){
			$sesion = $result->result_array()[0];
			$result->free_result();
			return $sesion;
		}else{
			return null;
		}
	}
	
	public function getSesionList(){
		$resultado = array();
		$this->db->select("agenda_id,CONCAT('Sesi&oacute;n del ',DATE_FORMAT(a_inicio,'%d-%m-%Y'),' al ',DATE_FORMAT(a_fin,'%d-%m-%Y'))as fecha");
		$this->db->from('rist_agenda');
		$this->db->where("a_estado",1);
		$this->db->order_by("a_inicio","ASC");
		$query = $this->db->get();
		
        $resultado['data']=$query->result_array();  
		// pr( $resultado);
        $query->free_result(); //Libera la memoria
        return dropdown_options($resultado['data'], 'agenda_id', 'fecha');
	}
	
	public function getStudents($id = null){
		if(is_null($id) || $id == 0){
			return null;
		}
		$sql = "SELECT 
			taller.taller_id,
<<<<<<< .mine
			usr.usr_matricula ,CONCAT(usr.usr_nombre,' ',usr.usr_paterno,' ',usr.usr_materno) as fullname,
=======
			usr.usr_matricula ,CONCAT(usr.usr_nombre,' ',usr.usr_paterno,' ',usr.usr_materno)fullname,taller.t_hash_constancia,usr.usr_correo,
>>>>>>> .r87
			cat.nom_categoria,
			dept.dpt_rama
		FROM rist_taller taller 
			JOIN rist_usuario usr ON (usr.usr_matricula = taller.usr_matricula)
			JOIN rist_categoria cat ON(cat.id_cat = taller.cve_categoria)
			JOIN rist_departamentos dept ON(dept.cve_depto_adscripcion = taller.cve_depto_adscripcion)
		WHERE taller.agenda_id ={$id} AND taller.t_estado = 1
		ORDER BY 3";
		
		$result = $this->db->query($sql);

		//pr($result);
		if($result->num_rows() > 0){
			$students = $result->result_array();
			$result->free_result();
			foreach($students as $id=>$student){
				$this->db->from("rist_asistencia");
				$this->db->where("taller_id",$student["taller_id"]);
				$result = $this->db->get();
				$students[$id]["asistencias"] = $result->result_array();
				$result->free_result();
			}
			//pr($result);
			return $students;
		}else{
			return null;
		}
	}
	
	public function getAsistencias($id = null, $type="I"){
		if(is_null($id) || $id == 0){
			return null;
		}
		$tipo = array("I"=>1,"F"=>2);
		// as_asistenca 1=> Inicio, 2=> FIN
		$this->db->from("rist_asistencia");
		$this->db->where("taller_id",$id);
		$this->db->where("as_asistencia",$tipo[$type]);
		$result = $this->db->get();
		if($result->num_rows() == 1){
			$asistencias = $result->result_array()[0];
			$result->free_result();
			return $asistencias;
		}else{
			// echo "null";
			return null;
		}
		
	}
	
	public function saveAsistencias($data=null){
		if(is_null($data) || empty($data)){
			return FALSE;
		}
		$tipo = array("I"=>1,"F"=>2);
		$data["as_asistencia"] = $tipo[$data["as_asistencia"]];
		$this->db->insert('rist_asistencia', $data);
		
		$this->db->from("rist_asistencia");
		$this->db->where("as_id",$this->db->insert_id());
		$result = $this->db->get();
		$data = $result->result_array()[0];
		$result->free_result();		
		return $data;		
		// return false;		
	}
}
	

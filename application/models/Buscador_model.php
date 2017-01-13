<?php   defined('BASEPATH') OR exit('No direct script access allowed');

class Buscador_model extends CI_Model {
        //Aquí definimos los nombres que harán referencia a las bases de datos

        public function __construct() {
                // Call the CI_Model constructor
                parent::__construct();

                $this->load->database();
                //$this->db = $this->load->database('buscador', true);
        }

        /**
        * @access 	public
        * @param 	array[optional] (params=>)
        * @return 	array (total=>total de registros, columns=>nombres de los campos, data=>campos)
        *
        */
        public function listado($params=null) {
                $resultado = array();


                $this->db->start_cache();
                $this->db->select('rist_taller.taller_id');

                if(isset($params['sesiones']) && !empty($params['sesiones'])){
                    $this->db->where('rist_taller.agenda_id',$params['sesiones']);
                }

                if(isset($params['delegacion']) && !empty($params['delegacion'])){
                    $this->db->where('rist_taller.cve_delegacion',$params['delegacion']);
                }
                if(isset($params['categoria']) && !empty($params['categoria'])){
                        $this->db->where('rist_taller.cve_categoria',$params['categoria']);
                }

                if(isset($params['adscripcion']) && !empty($params['adscripcion'])){
                        $this->db->where('rist_taller.cve_depto_adscripcion',$params['adscripcion']);
                }

                if(isset($params['tipo']) && !empty($params['tipo'])){
                    $this->db->where('rist_agenda.a_tipo',$params['tipo']);
                }

                //pr($params);
                $this->db->join('rist_agenda', 'rist_taller.agenda_id=rist_agenda.agenda_id AND rist_agenda.a_estado=1', 'left');
                $this->db->join('rist_departamentos', 'rist_taller.cve_depto_adscripcion=rist_departamentos.cve_depto_adscripcion', 'left');
                $this->db->join('rist_usuario', 'rist_taller.usr_matricula=rist_usuario.usr_matricula', 'left');
                $this->db->join('rist_categoria', 'rist_taller.cve_categoria=rist_categoria.des_clave', 'left');
                $this->db->join('rist_delegacion', 'rist_taller.cve_delegacion=rist_delegacion.cve_delegacion', 'left');
                $this->db->group_by("rist_taller.taller_id");

                $this->db->stop_cache();
                /////////////////////// Fin almacenado de parámetros en cache ///////////////////////////

                ///////////////////////////// Obtener número de registros ///////////////////////////////
                $nr = $this->db->get_compiled_select('rist_taller'); //Obtener el total de registros
                $num_rows = $this->db->query("SELECT count(*) AS total FROM (".$nr.") AS temp")->result();
                //pr($this->db->last_query());
                /////////////////////////////// FIN número de registros /////////////////////////////////
                $busqueda = array(
                            'rist_usuario.usr_matricula',
                            'CONCAT(rist_usuario.usr_nombre, " ", rist_usuario.usr_paterno, " ", rist_usuario.usr_materno) AS fullname',
                            'rist_usuario.usr_correo',
                            'rist_categoria.nom_categoria',
                            'CONCAT(rist_departamentos.cve_depto_adscripcion, " - ", rist_departamentos.nom_depto_adscripcion) AS nom_depto_adscripcion',
                            'rist_agenda.a_nombre',
                            'rist_delegacion.nom_delegacion',
                            'rist_taller.t_folio',
                            'rist_taller.t_hash_constancia',
                            'rist_taller.t_estado',
                            'rist_agenda.a_inicio',     //'('.$fecha_inicio.') AS a_inicio' -   'rist_agenda.a_inicio'
                            'rist_agenda.a_fin',
                            'rist_agenda.a_tipo'         //'('.$fecha_fin.') AS a_fin'       -   'rist_agenda.a_fin'
                            );

                $this->db->select($busqueda);
                if(isset($params['order']) && !empty($params['order'])){
                        $tipo_orden = (isset($params['order_type']) && !empty($params['order_type'])) ? $params['order_type'] : "ASC";
                        $this->db->order_by($params['order'], $tipo_orden);
                }
                if(!isset($params['export']) || (isset($params['export']) && $params['export']!=true)) {
                    if(isset($params['per_page']) && isset($params['current_row'])){ //Establecer límite definido para paginación
                            $this->db->limit($params['per_page'], $params['current_row']);
                    }
                }

                $query = $this->db->get('rist_taller'); //Obtener conjunto de registros
                //pr($this->db->last_query());

                $resultado['total']=$num_rows[0]->total;
                $resultado['columns']=$query->list_fields();

                $resultado['data']=$query->result_array();

                $this->db->flush_cache();
                $query->free_result();


                if($resultado['total'] > 0){
                    $students = $resultado['data'];

                    foreach($students as $id=>$student){
                        $this->db->from("rist_asistencia");
                        $this->db->where("taller_id", $student["taller_id"]);
                        $result = $this->db->get();
                        $students[$id]["asistencias"] = $result->result_array();
                        $result->free_result();
                    }
                    $resultado['data'] = $students;
                }

                //pr($resultado['data']);
                //Libera la memoria


                return $resultado;
        }

        public function getSesion($id = null){
            if(is_null($id) || $id == 0){
                return null;
            }
            $this->db->select("agenda_id,a_nombre,a_cupo,a_desc,a_inicio,a_fin,a_evaluacion_inicio,a_evaluacion_fin,CONCAT('Sesi&oacute;n del ',DATE_FORMAT(a_inicio,'%d-%m-%Y'),' al ',DATE_FORMAT(a_fin,'%d-%m-%Y'))as fecha");
            $this->db->from("rist_agenda");
            //$this->db->where("agenda_id",$id);
            $result = $this->db->get();
            if($result->num_rows() == 1){
                $sesion = $result->result_array();
                $result->free_result();
                return $sesion[0];
            }else{
                return null;
            }
        }

        public function listado_adscripcion($params=array()) {
        	$resultado = array();

            $this->db->select("cve_depto_adscripcion,  CONCAT(rist_departamentos.cve_depto_adscripcion, ' - ', rist_departamentos.nom_depto_adscripcion) AS nom_depto_adscripcion");
            if(array_key_exists('conditions', $params)){
                $this->db->where($params['conditions']);
            }
            $this->db->order_by('nom_depto_adscripcion', 'ASC');

            $query = $this->db->get('rist_departamentos'); //Obtener conjunto de registros

    	    $resultado['data']=$query->result_array();

    	    $query->free_result();

            return $resultado;
        }


        public function listado_delegacion($params=array()) {
        	$resultado = array();

            if(array_key_exists('conditions', $params)){
                $this->db->where($params['conditions']);
            }

            $this->db->order_by('rist_delegacion.nom_delegacion', 'ASC');
            $query = $this->db->get('rist_delegacion'); //Obtener conjunto de registros

    	    $resultado['data']=$query->result_array();

    	    $query->free_result(); //Libera la memoria

            return $resultado;
        }

        public function get_taller_adscripcion($params=array()){
            $resultado = array();

            if(array_key_exists('conditions', $params)){
                $this->db->where($params['conditions']);
            }

            $this->db->join('rist_departamentos', 'rist_departamentos.cve_depto_adscripcion=rist_taller.cve_depto_adscripcion ', 'left');
            $query = $this->db->get('pub_bd');
            //pr($this->db->last_query());
            $resultado=$query->result_array();

            $query->free_result(); //Libera la memoria

            return $resultado;
        }

        public function get_taller_agenda($params=array()){
            $resultado = array();

            if(array_key_exists('conditions', $params)){
                $this->db->where($params['conditions']);
            }
            $this->db->order_by('rist_agenda.a_nombre', 'ASC');

            $this->db->join('rist_agenda', 'rist_agenda.agenda_id=rist_taller.agenda_id AND rist_agenda.a_estado=1', 'left');
            $query = $this->db->get('rist_taller');
            //pr($this->db->last_query());
            $resultado=$query->result_array();

            $query->free_result(); //Libera la memoria

            return $resultado;
        }

        /**
        * @access   public
        * @param    array[optional] (params=>)
        * @return   array (total=>total de registros, data=>registros)
        *
        */
        public function listado_categoria($params=array()) {
            $resultado = array();

            if(array_key_exists('conditions', $params)){
                $this->db->where($params['conditions']);
            }
            $this->db->order_by('nom_categoria', 'ASC');

            $query = $this->db->get('rist_categoria'); //Obtener conjunto de registros
            //pr($this->db->last_query());
            //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
            $resultado['data']=$query->result_array();

            $query->free_result(); //Libera la memoria

            return $resultado;
        }

        public function sesion_activa($sesion) {
            $this->db->where('agenda_id', $sesion);
            $this->db->where('a_estado', 1);
            $query = $this->db->get('rist_agenda');
            if($query->num_rows() > 0){
                return true;
            }else{
                return false;
            }
        }

        public function listado_sesiones($params=array()) {
        	$resultado = array();

        	///////////////////// Iniciar almacenado de parámetros en cache /////////////////////////
        	//$this->db->start_cache();
            if(array_key_exists('conditions', $params)){
                $this->db->where($params['conditions']);
            }
            $this->db->order_by('a_inicio', 'DESC');
        	//$this->db->stop_cache();
        	/////////////////////// Fin almacenado de parámetros en cache ///////////////////////////
            $this->db->select("CONCAT(a_nombre, ' (', DATE_FORMAT(a_inicio,'%d-%m-%Y %H:%i'), ')') AS a_nombre, agenda_id,a_cupo,a_desc,a_inicio,a_fin,a_evaluacion_inicio,a_evaluacion_fin");
            $query = $this->db->get('rist_agenda'); //Obtener conjunto de registros

    	    //$resultado['total']=$this->db->count_all_results('idioma'); //Obtener el total de registros
    	    $resultado['data']=$query->result_array();

    	    $query->free_result(); //Libera la memoria

            return $resultado;
        }

}

/*

SELECT
    taller.taller_id,
    usr.usr_matricula,
    CONCAT(usr.usr_nombre,' ',usr.usr_paterno,' ',usr.usr_materno) as fullname,
    cat.nom_categoria,
    dept.nom_depto_adscripcion,
    agenda.a_nombre,
    agenda.a_inicio,
    agenda.a_fin
FROM
    rist_taller taller
JOIN
    rist_agenda agenda ON (taller.agenda_id = agenda.agenda_id)
JOIN
    rist_usuario usr ON (usr.usr_matricula = taller.usr_matricula)
JOIN
    rist_categoria cat ON(cat.id_cat = taller.cve_categoria)
JOIN
    rist_departamentos dept ON(dept.cve_depto_adscripcion = taller.cve_depto_adscripcion)
WHERE
    taller.agenda_id = 2 ORDER BY 3


*/

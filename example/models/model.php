<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
	}

	public function show()
	{
		$query = "SELECT 
				inventario.id as id, 
				usuarios.nombre as usuario, 
				inventario.nombre as inventario
				FROM 
  				inventario,
  				usuarios
				WHERE
				inventario.id_usuarios = usuarios.id";

		$result = $this->db->query($query);

		return $result->result_array();
	}

}

/* End of file model.php */
/* Location: ./application/models/model.php */
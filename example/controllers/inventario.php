<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model');
		$this->load->library('grid');
	}

	public function index()
	{
		$seg1 = $this->uri->segment(3);
		$seg2 = $this->uri->segment(4);

		$data['base'] = "inventario/index";
		$order		  = (!empty($seg4)) ? $seg1 : "FALSE";
		$typeorder	  = (!empty($seg5)) ? $seg2 : "desc";

		$query = $this->portafolios_model->showportafolios();

		$arraygrid = array(
					'DataArray' => $query,
					'Url' 		=> base_url($data['base']."/".$order."/".$typeorder),
					'SegOrder' 	=> 3,
					'TypeOrder' => $typeorder,
					'SegPag' 	=> 5,
					'PageRows' 	=> 20,
					'StrTo'		=> 'strtoupper'
					);

		$this->grid->load($arraygrid);
		$data['datos'] = $this->grid->show();

		$this->load->view('view', $data);
	}

}

/* End of file inventario.php */
/* Location: ./application/controllers/administracion/inventario.php */
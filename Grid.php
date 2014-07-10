<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grid
{
  protected 	$ci;

 /**
 * Establece largos, rows, data y paginacion de un array
 * importante: esta libreria requiere de la libreria pagination para funcionar.
 * @param array $DataArray, data
 * @param string $Url, url en la que debe trabajar el paginador
 * @param integer $SegOrder, segmento de uri para establecer campo a ordenar array
 * @param string $TypeOrder, establecer tipo de orden asc: ascendente desc: descendente
 * @param integer $SegPag, segmento de uri para establecer el numrow del primer campo en la pagina
 * @param integer $PageRows, numero de rows a desplegar por pagina
 * @param string $StrTo, propiedad de array_map, 
 * strtoupper: todo el texto en mayusculas, 
 * strtolower: todo el texto en minusculas, 
 * ucfirst: Convierte el primer caracter a mayúsculas, 
 * ucwords: Convierte a mayúsculas el primer caracter de cada palabra
 * @param string $First_Link, string a mostrar para ir directamente a la primera pagina
 * @param string $Last_Link, string a mostrar para ir directamente a la ultima pagina
 */
  var $DataArray 	= array();
  var $Url 			= '';
  var $SegOrder 	= FALSE;
  var $TypeOrder 	= 'desc';
  var $SegPag 		= '';
  var $PageRows 	= '';
  var $StrTo 		= 'normal';
  var $First_Link 	= '<<';
  var $Last_Link	= '>>';

  	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array())
	{
        $this->ci =& get_instance();
        $this->ci->load->library('pagination');
        
        if (count($params) > 0)
		{
			$this->load($params);
		}
       
	}

	/**
	 * load Preferences
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	public function load($params = array()){
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
	}

	/**
	 * show array
	 * @return array 
	 * (GTotalreg	= 'cantidad total de registros', 
	 * GPagerows	= 'cantidad de registros por pagina', 
	 * GPagin		= 'paginacion',
	 * GSegm 		= 'Segmento de paginado',
	 * GData 		= 'array data')
	 */
	public function show(){
		/*validation information*/
		$this->TypeOrder = ($this->TypeOrder == 'asc') ? 'asc' : 'desc';

		/*set information array*/
		$GridData['GTotalreg'] = count($this->DataArray);
		$GridData['GData']	   = array();
		$GridData['GPagerows'] = (!empty($this->PageRows)) ? $this->PageRows : count($this->DataArray);

		for($Gi = 0 ; $Gi < $GridData['GTotalreg'] ; $Gi++){
			$GridData['GData'][$Gi] = '';
		}
		
		/*Array map*/
		$setarray = (!empty($this->StrTo) && !empty($this->DataArray)) ? $this->ArrayMap($this->DataArray,$this->StrTo) : $this->DataArray;

		/*Array order*/
		if (($this->SegOrder != FALSE) && !empty($this->SegOrder)) {
			$setarray = $this->ordernarArray($setarray,$this->ci->uri->segment($this->SegOrder),$this->TypeOrder);
		}else{
			if ($setarray != FALSE && count($setarray)>0) {
				foreach ($setarray as $column => $valor) {break;}
		 		$setarray = (isset($column)) ? $this->ordernarArray($setarray,$column) : '';
			}else{
				$setarray = '';
			}
			
		}

		/*paginacion*/	
		if (!empty($this->SegPag)) {
		
		$this->ci->pagination->base_url 			= $this->Url;
		$this->ci->pagination->total_rows 			= $GridData['GTotalreg'];
		$this->ci->pagination->per_page 			= $GridData['GPagerows'];
		$this->ci->pagination->first_link			= $this->First_Link;
		$this->ci->pagination->last_link			= $this->Last_Link;
		$this->ci->pagination->uri_segment			= $this->SegPag;
		$this->ci->pagination->num_links			= 3;
		$this->ci->pagination->page_query_string	= FALSE;

		/*set pagination*/
		$GridData['GPagin'] = $this->ci->pagination->create_links();

		}	
		/*data pag segment*/
		$GetSegm			= (!empty($this->SegPag)) ? $this->ci->uri->segment($this->SegPag) : '';
    	$GridData['GSegm']	= (empty($GetSegm)) ? 0 : $GetSegm ;
    	$GMaxReg 			= (($GridData['GSegm'] + $GridData['GPagerows']) > $GridData['GTotalreg']) ? $GridData['GTotalreg'] : ($GridData['GSegm']  + $GridData['GPagerows']) ;

    	/*set array*/
    	if(!empty($setarray)){
    		for($Gi = 0 ; $Gi < $GridData['GPagerows'] ; $Gi++){
    			foreach ($setarray[0] as $key => $value) {
    				$GridData['GData'][$Gi][$key]  =  "";
    			}
    		}
    		/*set data array*/
    		$GCount=0;
    		for($Gi = $GridData['GSegm'] ; $Gi < $GMaxReg ; $Gi++){
       			foreach ($setarray[$Gi] as $key => $value) {
    				$GridData['GData'][$GCount][$key]  =  $value;
    			}
    			$GCount++;
    		}
    	}

		return $GridData;
	}


	function ordernarArray ($ArrayaOrdenar, $ArrayCampo, $typeorder = 'desc') {
		$Array = array();
		$NuevaFila = array();
		$ArrayContador = 0;

		foreach ($ArrayaOrdenar as $key => $value) {
			if (!isset($value[$ArrayCampo])) {
				return $ArrayaOrdenar;
			}
		}
		
		foreach ($ArrayaOrdenar as $clave => $fila) {
			$Array[$clave] = $fila[$ArrayCampo];
			$NuevaFila[$clave] = $fila;
		}
		
		if ($typeorder == 'asc'){
			arsort($Array);
		}else{
			asort($Array);
		}

		$ArrayOrdenado = array();
		
		foreach ($Array as $clave => $pos) {
			$ArrayOrdenado[] = $NuevaFila[$clave];
		}
		
		return $ArrayOrdenado;
	} //fin de la funcion

	function ArrayMap ($setArray,$StrTo = 'strtoupper'){
		$array = array();

		if($StrTo == 'ucfirst' || $StrTo == 'ucwords'):
			foreach ($setArray as $key => $value) {
			$setArray[$key] = array_map('strtolower', $setArray[$key]);
			}
		endif;

		foreach ($setArray as $key => $value) {
			$array[$key] = ($StrTo != 'normal') ? array_map($StrTo, $setArray[$key]) : $setArray[$key];

			if ($StrTo == 'strtoupper'){
				foreach ($array[$key] as $llave => $valor){
					$array[$key][$llave] = str_replace("á", "Á", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("é", "É", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("í", "Í", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("ó", "Ó", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("ú", "Ú", $array[$key][$llave]);
					$array[$key][$llave] = str_replace("ñ", "Ñ", $array[$key][$llave]);
				}
				
			}

			if ($StrTo == 'strtolower' || $StrTo == 'ucfirst' || $StrTo == 'ucwords'){
				foreach ($array[$key] as $llave => $valor){
					$array[$key][$llave] = str_replace("Á", "á", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("É", "é", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("Í", "í", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("Ó", "ó", $array[$key][$llave]); 
					$array[$key][$llave] = str_replace("Ú", "ú", $array[$key][$llave]);
					$array[$key][$llave] = str_replace("Ñ", "ñ", $array[$key][$llave]);
				}
			}

			
		}

		return $array;
	}

}

/* End of file Grid.php */
/* Location: ./application/libraries/Grid.php */
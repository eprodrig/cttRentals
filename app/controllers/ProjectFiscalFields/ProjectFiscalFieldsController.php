<?php
<<<<<<< HEAD
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/ProjectList/ProjectListModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class ProjectListController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new ProjectListModel();
			$this->session= new Session();
			$this->session->init();
			if($this->session->getStatus()===1 || empty($this->session->get('user')))
				header('location: ' . FOLDER_PATH . '/Login');
		}
		public function exec()
		{
		  $params = array('user' => $this->session->get('user'));
		  $this->render(__CLASS__, $params);
		}

		public function GetProveedores($request_params)
		{
	      $result = $this->model->GetProveedores($request_params);
		  $i = 0;
		  while($row = $result->fetch_assoc()){
			 $rowdata[$i] = $row;
			$i++;
		  }
			if ($i>0){
				$res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
			} else {
				$res =  '[{"pjt_id":"0"}]';	
			}
			echo $res;
		}

		public function SaveProveedores($request_params)
		{
		  if($request_params['IdProveedor'] == ""){
			$result = $this->model->SaveProveedores($request_params);	  
		  }else{
			$result = $this->model->ActualizaProveedor($request_params);	  
		  }
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function GetProveedor($request_params)
		{
	      $result = $this->model->GetProveedor($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function DeleteProveedores($request_params)
		{
		  $result = $this->model->DeleteProveedores($request_params);	  
		  echo json_encode($result ,JSON_UNESCAPED_UNICODE);	
		}

		public function GetTipoProveedores($request_params)
		{
		  $result = $this->model->GetTipoProveedores($request_params);	  
		  echo json_encode($result ,JSON_UNESCAPED_UNICODE);	
		}

	  
	}
=======
    defined('BASEPATH') or exit('No se permite acceso directo');
    require_once ROOT . FOLDER_PATH . '/app/models/ProjectFiscalFields/ProjectFiscalFieldsModel.php';
    require_once LIBS_ROUTE .'Session.php';

class ProjectFiscalFieldsController extends Controller
{
    private $session;
    public $model;


    public function __construct()
    {
        $this->model = new ProjectFiscalFieldsModel();
        $this->session = new Session();
        $this->session->init();
        if($this->session->getStatus() === 1 || empty($this->session->get('user')))
            header('location: ' . FOLDER_PATH .'/Login');
    }

    public function exec()
    {
        $params = array('user' => $this->session->get('user'));
        $this->render(__CLASS__, $params);
    }


// Obtiene la lista de productos
    public function tableProjects($request_params)
    {
        $result = $this->model->tableProjects($request_params);
        echo $result;
    }

// Cambia el estatus del projecto
    public function updateStatus($request_params)
    {
        $result = $this->model->updateStatus($request_params);
        echo $result;
    }
// Actualiza la información del cliente
    public function updateInfoCustomer($request_params)
    {
        $result = $this->model->updateInfoCustomer($request_params);
        echo $result;

    }

// Obtiene la información del cliente
    public function getCustomerFields($request_params)
    {
        $result = $this->model->getCustomerFields($request_params);
        $i = 0;
		while($row = $result->fetch_assoc()){
			$rowdata[$i] = $row;
			$i++;
		}
		if ($i>0){
			$res =  json_encode($rowdata,JSON_UNESCAPED_UNICODE);	
		} else {
			$res =  '[{"cus_id":"0"}]';	
		}
		echo $res;
    }

}
>>>>>>> 321c088b6178e8b05cb637fa46c7037d57bee731

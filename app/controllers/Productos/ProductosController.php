<?php
	defined('BASEPATH') or exit('No se permite acceso directo');
	require_once ROOT . FOLDER_PATH . '/app/models/Productos/ProductosModel.php';
	require_once LIBS_ROUTE . 'Session.php';

	class ProductosController extends Controller
	{

		private $session;
		public $model;

		public function __construct()
		{
			$this->model = new ProductosModel();
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

		public function GetSubCategoria($request_params)
		{
	      $result = $this->model->GetSubCategoria($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function SaveSubCategoria($request_params)
		{
		  if($request_params['IdSubCategoria'] == ""){
			$result = $this->model->SaveSubCategoria($request_params);	  
		  }else{
			$result = $this->model->ActualizaSubCategoria($request_params);	  
		  }
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function GetSubCategorias($request_params)
		{
	      $result = $this->model->GetSubCategorias($request_params);
		  echo json_encode($result,JSON_UNESCAPED_UNICODE);	
		}

		public function DeleteSubCategoria($request_params)
		{
		  $result = $this->model->DeleteSubCategoria($request_params);	  
		  echo json_encode($result ,JSON_UNESCAPED_UNICODE);	
		}


	  
	}
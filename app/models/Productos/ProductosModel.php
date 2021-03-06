<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class ProductosModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
//Guarda prodducto 
	public function SaveProductos($params)
	{
        $estatus = 0;
			try {
               
			    $qry = "INSERT INTO ctt_products(prd_sku, prd_name, prd_english_name, prd_model, prd_serial_number, prd_cost, 
				prd_price, prd_coin_type, prd_date_registry, prd_visibility, prd_comments, prd_status, sbc_id, sup_id, srv_id)
				VALUES('".$params['SkuProducto']."','".$params['NomProducto']."','".$params['NomEngProducto']."'
				,'".$params['ModelProducto']."','".$params['SerieProducto']."','".$params['CostProducto']."'
				,'".$params['PriceProducto']."',".$params['idMoneda'].",NOW(),".$params['visible'].",'".$params['DesProducto']."'
				,1,".$params['idSubCategoria'].",".$params['idProveedor'].",".$params['idTipeService'].")";
			
			    $this->db->query($qry);	

				$qry = "SELECT MAX(prd_id) AS id FROM ctt_products;";
				$result = $this->db->query($qry);
				if ($row = $result->fetch_row()) {
				    $lastid = trim($row[0]);
				}
			    $qry = "INSERT INTO ctt_store_product(stp_quantity, str_id, prd_id)
				VALUES(1,".$params['idAlmacen'].",$lastid) ";
				$result = $this->db->query($qry);

				$estatus = 1;
			} catch (Exception $e) {
				$estatus = 0;
			}
		return $estatus;
	}
// Optiene las subcategorias existentes
	public function GetProductos()
	{
		$qry = "SELECT pro.prd_id, pro.prd_sku, pro.prd_name, pro.prd_english_name,
				 pro.prd_model, pro.prd_serial_number, pro.prd_cost, pro.prd_price,
				 pro.prd_coin_type, pro.prd_visibility,pro.prd_comments, pro.sbc_id, 
				 pro.sup_id, pro.srv_id, cat.cat_id, storeP.str_id 
				,subcat.sbc_name , cat.cat_name , store.str_name , serv.srv_name , storeP.stp_id
				FROM  ctt_products AS pro
				INNER JOIN ctt_subcategories AS subcat ON subcat.sbc_id = pro.sbc_id
				INNER JOIN ctt_categories AS cat ON cat.cat_id = subcat.cat_id
				INNER JOIN ctt_store_product AS storeP ON storeP.prd_id = pro.prd_id
				INNER JOIN ctt_stores AS store ON store.str_id = storeP.str_id
				INNER JOIN ctt_services AS serv ON serv.srv_id = pro.srv_id
				where pro.prd_status = 1;";
		$result = $this->db->query($qry);
		$lista = array();
		while ($row = $result->fetch_row()){
			$item = array("prd_id" =>$row[0],
						"prd_sku" =>$row[1],
                        "prd_name" =>$row[2],
                        "prd_english_name" =>$row[3],
                        "prd_model" =>$row[4],
						"prd_serial_number" =>$row[5],
						"prd_cost" =>$row[6],
						"prd_price" =>$row[7],
						"prd_coin_type" =>$row[8],
						"prd_visibility" =>$row[9],
						"prd_comments" =>$row[10],
						"sbc_id" =>$row[11],
						"sup_id" =>$row[12],
						"srv_id" =>$row[13],
						"cat_id" =>$row[14],
						"str_id" =>$row[15],
						"sbc_name" =>$row[16],
						"cat_name" =>$row[17],
						"str_name" =>$row[18],
						"srv_name" =>$row[19],
						"stp_id" =>$row[20]);
			array_push($lista, $item);
		}
		return $lista;
	}

    public function GetProducto($params)
	{
		$qry = "SELECT pro.prd_id, pro.prd_sku, pro.prd_name, pro.prd_english_name, pro.prd_model, 
		pro.prd_serial_number, pro.prd_cost, pro.prd_price, pro.prd_coin_type, pro.prd_visibility,
		pro.prd_comments, pro.sbc_id, pro.sup_id, pro.srv_id, cat.cat_id, storeP.str_id 
		,subcat.sbc_name , cat.cat_name , store.str_name , serv.srv_name , storeP.stp_id
				FROM  ctt_products AS pro
				INNER JOIN ctt_subcategories AS subcat ON subcat.sbc_id = pro.sbc_id
				INNER JOIN ctt_categories AS cat ON cat.cat_id = subcat.cat_id
				INNER JOIN ctt_store_product AS storeP ON storeP.prd_id = pro.prd_id
				INNER JOIN ctt_stores AS store ON store.str_id = storeP.str_id
				INNER JOIN ctt_services AS serv ON serv.srv_id = pro.srv_id
				where pro.prd_status = 1 and pro.prd_id =  ".$params['id'].";";

		$result = $this->db->query($qry);
		if($row = $result->fetch_row()){
			$item = array("prd_id" =>$row[0],
						"prd_sku" =>$row[1],
                        "prd_name" =>$row[2],
                        "prd_english_name" =>$row[3],
                        "prd_model" =>$row[4],
						"prd_serial_number" =>$row[5],
						"prd_cost" =>$row[6],
						"prd_price" =>$row[7],
						"prd_coin_type" =>$row[8],
						"prd_visibility" =>$row[9],
						"prd_comments" =>$row[10],
						"sbc_id" =>$row[11],
						"sup_id" =>$row[12],
						"srv_id" =>$row[13],
						"cat_id" =>$row[14],
						"str_id" =>$row[15],
						"sbc_name" =>$row[16],
						"cat_name" =>$row[17],
						"str_name" =>$row[18],
						"srv_name" =>$row[19],
						"stp_id" =>$row[20]);
		}
		return $item;
	}

    public function ActualizaProducto($params)
	{
        $estatus = 0;
			try {
                $qry = "UPDATE ctt_products
						SET prd_sku = '".$params['SkuProducto']."'
						,prd_name = '".$params['NomProducto']."'
						,prd_english_name = '".$params['NomEngProducto']."'
						,prd_model = '".$params['ModelProducto']."'
						,prd_serial_number = '".$params['SerieProducto']."'
						,prd_cost = '".$params['CostProducto']."'
						,prd_price = '".$params['PriceProducto']."' 
						,prd_coin_type = '".$params['idMoneda']."'
						,prd_visibility = '".$params['visible']."'
						,prd_comments = '".$params['DesProducto']."'
						,sbc_id = '".$params['idSubCategoria']."'
						,sup_id = '".$params['idProveedor']."'
						,srv_id =  '".$params['idTipeService']."'
						WHERE prd_id =   ".$params['IdProducto'].";";
				$this->db->query($qry);	

                $qry = "UPDATE ctt_store_product
						SET str_id = '".$params['idAlmacen']."'
						WHERE stp_id =  ".$params['idStoreProducto'].";";
				$this->db->query($qry);	


				$estatus = 1;
			} catch (Exception $e) {
				$estatus = 0;
			}
		return $estatus;
	}

    //borra proveedor
	public function DeleteProducto($params)
	{
        $estatus = 0;
        try {
            $qry = "UPDATE ctt_products
                    SET prd_status = 0
                    WHERE prd_id in (".$params['IdProducto'].");";
            $this->db->query($qry);
            $estatus = 1;
        } catch (Exception $e) {
            $estatus = 0;
        }
		return $estatus;
	}


}
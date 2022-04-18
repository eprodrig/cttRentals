<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class BudgetModel extends Model
{

    public function __construct()
    {
      parent::__construct();
    }

// Listado de clientes
    public function listCustomers($params)
    {
        $prd = $this->db->real_escape_string($params['prm']);
        $qry = "SELECT cs.*, ct.cut_name FROM ctt_customers AS cs
                INNER JOIN ctt_customers_type AS ct ON ct.cut_id = cs.cut_id
                WHERE cs.cus_status = 1 ORDER BY cs.cus_name;";
        return $this->db->query($qry);
    }    

    
// Listado de proyectos
    public function listProjects($params)
    {
        // Debe leer todos los proyectos que se encuentren en estaus 1 - Cotización
        $pjId = $this->db->real_escape_string($params['pjId']);

        $qry = "SELECT 
                    pj.pjt_id  
                    , pj.pjt_number 
                    , pj.pjt_name  
                    , DATE_FORMAT(pj.pjt_date_project,'%d/%m/%Y') AS pjt_date_project 
                    , DATE_FORMAT(pj.pjt_date_start,'%d/%m/%Y') AS pjt_date_start 
                    , DATE_FORMAT(pj.pjt_date_end,'%d/%m/%Y') AS pjt_date_end 
                    , pj.pjt_location 
                    , pj.pjt_status 
                    , pj.cuo_id 
                    , pj.loc_id 
                    , co.cus_id 
                    , co.cus_parent 
                    , lo.loc_type_location 
                    , pt.pjttp_name 
                    , pt.pjttp_id
                    , '$pjId' as pjId
                FROM ctt_projects AS pj
                INNER JOIN ctt_customers_owner AS co ON co.cuo_id = pj.cuo_id
                INNER JOIN ctt_location AS lo ON lo.loc_id = pj.loc_id
                LEFT JOIN ctt_projects_type As pt ON pt.pjttp_id = pj.pjttp_id
                WHERE pj.pjt_status = '1' ORDER BY pj.pjt_id DESC;
                ";
        return $this->db->query($qry);
    }    

    
// Listado de tipos de proyectos
public function listProjectsType($params)
{

    $qry = "SELECT * FROM ctt_projects_type ORDER BY pjttp_name;";
    return $this->db->query($qry);
}    


    
// Listado de relaciones
    public function listCustomersDef($params)
    {
        $cusId = $this->db->real_escape_string($params['cusId']);
        $cutId = $this->db->real_escape_string($params['cutId']);

        if ($cutId == 1){
            $subQry = "SELECT cuo_id, cus_id AS cus_id, $cutId AS cut_id FROM ctt_customers_owner WHERE cus_parent = $cusId";
        } else {
            $subQry = "SELECT cuo_id, cus_parent AS cus_id, $cutId AS cut_id FROM ctt_customers_owner WHERE cus_id = $cusId";
        }


        $qry = $subQry;
        return $this->db->query($qry);
    }    


    
// Listado de relaciones total
    public function listCustomersOwn($params)
    {
        $qry = "SELECT * FROM ctt_customers_owner";
        return $this->db->query($qry);
    }    


    
// Listado de claves de Relaciones
    public function listProjectsDef($params)
    {
        $cusId = $this->db->real_escape_string($params['cusId']);

        $qry = "SELECT * FROM ctt_customers_owner WHERE cus_id = $cusId OR cus_parent = $cusId;";
        return $this->db->query($qry);
    }    


    
    
// Listado de versiones
    public function listVersion($params)
    {
        $pjtId = $this->db->real_escape_string($params['pjtId']);

        $qry = "SELECT * FROM ctt_version WHERE pjt_id = $pjtId AND ver_status = 'C' ORDER BY ver_date DESC;";
        return $this->db->query($qry);
    }    


    
    
// Listado de cotizaciones
    public function listBudgets($params)
    {
        $verId = $this->db->real_escape_string($params['verId']);
        $dstr = $this->db->real_escape_string($params['dstr']);
        $dend = $this->db->real_escape_string($params['dend']);

        $qry = "SELECT bg.*, pj.pjt_id, sb.sbc_name,
                    date_format(pj.pjt_date_start, '%Y%m%d') AS pjt_date_start, 
                    date_format(pj.pjt_date_end, '%Y%m%d') AS pjt_date_end, 
                    CASE 
                        WHEN bdg_prod_level ='K' THEN 
                            (SELECT count(*) FROM ctt_products_packages WHERE prd_parent = bg.prd_id)
                        WHEN bdg_prod_level ='P' THEN 
                            (SELECT prd_stock FROM ctt_products WHERE prd_id = bg.prd_id)
                        ELSE 
                            (SELECT prd_stock FROM ctt_products WHERE prd_id = bg.prd_id)
                        END AS bdg_stock
                FROM ctt_budget AS bg
                INNER JOIN ctt_version AS vr ON vr.ver_id = bg.ver_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = vr.pjt_id
                LEFT JOIN ctt_products AS pd ON pd.prd_id = bg.prd_id
                LEFT JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
                WHERE bg.ver_id = $verId ORDER BY bdg_prod_name ASC;";

        return $this->db->query($qry);
    }    


// Listado de descuentos
public function listDiscounts($params)
{
    $level = $this->db->real_escape_string($params['level']);
    $qry = "SELECT * FROM ctt_discounts WHERE dis_level = $level ORDER BY dis_discount;";
    return $this->db->query($qry);
}    


// Listado de productos
    public function listProducts($params)
    {

        $word = $this->db->real_escape_string($params['word']);
        $dstr = $this->db->real_escape_string($params['dstr']);
        $dend = $this->db->real_escape_string($params['dend']);

        $qry = "SELECT pd.prd_id, pd.prd_sku, pd.prd_name, pd.prd_price, pd.prd_level, pd.prd_insured, 
                        sb.sbc_name,
                CASE 
                    WHEN prd_level ='K' THEN 
                        (SELECT count(*) FROM ctt_products_packages WHERE prd_parent = pd.prd_id)
                    WHEN prd_level ='P' THEN 
                        (SELECT prd_stock FROM ctt_products WHERE prd_id = pd.prd_id)
                    ELSE 
                        (SELECT prd_stock FROM ctt_products WHERE prd_id = pd.prd_id)
                    END AS stock
            FROM ctt_products AS pd
            INNER JOIN ctt_subcategories AS sb ON sb.sbc_id = pd.sbc_id
            WHERE pd.prd_status = 1 AND pd.prd_visibility = 1 
                AND upper(pd.prd_name) LIKE '%$word%' OR upper(pd.prd_sku) LIKE '%$word%'
            ORDER BY pd.prd_name ;";
        return $this->db->query($qry);
    } 


    
// Lista los relacionados al producto
public function listProductsRelated($params)
{

    $type = $this->db->real_escape_string($params['type']);
    $prdId = $this->db->real_escape_string($params['prdId']);


    if ($type == 'K'){
        $qry = "SELECT pr.*, sc.sbc_name, ct.cat_name 
                FROM ctt_products AS pr
                INNER JOIN ctt_subcategories AS sc ON sc.sbc_id = pr.sbc_id
                INNER JOIN ctt_categories AS ct ON ct.cat_id = sc.cat_id
                WHERE prd_id = $prdId AND pr.prd_status = 1 AND sc.sbc_status = 1 AND ct.cat_status = 1
                    UNION
                SELECT pr.*, sc.sbc_name, ct.cat_name 
                FROM ctt_products_packages AS pk
                INNER JOIN ctt_products AS pr ON pr.prd_id = pk.prd_id
                INNER JOIN ctt_subcategories AS sc ON sc.sbc_id = pr.sbc_id
                INNER JOIN ctt_categories AS ct ON ct.cat_id = sc.cat_id
                WHERE pk.prd_parent = $prdId AND pr.prd_status = 1 AND sc.sbc_status = 1 AND ct.cat_status = 1;";
        return $this->db->query($qry);

    } else if($type == 'P') {
        $qry = "SELECT pr.*, sc.sbc_name, ct.cat_name 
                FROM ctt_products AS pr
                INNER JOIN ctt_subcategories AS sc ON sc.sbc_id = pr.sbc_id
                INNER JOIN ctt_categories AS ct ON ct.cat_id = sc.cat_id
                WHERE prd_id = $prdId AND pr.prd_status = 1 AND sc.sbc_status = 1 AND ct.cat_status = 1
                    UNION
                SELECT pr.*, sc.sbc_name, ct.cat_name 
                FROM ctt_accesories AS ac
                INNER JOIN ctt_products AS pr ON pr.prd_id = ac.prd_id
                INNER JOIN ctt_subcategories AS sc ON sc.sbc_id = pr.sbc_id
                INNER JOIN ctt_categories AS ct ON ct.cat_id = sc.cat_id
                WHERE ac.acr_parent = $prdId AND pr.prd_status = 1 AND sc.sbc_status = 1 AND ct.cat_status = 1;";
        return $this->db->query($qry);

    } else {
        $qry = "SELECT * FROM ctt_products WHERE prd_id = $prdId";
        return $this->db->query($qry);

    }
}


// Listado de stock del productos
public function stockProdcuts($params)
{

    $prdId = $this->db->real_escape_string($params['prdId']);

    $qry = "SELECT
                pr.prd_name, ifnull(pj.pjt_name,'') as pjt_name, sr.ser_sku, sr.ser_serial_number, 
                sr.ser_situation, ifnull(pe.pjtpd_day_start,'') as pjtpd_day_start, 
                ifnull(pe.pjtpd_day_end,'') as pjtpd_day_end, pr.prd_id 
            FROM ctt_series                 AS sr
            INNER JOIN ctt_products         AS pr ON pr.prd_id = sr.prd_id
            LEFT JOIN ctt_projects_detail   AS pd ON pd.pjtdt_id = sr.pjtdt_id
            LEFT JOIN ctt_projects_content  AS pc ON pc.pjtcn_id = pd.pjtcn_id
            LEFT JOIN ctt_projects          AS pj ON pj.pjt_id = pc.pjt_id
            LEFT JOIN ctt_projects_periods  AS pe ON pe.pjtdt_id = sr.pjtdt_id
            WHERE sr.prd_id = $prdId;";
    return $this->db->query($qry);
} 




// Agrega Version
    public function SaveVersion($params)
    {
        $pjtId                  = $this->db->real_escape_string($params['pjtId']);
        $verCode                = $this->db->real_escape_string($params['verCode']);
        $qry = "INSERT INTO ctt_version (ver_code, pjt_id) VALUES ('$verCode', $pjtId);";
        $this->db->query($qry);
        $result = $this->db->insert_id;
        return $result;
    }



// Agrega Cotizaciones
    public function SaveBudget($params)
    {

        $bdg_prod_sku           = $params['bdgSku'];
        $bdg_prod_level         = $params['bdgLevel'];
        $bdg_prod_name          = str_replace('°','"',$params['bdgProduc']);
        $bdg_prod_price         = $params['bdgPricBs'];
        $bdg_quantity           = $params['bdgQtysBs'];
        $bdg_days_base          = $params['bdgDaysBs'];
        $bdg_discount_base      = $params['bdgDescBs'];
        $bdg_days_trip          = $params['bdgDaysTp'];
        $bdg_discount_trip      = $params['bdgDescTp'];
        $bdg_days_test          = $params['bdgDaysTr'];
        $bdg_discount_test      = $params['bdgDescTr'];
        $bdg_insured            = $params['bdgInsured'];
        $ver_id                 = $params['verId'];
        $prd_id                 = $params['prdId'];
        $pjt_id                 = $params['pjtId'];


        $qry = "INSERT INTO ctt_budget (
                    bdg_prod_sku, bdg_prod_name, bdg_prod_price, bdg_prod_level, 
                    bdg_quantity, bdg_days_base, bdg_discount_base, bdg_days_trip, 
                    bdg_discount_trip, bdg_days_test, bdg_discount_test,bdg_insured, 
                    ver_id, prd_id 
                ) VALUES (
                    '$bdg_prod_sku',
                    'ucase($bdg_prod_name)',    
                    '$bdg_prod_price',      
                    '$bdg_prod_level',
                    '$bdg_quantity',
                    '$bdg_days_base',           
                    '$bdg_discount_base',   
                    '$bdg_days_trip',
                    '$bdg_discount_trip',
                    '$bdg_days_test',
                    '$bdg_discount_test',
                    '$bdg_insured',
                    '$ver_id',
                    '$prd_id'
                );
                ";
            $this->db->query($qry);
            $result = $this->db->insert_id;
            return $pjt_id;
    }



// Agrega nuevo proyecto
    public function SaveProject($params)
    {
        $cuo            = $this->db->real_escape_string($params['cuoId']);
        $cusId          = $this->db->real_escape_string($params['cusId']); 
        $cusParent      = $this->db->real_escape_string($params['cusParent']);
        $cuoId          = $cuo;

        if ($cuo == '0'){
            $qry01 = "INSERT INTO ctt_customers_owner (cus_id, cus_parent)
                        VALUES ($cusId, $cusParent);";

            $this->db->query($qry01);
            $cuoId = $this->db->insert_id;
            
        }

        $pjt_name               = $this->db->real_escape_string($params['pjtName']); 
        $pjt_date_start         = $this->db->real_escape_string($params['pjtDateStart']);
        $pjt_date_end           = $this->db->real_escape_string($params['pjtDateEnd']); 
        $pjt_location           = $this->db->real_escape_string($params['pjtLocation']);
        $pjt_type               = $this->db->real_escape_string($params['pjtType']);
        $cuo_id                 = $cuoId;
        $loc_id                 = $this->db->real_escape_string($params['locId']);

        $qry02 = "INSERT INTO ctt_projects (
                    pjt_name, pjt_date_start, pjt_date_end, pjt_location, pjttp_id, cuo_id, loc_id
                ) VALUES (
                    'ucase($pjt_name)', 
                    '$pjt_date_start',
                    '$pjt_date_end', 
                    'ucase($pjt_location)', 
                    $pjt_type, 
                    $cuo_id, 
                    $loc_id
                );";
        $this->db->query($qry02);
        $pjtId = $this->db->insert_id;

        $pjt_number = 'P' . str_pad($pjtId, 7, "0", STR_PAD_LEFT);

        $qry03 = "UPDATE ctt_projects
                  SET pjt_number = '$pjt_number'
                  WHERE pjt_id = $pjtId;";
        $this->db->query($qry03);

        return $pjtId;


    }


// Actualiza los datos del proyecto
public function UpdateProject($params)
{
    $cuo_id         = $this->db->real_escape_string($params['cuoId']);
    $cus_id         = $this->db->real_escape_string($params['cusId']); 
    $cus_Parent     = $this->db->real_escape_string($params['cusParent']);

    $qry01 = "  UPDATE      ctt_customers_owner 
                    SET     cus_id      = '$cus_id', 
                            cus_parent  = '$cus_Parent'
                    WHERE   cuo_id      = '$cuo_id';";

    $this->db->query($qry01);

    $pjt_id                 = $this->db->real_escape_string($params['projId']); 
    $pjt_name               = $this->db->real_escape_string($params['pjtName']); 
    $pjt_date_start         = $this->db->real_escape_string($params['pjtDateStart']);
    $pjt_date_end           = $this->db->real_escape_string($params['pjtDateEnd']); 
    $pjt_location           = $this->db->real_escape_string($params['pjtLocation']);
    $pjt_type               = $this->db->real_escape_string($params['pjtType']);
    $loc_id                 = $this->db->real_escape_string($params['locId']);


    $qry02 = "UPDATE    ctt_projects
                 SET    pjt_name        = 'ucase($pjt_name)', 
                        pjt_date_start  = '$pjt_date_start', 
                        pjt_date_end    = '$pjt_date_end',
                        pjt_location    = 'ucase($pjt_location)', 
                        pjttp_id        = '$pjt_type',  
                        cuo_id          = '$cuo_id',
                        loc_id          = '$loc_id'
                WHERE   pjt_id          =  $pjt_id;
                ";
    $this->db->query($qry02);

    return $pjt_id;

}


// Promueve proyecto
public function saveBudgetList($params)
{
    $verId = $this->db->real_escape_string($params['verId']);
    $qry = "SELECT *, ucase(date_format(vr.ver_date, '%d-%b-%Y %H:%i')) as ver_date_real,
                CONCAT_WS(' - ' , date_format(pj.pjt_date_start, '%d-%b-%Y'), date_format(pj.pjt_date_end, '%d-%b-%Y')) as period
            FROM ctt_budget AS bg
            INNER JOIN ctt_version AS vr ON vr.ver_id = bg.ver_id
            INNER JOIN ctt_projects AS pj ON pj.pjt_id = vr.pjt_id
            INNER JOIN ctt_projects_type AS pt ON pt.pjttp_id = pj.pjttp_id
            INNER JOIN ctt_location AS lc ON lc.loc_id = pj.loc_id
            INNER JOIN ctt_products AS pd ON pd.prd_id = bg.prd_id
            INNER JOIN ctt_customers_owner AS co ON co.cuo_id = pj.cuo_id
            INNER JOIN ctt_customers AS cu ON cu.cus_id = co.cus_id
            WHERE bg.ver_id =  $verId;";
    return $this->db->query($qry);
}


// Promueve proyecto
    public function PromoteProject($params)
    {
        /* Actualiza el estado en 2 convirtiendolo en presupuesto  */
        $pjtId                  = $this->db->real_escape_string($params['pjtId']);
        $qry = "UPDATE ctt_projects SET pjt_status = '2' WHERE pjt_id = $pjtId;";
        $this->db->query($qry);

        return $pjtId;

    }

// Actualiza las fechas del proyecto
    public function UpdatePeriodProject($params)
    {
        $pjtId                  = $this->db->real_escape_string($params['pjtId']);
        $pjtDateStart           = $this->db->real_escape_string($params['pjtDateStart']);
        $pjtDateEnd             = $this->db->real_escape_string($params['pjtDateEnd']);
        $qry = "UPDATE ctt_projects 
                   SET pjt_date_start   = '$pjtDateStart', 
                       pjt_date_end     = '$pjtDateEnd' 
                 WHERE pjt_id = $pjtId;";
        $this->db->query($qry);

        return $pjtId;

    }





// Promueve la version de proyecto
    public function PromoteVersion($params)
    {
        $pjtId         = $this->db->real_escape_string($params['pjtId']);
        $verId         = $this->db->real_escape_string($params['verId']);

        $qry = "UPDATE ctt_version SET ver_status = 'P' WHERE ver_id = $verId;";
        $this->db->query($qry);

        return $pjtId.'|'. $verId;
    }

// Promueve la version de proyecto
    public function SaveProjectContent($params){

        $verId        = $this->db->real_escape_string($params['verId']);

        $qry = "INSERT INTO ctt_projects_content (
                    pjtcn_prod_sku, pjtcn_prod_name, pjtcn_prod_price, pjtcn_prod_level, pjtcn_quantity, 
                    pjtcn_days_base, pjtcn_discount_base, pjtcn_days_trip, pjtcn_discount_trip, 
                    pjtcn_days_test, pjtcn_discount_test, pjtcn_insured,  ver_id, prd_id, pjt_id
                )
                SELECT 
                    bg.bdg_prod_sku, ucase(bg.bdg_prod_name), bg.bdg_prod_price, bg.bdg_prod_level, bg.bdg_quantity,  
                    bg.bdg_days_base, bg.bdg_discount_base, bg.bdg_days_trip, bg.bdg_discount_trip,
                    bg.bdg_days_test, bg.bdg_discount_test, bg.bdg_insured, bg.ver_id, bg.prd_id, vr.pjt_id 
                FROM ctt_budget AS bg
                INNER JOIN ctt_version AS vr ON vr.ver_id = bg.ver_id
                WHERE bg.ver_id = $verId;";
        return $this->db->query($qry);
    }

    
// Obtiene la version de proyecto
    public function GetProjectContent($params)
    {
        $pjtId        = $this->db->real_escape_string($params['pjtId']);
        $verId        = $this->db->real_escape_string($params['verId']);

        $qry = "SELECT * 
                FROM ctt_projects_content AS pc
                INNER JOIN ctt_version AS vr ON vr.ver_id = pc.ver_id
                INNER JOIN ctt_projects AS pj ON pj.pjt_id = vr.pjt_id
                INNER JOIN ctt_products AS pd ON pd.prd_id = pc.prd_id
                WHERE pc.ver_id = $verId;";
        return $this->db->query($qry);
    }

    public function SettingSeries($params)
    {
        $prodId   = $this->db->real_escape_string($params['prodId']);
        $dtinic   = $this->db->real_escape_string($params['dtinic']);
        $dtfinl   = $this->db->real_escape_string($params['dtfinl']);
        $pjetId   = $this->db->real_escape_string($params['pjetId']);
        $detlId   = $this->db->real_escape_string($params['detlId']);


        $qry = "SELECT ser_id, ser_sku FROM ctt_series WHERE prd_id = $prodId 
                AND pjtdt_id = 0
                ORDER BY ser_reserve_count asc LIMIT 1;";
        $result =  $this->db->query($qry);
        
        $series = $result->fetch_object();
        if ($series != null){
            $serie  = $series->ser_id; 
            $sersku  = $series->ser_sku; 

            $qry1 = "UPDATE ctt_series 
                        SET 
                            ser_situation = 'EA',
                            ser_stage = 'R',
                            ser_reserve_count = ser_reserve_count + 1
                            WHERE ser_id = $serie;";
            $this->db->query($qry1);

        }else {
            $serie  = null; 
            $sersku  = 'Pendiente' ;
        }

        
        $qry2 = "INSERT INTO ctt_projects_detail (
                    pjtdt_belongs, pjtdt_prod_sku, ser_id, prd_id, pjtcn_id
                ) VALUES (
                    '$detlId', '$sersku', '$serie',  '$prodId',  '$pjetId'
                );        ";

        $this->db->query($qry2);
        $pjtdtId = $this->db->insert_id;

        if ( $serie != null){
            $qry3 = "UPDATE ctt_series 
                        SET 
                            pjtdt_id = '$pjtdtId'
                        WHERE ser_id = $serie;";
            $this->db->query($qry3);
        }

        $qry4 = "INSERT INTO ctt_projects_periods 
                    (pjtpd_day_start, pjtpd_day_end, pjtdt_id, pjtdt_belongs ) 
                VALUES 
                    ('$dtinic', '$dtfinl', '$pjtdtId', '$detlId')";


        $this->db->query($qry4);

        return  $pjtdtId;
        
    }

    public function GetAccesories($params)
    {
        $prodId        = $this->db->real_escape_string($params);
        $qry = "SELECT pd.* FROM ctt_products AS pd
                INNER JOIN ctt_accesories AS ac ON ac.prd_id = pd.prd_id 
                WHERE ac.acr_parent = $prodId;";
        return $this->db->query($qry);

    }
    public function GetProducts($params)
    {
        $prodId        = $this->db->real_escape_string($params);
        $qry = "SELECT pd.* 
                FROM ctt_products_packages AS pk 
                INNER JOIN ctt_products AS pd ON pd.prd_id = pk.prd_id
                WHERE  pk.prd_parent = $prodId;";
        return $this->db->query($qry);

    }

}
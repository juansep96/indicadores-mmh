<?php

require_once "../PDO.php";

$fechaIni = $_GET['desde']." 00:00:00";
$fechaFin = $_GET['hasta']." 23:59:59";
if($_GET['modulo'] == "1"){
    $_GET['modulo'] = "";
}
$modulo = "%".$_GET['modulo']."%";

$conn=$conexion;

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchArray = array();

## Search
$searchQuery = " ";
if($searchValue != ''){
    $searchQuery = " AND (id_venta LIKE :name) ";
    $searchArray = array(
        'name'=>"%$searchValue%"
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM auditorias WHERE tabla_auditoria LIKE '$modulo' AND fechaHora_auditoria BETWEEN '$fechaIni' AND '$fechaFin'");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM auditorias WHERE tabla_auditoria LIKE '$modulo' AND fechaHora_auditoria BETWEEN '$fechaIni' AND '$fechaFin'");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM auditorias left join usuarios on idUsuario_auditoria = id_usuario WHERE tabla_auditoria LIKE '$modulo' AND fechaHora_auditoria BETWEEN '$fechaIni' AND '$fechaFin' ORDER BY fechaHora_auditoria DESC LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach($empRecords as $row){
    $fecha = date('d/m/Y',strtotime($row['fechaHora_auditoria']));
    $hora = date('H:i',strtotime($row['fechaHora_auditoria']));
    $usuario = $row['apellido_usuario'] . ' ' . $row['nombre_usuario'];
   $data[] = array(
      "fecha_auditoria"=>$fecha,
      "hora_auditoria"=>$hora,
      "detalle_auditoria"=>strtoupper($row['detalle_auditoria']),
      "modulo_auditoria"=>strtoupper($row['tabla_auditoria']),
      "usuario_auditoria"=>strtoupper($usuario),
      "ippublica_auditoria"=>strtoupper($row['ippublica_auditoria']),
   );
}

## Response
$response = array(
   "draw" => intval($draw),
   "iTotalRecords" => $totalRecords,
   "iTotalDisplayRecords" => $totalRecordwithFilter,
   "aaData" => $data
);


echo json_encode($response);



?>
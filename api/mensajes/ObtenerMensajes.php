<?php

require_once "../PDO.php";

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
    $searchQuery = " AND (nombre_secretaria LIKE :name ) ";
    $searchArray = array(
        'name'=>"%$searchValue%"
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM mensajes WHERE estado_mensaje=1");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM mensajes WHERE 1 ".$searchQuery ." AND estado_mensaje=1");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM mensajes left join usuarios on quien_mensaje = id_usuario WHERE 1 ".$searchQuery." AND estado_mensaje=1 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
  $idMensaje = $row['id_mensaje'];
  $acciones = '<div class="d-flex align-items-center gap-3 fs-6">';
  if($_SESSION['nivel_usuario']=='ADMINISTRADOR'){
    $acciones = $acciones. '<a href="javascript:;" onclick="EditarMensaje('.$idMensaje.')" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Editar"><i class="bi bi-pencil-fill"></i></a>';
    $acciones = $acciones . '<a href="javascript:;" onclick="EliminarMensaje('.$idMensaje.')" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Eliminar"><i class="bi bi-trash-fill"></i></a>';
  }
  $quien = $row['apellido_usuario'] . " " . $row['nombre_usuario'];
  $cuando = date('d/m/Y H:i',strtotime($row['cuando_mensaje']));
  $acciones = $acciones.'</div>';
   $data[] = array(
      "titulo_mensaje"=>$row['titulo_mensaje'],
      "detalle_mensaje"=>strtoupper($row['detalle_mensaje']),
      "cuando_mensaje"=>$cuando,
      "quien_mensaje"=>strtoupper($quien),
      "acciones_mensaje"=>$acciones,
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
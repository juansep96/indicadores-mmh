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
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM secretarias WHERE estado_secretaria=1");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM secretarias WHERE 1 ".$searchQuery ." AND estado_secretaria=1");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM secretarias left join usuarios on quien_secretaria = id_usuario WHERE 1 ".$searchQuery." AND estado_secretaria=1 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
  $idSecretaria = $row['id_secretaria'];
  $acciones = '<div class="d-flex align-items-center gap-3 fs-6">';
  if($_SESSION['nivel_usuario']=='ADMINISTRADOR'){
    $acciones = $acciones. '<a href="javascript:;" onclick="EditarSecretaria('.$idSecretaria.')" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Editar"><i class="bi bi-pencil-fill"></i></a>';
    $acciones = $acciones . '<a href="javascript:;" onclick="EliminarSecretaria('.$idSecretaria.')" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Eliminar"><i class="bi bi-trash-fill"></i></a>';
  }
  $quien = $row['apellido_usuario'] . " " . $row['nombre_usuario'];
  $cuando = date('d/m/Y H:i',strtotime($row['cuando_secretaria']));
  $acciones = $acciones.'</div>';
   $data[] = array(
      "id_secretaria"=>$idSecretaria,
      "nombre_secretaria"=>strtoupper($row['nombre_secretaria']),
      "icono_secretaria"=>$row['icono_secretaria'],
      "cuando_secretaria"=>$cuando,
      "quien_secretaria"=>strtoupper($quien),
      "acciones_secretaria"=>$acciones,
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
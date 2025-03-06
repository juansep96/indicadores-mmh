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
    $searchQuery = " AND (dni_usuario LIKE :name OR apellido_usuario LIKE :name OR nombre_usuario LIKE :name) ";
    $searchArray = array(
        'name'=>"%$searchValue%"
   );
}

## Total number of records without filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM usuarios WHERE estado_usuario=1");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM usuarios WHERE 1 ".$searchQuery ." AND estado_usuario=1");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $conn->prepare("SELECT * FROM usuarios left join secretarias ON id_secretaria = idSecretaria_usuario left join areas ON id_area = idArea_usuario WHERE 1 ".$searchQuery." AND estado_usuario=1 ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
  $idUsuario = $row['id_usuario'];
  $acciones = '<div class="d-flex align-items-center gap-3 fs-6">';

  if($_SESSION['nivel_usuario'] == "ADMINISTRADOR"){
   $acciones = $acciones. '<a href="javascript:;" onclick="AbrirUsuario('.$idUsuario.')" class="text-info" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Editar"><i class="bi bi-eye"></i></a>';
   $acciones = $acciones. '<a href="javascript:;" onclick="RestaurarPassword('.$idUsuario.')" class="text-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Editar"><i class="bi bi-key"></i></a>';
   $acciones = $acciones. '<a href="javascript:;" onclick="EditarUsuario('.$idUsuario.')" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Edit info" aria-label="Editar"><i class="bi bi-pencil-fill"></i></a>';
   $acciones = $acciones . '<a href="javascript:;" onclick="EliminarUsuario('.$idUsuario.')" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-label="Eliminar"><i class="bi bi-trash-fill"></i></a>';
  }

  switch ($row['nivel_usuario']) {
   case 'ADMINISTRADOR':
      $secretaria = "-";
      $area = "-";
    
   break;
   case 'FUNCIONARIO':
      $secretaria = "-";
      $area = "-";
   break;
   case 'NIVEL 1':
      $secretaria = strtoupper($row['nombre_secretaria']);
      $area = strtoupper($row['nombre_area']);
   break;
   case 'NIVEL 2':
      $secretaria = strtoupper($row['nombre_secretaria']);
      $area = strtoupper($row['nombre_area']);
   break;
   case 'NIVEL 3':
      $secretaria = strtoupper($row['nombre_secretaria']);
      $area = strtoupper($row['nombre_area']);
   break;
}

  
  $acciones = $acciones.'</div>';
   $data[] = array(
      "dni_usuario"=>strtoupper($row['dni_usuario']),
      "apellido_usuario"=>strtoupper($row['apellido_usuario']),
      "nombre_usuario"=>strtoupper($row['nombre_usuario']),
      "nivel_usuario"=>strtoupper($row['nivel_usuario']),
      "nombre_secretaria"=>$secretaria,
      "nombre_area"=>$area,
      "acciones_usuario"=>$acciones,
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
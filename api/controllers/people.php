<?php
include_once __DIR__ .'/../models/people.php';
header('Content-Type: application/json');

if ($_REQUEST['action'] === 'index') {
  echo json_encode(People::all());

}
 ?>

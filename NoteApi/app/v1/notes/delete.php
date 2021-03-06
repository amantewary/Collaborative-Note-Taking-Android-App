<?php 
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  include_once '../../includes/ConnectDb.php';
  include_once '../../includes/Notes.php';
  include_once '../../includes/HttpLogger.php';
  $database = new ConnectDb();
  $db = $database->connect();
  $note = new Notes($db);
  $data = json_decode(file_get_contents("php://input"));
  $note->id = $data->id;
  error_log('Request to Access To Delete Notes ID: ' . $note->id);
  if($note->delete()) {
    echo json_encode(
      array('message' => 'Note Deleted')
    );
      $database->disconnect($db);
  } else {
    echo json_encode(
      array('message' => 'Note Not Deleted')
    );
      $database->disconnect($db);
  }


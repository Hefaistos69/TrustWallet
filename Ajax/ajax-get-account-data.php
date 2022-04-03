<?php
session_start();
if(isset($_POST['currency']) && isset($_POST['accountId'])){
  
  
  
  
  
  echo json_encode(array('success' => '1'));
}
else
  echo json_encode(array('success' => '0'));
die();
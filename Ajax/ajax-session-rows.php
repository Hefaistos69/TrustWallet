<?php
session_start();
if(isset($_POST['rows']))
  $_SESSION['numRows'] = intval($_POST['rows']);
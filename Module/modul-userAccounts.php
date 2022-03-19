<?php

$query = "SELECT * FROM accounts WHERE usersId = ?;";
$values = array();
$values[] = $_SESSION['userId'];
$userAccounts = QueryDatabase($conn, $query, $values);

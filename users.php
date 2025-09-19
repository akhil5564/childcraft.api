<?php
include_once "Db.php";

$db = new Db();
$conn = $db->getConnection();

echo "Users API working!";

<?php
include_once "Db.php";

$db = new Db();
$conn = $db->getConnection();

echo "Books API working!";


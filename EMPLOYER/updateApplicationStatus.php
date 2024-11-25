<?php
require_once '../DATABASE/dbConnection.php';

$database = new Database();
$db = $database->getConnect();

$applicationId = htmlspecialchars($_POST['application_id']);
$status = htmlspecialchars($_POST['status']);

$query = "UPDATE applications SET status = :status WHERE application_id = :application_id";

$stmt = $db->prepare($query);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':application_id', $applicationId);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

<?php
session_start();
require_once("usermanager.inc.php");

$database = new database();

if(isset($_POST['new-description-content']))
{
    $description = htmlspecialchars($_POST['new-description-content']);
    $database->update("UPDATE user SET description='{$description}' WHERE pseudo='{$_SESSION['pseudo']}'");
}

if(isset($_POST['new-paypal-content']))
{
    $paypal = htmlspecialchars($_POST['new-paypal-content']);
    $database->update("UPDATE user SET paypal='{$paypal}' WHERE pseudo='{$_SESSION['pseudo']}'");
}

header("Location:account.php");
?>
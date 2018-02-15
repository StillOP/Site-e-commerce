<?php

require 'bootstrap.php';
require 'download.php';
require 'database.inc.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\Payee;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

if (isset($_GET['success']) && $_GET['success'] == 'true') 
{
    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $apiContext);
    
    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);
    
    $transaction = new Transaction();
    $amount = new Amount();
    $amount->setCurrency('EUR');
    $amount->setTotal($_GET['price']);
   
    $transaction->setAmount($amount);
    
    $execution->addTransaction($transaction);
    
    $payouts = new \PayPal\Api\Payout();

    $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
    $senderBatchHeader->setSenderBatchId(uniqid())
        ->setEmailSubject("You have a payment from Kreativ.com");
    
    $database = new database();
    $payee= $database->select("SELECT paypal FROM user WHERE pseudo='{$_GET['owner']}'");
    $mail= $database->select("SELECT mail FROM user WHERE pseudo='{$_GET['owner']}'");
    
    $payeeAmount = $_GET['price'] * 0.7;
    $senderItem = new \PayPal\Api\PayoutItem();
    $senderItem->setRecipientType('Email')
        ->setNote('Thanks you.')
        ->setReceiver($payee[1]->paypal)
        ->setSenderItemId($_GET['article'] . uniqid())
        ->setAmount(new \PayPal\Api\Currency('{
                        "value":'."{$payeeAmount}".',
                        "currency":"EUR"
                    }'));

    $payouts->setSenderBatchHeader($senderBatchHeader)
        ->addItem($senderItem);
    // For Sample Purposes Only.
    //$request = clone $payouts;
    // ### Create Payout
    
    try {
        $result = $payment->execute($execution, $apiContext);
        $output = $payouts->create(null, $apiContext);
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printResult("Executed Payment", "Payment", $payment->getId(), $execution, $result);
        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
            //ResultPrinter::printError("Created Single Synchronous Payout", "Payout", null, $request, $ex);
            exit(1);
        }
    } catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
        exit(1);
    }
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    //ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);
    //ResultPrinter::printResult("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);
    
    if(isset($_GET['address']))
    {
        $address = rawurldecode($_GET['address']);
        mail($mail[1]->mail, "You have a new order", "Hi, you have a new order for: ".$_GET['article'].".\n\r Shipping address: ".$address.".", "From:Kreativ@noreply.com");
    }
    
    echo "<div style=\"margin:100px;\"><h1 style=\"color:#28a745;\">Thank for your purchase!</h1>
        <a href=\"index.php\">Back to index!</a></div>";
    
    echo "<form method=\"post\" action=\"get.article.php\" id=\"payment-form\">
            <input name=\"type\" value=\"{$_GET['type']}\" hidden />
            <input name=\"title\" value=\"{$_GET['article']}\" hidden />
            <input name=\"id\" value=\"{$_GET['id']}\" hidden />
            <input name=\"mail\" value=\"{$mail[1]->mail}\" hidden />
        </form>
        <script> document.querySelector('#payment-form').submit(); </script>";
    
    return array($payment, $payouts);
} 
else 
{
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    //ResultPrinter::printResult("User Cancelled the Approval", null);
    echo "<script> document.location=\"index.php\"</script>";
    exit;
}
<?php

require 'bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;


if(isset($_POST['support-price']))
{
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $price = floatval($_POST['price']) + floatval($_POST['support-price']);
        
    $item = new Item();
    $item->setName($_POST['name'])
        ->setCurrency('EUR')
        ->setQuantity(1)
        ->setSku($_POST['id']) // Similar to `item_number` in Classic API
        ->setPrice($price);

    $itemList = new ItemList();
    $itemList->setItems(array($item));

    $amount = new Amount();
    $amount->setCurrency("EUR")
        ->setTotal($price);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Payment for {$_POST['name']} from Kreativ.com")
        ->setInvoiceNumber(uniqid());

    $baseUrl = getBaseUrl();
    $redirectUrls = new RedirectUrls();
    $article = str_replace(' ', '_', $_POST['name']);
    $get_address;
    if(isset($_POST['address'])) 
    {
        $address = rawurlencode($_POST['address']);
        $address = "&address=".$address;
    }
    else { $get_address = ""; }
    
    $redirectUrls->setReturnUrl("$baseUrl/execute.payment.php?success=true&owner={$_POST['owner']}&price={$price}&article={$article}&type={$_POST['type']}&id={$_POST['id']}{$address}")
        ->setCancelUrl("$baseUrl/execute.payment.php?success=false");

    $payment = new Payment();
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));
    // For Sample Purposes Only.
    //$request = clone $payment;

    try {
        $payment->create($apiContext);
    } catch (Exception $ex) {
        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        //ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
        exit(1);
    }

    $approvalUrl = $payment->getApprovalLink();
    header("Location:{$approvalUrl}");
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
    //ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);
    return $payment;
}

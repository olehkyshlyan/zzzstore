<?php
session_start();

include_once "wallet.php";
include_once "transport.php";
include_once "countcart.php";

if(isset($_POST['val']))
{
  $vl = mb_substr((string)$_POST['val'],0,10,'UTF-8');
  unset($_POST['val']);
  $vl = preg_replace('/[^\p{N}\p{L}\p{Zs}\-\_]+/u','',$vl);
  if(array_key_exists($vl,$trans->typelist))
  {
    $trtype = $vl;
    $trvalue = $trans->typelist[$vl];
  }
  else
  {
    unset($_SESSION['cart']['trans']);
  }
}

if(isset($trtype))
{
  $cart = array();
  
  if($_SESSION['wallet'] < $trvalue)
  {
    $cart['notallowtobuy'] = true;
  }
  else
  {
    $allowtobuy = true;
  }
  
  if(isset($allowtobuy))
  {
    $prevTransVal = $_SESSION['cart']['trans']['value'];
    $_SESSION['cart']['trans']['type'] = $trtype;
    $_SESSION['cart']['trans']['value'] = $trvalue;
    $cartProd = $countCart->getProdCount();
    $_SESSION['cart']['tamount'] = $cartProd['amount'];
    $_SESSION['wallet'] = $_SESSION['wallet'] + $prevTransVal;
    $tamleft = $_SESSION['wallet'] - $trvalue;
    $tamleft = round($tamleft,2);
    $_SESSION['wallet'] = $tamleft;
    
    $cart['trans']['tamount'] = $cartProd['amount'];
    $cart['trans']['tamleft'] = $tamleft;
  }
  
  $jecart = json_encode($cart);
  print($jecart);
}

?>
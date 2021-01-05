<?php
session_start();

include_once "wallet.php";
include_once "countcart.php";

if(isset($_POST['name']))
{
  $nm = mb_substr((string)$_POST['name'],0,20,'UTF-8');
  unset($_POST['name']);
  $nm = preg_replace('/[^\p{N}\p{L}\p{Zs}\-\_]+/u','',$nm);
  $name = $nm;
}

if(isset($name))
{
  try
  {
    include_once "db.php";
  }
  catch(Exception $e)
  {
    print($e->getMessage()."<br />");
  }
}

if(isset($db))
{
  try
  {
    $qresult = $db->query("SELECT * FROM goods WHERE name='$name' ORDER BY id Desc");
    $qrow = $qresult->fetchAll(PDO::FETCH_ASSOC);
    
    $price = $qrow[0]['price'];
    $cart = array();
    $prod = array();
    $prod["$name"] = array();
    
    if($_SESSION['wallet'] < $price)
    {
      $cart['notallowtobuy'] = true;
    }
    else
    {
      $allowtobuy = true;
    }
    
    if(isset($allowtobuy)){
    if(isset($_SESSION['cart']['prod']["$name"]))
    {
      $num = $_SESSION['cart']['prod']["$name"]['num'];
      $nnum = $num + 1;
      $_SESSION['cart']['prod']["$name"]['num'] = $nnum;
      
      $cartProd = $countCart->getProdCount();
      $_SESSION['cart']['tamount'] = $cartProd['amount'];
      $tamleft = $_SESSION['wallet'] - $price;
      $tamleft = round($tamleft,2);
      $_SESSION['wallet'] = $tamleft;
      $_SESSION['cart']['totnum'] = $cartProd['number'];
      
      $cart['newnum']['name'] = $name;
      $cart['newnum']['nnum'] = $nnum;
      $cart['newnum']['tnum'] = $cartProd['number'];
      $cart['newnum']['tamount'] = $cartProd['amount'];
      $cart['newnum']['tamleft'] = $tamleft;
    }
    else
    {
      $qrow[0]['num'] = 1;
      $_SESSION['cart']['prod']["$name"] = $qrow[0];
      
      $cartProd = $countCart->getProdCount();
      $_SESSION['cart']['tamount'] = $cartProd['amount'];
      $tamleft = $_SESSION['wallet'] - $price;
      $tamleft = round($tamleft,2);
      $_SESSION['wallet'] = $tamleft;
      $_SESSION['cart']['totnum'] = $cartProd['number'];
      
      $cart['newline'] = $qrow[0];
      $cart['newline']['tnum'] = $cartProd['number'];
      $cart['newline']['tamount'] = $cartProd['amount'];
      $cart['newline']['tamleft'] = $tamleft;
    }
    }
    $jecart = json_encode($cart);
    print($jecart);
    
  }
  catch(Exception $e)
  {
    print($e->getMessage()."<br />");
  }
}

?>
<?php
session_start();

include_once "wallet.php";
include_once "transport.php";
include_once "ratinghighlight.php";

try
{
  include_once "db.php";
}
catch(Exception $e)
{
  print($e->getMessage()."<br />");
}

if(isset($db))
{
  try
  {
    $qresult = $db->query("SELECT * FROM goods ORDER BY id Desc");
    $qrow = $qresult->fetchAll(PDO::FETCH_ASSOC);
  }
  catch(Exception $e)
  {
    print($e->getMessage()."<br />");
  }
}

// if $_SESSION['wallet'] does NOT exist
$wallet->setWallet();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" href="css/index.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='js/functions.js'></script>
  <script type="text/javascript">
    
  </script>
</head>

<body>

<div id="mcont">

<div id="firstBlock">
  <div id="cart">
    <div id="wcart" title="Click to open the cart" onclick="opencart();">C a r t</div>
    <div id="cproducts"><span>Products: </span><span id="cartProdNum"><? if(isset($_SESSION['cart']['totnum'])){ print($_SESSION['cart']['totnum']); }else{ print('0'); } ?></span></div>
    <div id="camount"><span>Total amount: </span><span id="cartTotAm"><? if(isset($_SESSION['cart']['tamount'])){ print($_SESSION['cart']['tamount']); }else{ print('0'); } ?></span><span>$</span></div>
    <div id="cwallet"><span>Wallet: </span><span id="cartWalRest"><? if(isset($_SESSION['wallet'])){ print($_SESSION['wallet']); }else{ print('100'); } ?></span><span>$</span></div>
  </div>
</div>

<div id="bcart" style="<? if(isset($_SESSION['oc'])){ print('display: block'); } ?>">
  <div id="bcFirstBlock">
    <div id="bcTitle">C A R T</div>
    <div id="bcCloseBt" onclick="closecart();"><img src="images/close.png" /></div>
  </div>
  <div id="bcSecondBlock">
    <? if(isset($_SESSION['cart']['prod']) && count($_SESSION['cart']['prod']) > 0){ foreach($_SESSION['cart']['prod'] as $row){ ?>
    <div id="bc<? print($row['name']); ?>" class="bcGoods">
      <div id="" class="bcwimg">
        <img id="bcimg" src="images/<? print($row['image']); ?>" />
      </div>
      <div class="bcPrDetails">
        <div class="bcProdName"><? $bcname = $row['name']; $bcname = mb_convert_case($bcname,MB_CASE_TITLE,"UTF-8"); print($bcname); ?></div>
        <div class="bcDetPlus" onclick="addToCart('<? print($row['name']); ?>');">+</div>
        <div id="bcnum<? print($row['name']); ?>" class="bcDetNum"><? print($row['num']); ?></div>
        <div class="bcDetMinus" onclick="removeFromCart.remove('<? print($row['name']); ?>');">-</div>
      </div>
    </div>
    <? }}else{ ?>
    <div id="bcemptycart">Empty cart</div>
    <? } ?>
  </div>
  <div id="transport">
    <select id="transSelect" name="" onchange="chtrans.choose(this);">
      <option id="transchoose" value="selship" <? if($trans->chtype == 'selship'){ print('selected="selected"'); } ?>>Select shipment method</option>
      <option id="transpickup" value="pickup" <? if($trans->chtype == 'pickup'){ print('selected="selected"'); } ?>>Pick up | 0$</option>
      <option id="transups" value="ups" <? if($trans->chtype == 'ups'){ print('selected="selected"'); } ?>>UPS | 5$</option>
    </select>
  </div>
  <div id="wpay">
    <div id="paybt" onclick="sendorder();">P A Y</div>
  </div>
</div>

<div id="secondBlock">
  <? if(isset($qrow) && $qrow != false){ foreach($qrow as $row){ ?>
  <div id="<? $name = $row['name']; print($name); ?>" class="goods">
    <div class="name"><? $csname = mb_convert_case($name,MB_CASE_TITLE,"UTF-8"); print($csname); ?></div>
    <div id="" class="wimg">
      <img id="" class="dimg" src="images/<? if($row['image'] != ''){ print($row['image']); } ?>" />
    </div>
    <div id="" class="gchars">
      <div id="" class="price"><span>Price: </span><span><? print($row['price']); ?>$</span></div>
      <div id="" class="cart" onclick="addToCart('<? print($row['name']); ?>');">Add to cart</div>
    </div>
    <div class="rating">
      <div class="rate">Rate: </div>
      <div id="rt<? print($name); ?>1" class="rtstar star1" title="One star" onclick="prodRating.rate('<? print($name); ?>','1');" style="<? if(isset($rtgHlgt->stRating[$name.'1'])){ print('opacity:1;'); } ?>"><img src="images/star.png" /></div>
      <div id="rt<? print($name); ?>2" class="rtstar star2" title="Two stars" onclick="prodRating.rate('<? print($name); ?>','2');" style="<? if(isset($rtgHlgt->stRating[$name.'2'])){ print('opacity:1;'); } ?>"><img src="images/star.png" /></div>
      <div id="rt<? print($name); ?>3" class="rtstar star3" title="Three stars" onclick="prodRating.rate('<? print($name); ?>','3');" style="<? if(isset($rtgHlgt->stRating[$name.'3'])){ print('opacity:1;'); } ?>"><img src="images/star.png" /></div>
      <div id="rt<? print($name); ?>4" class="rtstar star4" title="Four stars" onclick="prodRating.rate('<? print($name); ?>','4');" style="<? if(isset($rtgHlgt->stRating[$name.'4'])){ print('opacity:1;'); } ?>"><img src="images/star.png" /></div>
      <div id="rt<? print($name); ?>5" class="rtstar star5" title="Five stars" onclick="prodRating.rate('<? print($name); ?>','5');" style="<? if(isset($rtgHlgt->stRating[$name.'5'])){ print('opacity:1;'); } ?>"><img src="images/star.png" /></div>
      <div class="rtresult"><span id="rtg<? print($row['name']); ?>"><? $avrtg = $row['rating']/$row['nus']; print(intval($avrtg)); ?></span><span>/5</span></div>
    </div>
  </div>
  <? }} ?>
</div>

</div>

</body>

</html>

(function()
{
  var arr = [];
  addToCart = function(name)
  {
    if(arr.indexOf(name) == -1)
    {
      arr.push(name);
      jQuery.post("addtocart.php","name="+name,function(data,textStatus)
      {
        data = JSON.parse(data);
        if(data.newline)
        {
          var nm = data.newline.name;
          var nname = nm.charAt(0).toUpperCase() + nm.slice(1);
          var bcSecBl = document.getElementById('bcSecondBlock');
          var bcEmpCart = document.getElementById('bcemptycart');
          var newline = '<div id="bc'+nm+'" class="bcGoods">'+
          '<div id="" class="bcwimg">'+
          '<img id="bcimg" src="images/'+data.newline.image+'" />'+
          '</div>'+
          '<div class="bcPrDetails">'+
          '<div class="bcProdName">'+nname+'</div>'+
          '<div class="bcDetPlus" onclick="addToCart(\''+nm+'\');">+</div>'+
          '<div id="bcnum'+nm+'" class="bcDetNum">'+data.newline.num+'</div>'+
          '<div class="bcDetMinus" onclick="removeFromCart.remove(\''+nm+'\');">-</div>'+
          '</div>'+
          '</div>';
          if(bcEmpCart != null)
          {
            bcSecBl.removeChild(bcEmpCart);
          }
          bcSecBl.insertAdjacentHTML('beforeEnd',newline);
          
          var tnum = data.newline.tnum;
          document.getElementById('cartProdNum').innerHTML = tnum;
          var tamount = data.newline.tamount;
          document.getElementById('cartTotAm').innerHTML = tamount;
          var tamleft = data.newline.tamleft;
          document.getElementById('cartWalRest').innerHTML = tamleft;
        }
        
        if(data.newnum)
        {
          var numname = data.newnum.name;
          var bcnum = document.getElementById('bcnum'+numname);
          bcnum.innerHTML = data.newnum.nnum;
          
          var tnum = data.newnum.tnum;
          document.getElementById('cartProdNum').innerHTML = tnum;
          var tamount = data.newnum.tamount;
          document.getElementById('cartTotAm').innerHTML = tamount;
          var tamleft = data.newnum.tamleft;
          document.getElementById('cartWalRest').innerHTML = tamleft;
        }
        
        if(data.notallowtobuy)
        {
          var elnab = document.getElementById('notallowtobuy');
          if(elnab == null)
          {
            var mcont = document.getElementById('mcont');
            var nem = '<div id="notallowtobuy">NOT ENOUGH MONEY TO BUY</div>';
            mcont.insertAdjacentHTML('afterBegin',nem);
            jQuery('#notallowtobuy').slideDown({duration:500,complete:function()
            {
              jQuery('#notallowtobuy').delay(5000).slideUp({duration:500,complete:function()
              {
                var netobuy = document.getElementById('notallowtobuy');
                mcont.removeChild(netobuy);
              }});
            }});
          }
        }
        
        var ind = arr.indexOf(name);
        if(ind != -1)
        {
          arr.splice(ind,1);
        }
      });
    }
  }
})();


var chtrans = new function()
{
  this.choose = function(th)
  {
    var val = th.value;
    jQuery.post("chtrans.php","val="+val,function(data,textStatus)
    {
      data = JSON.parse(data);
      if(data.trans)
      {
        var tamount = data.trans.tamount;
        document.getElementById('cartTotAm').innerHTML = tamount;
        var tamleft = data.trans.tamleft;
        document.getElementById('cartWalRest').innerHTML = tamleft;
      }
      
      if(data.notallowtobuy)
      {
        var elnab = document.getElementById('notallowtobuy');
        if(elnab == null)
        {
          var mcont = document.getElementById('mcont');
          var nem = '<div id="notallowtobuy">NOT ENOUGH MONEY TO BUY</div>';
          mcont.insertAdjacentHTML('afterBegin',nem);
          jQuery('#notallowtobuy').slideDown({duration:500,complete:function()
          {
            jQuery('#notallowtobuy').delay(5000).slideUp({duration:500,complete:function()
            {
              var netobuy = document.getElementById('notallowtobuy');
              mcont.removeChild(netobuy);
            }});
          }});
        }
        var trSel = document.getElementById('transSelect');
        jQuery(trSel).delay(1000).show({duration:1000,progress:function()
        {
          trSel.innerHTML = transSelect.options;
        }});
      }
    });
  }
}


var removeFromCart = new function()
{
  var arr = [];
  this.remove = function(name)
  {
    if(arr.indexOf(name) == -1)
    {
      arr.push(name);
      jQuery.post("remfromcart.php","name="+name,function(data,textStatus)
      {
        data = JSON.parse(data);
        if(data.minusnum)
        {
          var numname = data.minusnum.name;
          var bcnum = document.getElementById('bcnum'+numname);
          bcnum.innerHTML = data.minusnum.nnum;
          
          var tnum = data.minusnum.tnum;
          document.getElementById('cartProdNum').innerHTML = tnum;
          var tamount = data.minusnum.tamount;
          document.getElementById('cartTotAm').innerHTML = tamount;
          var tamleft = data.minusnum.tamleft;
          document.getElementById('cartWalRest').innerHTML = tamleft;
        }
        
        if(data.removeline)
        {
          var rname = data.removeline.name;
          var rchild = document.getElementById('bc'+rname);
          var bcSecBl = document.getElementById('bcSecondBlock');
          bcSecBl.removeChild(rchild);
          
          var tnum = data.removeline.tnum;
          document.getElementById('cartProdNum').innerHTML = tnum;
          
          if(tnum == 0)
          {
            var bcEmpCart = '<div id="bcemptycart">Empty cart</div>';
            bcSecBl.insertAdjacentHTML('beforeEnd',bcEmpCart);
            document.getElementById('transSelect').innerHTML = transSelect.options;
          }
          
          var tamount = data.removeline.tamount;
          document.getElementById('cartTotAm').innerHTML = tamount;
          
          var tamleft = data.removeline.tamleft;
          document.getElementById('cartWalRest').innerHTML = tamleft;
        }
        
        var ind = arr.indexOf(name);
        if(ind != -1)
        {
          arr.splice(ind,1);
        }
      });
    }
  }
}

function opencart()
{
  jQuery('#bcart').slideDown({duration:500});
  jQuery.post("oc.php","open=yes");
}

function closecart()
{
  jQuery('#bcart').slideUp({duration:500});
  jQuery.post("oc.php","close=yes");
}

function sendorder()
{
  var bcSecondBlock = document.getElementById('bcSecondBlock');
  var childs = bcSecondBlock.childNodes;
  var lchilds = childs.length;
  var goodsCount = 0;
  for(var i=0;i<lchilds;i++)
  {
    if(childs[i].className == 'bcGoods')
    {
      goodsCount++;
    }
  }
  var mcont = document.getElementById('mcont');
  
  function slideDownAndUp(p,ch)
  {
    jQuery(ch).slideDown({duration:500,complete:function()
    {
      jQuery(ch).delay(5000);
      jQuery(ch).slideUp({duration:500,complete:function()
      {
        p.removeChild(ch);
      }});
    }});
  }
  
  if(goodsCount == 0)
  {
    var ecart = document.getElementById('emptycart');
    if(ecart == null)
    {
      var emcart = '<div id="emptycart">Your cart is empty</div>';
      mcont.insertAdjacentHTML('afterBegin',emcart);
      var emptycart = document.getElementById('emptycart');
      slideDownAndUp(mcont,emptycart);
    }
  }
  
  if(goodsCount > 0)
  {
    var trSel = document.getElementById('transSelect');
    var value = trSel.value;
    
    if(value == 'selship')
    {
      var msgship = document.getElementById('msgship');
      if(msgship == null)
      {
        var nodemsgship = '<div id="msgship">Select shipment method to send the order</div>';
        mcont.insertAdjacentHTML('afterBegin',nodemsgship);
        var msgship = document.getElementById('msgship');
        slideDownAndUp(mcont,msgship);
      }
    }
    else if(value == 'pickup' || value == 'ups')
    {
      var msgship = document.getElementById('msgship');
      if(msgship != null)
      {
        jQuery(msgship).stop();
        jQuery(msgship).slideUp({duration:500,complete:function()
        {
          mcont.removeChild(msgship);
        }});
      }
      
      jQuery.post("sendorder.php","send=yes");
      jQuery.post("oc.php","close=yes");
      jQuery('#bcart').slideUp({duration:500,complete:function()
      {
        document.getElementById('cartProdNum').innerHTML = 0;
        document.getElementById('cartTotAm').innerHTML = 0;
        bcSecondBlock.innerHTML = '';
        var bcEmpCart = '<div id="bcemptycart">Empty cart</div>';
        bcSecondBlock.insertAdjacentHTML('beforeEnd',bcEmpCart);
        trSel.innerHTML = transSelect.options;
      }});
      
      var orsent = '<div id="sentorder">Your order has been sent</div>';
      mcont.insertAdjacentHTML('afterBegin',orsent);
      var sentorder = document.getElementById('sentorder');
      slideDownAndUp(mcont,sentorder);
    }
  }
}

var transSelect = new function()
{
  this.options = '<option id="transchoose" value="selship" selected="selected">Select shipment method</option>';
  this.options += '<option id="transpickup" value="pickup">Pick up | 0$</option>';
  this.options += '<option id="transups" value="ups">UPS | 5$</option>';
}

var prodRating = new function()
{
  var arr = [];
  this.rate = function(nm,rt)
  {
    if(arr.indexOf(nm) == -1)
    {
      arr.push(nm);
      jQuery.post("rating.php","name="+nm+"&rating="+rt,function(data,textStatus)
      {
        data = JSON.parse(data);
        
        if(data.crating)
        {
          var crtg = data.crating;
          var stcrtg = document.getElementById('rt'+nm+crtg);
          jQuery(stcrtg).delay(100).animate({opacity:1},100).delay(100).animate({opacity:0.5},100).delay(100).animate({opacity:1},100);
        }
        
        if(data.avrating)
        {
          document.getElementById('rtg'+nm).innerHTML = data.avrating;
        }
        
        var ind = arr.indexOf(nm);
        if(ind != -1)
        {
          arr.splice(ind,1);
        }
      });
    }
  }
}

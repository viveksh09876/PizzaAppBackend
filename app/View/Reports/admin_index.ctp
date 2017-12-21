<?php 
//pr($out['guestRepeatedUser']);
//print_r($this->request->params['pass']);
 ?>
 <?php extract($pageVar); ?>
 
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<div class="col-md-12" style="margin-bottom:2%;">
	<section class="content-header">
		<h1><?=$title?></h1>
		<ol class="breadcrumb">
			<?=$breadcrumb?>
		</ol>
	</section>
</div>
<section class="content">
<div>


<label class="lab">Select Data</label>
<select onchange="getDataByDate(this.value)" id="date_filter">
<option Value="">All</option>
   <option Value="Yesterday">Yesterday</option>
   <option Value="Current Week">Current Week</option>
   <option Value="Last Week">Last Week</option>
   <option Value="This Day Last Week">This Day Last Week</option>
   <option Value="Current Month">Current Month</option>
   <option Value="Last Month">Last Month</option>
   <option Value="This Day Last Month">This Day Last Month</option>
   <option Value="Current Year">Current Year</option>
   <option Value="Last Year">Last Year</option>
   <option Value="This Day Last Year">This Day Last Year</option>
   <option Value="Custom">Custom</option>
</select>
<button class="editCal" type="button" onClick="showCal();">Change</button>
<span id="rDate"></span>
<a id="synBtn" class="btn btn-primary pull-right" href="javascript:void(0)" onClick="synData()">Synchronize Data</a>
<div class="cal_popup" id="cal_tab">
<button class="pull-right" type="button" onClick="closeCal();">X</button>
<ul class="nav nav-tabs nav-pills nav-justified" role="tablist">
    <li role="presentation" class="active"><a href="#sdate" aria-controls="sdate" role="tab" data-toggle="tab">Start Date</a></li>
    <li role="presentation"><a href="#edate" aria-controls="edate" role="tab" data-toggle="tab">End Date</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="sdate">
		<div id="datepicker" data-date="today"></div>
	</div>
    <div role="tabpanel" class="tab-pane" id="edate">
		<div id="datepicker1"></div>
	</div>
</div>	
</div>
</div>
<div class="col">
<div class="box box-primary">
<h3>Total Orders</h3>
<canvas id="total_ord"></canvas>
</div>
</div>
<div class="col">
<div class="box box-primary">
<h3>Total Placed Orders By Store</h3>
<canvas id="total_ord_store"></canvas>
<?php foreach($out['placedOrderByStore'] as $coup){
$label[]=$coup['NewOrderhistory']['storeId'];
$data[]=$coup['NewOrderhistory']['count'];
?>
<?php }?>
</div>
</div>
<div class="col">
<div class="box box-primary">
<h3>Used Coupons By Store</h3>
<?php foreach($out['totleUseCoupon'] as $key=>$val){ $count=0;?>
<h4>Store Id : <?php echo $key;?></h4>
<?php foreach($val as $coup){
	if(strpos($coup['coupon'], 'NKDSCOT') !== false){
		$count += $coup['count'];
	?>
<!--<span style='width:100%;display: inline-block;' ><?php //echo 'NKDSCOT';?> : <strong><?php //echo $count;?></strong></span>	-->
<?php }else{
	$couponLab[]=$coup['coupon'];
	$couponCount[]=$coup['count'];
?>
<!--<span style='width:100%;display: inline-block;' ><?php //echo $coup['coupon'];?> : <strong><?php //echo $coup['count'];?></strong></span>-->
<?php }
}
if($count>0){
$couponLab[]='NKDSCOT';
$couponCount[]=$count;
}
$couponData[$key]['label']=$couponLab;
$couponData[$key]['count']=$couponCount;
//pr($couponLab);
//pr($couponData);
 ?>
 <canvas id="<?php echo $key;?>"></canvas>
<?php } ?>
</div>
</div>
<div class="col">
<div class="box box-primary">
<h3>Repeated Users</h3>
<h4>&nbsp;</h4>
<canvas id="reg_user"></canvas>
<a class="btn btn-primary pull-right-btn" href="<?php echo $this->Html->url(array("controller" => "reports","action" => "repeatedUser",'?'=>array('sd'=>$this->request->params['pass'][0],'ed'=>$this->request->params['pass'][1])));?>">Show</a>
</div>
</div>
<div class="col">
<div class="box box-primary">
<h3>Repeated Users By Store</h3>
<?php foreach($out['repeatedUserbyStore'] as $key=>$users){?>
<h4>Store Id : <?php echo $key;?></h4>
<canvas id="reg_user_<?php echo $key;?>"></canvas>
<a href="<?php echo $this->Html->url(array("controller" => "reports","action" => "repeatedUser",$key,'?'=>array('sd'=>$this->request->params['pass'][0],'ed'=>$this->request->params['pass'][1])));?>" class="btn btn-primary pull-right-btn">Show</a>
<?php }?>
</div>
</div>
</section>
<div class="clearfix"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.js"></script>
<style>
.cal_popup{position:absolute;background:#fff;left:40%;padding:10px;display:none;z-index: 9;}
#date_filter{padding: 6px 12px;margin-left: 5px;}
.lab{margin-left: 10px;}
</style>
<script>
window.chartColors = {
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)',
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)'
};
var chColor=['rgb(75, 192, 192)','rgb(54, 162, 235)','rgb(153, 102, 255)','rgb(201, 203, 207)','rgb(255, 99, 132)','rgb(255, 159, 64)','rgb(255, 205, 86)'];
jQuery(function(){
	var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [<?php echo $out['startedOrder']; ?>,<?php echo $out['placedOrder']; ?>],
                backgroundColor: chColor,
                label: 'Dataset 1'
            }],
            labels: ["Started Orders :<?php echo $out['startedOrder']; ?>","Placed Orders :<?php echo $out['placedOrder']; ?>"]
        },
        options: {
            responsive: true
        }
    };
 var ctx = document.getElementById("total_ord");
 window.myPie = new Chart(ctx, config);
  
  var config1 = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: <?php echo json_encode($data);?>,
                backgroundColor: chColor,
                label: 'Dataset 1'
            }],
            labels: <?php echo json_encode($label);?>
        },
        options: {
            responsive: true
        }
    };
 var ctx1 = document.getElementById("total_ord_store");
 window.myPie = new Chart(ctx1, config1);
 
  var config2 = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [<?php echo $out['repeatedUser']['count'];?>,<?php echo $out['repeatedUser2']['count'];?>],
                backgroundColor: chColor,
                label: 'Dataset 1'
            }],
            labels:['Registered','Guest']
        },
        options: {
            responsive: true
        }
    };
 var ctx2 = document.getElementById("reg_user");
 window.myPie = new Chart(ctx2, config2);
 
<?php foreach($couponData as $key=>$data){?>
var id='<?php echo $key;?>';
var barChartData = {
            labels: <?php echo json_encode($data['label']);?>,
            datasets: [{
                label: id,
                backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data:<?php echo json_encode($data['count']);?>
            }]

        };
barChartGen(barChartData,id);
<?php }?>
<?php foreach($out['repeatedUserbyStore'] as $key=>$users){?>
var u_data=[<?php echo count($users);?>,<?php echo count($out['guestRepeatedUser'][$key]);?>];
var chId='reg_user_<?php echo $key;?>';
var u_label=['Registered','Guest'];
piChartGen(u_data,chId,u_label);
<?php }?>

});
 var color = Chart.helpers.color;
function barChartGen(data,cid){
	var ctx2 = document.getElementById(cid);
            window.myBar = new Chart(ctx2, {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom',
                    }
                }
            });
}

function piChartGen(data,id,labels){
		var config_dy = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: data,
                backgroundColor: chColor,
                label: 'Dataset 1'
            }],
            labels: labels
        },
        options: {
            responsive: true
        }
    };
 var ctx_dy = document.getElementById(id);
 window.myPie = new Chart(ctx_dy, config_dy);
}




function synData(){
	var data='';
$.ajax({
		method:'POST',
		url:'<?php echo $this->webroot;?>admin/reports/importOrderHistory',
		data:data,
		beforeSend:function(){
			$('#synBtn').html('Please Wait...');
		},
		success:function(res){
				$('#synBtn').html(res);
		},
		error: function (error) {
			alert('Error:A internal error, please try again.');
			$('#synBtn').html('Synchronize Data');
		}
	});
}
var customVal;
function getDataByDate(val){
	 var arg=val;
	 var myDate= new Date();
	 var fDate=new Date();
	 var lDate=new Date();
	
	 if(arg=='Yesterday'){
		 var yesturday=new Date(myDate);
		 yesturday=new Date(yesturday.setDate(myDate.getDate()-1));
		 fDate=yesturday;
		 lDate=yesturday;
		 
		var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+(fDate.getDate()-1)).slice(-2);
		var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		 customVal=startD;
	 }
	 
	 if(arg=='Current Week'){
		var curr = new Date; 
		var yesturday=new Date(myDate);
		var first = curr.getDate() - curr.getDay()+1;
		//alert(curr.getDay());
		var last = first + 6;
		
		 lDate= new Date(curr.setDate(first));
		 fDate= new Date(yesturday.setDate(myDate.getDate()-1));
		
		var ED=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+fDate.getDate()).slice(-2);
		var SD=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+(lDate.getDate())).slice(-2);
		var endD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		var startD=lDate.getDate()+'/'+(lDate.getMonth()+1)+'/'+lDate.getFullYear();
		
		customVal=startD+' TO '+endD; 
	 }
	 
	 if(arg=='Last Week'){
		var d = new Date();
        lDate = new Date(d.setTime(d.getTime() - (d.getDay() ? d.getDay() : 7) * 24 * 60 * 60 * 1000));
        fDate = new Date(d.setTime(d.getTime() - 6 * 24 * 60 * 60 * 1000));
		var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+fDate.getDate()).slice(-2);
		var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		var endD=lDate.getDate()+'/'+(lDate.getMonth()+1)+'/'+lDate.getFullYear();
		
		customVal=startD+' TO '+endD;
	 }
	 
	 if(arg=='This Day Last Week'){
		 var yesturday=new Date(myDate);
		 fDate=new Date(yesturday.setDate(myDate.getDate()-8));
		// lDate=new Date(yesturday.setDate(myDate.getDate()-8));
		var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+(fDate.getDate()-1)).slice(-2);
		var ED=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+fDate.getDate()).slice(-2);
		
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		
		customVal=startD; 
	 }
	 
	 
	 if(arg=='Current Month'){
		var curr_date =new Date();
        fDate = new Date(curr_date.getFullYear(), curr_date.getMonth(), 1);
        lDate = new Date(curr_date.setDate(myDate.getDate()-1));
		var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+fDate.getDate()).slice(-2);
		var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		var endD=lDate.getDate()+'/'+(lDate.getMonth()+1)+'/'+lDate.getFullYear();
		
		customVal=startD+' TO '+endD;
	 }
	 
	 if(arg=='Last Month'){
		var curr_date =new Date();
        fDate = new Date(curr_date.getFullYear(), curr_date.getMonth()-1, 1);
		lDate=new Date(fDate.getFullYear(), fDate.getMonth() + 1, 0);
		var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+fDate.getDate()).slice(-2);
		var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		var endD=lDate.getDate()+'/'+(lDate.getMonth()+1)+'/'+lDate.getFullYear();
		customVal=startD+' TO '+endD;
	 }
	 if(arg=='This Day Last Month'){
		 var yesturday=new Date(myDate);
		 fDate = new Date(yesturday.getFullYear(), yesturday.getMonth()-1, yesturday.getDate()-1);
		 lDate=new Date(yesturday.getFullYear(), yesturday.getMonth()-1, yesturday.getDate()-1);
		 var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+(fDate.getDate()-1)).slice(-2);
		 var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		 
		 var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		customVal=startD; 
	 }
	 
	  if(arg=='Current Year'){
		var curr_date =new Date();
		fDate = new Date(curr_date.getFullYear(), 0);
		lDate = new Date(curr_date.setDate(myDate.getDate()-1));
		var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+fDate.getDate()).slice(-2);
		var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		var endD=lDate.getDate()+'/'+(lDate.getMonth()+1)+'/'+lDate.getFullYear();
		
		customVal=startD+' TO '+endD;
	  }
	  
	  if(arg=='Last Year'){
		var curr_date =new Date();
		fDate = new Date(curr_date.getFullYear()-1, 0);
		lDate=new Date(fDate.getFullYear()+1, fDate.getMonth(), 0);
		var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+fDate.getDate()).slice(-2);
		var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		var endD=lDate.getDate()+'/'+(lDate.getMonth()+1)+'/'+lDate.getFullYear();
		customVal=startD+' TO '+endD; 
	  }
	  
	  if(arg=='This Day Last Year'){
		 var yesturday=new Date(myDate);
		 fDate = new Date(yesturday.getFullYear()-1, yesturday.getMonth(), yesturday.getDate()-1);
		 lDate=new Date(yesturday.getFullYear()-1, yesturday.getMonth(), yesturday.getDate()-1);
		 var SD=fDate.getFullYear()+'-'+('0'+(fDate.getMonth()+1)).slice(-2)+'-'+('0'+(fDate.getDate()-1)).slice(-2);
		 var ED=lDate.getFullYear()+'-'+('0'+(lDate.getMonth()+1)).slice(-2)+'-'+('0'+lDate.getDate()).slice(-2);
		var startD=fDate.getDate()+'/'+(fDate.getMonth()+1)+'/'+fDate.getFullYear();
		customVal=startD;
	 }
	  if(arg=='Custom'){
		 $('.cal_popup').show();
	 }else{
		 if(SD && ED){
			window.location.href='<?php echo $this->webroot;?>admin/reports/index/'+SD+'/'+ED;
			sessionStorage.setItem('selectedOpt', arg);
			sessionStorage.setItem('selectedDate', customVal);
		 }else{
			 window.location.href='<?php echo $this->webroot;?>admin/reports/index';
			 sessionStorage.setItem('selectedOpt', '');
			 sessionStorage.setItem('selectedDate', '');
		 }
	 }
}
function closeCal(){
	$('.cal_popup').hide();
}
function showCal(){
	$('.cal_popup').show();
}
var sDate;
var sfDate;
$(function(){
	var selectedOption=sessionStorage.getItem('selectedOpt');
	var selectedDate=sessionStorage.getItem('selectedDate');
	if(selectedOption){
		$('#date_filter option[value="'+selectedOption+'"]').attr('selected','selected');
	}
	if(selectedDate){
		$('#rDate').html(selectedDate);
	}else{
		$('#rDate').html('');
	}
	if(selectedOption=='Custom'){
		$('.editCal').show();
	}else{
		$('.editCal').hide();
	}
	
	
	
	$('#datepicker').datepicker({format:'yyyy-mm-dd'});
	$('#datepicker1').datepicker({format:'yyyy-mm-dd'});
	$('#datepicker').on('changeDate', function() {
		sDate=$('#datepicker').datepicker('getFormattedDate');
		sfDate=$('#datepicker').datepicker('getFormattedDate','dd-mm-yyyy');
		$('#cal_tab li:eq(1) a').tab('show');
	});
	$('#datepicker1').on('changeDate', function() {
		var eDate=$('#datepicker1').datepicker('getFormattedDate');
		var efDate=$('#datepicker1').datepicker('getFormattedDate','dd-mm-yyyy');
		window.location.href='<?php echo $this->webroot;?>admin/reports/index/'+sDate+'/'+eDate;
		var customVal=sfDate+' TO '+efDate; 
		sessionStorage.setItem('selectedOpt', 'Custom');
		sessionStorage.setItem('selectedDate', customVal);
	});
});
</script>
<style>
.col{width:50%;float:left;padding: 10px;}
.box.box-primary{padding: 10px;}
.pull-right-btn{position: absolute;bottom: 10px;right: 10px;}
</style>

      

 
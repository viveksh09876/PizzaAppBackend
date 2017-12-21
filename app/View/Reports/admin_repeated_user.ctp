<?php 
//pr($out['repeatedUserOrdersDetail']);
 ?>
<section class="content">
<a class="btn btn-primary" href="<?php echo $this->Html->url(array("controller" => "reports","action" => "index",$this->request->query['sd'],$this->request->query['ed']));?>">Back</a>
<div class="box box-primary">
<h3>All Repeated Users <?php if($out['store']){?>By Store <?php echo $out['store'];?><?php }?></h3>
<table class="table">
<thead class="thead-light">
<tr><th>#</th><th>User Name</th><th>Total Orders</th></tr>
</thead>
<?php $i=1;foreach($out['repeatedUser'] as $rUser){?>
<tr>
<td><?php echo $i;?></td>
	<td><a href="<?php echo $this->webroot;?>admin/reports/ordersDetail/<?php echo $rUser['NewOrderhistory']['UserId'];?>"><?php echo $rUser['Ecuser']['FirstName'].' '.$rUser['Ecuser']['LastName'];?></a> </td><td><strong><?php echo $rUser['NewOrderhistory']['count'];?></strong></td>
	</tr>
<?php $i++;}?>
</table>
</div>
</section>
<div class="clearfix"></div>
    
<style>
.box.box-primary{padding: 10px; margin-top:10px;}
</style>
      

 
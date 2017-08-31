<?php extract($pageVar); ?>
<section class="content-header">
  <h1>
    <?=$title;?>
    <small><?=$sub_title;?></small>
  </h1>
  <ol class="breadcrumb">
    <?=$breadcrumb;?>
  </ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
			<?php if(!empty($SubCategories)){?>
			<table class="table table-hover">
				<tr><!-- <th><?php // echo $this->Paginator->sort('lang_id','Language');?></th> --><th><?php echo $this->Paginator->sort('name','Sub Category Name');?></th><th><?php echo $this->Paginator->sort('cat_id','Main Category');?></th><th><?php echo $this->Paginator->sort('short_description','Price Title');?></th><!-- <th><?php // echo $this->Paginator->sort('image');?></th> --><th><?php echo $this->Paginator->sort('sort_order');?></th><th><?php echo $this->Paginator->sort('status');?></th><th class="pull-right"><?php echo __('Actions');?></th></tr>
				<?php
				foreach ($SubCategories as $SubCategory):
				?>
				<tr>
					<!-- <td><?php // echo $SubCategory['Language']['name']; ?></td> -->
					<td><?php echo $SubCategory['SubCategory']['name']; ?></td>
					<td><?php echo $SubCategory['Category']['name']; ?></td>
					<td><?php echo $SubCategory['SubCategory']['short_description']; ?></td>
					<!-- <td>
					<?php if(!empty($SubCategory['SubCategory']['image'])){ ?>
						<img src="<?=IMG_ADMIN.'categories/'.$SubCategory['SubCategory']['image']; ?>" height="50" width="50" >
					<?php } ?>
					</td> -->
					<td><?php echo $SubCategory['SubCategory']['sort_order']; ?></td>
					<td><?php echo getActiveInactive($SubCategory['SubCategory']['status']); ?></td>
					<td class="pull-right">
						<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $SubCategory['SubCategory']['id']),array('class'=>'btn btn-default btn-sm btn-primary')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<div class="pull-left">
				<p>
				<?php
				echo $this->Paginator->counter(array(
				'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
				));
				?>	
				</p>
			</div>
			<div class="paging pull-right">
				<?php echo $this->Paginator->prev('<< ' . __('previous', true), array('class'=>'btn btn-primary'), null, array('class'=>'disabled btn btn-default'));?>
			 | 	<?php echo $this->Paginator->numbers();?>
		 	 |	<?php echo $this->Paginator->next(__('next', true) . ' >>', array('class'=>'btn btn-primary'), null, array('class' => 'disabled  btn btn-default'));?>
			</div>
			<?php }else{ ?>
				<div class="alert alert-danger">Records not found.</div>
			<?php	} ?>
			</div>
		</div>
	</div>
</section>
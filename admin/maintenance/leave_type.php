<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Leave Types</h3>
		<?php if($_settings->userdata('type') == 1): ?>
		<div class="card-tools">
			<a href="javascript:void(0)" class="btn btn-flat btn-primary" id="create_new"><span class="fas fa-plus"></span>  Create New</a>
		</div>
		<?php endif; ?>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-stripped table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Description</th>
						<th>Default Credit</th>
						<th>Status</th>
						<th>Date Updated</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT * from `leave_types` order by unix_timestamp(date_updated) desc, unix_timestamp(date_created) desc ");
						while($row = $qry->fetch_assoc()):
                            $row['description'] = strip_tags(stripslashes(html_entity_decode($row['description'])));
					?>
						<tr title="<?php echo $row['description'] ?>">
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo '['.$row['code'].'] - '. $row['name'] ?></td>
							<td ><p class="truncate m-0"><?php echo $row['description'] ?></p></td>
							<td class='text-right'><?php echo number_format($row['default_credit']) ?></td>
							<td class='text-center'>
								<?php if($row['status'] == 1): ?>
									<span class="badge badge-success">Active</span>
								<?php else: ?>
									<span class="badge badge-secondary">Inactive</span>
								<?php endif; ?>
							</td>
							<td><?php echo ($row['date_updated'] != null) ? date('Y-m-d H:i',strtotime($row['date_updated'])) : date('Y-m-d H:i',strtotime($row['date_created'])); ?></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Leave Type permanently?","delete_leave_type",[$(this).attr('data-id')])
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Edit Leave Type Details",'maintenance/manage_leave_type.php?id='+$(this).attr('data-id'))
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Create New Leave Type",'maintenance/manage_leave_type.php')
		})
		$('.table').dataTable();
	})
	function delete_leave_type($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_leave_type",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>
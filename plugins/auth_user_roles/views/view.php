<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?=__lang("personnel_settings")?></a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)"><?=__lang("personnel_roles")?></a></li>
    </ol>
</div>
<?php if(user_can("rolleri_görüntüle")){ ?>
<div class="card">		
	<div class="card-header">
		<h4 class="card-title"><?=__lang("personnel_roles")?></h4>
		<?php if(user_can("rolleri_ekleme")){ ?>
		<a href="#add_role_modal" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"><i class="las la-plus-circle la-lg"></i> <?=__lang("new_personnel_role")?></a>
		<div class="modal fade bd-example-modal-lg" id="add_role_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><?=__lang("new_personnel_role")?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<form id="new_personnel_role_add">
						<div class="modal-body">
							<div class="default-tab">
								<div class="row pt-4">
									<div class="col-xl-6">
										<label class="form-label"><?=__lang("role_name")?> (*)</label>
										<input type="text" class="form-control mb-3" name="role_name" placeholder="Muhasebe">
									</div>
									<div class="col-xl-6">
										<label class="form-label"><?=__lang("role_status")?> (*)</label>
										<select name="role_status" id="role_status" class="default-select form-control wide ms-0">
											<option value=""><?=__lang("please_status_select")?></option>
											<option value="0"><?=__lang("active")?></option>
											<option value="1"><?=__lang("passive")?></option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger  btn-sm" data-bs-dismiss="modal"><?=__lang("close")?></button>
							<button type="submit" id="personnel_role_add_btn" class="btn btn-success btn-sm"><?=__lang("new_personnel_role")?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="card-body">
		<?php if(user_can("rolleri_görüntüle")): ?>
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="personnel_roles_table" class="display" style="min-width: 845px">
						<thead>
							<tr>
								<th style="width:20%"><?=__lang("role_name")?></th>
								<th style="width:10%" class="text-center"><?=__lang("role_status")?></th>
								<th style="width:30%"><?=__lang("roles")?></th>
								<th style="width:20%"></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
		<?php else: echo permissionFail(); endif; ?>
	</div>
</div>
<?php }else{ echo permissionFail(); } ?>
<?php 
$perms = array_unique(APP("permissions")); 

$editRolePermission  	= user_can("rolleri_düzenleme") ? 'true' : 'false';
$deleteRolePermission  	= user_can("rolleri_silme") ? 'true' : 'false';
$RoleEditPermission  	= user_can("izinleri_düzenle") ? 'true' : 'false';
$permissionFail 		= permissionFail();
?>
<script>
    var permissionsApp = <?= json_encode(array_values($perms), JSON_UNESCAPED_UNICODE); ?>;

	var editRolePermission 		= <?= $editRolePermission ?>;
	var deleteRolePermission 	= <?= $deleteRolePermission ?>;
	var roleEditPermission 		= <?= $RoleEditPermission ?>;
    var permissionFail 			= <?= json_encode($permissionFail) ?>;



	var please_status_select= '<?=__lang('please_status_select')?>';
	var active				= '<?=__lang('active')?>';
	var passive				= '<?=__lang('passive')?>';
	var role_name 			= '<?=__lang('role_name')?>';
	var role_status 		= '<?=__lang('role_status')?>';
	var close_btn			= '<?=__lang('close')?>';
	var new_personnel_role	= '<?=__lang('new_personnel_role')?>';
	var role_delete			= '<?=__lang('role_delete')?>';
</script>
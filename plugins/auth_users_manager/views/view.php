<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?=__lang("personnel_settings")?></a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)"><?=__lang("personnel_transcation")?></a></li>
    </ol>
</div>
<?php if(user_can("personelleri_görüntüle")){ ?>
<div class="card">	
	<div class="card-header">
		<?php if (user_can('personel_ekle')) { ?>
		<a href="#addpersonalmodal" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"><i class="las la-plus-circle la-lg"></i> <?=__lang("new_personnel_add")?></a>
		<div class="modal fade bd-example-modal-lg" id="addpersonalmodal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><?=__lang("new_personnel_add")?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<form id="new_personnel_form">
						<div class="modal-body">
							<div class="row pt-4">
								<div class="col-xl-4">
									<label class="form-label"><?=__lang("personnel_first_name")?> (*)</label>
									<input type="text" class="form-control mb-3" name="first_name" placeholder="Ayşe">
								</div>
								<div class="col-xl-4">
									<label class="form-label"><?=__lang("personnel_last_name")?> (*)</label>
									<input type="text" class="form-control mb-3" name="last_name" placeholder="Yılmaz">
								</div>
								<div class="col-xl-4">
									<label class="form-label"><?=__lang("id_number")?></label>
									<input type="text" class="form-control mb-3" name="id_number" placeholder="111111111">
								</div>
								<div class="col-xl-4">
									<label class="form-label"><?=__lang("personnel_phone")?> (*)</label>
									<input type="text" class="form-control mb-3" name="phone" placeholder="+90 (000) 000 00 00">
								</div>
								<div class="col-xl-3">
									<label class="form-label"><?=__lang("personnel_status")?> (*)</label>
									<select name="personnel_status" id="personnel_status" class="default-select form-control wide ms-0">
										<option value="1"><?=__lang("active")?></option>
										<option value="0"><?=__lang("passive")?></option>
									</select>
								</div>
								<div class="col-xl-5">
									<label class="form-label"><?=__lang("personnel_role_select")?></label>
									<select name="role_select" id="role_select" class="default-select form-control wide ms-0">
										<option value=""><?=__lang("please_select_personnel_role")?></option>
									</select>
								</div>
								<div class="col-xl-6">
									<label class="form-label"><?=__lang("personnel_email")?> (*)</label>
									<input type="text" class="form-control mb-3" name="email" placeholder="mail@example.com">
								</div>
								<div class="col-xl-3">
									<label class="form-label"><?=__lang("personnel_password")?> (*)</label>
									<input type="password" class="form-control mb-3" name="password" placeholder="12345678">
								</div>
								<div class="col-xl-3">
									<label class="form-label"><?=__lang("retype_personnel_password")?> (*)</label>
									<input type="password" class="form-control mb-3" name="password_retype" placeholder="12345678">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger  btn-sm" data-bs-dismiss="modal"><?=__lang("close")?></button>
							<button type="submit" id="personnel_add_btn" class="btn btn-success btn-sm"><?=__lang("new_personnel_add")?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="card-body">
		<?php if (user_can('personelleri_görüntüle')) { ?>
		<div class="table-responsive">
			<table id="personnel_table" class="display" style="min-width: 845px">
				<thead>
					<tr>
						<th style="width:30%"><?=__lang("personnel_full_name")?></th>
						<th style="width:20%"><?=__lang("personnel_email")?></th>
						<th style="width:20%"><?=__lang("personnel_role")?></th>
						<th style="width:15%" class="text-center"><?=__lang("personnel_status")?></th>
						<th style="width:9%"></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<?php }else { echo permissionFail(); } ?>
	</div>
</div>
<?php }else{ echo permissionFail(); } ?>
<?php 
	$perms = array_unique(APP("permissions")); 

	$viewUserPermission  	= user_can("personel_detay_görüntüle") ? 'true' : 'false';
	$editUserPermission  	= user_can("personel_düzenle") ? 'true' : 'false';
	$deleteUserPermission  	= user_can("personel_sil") ? 'true' : 'false';
	$UserRoleEditPermission = user_can("personel_rolü_güncelle") ? 'true' : 'false';
	$permissionFail 		= permissionFail();
?>
<script>
	var permissionsApp 			= <?= json_encode(array_values($perms), JSON_UNESCAPED_UNICODE); ?>;

	var viewUserPermission 		= <?= $viewUserPermission ?>;
	var editUserPermission 		= <?= $editUserPermission ?>;
	var deleteUserPermission 	= <?= $deleteUserPermission ?>;
	var UserRoleEditPermission 	= <?= $UserRoleEditPermission ?>;
	var permissionFail 			= <?= json_encode($permissionFail) ?>;

	var please_status_select= '<?=__lang('please_status_select')?>';
	var active				= '<?=__lang('active')?>';
	var passive				= '<?=__lang('passive')?>';
	var close_btn			= '<?=__lang('close')?>';



	var personnel_first_name 	= '<?=__lang('personnel_first_name')?>';
	var personnel_last_name  	= '<?=__lang('personnel_last_name')?>';
	var personnel_phone			= '<?=__lang('personnel_phone')?>';
	var personnel_email			= '<?=__lang('personnel_email')?>';
	var id_number				= '<?=__lang('id_number')?>';
	var personnel_address		= '<?=__lang('personnel_address')?>';
	var personnel_password		= '<?=__lang('personnel_password')?>';
	var personnel_status		= '<?=__lang('personnel_status')?>';
	var personnel_role			= '<?=__lang('personnel_role')?>';
	var please_select_personnel_role = '<?=__lang('please_select_personnel_role')?>';
	

	var role_name 			= '<?=__lang('role_name')?>';
	var role_status 		= '<?=__lang('role_status')?>';
	var new_personnel_role	= '<?=__lang('new_personnel_role')?>';
	var role_delete			= '<?=__lang('role_delete')?>';
</script>

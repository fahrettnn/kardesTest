<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?=__lang("customers_transactions")?></a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)"><?=__lang("customers")?></a></li>
    </ol>
</div>

<div class="col-12">
	<div class="card">
		<div class="card-header d-flex justify-content-end">
			<?php if (user_can(__lang("customers_add_permission"))) { ?>
			<a href="#addcustomersmodal" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"><i class="las la-plus-circle"></i> <?=__lang("new_customer_add")?></a>
			<div class="modal fade bd-example-modal-lg" id="addcustomersmodal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?=__lang("new_customer_add")?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
						</div>
						<form id="customer_add" method="post">
							<div class="modal-body">
								<div class="default-tab">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-bs-toggle="tab" href="#home"><i class="la la-home me-2"></i> <?=__lang("customer_information")?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#contact"><i class="la la-phone me-2"></i> <?=__lang("customer_contact_information")?></a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-bs-toggle="tab" href="#adress"><i class="la la-map-marked me-2"></i> <?=__lang("customer_adress_information")?></a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active" id="home" role="tabpanel">
											<div class="row pt-4">
												<div class="col-xl-12">
													<label class="form-label"><?=__lang("customer_company_name")?> (*)</label> 
													<input type="text" class="form-control mb-3" name="companyName" placeholder="<?=__lang("customer_company_name")?>">
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="contact">
											<div class="row pt-4">
												<div class="col-xl-6">
													<label class="form-label"><?=__lang("customer_company_email")?></label>
													<input type="text" class="form-control mb-3" name="companyEmail" placeholder="example@mail.com">
												</div>
												<div class="col-xl-6">
													<label class="form-label"><?=__lang("customer_company_web")?></label>
													<input type="text" class="form-control mb-3" name="companyWeb" placeholder="http/https://www.website.com">
												</div>
												<div class="col-xl-6">
													<label class="form-label"><?=__lang("customer_company_phone")?> (*)</label>
													<input type="text" class="form-control mb-3" name="companyPhone" placeholder="0 (000) (000) 00 00">
												</div>
												<div class="col-xl-6">
													<label class="form-label"><?=__lang("customer_company_fax")?></label>
													<input type="text" class="form-control mb-3" name="companyFax" placeholder="0 (000) (000) 00 00">
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="adress">
											<div class="row pt-4">
												<div class="col-xl-6">
													<label class="form-label"><?=__lang("please_adress_city")?> (*)</label>
													<select name="cityId" id="citySelect" class="form-control wide ms-0"></select>
												</div>
												<div class="col-xl-6">
													<label class="form-label"><?=__lang("please_adress_district")?> (*)</label>
													<select name="districtId" id="district" class="form-control wide ms-0" disabled>
														<option value=""><?=__lang("please_adress_district")?></option>
													</select>
												</div>
												<div class="col-xl-12 mt-1">
													<label class="form-label"><?=__lang("adress_detail")?> (*)</label>
													<textarea name="adressDetail" class="form-control h-auto mb-3" rows="4" placeholder="<?=__lang("adress_detail")?>"></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger  btn-sm" data-bs-dismiss="modal"><?=__lang("close")?></button>
								<button type="submit" id="customer_add_btn" class="btn btn-success btn-sm"><?=__lang("new_customer_add")?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="card-body">
			<?php if (user_can("view_customer")) { ?>
			<div class="table-responsive">
				<table id="customerlistTable" class="display" style="min-width: 845px">
					<thead>
						<tr>
							<th><?=__lang("customer_company_name")?></th>
							<th><?=__lang("phone_number")?></th>
							<th><?=__lang("email_adress")?></th>
							<th></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
			<?php }else{ echo permissionFail();} ?>
		</div>
	</div>
</div>
<?php 
	$viewPermission 	= (user_can(__lang("customers_view_permission"))) ? 'true' : 'false'; 
	$editPermission 	= (user_can(__lang("customers_edit_permission"))) ? 'true' : 'false'; 
	$deletePermission 	= (user_can(__lang("customers_delete_permission"))) ? 'true' : 'false';
	$permissionFail 	= permissionFail();
?>
<script>
	var viewPermission   = "<?=$viewPermission?>";
	var editpermission   = "<?=$editPermission?>";
	var deletepermission = "<?=$deletePermission?>";
    var permissionFail   = <?= json_encode($permissionFail) ?>;

	var emptyDataMessage = "<?=__lang("registered_customer_company_not_found")?>"; var select_city = "<?=__lang("please_adress_city")?>"; var select_district = "<?=__lang("please_adress_district")?>";var real_person = "<?=__lang("real_person")?>";var legal_entity = "<?=__lang("legal_entity")?>";
	
	/** Language */
	var customer_information 		  = '<?=__lang("customer_information")?>';
	var customer_contact_information = "<?=__lang("customer_contact_information")?>";
	var customer_adress_information = "<?=__lang("customer_adress_information")?>";

	var authorized_position = "<?=__lang("position")?>";
	var authorized_firstname = "<?=__lang("customer_authorized_firstname")?>";
	var authorized_lastname = "<?=__lang("customer_authorized_lastname")?>";
	var customer_authorized_email = "<?=__lang("customer_authorized_email")?>";
	var customer_authorized_phone = "<?=__lang("customer_authorized_phone")?>";
	var customer_authorized_gsm = "<?=__lang("customer_authorized_gsm")?>";


	var customer_company_name = "<?=__lang("customer_company_name")?>";
	var customer_company_email = "<?=__lang("customer_company_email")?>";
	var customer_company_web = "<?=__lang("customer_company_web")?>";
	var customer_company_phone = "<?=__lang("customer_company_phone")?>";
	var customer_company_fax = "<?=__lang("customer_company_fax")?>";
	var adress_detail = "<?=__lang("adress_detail")?>";

	var close_btn						= "<?=__lang("close")?>";
	var delete_btn						= "<?=__lang("delete")?>";
	var update_btn						= "<?=__lang("update")?>";
	var customer_edit					= "<?=__lang("customer_edit")?>";
	var customer_delete					= "<?=__lang("customer_delete")?>";
	var customer_delete_message			= "<?=__lang("customer_delete_message")?>";

	var authorized_contact_record_not_found = "<?=__lang("authorized_contact_record_not_found")?>";
</script>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?=__lang("general_settings")?></a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">İlçe Ayarları</a></li>
    </ol>
</div>
<?php if(user_can("ilçe_görüntüleme")){ ?>
<div class="card">	
	<div class="card-header">
		<h4 class="card-title">İlçe Ayarları</h4>
		<div class="col-xl-5">
			<select name="city_id" id="city_select_table" class="default-select form-control wide ms-0"></select>
		</div>
		<?php if(user_can("ilçe_ekleme")) { ?>
		<a href="#add_discrits_modal" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"><i class="las la-plus-circle la-lg"></i> Yeni İlçe Ekle</a>
		<div class="modal fade bd-example-modal-md" id="add_discrits_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Yeni İlçe Ekle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<form id="new_discrits_form" method="POST">
						<div class="modal-body">
							<div class="default-tab">
								<div class="col-xl-12">
									<label class="form-label">İl Seç (*)</label>
									<select name="city_id" id="city_select" class="default-select form-control wide ms-0">
									</select>
								</div>
								<div class="col-xl-12">
									<label class="form-label">İlçe Adı (*)</label>
									<input type="text" class="form-control mb-3" name="discrit_name" placeholder="Ankara">
								</div>
								<div class="col-xl-12">
									<label class="form-label">İlçe Durumu (*)</label>
									<select name="discrit_status" id="discrit_status" class="default-select form-control wide ms-0">
										<option value=""><?=__lang("please_status_select")?></option>
										<option value="1"><?=__lang("active")?></option>
										<option value="2"><?=__lang("passive")?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger  btn-sm" data-bs-dismiss="modal"><?=__lang("close")?></button>
							<button type="submit" id="discrit_add_btn" class="btn btn-success btn-sm">Yeni İlçe Ekle</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="card-body">
		<?php if (user_can('ilçe_görüntüleme')) { ?>
		<div class="table-responsive">
			<table id="discrits_table" class="display" style="min-width: 845px">
				<thead>
					<tr><th style="width:15%">İl</th><th style="width:30%">İlçe Adı</th><th style="width:15%" class="text-center">İlçe Durumu</th><th style="width:9%"></th></tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<?php }else { echo permissionFail(); } ?>
	</div>


	<div class="modal fade bd-example-modal-md" id="edit_discrits_modal" tabindex="-1" aria-labelledby="editCityModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editDiscritsModalLabel">İlçe Düzenle</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
				</div>
				<form id="edit_discrit_form" method="PUT">
					<div class="modal-body">
						<?php if(user_can("ilçe_düzenleme")){ ?>
						<input type="hidden" id="edit_discrit_id" name="discritId">
						<div class="default-tab">
							<div class="col-xl-12">
								<label for="edit_city_select" class="form-label">Bulunduğu İl (*)</label>
								<select name="cityId" id="edit_city_select" class="default-select form-control wide ms-0">
								</select>
							</div>
							<div class="col-xl-12">
								<label for="edit_discrit_name" class="form-label">İlçe Adı (*)</label>
								<input type="text" class="form-control mb-3" id="edit_discrit_name" name="discrit_name" placeholder="Ankara" required>
							</div>
							<div class="col-xl-12">
								<label for="edit_discrit_status" class="form-label">İl Durumu (*)</label>
								<select name="status" id="edit_discrit_status" class="default-select form-control wide ms-0">
									<option value=""><?=__lang("please_status_select")?></option>
									<option value="1"><?=__lang("active")?></option>
									<option value="2"><?=__lang("passive")?></option>
								</select>
							</div>
						</div>
						<?php }else{ echo permissionFail(); } ?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger  btn-sm" data-bs-dismiss="modal"><?=__lang("close")?></button>
						<?php if(user_can("ilçe_düzenleme")){ ?>
						<button type="submit" id="discrit_edit_btn" class="btn btn-success btn-sm">Güncelle</button>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade bd-example-modal-md" id="delete_district_modal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">İlçe Sil</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body text-center">
					<?php if(user_can("ilçe_silme")){ ?>
					<p><strong id="delete_district_name"></strong> ilçesini silmek üzeresiniz.</p>
					<input type="hidden" id="delete_district_id">
					<?php }else{ echo permissionFail(); } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">İptal</button>
					<?php if(user_can("ilçe_silme")){ ?>
					<button type="button" id="confirm_delete_btn" class="btn btn-danger btn-sm">Onayla</button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }else{ echo permissionFail(); } ?>
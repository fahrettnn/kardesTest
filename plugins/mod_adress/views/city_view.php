<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?=__lang("general_settings")?></a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">İl Ayarları</a></li>
    </ol>
</div>
<?php if(user_can("il_görüntüleme")){ ?>
<div class="card">	
	<div class="card-header">
		<h4 class="card-title">İl Ayarları</h4>
		<?php if(user_can("il_ekleme")) { ?>
		<a href="#add_city_modal" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"><i class="las la-plus-circle la-lg"></i> Yeni İl Ekle</a>
		<div class="modal fade bd-example-modal-md" id="add_city_modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Yeni İl Ekle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<form id="new_city_form" method="POST">
						<div class="modal-body">
							<div class="default-tab">
								<div class="col-xl-12">
									<label class="form-label">İl Adı (*)</label>
									<input type="text" class="form-control mb-3" name="city_name" placeholder="Ankara">
								</div>
								<div class="col-xl-12">
									<label class="form-label">İl Durumu (*)</label>
									<select name="city_status" id="city_status" class="default-select form-control wide ms-0">
										<option value=""><?=__lang("please_status_select")?></option>
										<option value="1"><?=__lang("active")?></option>
										<option value="2"><?=__lang("passive")?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger  btn-sm" data-bs-dismiss="modal"><?=__lang("close")?></button>
							<button type="submit" id="city_add_btn" class="btn btn-success btn-sm">Yeni İl Ekle</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="card-body">
		<?php if (user_can('il_görüntüleme')) { ?>
		<div class="table-responsive">
			<table id="city_table" class="display" style="min-width: 845px">
				<thead>
					<tr><th style="width:5%">Plaka No</th><th style="width:30%">İl Adı</th><th style="width:15%" class="text-center">İl Durumu</th><th style="width:9%"></th></tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<?php }else { echo permissionFail(); } ?>
	</div>
	
	<div class="modal fade bd-example-modal-md" id="edit_city_modal" tabindex="-1" aria-labelledby="editCityModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editCityModalLabel">Şehir Düzenle</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
				</div>
				<form id="edit_city_form" method="PUT">
					<div class="modal-body">
						<?php if(user_can("il_düzenleme")){ ?>
						<input type="hidden" id="edit_city_id" name="id">
						<div class="default-tab">
							<div class="col-xl-12">
								<label for="edit_city_name" class="form-label">İl Adı (*)</label>
								<input type="text" class="form-control mb-3" id="edit_city_name" name="city_name" placeholder="Ankara" required>
							</div>
							<div class="col-xl-12">
								<label for="edit_city_status" class="form-label">İl Durumu (*)</label>
								<select name="status" id="edit_city_status" class="default-select form-control wide ms-0">
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
						<?php if(user_can("il_düzenleme")){ ?>
						<button type="submit" id="city_edit_btn" class="btn btn-success btn-sm">Güncelle</button>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="delete_city_modal" tabindex="-1" aria-labelledby="deleteCityModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">İl Sil</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
				</div>
				<div class="modal-body">
					<?php if(user_can("il_silme")){ ?>
					<p><strong><span id="delete_city_name"></span></strong> adlı şehri silmek istediğinize emin misiniz?</p>
					<input type="hidden" id="delete_city_id">
					<?php }else{ echo permissionFail(); } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">İptal</button>
					<?php if(user_can("il_silme")){ ?>
					<button type="button" id="confirm_delete_city_btn" class="btn btn-success btn-sm">Sil</button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }else{ echo permissionFail(); } ?>

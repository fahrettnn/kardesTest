<?php $ImageUrl = get_image("public/uploads/images/profiles/".$getUser->user_img); ?>
<form id="editform">
<div class="row">
	<div class="col-xl-4">
		<div class="card h-auto">
			<div class="card-body">
				<div class="profile text-center">
					<div class="setting-img mb-4">
						<div class="avatar-upload ">
							<div class="avatar-preview">
								<div id="imagePreview" style="background-image: url(<?=$ImageUrl?>);"></div>
							</div>
						</div>
					</div>
					<div><h6><?=$getUser->user_firstname . " " . $getUser->user_lastname?></h6></div>
					<div class="row"></div>
					<div class="change-btn d-flex align-items-center justify-content-center mt-3">	
						<input type='file' name="userImage" class="form-control ms-0" id="imageUpload" accept=".png, .jpg, .jpeg">
						<label for="imageUpload" class="dlab-upload">Fotoğraf Seç</label>
						<a href="javascript:void" class="btn remove-img ms-2">Vazgeç</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card h-auto">
			<div class="card-body">
				<div class="Security">
					<div class="d-flex align-items-center justify-content-between mb-3"><h4>Parola Güncelle</h4></div>
					<div class="row">
						<div class="col-xl-12">
							<label class="form-label">Mevcut Parola</label>
							<input type="password" name="avail_password" id="avail_password" class="form-control mb-3" placeholder="Parolanızı Giriniz">
						</div>
						<div class="col-xl-12">
							<label class="form-label">Yeni Parola Belirle</label>
							<input type="password" name="new_password" id="new_password" class="form-control mb-3" placeholder="Yeni Parolanızı Giriniz">
						</div>
						<div class="col-xl-12">
							<label class="form-label">Yeni Parola Tekrar</label>
							<input type="password" name="new_password_again" id="new_password_again" class="form-control mb-3" placeholder="Yeni Parola Tekrar Giriniz">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-8">
		<div class="card">
			<div class="card-body">
				<div class="bacic-info mb-3">
					<h4 class="mb-3"><?=__lang("edit_profile")?></h4>
					<div class="row">
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("your_name")?></label>
							<input type="text" name="firstname" class="form-control mb-3" placeholder="Ayşe" value="<?=$getUser->user_firstname?>"/>
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("your_last_name")?></label>
							<input type="text" name="lastname" class="form-control mb-3" placeholder="Yılmaz" value="<?=$getUser->user_lastname?>" />
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("your_phone_number")?></label>
							<input type="text" name="phone" class="form-control mb-3" placeholder="+90 (5xx) xxx xx xx" value="<?=$getUser->user_phone?>" />
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("your_email_address")?></label>
							<input class="form-control mb-3" value="<?=$getUser->user_email?>" disabled/>
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("last_session_date")?></label>
							<input class="form-control mb-3" value="<?=$getUser->last_session?>" disabled/>
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("account_status")?></label>
							<input class="form-control mb-3" value="<?=$getUser->status=1 ? __lang("active_account") : __lang("passive_account") ?>" disabled/>
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("account_created_date")?></label>
							<input class="form-control mb-3" value="<?=$getUser->date_created ?>" disabled/>
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("account_updated_date")?></label>
							<input class="form-control mb-3" value="<?=$getUser->date_updated?>" disabled/>
						</div>
					</div>
				</div>
				<button class="btn btn-success float-end" id="edit_btn"><?=__lang("profile_update")?></button>
			</div>
		</div>
	</div>
</div>
</form>
<script> var imageUrl = "<?=$ImageUrl?>"; </script>

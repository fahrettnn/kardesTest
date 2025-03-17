<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)"><?=__lang("general_settings")?></a></li>
    </ol>
</div>
<?php if(user_can('view_general_settings')){ ?>
<form id="edit_general_settings" method="put">
	<div class="col-xl-12">
		<div class="card">
			<div class="card-body">
				<div class="bacic-info mb-3">
					<h4 class="mb-3"><?=__lang("mailing_settings")?></h4>
					<div class="row">
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("smtp_host_name")?> (*)</label>
							<input type="text" class="form-control mb-3" name="smtp_host" placeholder="smtp-mail.outlook.com" value="<?=$_ENV['SMTP_HOST']?>"/>
						</div>
						<div class="col-xl-3">
							<label class="form-label"><?=__lang("smtp_port")?> (*)</label>
							<input type="number" class="form-control mb-3" name="smtp_port" placeholder="587" value="<?=$_ENV['SMTP_PORT']?>" />
						</div>
						<div class="col-xl-3">
							<label class="form-label"><?=__lang("smtp_security")?> (*)</label>
							<select name="smtp_securty" class="form-control mb-3">
								<option value=""><?=__lang('smtp_no_security')?></option>
								<option value="tls">TLS</option>
								<option value="ssl">SSL</option>
							</select>
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("smtp_mail_adress")?> (*)</label>
							<input class="form-control mb-3" name="smtp_email" value="<?=$_ENV['SMTP_EMAIL']?>" placeholder="mail@example.com"/>
						</div>
						<div class="col-xl-6">
							<label class="form-label"><?=__lang("smtp_password")?> (*)</label>
							<input class="form-control mb-3" name="smtp_password" value="<?=$_ENV['SMTP_PASSWORD']?>" placeholder="123456"/>
						</div>
					</div>
				</div>
				<?php if(user_can("genel_ayarlar_düzenleme")){ ?>
				<button class="btn btn-success float-end" id="edit_btn"><?=__lang("mailing_settings_update")?></button>
				<?php } ?>
			</div>
		</div>
	</div>
</form>
<?php }else { echo permissionFail(); } ?>

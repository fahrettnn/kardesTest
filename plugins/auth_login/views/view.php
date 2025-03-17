<?php
use App\Core\Helpers\ActionFilterHelper;
use App\Core\Models\Security;
?>
<div class="vh-100"><div class="authincation h-100">
<div class="container mt-0">
	<div class="row align-items-center justify-contain-center">
		<div class="col-xl-12">
			<div class="border-0">
				<div class="card-body login-bx">
					<div class="row  mt-4">
						<div class="col-xl-8 col-md-6 sign text-center">
							<div><img src="<?=get_image(plugin_path('assets/images/erp-system-1.gif'))?>" class="food-img" ></div>
						</div>
						<div class="col-xl-4 col-md-6 lovesta-sign-in-your">
							<div class="sign-in-your mt-5">
								<div class="text-center mb-3">
									<img src="<?=get_image('public/uploads/images/logos.svg')?>" class="mb-3" width="250" height="50">
									<h4 class="fs-20 font-w800 text-black"><?=__lang('please_log_in_to_your_account')?></h4>
									<span class="dlab-sign-up"><?=__lang('login')?></span>
								</div>
								<form id="login_form" method="post">
									<?=Security::csrf("login"); ?>
									<div class="mb-3">
										<label class="mb-1"><strong><?=__lang('your_email_address')?></strong></label>
										<input type="email" name="email" class="form-control" placeholder="<?=__lang('please_enter_your_email_address')?>" autocomplete="current-password" required>
									</div>
									<div class="mb-3">
										<label class="mb-1"><strong><?=__lang('your_password')?></strong></label>
										<input type="password" name="password" class="form-control" placeholder="<?=__lang('please_enter_your_password')?>" autocomplete="current-password" required>
									</div>
									<div class="row d-flex justify-content-between mt-4 mb-2">
										<?php ActionFilterHelper::doAction(plugin_id().'_resetLoginBtn'); ?>
									</div>
									<div class="text-center">
										<button type="submit" id="login_btn" class="btn btn-primary btn-block shadow"><?=__lang('login')?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div></div></div>
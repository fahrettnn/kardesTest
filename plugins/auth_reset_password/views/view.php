<?php

use App\Core\Models\Security;
?>
<div class="vh-100">
    <div class="authincation h-100">
        <div class="container">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-3"><a href="<?=ROOT?>"><img src="<?=get_image('public/uploads/images/logos.svg')?>" class="img-fluid"></a></div>
                                    <h4 class="text-center mb-4">Parolanızı mı unuttunuz? </h4>
                                    <form id="password_reset" method="POST">
                                        <?=Security::csrf("forget")?>
                                        <div class="mb-3">
                                            <label><strong>E-Posta Adresiniz</strong></label>
                                            <input type="email" name="email" class="form-control" placeholder="example@mail.com">
                                        </div>
										<div class="text-center row">
                                            <div class="col-md-6">
												<a href="<?=ROOT.'/auth/login'?>" class="btn btn-success btn-block"><i class="fa-solid fa-right-to-bracket px-1"></i> Giriş Yap</a>
											</div>
											<div class="col-md-6">
												<button type="submit" id="reset_btn" class="btn btn-primary btn-block"><i class="fas fa-lock px-1"></i> Yeni Parola Talep Et</button>
											</div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
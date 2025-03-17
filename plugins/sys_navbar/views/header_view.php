<?php

use App\Core\Helpers\ActionFilterHelper;
?>
<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="container d-block my-0">
                <div class="d-flex align-items-center justify-content-sm-between justify-content-end">
                    <div class="header-left"></div>
                    <ul class="navbar-nav header-right ">
                        <li class="nav-item d-flex align-items-center"></li>
                        <li>
                            <div class="dropdown header-profile2 ">
                                <a class="nav-link " href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <div class="header-info2 d-flex align-items-center">
                                        <img src="<?=get_image("public/uploads/images/profiles/".$getUser->user_img)?>">
                                        <div class="d-flex align-items-center sidebar-info">
                                            <div><h6 class="font-w500 mb-0 ms-2"><?=$getUser->user_firstname . " " . $getUser->user_lastname?></h6></div><i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <?=ActionFilterHelper::doAction(plugin_id().'_profile_edit'); ?>
                                    <?=ActionFilterHelper::doAction(plugin_id().'_logout'); ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
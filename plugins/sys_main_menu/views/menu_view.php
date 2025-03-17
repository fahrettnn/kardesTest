<?php use App\Core\Helpers\UrlHelper; ?>
<div class="dlabnav border-right">
    <div class="dlabnav-scroll">
        <p class="menu-title style-1"></p>
        <ul class="metismenu" id="menu">
            <?php
            if (!empty($links)):
                foreach ($links as $link):
                    if (user_can($link->permission)):
                        $route = empty($link->childrens) ? ROOT.'/'.$link->slug : 'javascript:void(0);' ;
                        $active = UrlHelper::URL(0) == $link->slug ? 'mm-active' : '';
            ?>
            <li class="<?=$active?>">
                <a href="<?=$route?>" class="<?=!empty($link->childrens) ? 'has-arrow' : '';?>" aria-expanded="<?=!empty($link->childrens) ? 'true' : 'false';?>">
                    <i class="<?=$link->icon?>"></i>
                    <span class="nav-text"><?=ucfirst($link->title)?></span>
                </a>
                <?php if(!empty($link->childrens)): ?>
                <ul aria-expanded="<?=!empty($link->childrens) ? 'true' : 'false';?>">
                    <?php 
                    foreach ($link->childrens as $childrens): 
                        $active = UrlHelper::URL(1) == $childrens->slug ? 'mm-active' : '';
                    ?>
                    <li><a href="<?=ROOT.'/'.$link->slug.'/'.$childrens->slug?>" class="<?=$active?>"><?=ucfirst($childrens->title)?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </li>
            <?php endif; endforeach; endif; ?>
        </ul>
		
		<div class="plus-box">
			<div class="d-flex align-items-center">
                <h5 class="text-center text-primary">Kardeş Sondajcılık <br> Dosya Yönetim Sistemi</h5>
			</div>
            <!--<div id="kalanalan"></div>
            <button id="install-app" class="btn bg-primary btn-sm" style="display: block"><span class="text-white">Uygulamayı Yükle</span></button>-->
		</div>
    </div>
</div>
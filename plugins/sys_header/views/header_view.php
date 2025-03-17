<!DOCTYPE html><html lang="tr"><head>
<?php use App\Core\Helpers\ActionFilterHelper; ActionFilterHelper::doAction(plugin_id().'_headsTags');?>
</head><body>
<div id="preloader"><div class="lds-ripple"><div></div><div></div></div></div>
<div id="main-wrapper">
    <?php
    /** Navheader */
    ActionFilterHelper::doAction(plugin_id().'_navheader'); 
    /** chatbox */
    ActionFilterHelper::doAction(plugin_id().'_chatbox');
    /** Header */
    ActionFilterHelper::doAction(plugin_id().'_header');
    /** Menu */
    ActionFilterHelper::doAction(plugin_id().'_menu'); 
    ?>
    <div class="content-body">
        <div class="container">
            <?php if (!empty($section) or !empty($section_title)) { ?>
            <div class="row page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="javascript:void(0)"><?=$section ?></a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)"><?=$section_title?></a></li>
                </ol>
            </div>
            <?php } ?>
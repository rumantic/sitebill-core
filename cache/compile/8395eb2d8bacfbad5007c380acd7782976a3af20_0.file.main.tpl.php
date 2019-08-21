<?php
/* Smarty version 3.1.33, created on 2019-08-21 13:11:19
  from 'D:\OpenServer\domains\nplan\apps\admin\admin\template1\main.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5d18c730f220_88670108',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8395eb2d8bacfbad5007c380acd7782976a3af20' => 
    array (
      0 => 'D:\\OpenServer\\domains\\nplan\\apps\\admin\\admin\\template1\\main.tpl',
      1 => 1565709756,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:top_nav_notify.tpl' => 1,
    'file:sidebar.tpl' => 1,
  ),
),false)) {
function content_5d5d18c730f220_88670108 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
    <head>

        <?php if (@constant('SITE_ENCODING') != '') {?>
            <meta charset="<?php echo @constant('SITE_ENCODING');?>
" />
        <?php } else { ?>
            <meta charset="windows-1251" />
        <?php }?>
        <title>CMS Sitebill</title>


        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- basic styles -->

        <link href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/bootstrap.css" rel="stylesheet" />
        <!-- link href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" /-->
        <link href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/font-awesome.min.css" />

        <!--[if IE 7]>
          <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <!-- page specific plugin styles -->

        <!-- fonts -->

        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/ace-fonts.css" />
        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/colorbox.css" />
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/ace.min.css" />
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/ace-skins.min.css" />
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/css/styles.css" />
        <!--[if lte IE 8]>
          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->

        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/admin/admin/template/css/admin.css">

        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/jquery/jquery.3.3.1.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/jquery/jquery-migrate.min.js"><?php echo '</script'; ?>
>

        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/bootstrap/js/bootstrap.min.js"><?php echo '</script'; ?>
>

        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/bootstrap-editable/js/bootstrap-editable.min.js"><?php echo '</script'; ?>
>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/bootstrap-editable/css/bootstrap-editable.css" />
        <?php if ($_smarty_tpl->tpl_vars['ADMIN_NO_NANOAPI']->value == 1) {?>
        <?php } else { ?>
            <link href="https://www.sitebill.ru/css/nano.css" rel="stylesheet" type="text/css" />
            <?php echo '<script'; ?>
 src="https://www.sitebill.ru/js/nanoapi.js"><?php echo '</script'; ?>
>
            <?php echo '<script'; ?>
 src="https://www.sitebill.ru/js/nanoapi_beta.js"><?php echo '</script'; ?>
>
        <?php }?>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/js/interface.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/js/estate.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/js/jquery.tablesorter.min.js"><?php echo '</script'; ?>
>
        <link href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css"/>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/jqueryui/jquery-ui.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/sitebillcore.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/mycombobox.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/js/jquery.cookie.js"><?php echo '</script'; ?>
>
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/css/jquery-ui.custom.css" />
        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/system/css/mycombobox.css" />

<!-- <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/js/jquery.ui.datepicker.js"><?php echo '</script'; ?>
> -->
        <?php if ($_smarty_tpl->tpl_vars['ADMIN_NO_MAP_PROVIDERS']->value == 1) {?>
        <?php } else { ?>
            <?php if ($_smarty_tpl->tpl_vars['map_type']->value == 'yandex') {?>
                <?php echo '<script'; ?>
 type="text/javascript" src="https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU"><?php echo '</script'; ?>
>
            <?php } else { ?>
                <?php echo '<script'; ?>
 type="text/javascript" src="https://maps.google.com/maps/api/js<?php if ($_smarty_tpl->tpl_vars['g_api_key']->value != '') {?>?key=<?php echo $_smarty_tpl->tpl_vars['g_api_key']->value;
}?>"><?php echo '</script'; ?>
>
            <?php }?>
            <?php if (1 == 0) {
echo '<script'; ?>
 type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing,geometry"><?php echo '</script'; ?>
><?php }?>

        <?php }?>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/ace-extra.min.js"><?php echo '</script'; ?>
>


        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/jquery-ui-1.10.3.custom.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/jquery.ui.touch-punch.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/jquery.slimscroll.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/jquery.easy-pie-chart.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/jquery.sparkline.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/flot/jquery.flot.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/flot/jquery.flot.pie.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/flot/jquery.flot.resize.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/bootstrap-tag.min.js"><?php echo '</script'; ?>
>

        <!-- ace scripts -->

        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/ace-elements.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/assets/js/ace.min.js"><?php echo '</script'; ?>
>

        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['assets_folder']->value;?>
/css/custom.css" />
        
            <style>
                .modal.fade{top: -200%;}
                .inline-tags {
                    position: relative;
                    /*overflow-x: hidden;
                    overflow-y: auto;*/
                }
                .inline-tags .tags {
                    width: 40px;
                }
                .inline-tags .tags .tag {
                    padding-left: 22px;
                    padding-right: 9px;
                }
                .inline-tags .tags .tag .close {
                    left: 0;
                    right: auto;
                }
            </style>
        



        <?php echo '<script'; ?>
>
            var estate_folder = '<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
';
        <?php echo '</script'; ?>
>

    </head>
    <body onload="runDialog('homescript_etown_ru');
        <?php echo $_smarty_tpl->tpl_vars['onload']->value;?>
" class="">




        <div class="navbar" id="navbar">
            <?php echo '<script'; ?>
 type="text/javascript">
                try {
                                        ace.settings.check('navbar', 'fixed')
                                    } catch (e) {
                                    }
            <?php echo '</script'; ?>
>

            <div class="navbar-inner">
                <div class="container-fluid">
                    <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                        <span class="sr-only">Toggle sidebar</span>

                        <span class="icon-bar"></span>

                        <span class="icon-bar"></span>

                        <span class="icon-bar"></span>
                    </button>
                    <div class="brand">
                        <div class="dragon"></div>
                        <div class="ttl">
                            CMS Sitebill
                        </div>
                    </div>

                    <?php $_smarty_tpl->_subTemplateRender('file:top_nav_notify.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


                    <?php if (@constant('DEVMODE') == 1) {?>

                        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['apps']['childs']) {?>

                            <div class="modal custom_modal hide fade" id="myModalAPP">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h3><?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_APPLICATIONS']->value;?>
</h3>
                                </div>
                                <div class="modal-body">
                                    <ul>
                                        <?php $_smarty_tpl->_assignInScope('fletter', '');?>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_menua']->value['apps']['childs'], 'ama');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ama']->value) {
?>
                                            <?php if ($_smarty_tpl->tpl_vars['fletter']->value == '') {?>
                                                <?php $_smarty_tpl->_assignInScope('fletter', strtoupper(mb_substr($_smarty_tpl->tpl_vars['ama']->value['title'],0,1)));?>
                                                <li class="letter">
                                                    <?php echo $_smarty_tpl->tpl_vars['fletter']->value;?>

                                                </li>
                                            <?php } else { ?>
                                                <?php if ($_smarty_tpl->tpl_vars['fletter']->value != strtoupper(mb_substr($_smarty_tpl->tpl_vars['ama']->value['title'],0,1))) {?>
                                                    <?php $_smarty_tpl->_assignInScope('fletter', strtoupper(mb_substr($_smarty_tpl->tpl_vars['ama']->value['title'],0,1)));?>
                                                </ul>
                                                <ul>
                                                    <li class="letter">
                                                        <?php echo $_smarty_tpl->tpl_vars['fletter']->value;?>

                                                    </li>
                                                <?php }?>
                                            <?php }?>
                                            <li>
                                                <a <?php if (isset($_smarty_tpl->tpl_vars['ama']->value['childs']) && count($_smarty_tpl->tpl_vars['ama']->value['childs']) > 0) {?>data-toggle="dropdown"  class="dropdown-toggle" href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
" data-target="#"<?php } else { ?>href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
"<?php }?>><?php echo $_smarty_tpl->tpl_vars['ama']->value['title'];?>
</a>
                                            </li>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" class="btn" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['L_CLOSE']->value;?>
</a>
                                </div>
                            </div>
                        <?php }?>
                    <?php }?>				
                    <div class="pull-right">
                        <a href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/admin/" target="_blank" class="btn btn-small btn-warning"><i class="icon-dashboard"></i> Новая админка</a>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/" target="_blank" class="btn btn-small btn-primary"><i class="icon-eye-open"></i> <?php echo $_smarty_tpl->tpl_vars['L_SITE']->value;?>
</a>


                        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['apps']['childs']) {?>

                            <?php if (@constant('DEVMODE') == 1) {?>
                                <a href="#myModalAPP" role="button" class="btn" data-toggle="modal"><?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_APPLICATIONS']->value;?>
</a>
                            <?php } else { ?>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                                        <?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_APPLICATIONS']->value;?>

                                        <i class="icon-angle-down icon-on-right"></i>
                                    </button>

                                    <ul class="dropdown-menu">
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_menua']->value['apps']['childs'], 'ama');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ama']->value) {
?>
                                            <li>
                                                <a <?php if (isset($_smarty_tpl->tpl_vars['ama']->value['childs']) && count($_smarty_tpl->tpl_vars['ama']->value['childs']) > 0) {?>data-toggle="dropdown"  class="dropdown-toggle" href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
" data-target="#"<?php } else { ?>href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
"<?php }?>><?php echo $_smarty_tpl->tpl_vars['ama']->value['title'];?>
</a>
                                            </li>
                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    </ul>
                                </div>
                            <?php }?>
                        <?php }?>
                        <?php if (isset($_smarty_tpl->tpl_vars['custom_admin_entity_menu']->value) && count($_smarty_tpl->tpl_vars['custom_admin_entity_menu']->value) > 0) {?>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                                    <?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_ADDITIONAL_APPLICATIONS']->value;?>

                                    <i class="icon-angle-down icon-on-right"></i>
                                </button>

                                <ul class="dropdown-menu">
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['custom_admin_entity_menu']->value, 'custom_admin_entity');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['custom_admin_entity']->value) {
?>
                                        <li>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['custom_admin_entity']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['custom_admin_entity']->value['entity_title'];?>
</a>
                                        </li>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                </ul>
                            </div>   
                        <?php }?>   
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                                <i class="icon-globe icon-on-right"></i>
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/admin/?_lang=ru"><img src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/admin/admin/template/img/flag_ru.gif" alt="Русский" title="Русский"/> Русский</a>
                                </li>
                                <li>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/admin/?_lang=en"><img src="<?php echo $_smarty_tpl->tpl_vars['MAIN_URL']->value;?>
/apps/admin/admin/template/img/flag_en.png" alt="English" title="English"/> English</a>
                                </li>

                            </ul>
                        </div>

                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                                <i class="icon-question-sign icon-on-right"></i>
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="http://wiki.sitebill.ru/" target="_blank"><i class="icon-white icon-book"></i> База знаний</a>
                                </li>

                                <li>
                                    <a href="http://www.etown.ru/s/" target="_blank"><i class="icon-white icon-comment"></i> Форум</a>
                                </li>

                                <li>
                                    <a href="http://www.youtube.com/user/DMn1c" target="_blank"><i class="icon-white icon-film"></i> Видео-уроки</a>
                                </li>

                                <li>
                                    <a href="http://www.sitebill.ru/" target="_blank"><i class="icon-white icon-heart"></i> Наш сайт</a>
                                </li>

                                <li>
                                    <a href="https://play.google.com/store/apps/details?id=ru.sitebill.mobilecms" target="_blank"><i class="icon-white icon-camera"></i> Мобильное приложение</a>
                                </li>



                            </ul>
                        </div>


                    </div>

                </div><!-- /.container-fluid -->
            </div><!-- /.navbar-inner -->
        </div>


        <div class="main-container container-fluid">
            <?php $_smarty_tpl->_subTemplateRender('file:sidebar.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <?php echo '<script'; ?>
 type="text/javascript">

                    <?php echo '</script'; ?>
>

                    <ul class="breadcrumb">

                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['breadcrumbs_array']->value, 'crumb', false, NULL, 'bread', array (
  'first' => true,
  'last' => true,
  'index' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['crumb']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['index'];
$_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['total'];
?>
                            <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['first'] : null)) {?><i class="icon-home home-icon"></i><?php }?>
                            <li <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['last'] : null)) {?>class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['crumb']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['crumb']->value['title'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_bread']->value['last'] : null)) {?> <span class="divider"><i class="icon-angle-right arrow-icon"></i></span><?php }?></li>
                                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

                    </ul><!-- .breadcrumb -->

                    <!-- div class="nav-search" id="nav-search">
                            <form class="form-search">
                                    <span class="input-icon">
                                            <input type="text" placeholder="Search ..." class="input-small nav-search-input" id="nav-search-input" autocomplete="off" />
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
                            </form>
                    </div><!-- #nav-search -->
                    <!-- div class="pull-right"><?php if ($_smarty_tpl->tpl_vars['help_link']->value != '') {
echo $_smarty_tpl->tpl_vars['help_link']->value;
}?></div-->
                </div>

                <div class="page-content">
                    <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

                </div>

            </div>
        </div>
        <?php echo $_smarty_tpl->tpl_vars['messenger_widget']->value;?>

        <a href="#" class="scrollup"><?php echo $_smarty_tpl->tpl_vars['LT_SCROLLUP']->value;?>
</a>
    </body>
</html><?php }
}

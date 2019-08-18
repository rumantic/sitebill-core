<!DOCTYPE html>
<html lang="en">
    <head>

        {if $smarty.const.SITE_ENCODING != '' }
            <meta charset="{$smarty.const.SITE_ENCODING}" />
        {else}
            <meta charset="windows-1251" />
        {/if}
        <title>CMS Sitebill</title>


        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- basic styles -->

        <link href="{$assets_folder}/assets/css/bootstrap.css" rel="stylesheet" />
        <!-- link href="{$MAIN_URL}/apps/system/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" /-->
        <link href="{$assets_folder}/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{$assets_folder}/assets/css/font-awesome.min.css" />

        <!--[if IE 7]>
          <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
        <![endif]-->

        <!-- page specific plugin styles -->

        <!-- fonts -->

        <link rel="stylesheet" href="{$assets_folder}/assets/css/ace-fonts.css" />
        <!-- ace styles -->
        <link rel="stylesheet" href="{$assets_folder}/assets/css/colorbox.css" />
        <link rel="stylesheet" href="{$assets_folder}/assets/css/ace.min.css" />
        <link rel="stylesheet" href="{$assets_folder}/assets/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="{$assets_folder}/assets/css/ace-skins.min.css" />
        <link rel="stylesheet" href="{$assets_folder}/assets/css/styles.css" />
        <!--[if lte IE 8]>
          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
        <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->

        <link rel="stylesheet" href="{$MAIN_URL}/apps/admin/admin/template/css/admin.css">

        <script type="text/javascript" src="{$MAIN_URL}/apps/system/js/jquery/jquery.3.3.1.js"></script>
        <script type="text/javascript" src="{$MAIN_URL}/apps/system/js/jquery/jquery-migrate.min.js"></script>

        <script src="{$MAIN_URL}/apps/system/js/bootstrap/js/bootstrap.min.js"></script>

        <script src="{$MAIN_URL}/apps/system/js/bootstrap-editable/js/bootstrap-editable.min.js"></script>
        <link rel="stylesheet" href="{$MAIN_URL}/apps/system/js/bootstrap-editable/css/bootstrap-editable.css" />
        {if $ADMIN_NO_NANOAPI==1}
        {else}
            <link href="https://www.sitebill.ru/css/nano.css" rel="stylesheet" type="text/css" />
            <script src="https://www.sitebill.ru/js/nanoapi.js"></script>
            <script src="https://www.sitebill.ru/js/nanoapi_beta.js"></script>
        {/if}
        <script src="{$MAIN_URL}/js/interface.js"></script>
        <script src="{$MAIN_URL}/js/estate.js"></script>
        <script type="text/javascript" src="{$MAIN_URL}/js/jquery.tablesorter.min.js"></script>
        <link href="{$MAIN_URL}/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="{$MAIN_URL}/apps/system/js/jqueryui/jquery-ui.js"></script>
        <script type="text/javascript" src="{$MAIN_URL}/apps/system/js/sitebillcore.js"></script>
        <script type="text/javascript" src="{$MAIN_URL}/apps/system/js/mycombobox.js"></script>
        <script type="text/javascript" src="{$MAIN_URL}/apps/system/js/jquery.cookie.js"></script>
        <link rel="stylesheet" href="{$MAIN_URL}/apps/system/css/jquery-ui.custom.css" />
        <link rel="stylesheet" href="{$MAIN_URL}/apps/system/css/mycombobox.css" />

<!-- <script type="text/javascript" src="{$MAIN_URL}/js/jquery.ui.datepicker.js"></script> -->
        {if $ADMIN_NO_MAP_PROVIDERS==1}
        {else}
            {if $map_type=='yandex'}
                <script type="text/javascript" src="https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU"></script>
            {else}
                <script type="text/javascript" src="https://maps.google.com/maps/api/js{if $g_api_key!=''}?key={$g_api_key}{/if}"></script>
            {/if}
            {if 1==0}<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing,geometry"></script>{/if}

        {/if}
        <script src="{$assets_folder}/assets/js/ace-extra.min.js"></script>


        <script src="{$assets_folder}/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="{$assets_folder}/assets/js/jquery.ui.touch-punch.min.js"></script>
        <script src="{$assets_folder}/assets/js/jquery.slimscroll.min.js"></script>
        <script src="{$assets_folder}/assets/js/jquery.easy-pie-chart.min.js"></script>
        <script src="{$assets_folder}/assets/js/jquery.sparkline.min.js"></script>
        <script src="{$assets_folder}/assets/js/flot/jquery.flot.min.js"></script>
        <script src="{$assets_folder}/assets/js/flot/jquery.flot.pie.min.js"></script>
        <script src="{$assets_folder}/assets/js/flot/jquery.flot.resize.min.js"></script>
        <script src="{$assets_folder}/assets/js/bootstrap-tag.min.js"></script>

        <!-- ace scripts -->

        <script src="{$assets_folder}/assets/js/ace-elements.min.js"></script>
        <script src="{$assets_folder}/assets/js/ace.min.js"></script>

        <link rel="stylesheet" href="{$assets_folder}/css/custom.css" />
        {literal}
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
        {/literal}



        <script>
            var estate_folder = '{$estate_folder}';
        </script>

    </head>
    <body onload="runDialog('homescript_etown_ru');
        {$onload}" class="">




        <div class="navbar" id="navbar">
            <script type="text/javascript">
                {literal}try {
                                        ace.settings.check('navbar', 'fixed')
                                    } catch (e) {
                                    }{/literal}
            </script>

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

                    {include file='top_nav_notify.tpl'}


                    {if $smarty.const.DEVMODE==1}

                        {if $admin_menua.apps.childs}

                            <div class="modal custom_modal hide fade" id="myModalAPP">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h3>{$L_ADMIN_MENU_APPLICATIONS}</h3>
                                </div>
                                <div class="modal-body">
                                    <ul>
                                        {assign var=fletter value=''}
                                        {foreach from=$admin_menua.apps.childs item=ama}
                                            {if $fletter==''}
                                                {assign var=fletter value=$ama.title|mb_substr:0:1|strtoupper}
                                                <li class="letter">
                                                    {$fletter}
                                                </li>
                                            {else}
                                                {if $fletter ne $ama.title|mb_substr:0:1|strtoupper}
                                                    {assign var=fletter value=$ama.title|mb_substr:0:1|strtoupper}
                                                </ul>
                                                <ul>
                                                    <li class="letter">
                                                        {$fletter}
                                                    </li>
                                                {/if}
                                            {/if}
                                            <li>
                                                <a {if isset($ama.childs) && $ama.childs|count>0}data-toggle="dropdown"  class="dropdown-toggle" href="{$ama.href}" data-target="#"{else}href="{$ama.href}"{/if}>{$ama.title}</a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <a href="#" class="btn" data-dismiss="modal">{$L_CLOSE}</a>
                                </div>
                            </div>
                        {/if}
                    {/if}				
                    <div class="pull-right">
                        <a href="{$MAIN_URL}/apps/admin/" target="_blank" class="btn btn-small btn-warning"><i class="icon-dashboard"></i> Новая админка</a>
                        <a href="{$MAIN_URL}/" target="_blank" class="btn btn-small btn-primary"><i class="icon-eye-open"></i> {$L_SITE}</a>


                        {if $admin_menua.apps.childs}

                            {if $smarty.const.DEVMODE==1}
                                <a href="#myModalAPP" role="button" class="btn" data-toggle="modal">{$L_ADMIN_MENU_APPLICATIONS}</a>
                            {else}
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                                        {$L_ADMIN_MENU_APPLICATIONS}
                                        <i class="icon-angle-down icon-on-right"></i>
                                    </button>

                                    <ul class="dropdown-menu">
                                        {foreach from=$admin_menua.apps.childs item=ama}
                                            <li>
                                                <a {if isset($ama.childs) && $ama.childs|count>0}data-toggle="dropdown"  class="dropdown-toggle" href="{$ama.href}" data-target="#"{else}href="{$ama.href}"{/if}>{$ama.title}</a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}
                        {/if}
                        {if isset($custom_admin_entity_menu) && $custom_admin_entity_menu|count>0}
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                                    {$L_ADMIN_MENU_ADDITIONAL_APPLICATIONS}
                                    <i class="icon-angle-down icon-on-right"></i>
                                </button>

                                <ul class="dropdown-menu">
                                    {foreach from=$custom_admin_entity_menu item=custom_admin_entity}
                                        <li>
                                            <a href="{$custom_admin_entity.href}">{$custom_admin_entity.entity_title}</a>
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>   
                        {/if}   
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                                <i class="icon-globe icon-on-right"></i>
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{$MAIN_URL}/admin/?_lang=ru"><img src="{$MAIN_URL}/apps/admin/admin/template/img/flag_ru.gif" alt="Русский" title="Русский"/> Русский</a>
                                </li>
                                <li>
                                    <a href="{$MAIN_URL}/admin/?_lang=en"><img src="{$MAIN_URL}/apps/admin/admin/template/img/flag_en.png" alt="English" title="English"/> English</a>
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
            {include file='sidebar.tpl'}
            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">

                    </script>

                    <ul class="breadcrumb">

                        {foreach from=$breadcrumbs_array item=crumb name=bread}
                            {if $smarty.foreach.bread.first}<i class="icon-home home-icon"></i>{/if}
                            <li {if $smarty.foreach.bread.last}class="active"{/if}><a href="{$crumb.href}">{$crumb.title}</a>{if !$smarty.foreach.bread.last} <span class="divider"><i class="icon-angle-right arrow-icon"></i></span>{/if}</li>
                                {/foreach}

                    </ul><!-- .breadcrumb -->

                    <!-- div class="nav-search" id="nav-search">
                            <form class="form-search">
                                    <span class="input-icon">
                                            <input type="text" placeholder="Search ..." class="input-small nav-search-input" id="nav-search-input" autocomplete="off" />
                                            <i class="icon-search nav-search-icon"></i>
                                    </span>
                            </form>
                    </div><!-- #nav-search -->
                    <!-- div class="pull-right">{if $help_link!=''}{$help_link}{/if}</div-->
                </div>

                <div class="page-content">
                    {$content}
                </div>

            </div>
        </div>
        {$messenger_widget}
        <a href="#" class="scrollup">{$LT_SCROLLUP}</a>
    </body>
</html>
<?php
/* Smarty version 3.1.33, created on 2019-08-21 13:11:20
  from 'D:\OpenServer\domains\nplan\apps\admin\admin\template1\sidebar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d5d18c89afde1_31532878',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c33f267946cb25ee98fd4cc457cc3c12c03de39e' => 
    array (
      0 => 'D:\\OpenServer\\domains\\nplan\\apps\\admin\\admin\\template1\\sidebar.tpl',
      1 => 1565709756,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:local_data_menu.tpl' => 1,
  ),
),false)) {
function content_5d5d18c89afde1_31532878 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="sidebar" class="sidebar                  responsive" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">            
    <ul class="nav nav-list">
        <li>
            <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/"><i class="icon-home"></i> <span class="menu-text"><?php echo $_smarty_tpl->tpl_vars['L_HOME']->value;?>
</span></a>
        </li>

        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['data']) {?>

            <?php $_smarty_tpl->_assignInScope('local_data_menu', ((string)@constant('SITEBILL_DOCUMENT_ROOT'))."/apps/admin/admin/template1/local_data_menu.tpl");?>
            <?php if (file_exists($_smarty_tpl->tpl_vars['local_data_menu']->value)) {?>
                <?php $_smarty_tpl->_subTemplateRender('file:local_data_menu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php } else { ?>
                <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['data']['active']) {?>class="active open"<?php }?>>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=data">
                        <i class="icon-book"></i> <span class="menu-text"><?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_AUTOADVERTS']->value;?>
</span>
                    </a>
                    <!-- 
                    <?php if (count($_smarty_tpl->tpl_vars['admin_menua']->value['datamain']['childs']['data']['childs']) > 0) {?>
                    <ul class="submenu">
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_menua']->value['datamain']['childs']['data']['childs'], 'ama');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ama']->value) {
?>
                          <li <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                          <a href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['ama']->value['title'];?>
</a>
                          </li>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        </ul>
                    <?php }?> 
                    -->


                    <?php if (1 == 0) {?>
                        <!-- ul class="submenu">
                        
                          <li <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                          <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=data">Актуальные</a>
                          </li>

                          <li <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                          <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=data">На прозвон</a>
                          </li>

                          <li <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                          <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=data">Не дозвонились</a>
                          </li>

                          <li <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                          <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=data">Модерация</a>
                          </li>

                          <li <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                          <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=data">Архив</a>
                          </li>
                          
                        </ul-->
                    <?php }?>
                </li>
            <?php }?>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['client']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['client']['active']) {?>class="active open"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=client"><i class="icon- ace-icon fa fa-heart bigger-125"></i> <span class="menu-text"><?php if ($_smarty_tpl->tpl_vars['L_CLIENT_MENU']->value != '') {
echo $_smarty_tpl->tpl_vars['L_CLIENT_MENU']->value;
} else { ?>Клиенты<?php }?></span></a></li>
            <?php }?>


        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['references']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['references']['active']) {?>class="active open"<?php }?>>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-globe"></i>
                    <span class="menu-text"> <?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_REFERENCES']->value;?>
 </span>
                    <b class="arrow icon-angle-down"></b>
                </a>

                <ul class="submenu">

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_menua']->value['references']['childs'], 'ama');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ama']->value) {
?>
                        <li <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['ama']->value['title'];?>
</a>
                        </li>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </ul>
            </li>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['components']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['components']['active']) {?>class="active open"<?php }?>>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-legal"></i>
                    <span class="menu-text"> Компоненты </span>
                    <b class="arrow icon-angle-down"></b>
                </a>

                <ul class="submenu">

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_menua']->value['components']['childs'], 'ama');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ama']->value) {
?>
                        <li  <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['ama']->value['title'];?>
</a>
                        </li>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </ul>
            </li>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['content']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['content']['active']) {?>class="active open"<?php }?>>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-coffee"></i>
                    <span class="menu-text"> <?php if ($_smarty_tpl->tpl_vars['L_CONTENT_MENU']->value != '') {
echo $_smarty_tpl->tpl_vars['L_CONTENT_MENU']->value;
} else { ?>Content<?php }?> </span>
                    <b class="arrow icon-angle-down"></b>
                </a>

                <ul class="submenu">

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_menua']->value['content']['childs'], 'ama');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ama']->value) {
?>
                        <li  <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['ama']->value['title'];?>
</a>
                        </li>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </ul>
            </li>
        <?php }?>



        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['apps']['childs']['config']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['config']['active']) {?>class="active open"<?php }?>>
                <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=config"><i class="icon-cog"></i> <span class="menu-text"><?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_SETTINGS']->value;?>
</span></a>
            </li>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['sitebill']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['sitebill']['active']) {?>class="active open"<?php }?>>
                <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=sitebill"><i class="icon-refresh"></i> 
                    <span class="menu-text"><?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_UPDATES']->value;?>
</span>
                </a>
            </li>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['user']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['user']['active']) {?>class="active open"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=user"><i class="icon-user"></i> <span class="menu-text"><?php echo $_smarty_tpl->tpl_vars['L_USER_MENU']->value;?>
</span></a></li>
                <?php }?>

        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['structure']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['structure']['active']) {?>class="active open"<?php }?>>
                <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=structure"><i class="icon-th-list"></i> <span class="menu-text"><?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_STRUCTURE']->value;?>
</span></a></li>
                <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['table']) {?>
        <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['table']['active']) {?>class="active open"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/admin/?action=table">
                <i class="icon-edit"></i> 
                <span class="menu-text"><?php if ($_smarty_tpl->tpl_vars['L_TABLE_MENU']->value != '') {
echo $_smarty_tpl->tpl_vars['L_TABLE_MENU']->value;
} else { ?>Form editor<?php }?></span></a></li>
        <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['access']) {?>
            <li <?php if ($_smarty_tpl->tpl_vars['admin_menua']->value['access']['active']) {?>class="active open"<?php }?>>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-group"></i>
                    <span class="menu-text"> <?php echo $_smarty_tpl->tpl_vars['L_ADMIN_MENU_ACCESS']->value;?>
 </span>
                    <b class="arrow icon-angle-down"></b>
                </a>

                <ul class="submenu">

                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['admin_menua']->value['access']['childs'], 'ama');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['ama']->value) {
?>
                        <li  <?php if ($_smarty_tpl->tpl_vars['ama']->value['active']) {?>class="active"<?php }?>>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['ama']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['ama']->value['title'];?>
</a>
                        </li>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </ul>
            </li>
        <?php }?>

        <li>
            <a href="#" class="dropdown-toggle">
                <i class="icon-desktop"></i>
                <span class="menu-text"> Недавние </span>

                <b class="arrow icon-angle-down"></b>
            </a>

            <ul class="submenu">
                <?php
$__section_le_0_loop = (is_array(@$_loop=$_SESSION['recently_apps']) ? count($_loop) : max(0, (int) $_loop));
$__section_le_0_total = min(($__section_le_0_loop - 0), 10);
$_smarty_tpl->tpl_vars['__smarty_section_le'] = new Smarty_Variable(array());
if ($__section_le_0_total !== 0) {
for ($__section_le_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_le']->value['index'] = 0; $__section_le_0_iteration <= $__section_le_0_total; $__section_le_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_le']->value['index']++){
?>
                    <li><?php echo $_SESSION['recently_apps'][(isset($_smarty_tpl->tpl_vars['__smarty_section_le']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_le']->value['index'] : null)];?>
</li>
                    <?php
}
}
?>
            </ul>
        </li>

        <?php if ($_smarty_tpl->tpl_vars['data_category_tree']->value != '') {?>
            <li>
                <a href="#" class="dropdown-toggle">
                    <i class="icon-folder-close"></i>
                    <span class="menu-text"> Категории </span>

                    <b class="arrow icon-angle-down"></b>
                </a>

                <div class="submenu">
                    <div class=" nolinedotted"><?php echo $_smarty_tpl->tpl_vars['data_category_tree']->value;?>
</div>
                </div>
            </li>
        <?php }?>

        <li>
            <a href="https://play.google.com/store/apps/details?id=ru.sitebill.mobilecms" target="_blank">
                <i class="icon-camera"></i>
                <span class="menu-text">Мобильное фото</span></a>
        </li>


    </ul>


    <div class="sidebar-collapse" id="sidebar-collapse">
        <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
    </div>

    
        <?php echo '<script'; ?>
 type="text/javascript">
            try {
                ace.settings.check('sidebar', 'collapsed')
            } catch (e) {
            }
        <?php echo '</script'; ?>
>
    

</div>
<?php }
}

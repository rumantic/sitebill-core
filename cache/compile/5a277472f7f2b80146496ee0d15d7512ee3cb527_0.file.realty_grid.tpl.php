<?php
/* Smarty version 3.1.33, created on 2019-08-30 10:08:09
  from 'D:\OpenServer\domains\nplan\template\frontend\realia\realty_grid.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d68cb59a52e44_04353905',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5a277472f7f2b80146496ee0d15d7512ee3cb527' => 
    array (
      0 => 'D:\\OpenServer\\domains\\nplan\\template\\frontend\\realia\\realty_grid.tpl',
      1 => 1565709775,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:realty_grid_thumbs.tpl' => 1,
    'file:realty_grid_list.tpl' => 1,
    'file:search_form.tpl' => 1,
    'file:right_special.tpl' => 1,
  ),
),false)) {
function content_5d68cb59a52e44_04353905 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/apps/system/js/json2.js" type="text/javascript"><?php echo '</script'; ?>
>

<div class="row">
    <div class="span9">


        <?php if (count($_smarty_tpl->tpl_vars['grid_items']->value) == 0) {?>     
            <h1 class="page-header"><?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['_e'][0], array( array('t'=>"Ничего не удалось найти"),$_smarty_tpl ) );?>
</h1>
        <?php } else { ?>
            <h1 class="page-header"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>

            <?php if ($_REQUEST['page'] == 1 || $_REQUEST['page'] == '') {?>
                <span itemprop="description"><?php echo $_smarty_tpl->tpl_vars['description']->value;?>
</span>
            <?php }?>


            <?php $_smarty_tpl->_assignInScope('lang_topic_name', "name_".((string)$_SESSION['_lang']));?>

            <div id="map" style="margin: 10px 0;">
                <iframe src="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/js/ajax.php?action=iframe_map&<?php echo $_smarty_tpl->tpl_vars['QUERY_STRING']->value;?>
" style="border: 0px;" border="0" width="100%" height="100%"></iframe>
            </div>

            <?php if ($_SESSION['grid_type'] == 'thumbs') {?>
                <?php $_smarty_tpl->_subTemplateRender('file:realty_grid_thumbs.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php } else { ?>
                <?php $_smarty_tpl->_subTemplateRender('file:realty_grid_list.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <?php }?>




            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pager_array']->value['pages'], 'pager_page');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['pager_page']->value) {
?>
                <?php if ($_smarty_tpl->tpl_vars['pager_page']->value['current'] == 1) {?>
                    <?php $_smarty_tpl->_assignInScope('__curpagenr', $_smarty_tpl->tpl_vars['pager_page']->value['text']);?>
                <?php }?>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

            <?php if ($_smarty_tpl->tpl_vars['__curpagenr']->value-3 < 1) {?>
                <?php $_smarty_tpl->_assignInScope('__startnr', 1);?>
                <?php $_smarty_tpl->_assignInScope('__leftsep', 0);?>
            <?php } else { ?>
                <?php $_smarty_tpl->_assignInScope('__startnr', $_smarty_tpl->tpl_vars['__curpagenr']->value-3);?>
                <?php $_smarty_tpl->_assignInScope('__leftsep', 1);?>
            <?php }?>

            <?php if ($_smarty_tpl->tpl_vars['__curpagenr']->value+3 > count($_smarty_tpl->tpl_vars['pager_array']->value['pages'])) {?>
                <?php $_smarty_tpl->_assignInScope('__endnr', count($_smarty_tpl->tpl_vars['pager_array']->value['pages']));?>
                <?php $_smarty_tpl->_assignInScope('__rightsep', 0);?>
            <?php } else { ?>
                <?php $_smarty_tpl->_assignInScope('__endnr', $_smarty_tpl->tpl_vars['__curpagenr']->value+3);?>
                <?php $_smarty_tpl->_assignInScope('__rightsep', 1);?>
            <?php }?>

            <?php if (count($_smarty_tpl->tpl_vars['pager_array']->value['pages']) > 1) {?>
                <div class="pagination pagination-centered">
                    <ul>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager_array']->value['ppn']['href'];?>
">&lsaquo;</a></li>
                            <?php if ($_smarty_tpl->tpl_vars['__leftsep']->value == 1) {?>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager_array']->value['pages'][1]['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['pager_array']->value['pages'][1]['text'];?>
</a></li>
                            <li><a href="javascript:void(0);" class="selected">...</a></li>
                            <?php }?>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pager_array']->value['pages'], 'pager_page');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['pager_page']->value) {
?>
                                <?php if ($_smarty_tpl->tpl_vars['pager_page']->value['text'] >= $_smarty_tpl->tpl_vars['__startnr']->value && $_smarty_tpl->tpl_vars['pager_page']->value['text'] <= $_smarty_tpl->tpl_vars['__endnr']->value) {?>
                                <li<?php if ($_smarty_tpl->tpl_vars['pager_page']->value['current'] == 1) {?> class="active"<?php }?>><a href="<?php echo $_smarty_tpl->tpl_vars['pager_page']->value['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['pager_page']->value['text'];?>
</a></li>
                                <?php }?>
                            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            <?php if ($_smarty_tpl->tpl_vars['__rightsep']->value == 1) {?>
                            <li><a href="javascript:void(0);" class="selected">...</a></li>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager_array']->value['pages'][count($_smarty_tpl->tpl_vars['pager_array']->value['pages'])]['href'];?>
"><?php echo $_smarty_tpl->tpl_vars['pager_array']->value['pages'][count($_smarty_tpl->tpl_vars['pager_array']->value['pages'])]['text'];?>
</a></li>
                            <?php }?>
                        <li><a href="<?php echo $_smarty_tpl->tpl_vars['pager_array']->value['npn']['href'];?>
">&rsaquo;</a></li>
                    </ul>
                </div>
            <?php }?>

        <?php }?>
    </div>

    <div class="sidebar span3">
        <?php $_smarty_tpl->_subTemplateRender('file:search_form.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <br/>
        <?php $_smarty_tpl->_subTemplateRender('file:right_special.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    </div>
</div>
<?php }
}

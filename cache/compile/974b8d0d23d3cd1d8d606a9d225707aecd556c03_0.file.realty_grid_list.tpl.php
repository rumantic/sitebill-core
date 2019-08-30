<?php
/* Smarty version 3.1.33, created on 2019-08-30 10:08:10
  from 'D:\OpenServer\domains\nplan\template\frontend\realia\realty_grid_list.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d68cb5a1a3d29_54536306',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '974b8d0d23d3cd1d8d606a9d225707aecd556c03' => 
    array (
      0 => 'D:\\OpenServer\\domains\\nplan\\template\\frontend\\realia\\realty_grid_list.tpl',
      1 => 1565709775,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:realty_grid_filter.tpl' => 1,
  ),
),false)) {
function content_5d68cb5a1a3d29_54536306 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\OpenServer\\domains\\nplan\\apps\\third\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
$_smarty_tpl->_assignInScope('lang_data_text', "text_".((string)$_SESSION['_lang']));?>

<div class="properties-rows">

    <?php $_smarty_tpl->_subTemplateRender('file:realty_grid_filter.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <div class="row">
        <?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['grid_items']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
            <div class="property span9<?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['bold_status'] == 1) {?> grid_list_bold<?php }
if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['premium_status'] == 1) {?> grid_list_premium<?php }
if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['vip_status'] == 1) {?> grid_list_vip<?php }?>">
                <div class="row">
                    <div class="image span3">
                        <div class="content">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['href'];?>
"></a>
                            <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['img'] != '') {?>
                                <img src="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/img/data/<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['img'][0]['preview'];?>
" class="previewi">
                            <?php } else { ?>
                                <img src="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/template/frontend/realia/img/no_foto_270x200.png" class="previewi">
                            <?php }?>
                        </div><!-- /.content -->
                    </div><!-- /.image -->

                    <div class="body span6">
                        <div class="title-price row">
                            <div class="title span4">
                                <h2>
                                    <?php if (isset($_SESSION['favorites'])) {?>
                                        <?php if (in_array($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id'],$_SESSION['favorites'])) {?>
                                            <a class="fav-rem" alt="<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['L_DELETEFROMFAVORITES']->value;?>
" href="#remove_from_favorites"></a>
                                        <?php } else { ?>
                                            <a class="fav-add" alt="<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['L_ADDTOFAVORITES']->value;?>
" href="#add_to_favorites"></a>
                                        <?php }?>
                                    <?php } else { ?>
                                        <a class="fav-add" alt="<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['id'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['L_ADDTOFAVORITES']->value;?>
" href="#add_to_favorites"></a>
                                    <?php }?>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['href'];?>
">
                                        <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['city'] != '') {?> <?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['city'];
if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['street'] != '') {?>, <?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['street'];
if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['number'] != '') {?>, <?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['number'];
}
}?>
                                        <?php } else { ?> <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['street'] != '') {?> <?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['street'];
if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['number'] != '') {?>, <?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['number'];
}?> <?php }?>
                                            <?php }?>
                                        </a>
                                    </h2>
                                </div><!-- /.title -->
                                <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['price_discount'] > 0) {?>
                                    <div class="price">
                                        <?php echo number_format($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['price_discount'],0,","," ");?>
 <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['currency_name'] != '') {
echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['currency_name'];
}?>
                                        <div class="price_discount_list"><?php echo number_format($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['price'],0,","," ");?>
 <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['currency_name'] != '') {
echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['currency_name'];
}?></div>
                                    </div><!-- /.price -->
                                <?php } else { ?>
                                    <div class="price"><?php echo number_format($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['price'],0,","," ");?>
 <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['currency_name'] != '') {
echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['currency_name'];
}?></div>
                                <?php }?>
                            </div><!-- /.title -->

                            <div class="location"><?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['topic_info'][$_smarty_tpl->tpl_vars['lang_topic_name']->value] != '') {
echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['topic_info'][$_smarty_tpl->tpl_vars['lang_topic_name']->value];
} else {
echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['type_sh'];
}?></div><!-- /.location -->
                            <p>
                                <?php if ($_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)][$_smarty_tpl->tpl_vars['lang_data_text']->value] != '') {?>
                                    <?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)][$_smarty_tpl->tpl_vars['lang_data_text']->value]),200);?>

                                <?php } else { ?>
                                    <?php echo smarty_modifier_truncate(preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['text']),200);?>

                                <?php }?>
                            </p>
                            <div class="area">
                                <span class="key"><?php echo $_smarty_tpl->tpl_vars['L_SQUARE']->value;?>
 <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['_e'][0], array( array('t'=>"м"),$_smarty_tpl ) );?>
<sup>2</sup>:</span><!-- /.key -->
                                <span class="value"><?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['square_all'];?>
/<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['square_live'];?>
/<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['square_kitchen'];?>
</span><!-- /.value -->
                            </div><!-- /.area -->
                            <div class="area">
                                <span class="key"><?php echo $_smarty_tpl->tpl_vars['L_FLOOR']->value;?>
:</span><!-- /.key -->
                                <span class="value"><?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['floor'];?>
/<?php echo $_smarty_tpl->tpl_vars['grid_items']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['floor_count'];?>
</span><!-- /.value -->
                            </div><!-- /.area -->
                        </div><!-- /.body -->
                    </div><!-- /.property -->
                </div><!-- /.row -->
                <?php
}
}
?>
                </div>
            </div><?php }
}

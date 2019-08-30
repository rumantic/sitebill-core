<?php
/* Smarty version 3.1.33, created on 2019-08-30 10:08:10
  from 'D:\OpenServer\domains\nplan\template\frontend\realia\realty_grid_filter.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5d68cb5a61c428_04389913',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'eb888ef2e996ea489afdd6a1244b0b60e7aec11c' => 
    array (
      0 => 'D:\\OpenServer\\domains\\nplan\\template\\frontend\\realia\\realty_grid_filter.tpl',
      1 => 1565709775,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5d68cb5a61c428_04389913 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
>
    var sort_links = [];
    var core_link = '<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
';
        function run() {
            var inputSortBy = $('#inputSortBy option:selected');
            var inputOrder = $('#inputOrder option:selected');
            core_link = core_link + '&order=' + inputSortBy.attr('data-id') + '&asc=' + inputOrder.attr('data-id');
            window.location = core_link;
        }
        ;
    
        $(document).ready(function () {
            $('#inputSortBy').change(function () {
                run();
            });
            $('#inputOrder').change(function () {
                run();
            });
        });

    
<?php echo '</script'; ?>
>

<div class="filter">
    <div class="spec_grid_info">
        <?php echo $_smarty_tpl->tpl_vars['L_FIND_TOTAL']->value;?>
: <b><?php echo $_smarty_tpl->tpl_vars['_total_records']->value;?>
</b>
        <div class="viewtype_buttons">
            <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&grid_type=list" class="list_view<?php if ($_SESSION['grid_type'] == 'list') {?> active<?php }?>" rel="nofollow"><i class="icon-align-justify"></i></a>
            <a href="<?php echo $_smarty_tpl->tpl_vars['estate_folder']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['url']->value;?>
&grid_type=thumbs" class="thumbs_view<?php if ($_SESSION['grid_type'] == 'thumbs') {?> active<?php }?>" rel="nofollow"><i class="icon-th"></i></a>
        </div>
    </div>
    <form action="?" method="get" class="form-horizontal">
        <div class="control-group">
            <div class="controls">
                <select id="inputSortBy">
                    <option data-id=""><?php echo $_smarty_tpl->tpl_vars['LT_SORTBY']->value;?>
</option>
                    <option data-id="type"<?php if ($_REQUEST['order'] == 'type') {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['L_TYPE']->value;?>
</option>
                    <option data-id="city"<?php if ($_REQUEST['order'] == 'city') {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['L_CITY']->value;?>
</option>
                    <option data-id="district"<?php if ($_REQUEST['order'] == 'district') {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['L_DISTRICT']->value;?>
</option>
                    <option data-id="street"<?php if ($_REQUEST['order'] == 'street') {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['L_STREET']->value;?>
</option>
                    <option data-id="price"<?php if ($_REQUEST['order'] == 'price') {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['L_PRICE']->value;?>
</option>
                </select>
            </div><!-- /.controls -->
        </div><!-- /.control-group -->

        <div class="control-group">
            <div class="controls">
                <select id="inputOrder">
                    <option data-id=""><i class="icon-search"></i><?php echo $_smarty_tpl->tpl_vars['LT_ORDER']->value;?>
</option>
                    <option data-id="asc"<?php if ($_REQUEST['asc'] == 'asc') {?> selected="selected"<?php }?>><i class="icon-search"></i><?php echo $_smarty_tpl->tpl_vars['LT_ORDER_UP']->value;?>
</option>
                    <option data-id="desc"<?php if ($_REQUEST['asc'] == 'desc') {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LT_ORDER_DOWN']->value;?>
</option>
                </select>
            </div><!-- /.controls -->
        </div><!-- /.control-group -->
    </form>
</div><!-- /.filter --><?php }
}

<?php
/* Smarty version 3.1.47, created on 2022-12-29 17:16:26
  from 'C:\laragon\www\prestashop\4admin_1537\themes\new-theme\template\components\layout\quick_access.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.47',
  'unifunc' => 'content_63adbd5a8755e0_91143198',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '213f9b8cc3df4ef5f50b4351f965f3d48cb885c6' => 
    array (
      0 => 'C:\\laragon\\www\\prestashop\\4admin_1537\\themes\\new-theme\\template\\components\\layout\\quick_access.tpl',
      1 => 1672316996,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63adbd5a8755e0_91143198 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="dropdown quick-accesses">
  <button class="btn btn-link btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="quick_select">
    <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Quick Access','d'=>'Admin.Navigation.Header'),$_smarty_tpl ) );?>

  </button>
  <div class="dropdown-menu">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['quick_access']->value, 'quick');
$_smarty_tpl->tpl_vars['quick']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['quick']->value) {
$_smarty_tpl->tpl_vars['quick']->do_else = false;
?>
      <a class="dropdown-item quick-row-link<?php ob_start();
echo $_smarty_tpl->tpl_vars['quick']->value['link'];
$_prefixVariable2 = ob_get_clean();
if ($_smarty_tpl->tpl_vars['link']->value->matchQuickLink($_prefixVariable2)) {
$_smarty_tpl->_assignInScope('matchQuickLink', $_smarty_tpl->tpl_vars['quick']->value['id_quick_access']);?> active<?php }?>"
         href="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_smarty_tpl->tpl_vars['quick']->value['link'],'html','UTF-8' ));?>
"
        <?php if ($_smarty_tpl->tpl_vars['quick']->value['new_window']) {?> target="_blank"<?php }?>
         data-item="<?php echo $_smarty_tpl->tpl_vars['quick']->value['name'];?>
"
      ><?php echo $_smarty_tpl->tpl_vars['quick']->value['name'];?>
</a>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    <div class="dropdown-divider"></div>
    <?php if ((isset($_smarty_tpl->tpl_vars['matchQuickLink']->value))) {?>
      <a id="quick-remove-link"
        class="dropdown-item js-quick-link"
        href="#"
        data-method="remove"
        data-quicklink-id="<?php echo $_smarty_tpl->tpl_vars['matchQuickLink']->value;?>
"
        data-rand="<?php echo rand(1,200);?>
"
        data-icon="<?php echo $_smarty_tpl->tpl_vars['quick_access_current_link_icon']->value;?>
"
        data-url="<?php echo $_smarty_tpl->tpl_vars['link']->value->getQuickLink(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_SERVER['REQUEST_URI'],'javascript' )));?>
"
        data-post-link="<?php echo $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminQuickAccesses');?>
"
        data-prompt-text="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please name this shortcut:','js'=>1,'d'=>'Admin.Navigation.Header'),$_smarty_tpl ) );?>
"
        data-link="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['quick_access_current_link_name']->value,32 ));?>
"
      >
        <i class="material-icons">remove_circle_outline</i>
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Remove from Quick Access','d'=>'Admin.Navigation.Header'),$_smarty_tpl ) );?>

      </a>
    <?php } else { ?>
      <a id="quick-add-link"
        class="dropdown-item js-quick-link"
        href="#"
        data-rand="<?php echo rand(1,200);?>
"
        data-icon="<?php echo $_smarty_tpl->tpl_vars['quick_access_current_link_icon']->value;?>
"
        data-method="add"
        data-url="<?php echo $_smarty_tpl->tpl_vars['link']->value->getQuickLink(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'escape' ][ 0 ], array( $_SERVER['REQUEST_URI'],'javascript' )));?>
"
        data-post-link="<?php echo $_smarty_tpl->tpl_vars['link']->value->getAdminLink('AdminQuickAccesses');?>
"
        data-prompt-text="<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Please name this shortcut:','js'=>1,'d'=>'Admin.Navigation.Header'),$_smarty_tpl ) );?>
"
        data-link="<?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'truncate' ][ 0 ], array( $_smarty_tpl->tpl_vars['quick_access_current_link_name']->value,32 ));?>
"
      >
        <i class="material-icons">add_circle</i>
        <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Add current page to Quick Access','d'=>'Admin.Actions'),$_smarty_tpl ) );?>

      </a>
    <?php }?>
    <a id="quick-manage-link" class="dropdown-item" href="<?php echo addslashes($_smarty_tpl->tpl_vars['link']->value->getAdminLink("AdminQuickAccesses"));?>
">
      <i class="material-icons">settings</i>
      <?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>'Manage your quick accesses','d'=>'Admin.Navigation.Header'),$_smarty_tpl ) );?>

    </a>
  </div>
</div>
<?php }
}

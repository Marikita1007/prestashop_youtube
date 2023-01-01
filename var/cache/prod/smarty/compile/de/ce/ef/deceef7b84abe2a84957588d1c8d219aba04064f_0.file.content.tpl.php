<?php
/* Smarty version 3.1.47, created on 2022-12-30 17:23:28
  from 'C:\laragon\www\prestashop\4admin_1537\themes\default\template\content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.47',
  'unifunc' => 'content_63af10802028b2_30165125',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'deceef7b84abe2a84957588d1c8d219aba04064f' => 
    array (
      0 => 'C:\\laragon\\www\\prestashop\\4admin_1537\\themes\\default\\template\\content.tpl',
      1 => 1672317012,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_63af10802028b2_30165125 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="ajax_confirmation" class="alert alert-success hide"></div>
<div id="ajaxBox" style="display:none"></div>

<div class="row">
	<div class="col-lg-12">
		<?php if ((isset($_smarty_tpl->tpl_vars['content']->value))) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }
}

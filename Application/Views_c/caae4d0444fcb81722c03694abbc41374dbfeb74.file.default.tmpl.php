<?php /* Smarty version Smarty-3.1.8, created on 2015-08-05 15:37:41
         compiled from "/Users/alanshore/work/open_source/lazy_framework/Application/Views/layout/default.tmpl" */ ?>
<?php /*%%SmartyHeaderCode:17170435655c22dc5c73052-25217169%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'caae4d0444fcb81722c03694abbc41374dbfeb74' => 
    array (
      0 => '/Users/alanshore/work/open_source/lazy_framework/Application/Views/layout/default.tmpl',
      1 => 1438776059,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17170435655c22dc5c73052-25217169',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_55c22dc5dabcb4_74250234',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55c22dc5dabcb4_74250234')) {function content_55c22dc5dabcb4_74250234($_smarty_tpl) {?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    </head>
    <body>
    <?php echo $_smarty_tpl->getSubTemplate (($_smarty_tpl->tpl_vars['tmpl_main']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </body>
</html>
<?php }} ?>
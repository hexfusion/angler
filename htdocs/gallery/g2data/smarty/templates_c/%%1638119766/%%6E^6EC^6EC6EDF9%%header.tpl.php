<?php /* Smarty version 2.6.10, created on 2006-03-08 18:06:41
         compiled from gallery:themes/hybrid/templates/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:themes/hybrid/templates/header.tpl', 17, false),)), $this); ?>
<script type="text/javascript">
// <![CDATA[
<?php if ($this->_tpl_vars['theme']['imageCount'] == 1): ?>
var data_iw = new Array(1); data_iw[0] = <?php echo $this->_tpl_vars['theme']['imageWidths']; ?>
;
var data_ih = new Array(1); data_ih[0] = <?php echo $this->_tpl_vars['theme']['imageHeights']; ?>
;
<?php else: ?>
var data_iw = new Array(<?php echo $this->_tpl_vars['theme']['imageWidths']; ?>
);
var data_ih = new Array(<?php echo $this->_tpl_vars['theme']['imageHeights']; ?>
);
<?php endif; ?>
var data_count = data_iw.length, data_name = '<?php echo $this->_tpl_vars['theme']['item']['id']; ?>
',
    data_view = <?php echo ((is_array($_tmp=@$this->_tpl_vars['theme']['viewIndex'])) ? $this->_run_mod_handler('default', true, $_tmp, -1) : smarty_modifier_default($_tmp, -1)); ?>
,
    album_showtext = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show Details','forJavascript' => true), $this);?>
',
    album_hidetext = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide Details','forJavascript' => true), $this);?>
',
    album_showlinks = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show Item Links','forJavascript' => true), $this);?>
',
    album_hidelinks = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide Item Links','forJavascript' => true), $this);?>
',
    item_details = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Item Details','forJavascript' => true), $this);?>
';
// ]]>
</script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/hybrid.js"></script>
<style type="text/css">
#gsAlbumContent td.t { width: <?php echo $this->_tpl_vars['theme']['columnWidthPct']; ?>
%; }
</style>
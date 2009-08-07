<?php /* Smarty version 2.6.10, created on 2006-03-08 17:30:24
         compiled from gallery:modules/core/templates/blocks/Navigator.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:modules/core/templates/blocks/Navigator.tpl', 12, false),array('modifier', 'split', 'gallery:modules/core/templates/blocks/Navigator.tpl', 19, false),)), $this); ?>
<?php if (isset ( $this->_tpl_vars['reverseOrder'] ) && $this->_tpl_vars['reverseOrder']): ?>
  <?php $this->assign('order', "next-and-last current first-and-previous");  else: ?>
  <?php $this->assign('order', "first-and-previous current next-and-last");  endif;  $this->assign('prefix', ((is_array($_tmp=@$this->_tpl_vars['prefix'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, "")));  $this->assign('suffix', ((is_array($_tmp=@$this->_tpl_vars['suffix'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, ""))); ?>
<div class="<?php echo $this->_tpl_vars['class']; ?>
">
<?php $_from = ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('split', true, $_tmp) : smarty_modifier_split($_tmp)); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['which']):
 if ($this->_tpl_vars['which'] == "next-and-last"): ?>
  <div class="next-and-last<?php if (! isset ( $this->_tpl_vars['navigator']['first'] ) && ! isset ( $this->_tpl_vars['navigator']['back'] )): ?> no-previous<?php endif; ?>">
    <?php echo '';  if (isset ( $this->_tpl_vars['navigator']['next'] ) && ( ! isset ( $this->_tpl_vars['navigator']['last'] ) || $this->_tpl_vars['navigator']['next']['urlParams'] != $this->_tpl_vars['navigator']['last']['urlParams'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['navigator']['next']['urlParams']), $this); echo '" class="next">';  echo $this->_reg_objects['g'][0]->text(array('text' => 'next'), $this); echo '';  echo $this->_tpl_vars['suffix'];  echo '';  if (isset ( $this->_tpl_vars['navigator']['next']['thumbnail'] )):  echo '';  echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['navigator']['next']['item'],'image' => $this->_tpl_vars['navigator']['next']['thumbnail'],'maxSize' => 40,'class' => 'next'), $this); echo '';  endif;  echo '</a>';  endif;  echo '';  if (isset ( $this->_tpl_vars['navigator']['last'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['navigator']['last']['urlParams']), $this); echo '" class="last">';  echo $this->_reg_objects['g'][0]->text(array('text' => 'last'), $this); echo '';  echo $this->_tpl_vars['suffix'];  echo '';  if (isset ( $this->_tpl_vars['navigator']['last']['thumbnail'] )):  echo '';  echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['navigator']['last']['item'],'image' => $this->_tpl_vars['navigator']['last']['thumbnail'],'maxSize' => 40,'class' => 'last'), $this); echo '';  endif;  echo '</a>';  endif;  echo ''; ?>

  </div>
<?php elseif ($this->_tpl_vars['which'] == 'current'): ?>
  <?php if (( isset ( $this->_tpl_vars['currentPage'] ) && isset ( $this->_tpl_vars['totalPages'] ) ) || ( isset ( $this->_tpl_vars['currentItem'] ) && isset ( $this->_tpl_vars['totalItems'] ) )): ?>
  <span class="current">
    <?php if (isset ( $this->_tpl_vars['currentPage'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Page %d of %d",'arg1' => $this->_tpl_vars['currentPage'],'arg2' => $this->_tpl_vars['totalPages']), $this);?>

    <?php else: ?>
      <?php if (isset ( $this->_tpl_vars['currentItem'] )): ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "%d of %d",'arg1' => $this->_tpl_vars['currentItem'],'arg2' => $this->_tpl_vars['totalItems']), $this);?>

      <?php endif; ?>
    <?php endif; ?>
  </span>
  <?php endif;  else: ?>
  <div class="first-and-previous">
    <?php echo '';  if (isset ( $this->_tpl_vars['navigator']['first'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['navigator']['first']['urlParams']), $this); echo '" class="first">';  if (isset ( $this->_tpl_vars['navigator']['first']['thumbnail'] )):  echo '';  echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['navigator']['first']['item'],'image' => $this->_tpl_vars['navigator']['first']['thumbnail'],'maxSize' => '40','class' => 'first'), $this); echo '';  endif;  echo '';  echo $this->_tpl_vars['prefix'];  echo '';  echo $this->_reg_objects['g'][0]->text(array('text' => 'first'), $this); echo '</a>';  endif;  echo '';  if (isset ( $this->_tpl_vars['navigator']['back'] ) && ( ! isset ( $this->_tpl_vars['navigator']['first'] ) || $this->_tpl_vars['navigator']['back']['urlParams'] != $this->_tpl_vars['navigator']['first']['urlParams'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['navigator']['back']['urlParams']), $this); echo '" class="previous">';  if (isset ( $this->_tpl_vars['navigator']['back']['thumbnail'] )):  echo '';  echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['navigator']['back']['item'],'image' => $this->_tpl_vars['navigator']['back']['thumbnail'],'maxSize' => '40','class' => 'previous'), $this); echo '';  endif;  echo '';  echo $this->_tpl_vars['prefix'];  echo '';  echo $this->_reg_objects['g'][0]->text(array('text' => 'previous'), $this); echo '</a>';  endif;  echo ''; ?>

  </div>
<?php endif;  endforeach; endif; unset($_from); ?>
</div>
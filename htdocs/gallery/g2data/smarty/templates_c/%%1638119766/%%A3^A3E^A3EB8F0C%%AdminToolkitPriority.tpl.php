<?php /* Smarty version 2.6.10, created on 2006-03-08 17:22:41
         compiled from gallery:modules/core/templates/AdminToolkitPriority.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'gallery:modules/core/templates/AdminToolkitPriority.tpl', 39, false),array('function', 'cycle', 'gallery:modules/core/templates/AdminToolkitPriority.tpl', 40, false),)), $this); ?>
<script type="text/javascript"><?php echo '
function swap(j,k) {
 var tt = document.getElementById(\'t.\'+k).innerHTML,
     ti = document.getElementById(\'i.\'+k).value;
 document.getElementById(\'t.\'+k).innerHTML = document.getElementById(\'t.\'+j).innerHTML;
 document.getElementById(\'i.\'+k).value = document.getElementById(\'i.\'+j).value;
 document.getElementById(\'t.\'+j).innerHTML = tt;
 document.getElementById(\'i.\'+j).value = ti;
}
function up(j) { swap(j,j-1); }
function down(j) { swap(j,j+1); }
'; ?>
</script>

<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Toolkit Priority'), $this);?>
 </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Settings saved successfully'), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The toolkits shown below have some overlap in supported operations and mime types.  Rank the toolkits here to determine which will perform these operations."), $this);?>

  </p>
  <table class="gbDataTable"><tr>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Toolkit'), $this);?>
 </th>
    <th colspan="2"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Order'), $this);?>
 </th>
  </tr>
  <?php $_from = $this->_tpl_vars['AdminToolkitPriority']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['toolkit'] => $this->_tpl_vars['priority']):
?>
    <?php echo smarty_function_counter(array('assign' => 'idx'), $this);?>

    <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
      <td id="t.<?php echo $this->_tpl_vars['idx']; ?>
">
	<?php echo $this->_tpl_vars['toolkit']; ?>

      </td><td>
	<?php if ($this->_tpl_vars['idx'] > 1): ?>
	  <a href="" onclick="up(<?php echo $this->_tpl_vars['idx']; ?>
);this.blur();return false" style="padding:0 2px">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'up'), $this);?>

	  </a>
	<?php endif; ?>
      </td><td>
	<?php if ($this->_tpl_vars['idx'] < $this->_tpl_vars['AdminToolkitPriority']['count']): ?>
	  <a href="" onclick="down(<?php echo $this->_tpl_vars['idx']; ?>
);this.blur();return false" style="padding:0 2px">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'down'), $this);?>

	  </a>
	<?php endif; ?>
	<input type="hidden" id="i.<?php echo $this->_tpl_vars['idx']; ?>
"
	 name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[priority][".($this->_tpl_vars['priority'])."]"), $this);?>
" value="<?php echo $this->_tpl_vars['toolkit']; ?>
"/>
      </td>
    </tr>
  <?php endforeach; endif; unset($_from); ?>
  </table>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][reset]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
</div>
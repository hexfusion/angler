<?php /* Smarty version 2.6.10, created on 2006-03-08 17:22:20
         compiled from gallery:modules/gd/templates/AdminGd.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/gd/templates/AdminGd.tpl', 24, false),array('function', 'cycle', 'gallery:modules/gd/templates/AdminGd.tpl', 50, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Gd Settings'), $this);?>
 </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Settings saved successfully'), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Gd is a graphics toolkit that can be used to process images that you upload to Gallery. The GD-library should be compiled in your PHP (--with-gd)."), $this);?>

  </p>

  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "JPEG Quality:"), $this);?>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[jpegQuality]"), $this);?>
">
    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['AdminGd']['jpegQualityList'],'selected' => $this->_tpl_vars['form']['jpegQuality'],'output' => $this->_tpl_vars['AdminGd']['jpegQualityList']), $this);?>

  </select>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <?php if ($this->_tpl_vars['AdminGd']['isConfigure']): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
  <?php else: ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][reset]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'GD library version'), $this);?>
 </h3>

  <?php if ($this->_tpl_vars['AdminGd']['gdVersion']): ?>
    <table class="gbDataTable"><tr>
      <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'GD version'), $this);?>
 </th>
      <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Required'), $this);?>
 </th>
      <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Pass/fail"), $this);?>
 </th>
    </tr>
    <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
      <td>
	<?php echo $this->_tpl_vars['AdminGd']['gdVersion']; ?>

	<?php if ($this->_tpl_vars['AdminGd']['isGdBundled']): ?>
	  (<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'bundled'), $this);?>
)
	<?php endif; ?>
      </td><td>
	<?php echo $this->_tpl_vars['AdminGd']['minGdVersion']; ?>

      </td><td>
	<?php if (( $this->_tpl_vars['AdminGd']['gdVersionTooOld'] )): ?>
	  <div class="giError">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Failed'), $this);?>

	  </div>
	  <div class="giError">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This GD version is too old and is not supported by this module! Please upgrade your PHP installation to include the latest GD version."), $this);?>

	  </div>
	<?php else: ?>
	  <div class="giSuccess">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Passed'), $this);?>

	  </div>
	<?php endif; ?>
      </td>
    </tr></table>
  <?php else: ?>
    <p class="giDescription">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You don't seem to have the GD library available on this PHP installation."), $this);?>

    </p>
  <?php endif; ?>
</div>

<?php if ($this->_tpl_vars['AdminGd']['mimeTypes']): ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Supported MIME Types'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The Gd module will support files with the following MIME types:"), $this);?>

  </p>
  <p class="giDescription">
  <?php $_from = $this->_tpl_vars['AdminGd']['mimeTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mimeType']):
?>
    <?php echo $this->_tpl_vars['mimeType']; ?>
<br/>
  <?php endforeach; endif; unset($_from); ?>
  </p>
</div>
<?php endif; ?>
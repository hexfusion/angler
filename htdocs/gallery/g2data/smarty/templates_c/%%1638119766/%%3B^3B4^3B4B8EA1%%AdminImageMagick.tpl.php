<?php /* Smarty version 2.6.10, created on 2006-03-08 17:22:29
         compiled from gallery:modules/imagemagick/templates/AdminImageMagick.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/imagemagick/templates/AdminImageMagick.tpl', 59, false),array('function', 'cycle', 'gallery:modules/imagemagick/templates/AdminImageMagick.tpl', 100, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'ImageMagick Settings'), $this);?>
 </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Settings saved successfully'), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "ImageMagick is a graphics toolkit that can be used to process images that you upload to Gallery.  You must install the ImageMagick binaries on your server, then enter the path to them in the text box below.  If you're on a Unix machine, don't forget to make the binaries executable (<i>chmod 755 *</i> in the ImageMagick directory should do it)"), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Directory to ImageMagick/GraphicsMagick binaries:"), $this);?>

    </td><td>
      <input type="text" id='giFormPath' size="40" autocomplete="off"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[path]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['path']; ?>
"/>
      <?php $this->_tag_stack[] = array('autoComplete', array('element' => 'giFormPath'), $this); $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
	<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SimpleCallback",'arg2' => "command=lookupDirectories",'arg3' => "prefix=__VALUE__",'forJavascript' => 'true'), $this);?>

      <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>


      <?php if (isset ( $this->_tpl_vars['form']['error']['path']['missing'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a path to your ImageMagick binaries'), $this);?>

      </div>
      <?php endif; ?>
      <?php if (isset ( $this->_tpl_vars['form']['error']['path']['bad'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "The path you entered is not a valid directory or is not accessible."), $this);?>

      </div>
      <?php endif; ?>
      <?php if (isset ( $this->_tpl_vars['form']['error']['path']['testError'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "The path you entered doesn't contain valid ImageMagick binaries. Use the 'test' button to check where the error is."), $this);?>

      </div>
      <?php endif; ?>
      <?php if (isset ( $this->_tpl_vars['form']['error']['path']['badPath'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "The path you entered isn't a valid path."), $this);?>

      </div>
      <?php endif; ?>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "JPEG Quality:"), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[jpegQuality]"), $this);?>
">
	<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['AdminImageMagick']['jpegQualityList'],'selected' => $this->_tpl_vars['form']['jpegQuality'],'output' => $this->_tpl_vars['AdminImageMagick']['jpegQualityList']), $this);?>

      </select>
    </td>
  <?php if ($this->_tpl_vars['form']['cmykSupport'] != 'none'): ?>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "CMYK JPEG Support:"), $this);?>

    </td><td>
      <input type="checkbox"<?php if ($this->_tpl_vars['form']['cmykSupport'] == 'on'): ?> checked="checked"<?php endif; ?>
       onclick="document.getElementById('cmykSupport').value = this.checked ? 'on' : 'off'"/>
    </td>
  <?php endif; ?>
  </tr></table>
  <input type="hidden" id="cmykSupport"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[cmykSupport]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['cmykSupport']; ?>
"/>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save Settings'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][test]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Test Settings'), $this);?>
"/>
  <?php if ($this->_tpl_vars['AdminImageMagick']['isConfigure']): ?>
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

<?php if (! empty ( $this->_tpl_vars['AdminImageMagick']['tests'] )): ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'ImageMagick binary test results'), $this);?>
 </h3>

  <table class="gbDataTable"><tr>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Binary Name'), $this);?>
 </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Pass/Fail"), $this);?>
 </th>
  </tr>
  <?php $_from = $this->_tpl_vars['AdminImageMagick']['tests']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['test']):
?>
    <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
      <td>
	<?php echo $this->_tpl_vars['test']['name']; ?>

    </td><td>
	<?php if (( $this->_tpl_vars['test']['success'] )): ?>
	  <div class="giSuccess">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Passed'), $this);?>

	  </div>
	<?php else: ?>
	  <div class="giError">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Failed'), $this);?>

	  </div>
	  <?php if (! empty ( $this->_tpl_vars['test']['message'] )): ?>
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Error messages:"), $this);?>

	    <br/>
	    <div class="giError">
	      <?php $_from = $this->_tpl_vars['test']['message']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['line']):
?>
		<pre><?php echo $this->_tpl_vars['line']; ?>
</pre>
	      <?php endforeach; endif; unset($_from); ?>
	    </div>
	  <?php endif; ?>
	<?php endif; ?>
      </td>
    </tr>
  <?php endforeach; endif; unset($_from); ?>
  </table>
</div>

<?php if ($this->_tpl_vars['AdminImageMagick']['mimeTypes']): ?>
<div class="gbBlock">
  <div class="giTitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Version'), $this);?>
 </div>
  <p class="giDescription">
    <?php echo $this->_tpl_vars['AdminImageMagick']['version']['0']; ?>
 <?php echo $this->_tpl_vars['AdminImageMagick']['version']['1']; ?>

  </p>

  <div class="giTitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Supported MIME Types'), $this);?>
 </div>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The ImageMagick module can support files with the following MIME types:"), $this);?>

  </p>
  <p class="giDescription">
  <?php $_from = $this->_tpl_vars['AdminImageMagick']['mimeTypes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mimeType']):
?>
    <?php echo $this->_tpl_vars['mimeType']; ?>
<br />
  <?php endforeach; endif; unset($_from); ?>
  </p>
</div>
<?php endif; ?>

<?php if (( $this->_tpl_vars['AdminImageMagick']['failCount'] > 0 )): ?>
<div class="gbBlock">
  <h3>
    <?php echo $this->_reg_objects['g'][0]->text(array('one' => "Debug output (%d failed test)",'many' => "Debug output (%d failed tests)",'count' => $this->_tpl_vars['AdminImageMagick']['failCount'],'arg1' => $this->_tpl_vars['AdminImageMagick']['failCount']), $this);?>

    <span id="AdminImageMagick_trace-toggle"
     class="giBlockToggle gcBackground1 gcBorder2" style="border-width: 1px"
     onclick="BlockToggle('AdminImageMagick_debugSnippet', 'AdminImageMagick_trace-toggle')">+</span>
  </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "We gathered this debug output while testing your ImageMagick binaries.  If you read through this carefully you may discover the reason why your ImageMagick binaries failed the tests."), $this);?>

  </p>
  <pre id="AdminImageMagick_debugSnippet" class="gcBackground1 gcBorder2"
   style="display: none; border-width: 1px; border-style: dotted; padding: 4px">
    <?php echo $this->_tpl_vars['AdminImageMagick']['debugSnippet']; ?>

  </pre>
</div>
<?php endif;  endif; ?>
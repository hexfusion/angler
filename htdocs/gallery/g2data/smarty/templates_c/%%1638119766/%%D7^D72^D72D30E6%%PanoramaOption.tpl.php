<?php /* Smarty version 2.6.10, created on 2006-06-13 12:26:34
         compiled from gallery:modules/panorama/templates/PanoramaOption.tpl */ ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Panorama'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Note that panorama view only applies to full size photo, not resizes."), $this);?>

  </p>

  <input type="checkbox" id="Panorama_cb"<?php if ($this->_tpl_vars['form']['PanoramaOption']['isPanorama']): ?> checked="checked"<?php endif; ?>
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[PanoramaOption][isPanorama]"), $this);?>
"/>
  <label for="Panorama_cb">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Activate panorama viewer applet for this photo'), $this);?>

  </label>
</div>
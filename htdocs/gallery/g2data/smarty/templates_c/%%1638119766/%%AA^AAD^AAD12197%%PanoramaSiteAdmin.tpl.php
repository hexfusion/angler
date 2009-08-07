<?php /* Smarty version 2.6.10, created on 2006-03-08 17:27:58
         compiled from gallery:modules/panorama/templates/PanoramaSiteAdmin.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Panorama Settings'), $this);?>
 </h2>
</div>

<?php if (! empty ( $this->_tpl_vars['status'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Settings saved successfully'), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['reset'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Items reset successfully'), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['deactivated'] )): ?>
  <span class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Reset panorama items to enable deactivation (see below)"), $this);?>

  </span>
  <?php endif; ?>
</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The panorama viewer can be activated in two ways: the first allows album administrators to select individual images for panorama display (Panorama section in \"edit photo\"), overriding the normal display of the entire image.  The second method retains the normal image display but gives users an option in the \"item actions\" to view the image in the panorama viewer."), $this);?>

  </p>
  <p style="line-height: 2.5em; margin-left: 1em">
    <input type="checkbox" id="cbItemType"<?php if ($this->_tpl_vars['form']['itemType']): ?> checked="checked"<?php endif; ?>
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[itemType]"), $this);?>
"/>
    <label for="cbItemType">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Use applet to display wide images'), $this);?>

    </label>
    <br/>

    <input type="checkbox" id="cbItemLink"<?php if ($this->_tpl_vars['form']['itemLink']): ?> checked="checked"<?php endif; ?>
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[itemLink]"), $this);?>
"/>
    <label for="cbItemLink">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Add \"view panorama\" option in item actions for wide images"), $this);?>

    </label>
    <br/>

    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Width of panorama viewer: "), $this);?>

    <input type="text" size="6" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[width]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['width']; ?>
"/>

    <?php if (isset ( $this->_tpl_vars['form']['error']['width'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invalid width value'), $this);?>

    </div>
    <?php endif; ?>
  </p>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][undo]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
</div>

<?php if (( $this->_tpl_vars['form']['count'] > 0 )): ?>
<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This gallery contains items activated for panorama view.  These must be reset to standard items before this module can be deactivated.  You can reset all items here.  Warning: there is no undo."), $this);?>

  </p>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][reset]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset all items'), $this);?>
"/>
</div>
<?php endif; ?>
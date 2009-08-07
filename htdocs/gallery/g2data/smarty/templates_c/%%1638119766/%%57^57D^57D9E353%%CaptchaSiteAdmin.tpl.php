<?php /* Smarty version 2.6.10, created on 2006-04-02 13:11:24
         compiled from gallery:modules/captcha/templates/CaptchaSiteAdmin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/captcha/templates/CaptchaSiteAdmin.tpl', 36, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Captcha Settings'), $this);?>

  </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
<div class="gbBlock">
  <h2 class="giSuccess">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Settings saved successfully'), $this);?>

  </h2>
</div>
<?php endif; ?>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "High Security - Always On"), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Modules such as User Registration will always require the Captcha value to be entered before proceeding."), $this);?>

  </p>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Medium Security - Failed Attempts"), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Users are not required to pass the Captcha test unless they have failed validation or user input at least this many times.  After that, they have to enter the Captcha value to log in, or perform certain other secured actions."), $this);?>

  </p>

  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Failed attempts:"), $this);?>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[failedAttemptThreshold]"), $this);?>
">
    <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['CaptchaSiteAdmin']['failedAttemptThresholdList'],'selected' => $this->_tpl_vars['form']['failedAttemptThreshold'],'output' => $this->_tpl_vars['CaptchaSiteAdmin']['failedAttemptThresholdList']), $this);?>

  </select>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Low Security - Always Off"), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Most modules rely solely on the user being authenticated by Gallery and require no special Captcha interaction."), $this);?>

  </p>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save Settings'), $this);?>
"/>
  <input type="submit" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>
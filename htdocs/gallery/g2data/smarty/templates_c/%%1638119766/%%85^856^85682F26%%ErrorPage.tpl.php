<?php /* Smarty version 2.6.10, created on 2006-03-08 17:21:36
         compiled from modules/core/templates/ErrorPage.tpl */ ?>
<div id="gsContent" class="gcBorder1" style="border-width: 1px 0 0 1px">
  <div class="gbBlock gcBackground1">
    <h2>
      <?php if (isset ( $this->_tpl_vars['ErrorPage']['obsoleteData'] )): ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Edit Conflict!"), $this);?>

      <?php elseif (isset ( $this->_tpl_vars['ErrorPage']['securityViolation'] )): ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Security Violation'), $this);?>

      <?php elseif (isset ( $this->_tpl_vars['ErrorPage']['storageFailure'] )): ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Database Error'), $this);?>

      <?php else: ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Error'), $this);?>

      <?php endif; ?>
    </h2>
  </div>

  <div class="gbBlock">
    <?php if (isset ( $this->_tpl_vars['ErrorPage']['obsoleteData'] )): ?>
      <p class="giDescription">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your change cannot be completed because somebody else has made a conflicting change to the same item.  Use the back button in your browser to go back to the page you were on, then <b>reload that page</b> and try your change again."), $this);?>

      </p>
      <a href="javascript:history.back()">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Go back and try again'), $this);?>

      </a>
      <p class="giDescription" style="margin-top: 0.5em">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Alternatively, you can return to the main Gallery page and resume browsing."), $this);?>

      </p>
    <?php elseif (isset ( $this->_tpl_vars['ErrorPage']['securityViolation'] )): ?>
      <p class="giDescription">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "The action you attempted is not permitted."), $this);?>

      </p>
    <?php elseif (isset ( $this->_tpl_vars['ErrorPage']['storageFailure'] )): ?>
      <p class="giDescription">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "An error has occurred while interacting with the database."), $this);?>

      </p>
      <?php if ($this->_tpl_vars['ErrorPage']['isAdmin'] && ! isset ( $this->_tpl_vars['ErrorPage']['debug'] )): ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "The exact nature of database errors is not captured unless Gallery debug mode is enabled in config.php.  Before seeking support for this error please enable buffered debug output and retry the operation.  Look near the bottom of the lengthy debug output to find error details."), $this);?>

      <?php endif; ?>
    <?php else: ?>
      <p class="giDescription">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "An error has occurred."), $this);?>

      </p>
    <?php endif; ?>

    <p class="giDescription">
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array(), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Back to the Gallery'), $this);?>
</a>
    </p>
  </div>

  <div class="gbBlock">
    <h3>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Error Detail'), $this);?>

      <span id="trace-toggle" class="giBlockToggle gcBackground1 gcBorder2"
       style="border-width: 1px" onclick="BlockToggle('giStackTrace', 'trace-toggle')">
	<?php if ($this->_tpl_vars['ErrorPage']['isAdmin']): ?>-<?php else: ?>+<?php endif; ?>
      </span>
    </h3>
    <div id="giStackTrace" style="margin-left: 0.8em<?php if (! $this->_tpl_vars['ErrorPage']['isAdmin']): ?>; display:none<?php endif; ?>">
      <?php echo $this->_tpl_vars['ErrorPage']['stackTrace']; ?>

    </div>
  </div>

  <?php if ($this->_tpl_vars['ErrorPage']['isAdmin']): ?>
  <div class="gbBlock">
    <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'System Information'), $this);?>
 </h3>
    <table class="gbDataTable"><tr>
      <td>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Gallery version'), $this);?>

      </td><td>
	<?php echo $this->_tpl_vars['ErrorPage']['version']; ?>

      </td>
    </tr><tr>
      <td>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'PHP version'), $this);?>

      </td><td>
	<?php echo $this->_tpl_vars['ErrorPage']['phpversion']; ?>
 <?php echo $this->_tpl_vars['ErrorPage']['php_sapi_name']; ?>

      </td>
     </tr><tr>
      <td>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Webserver'), $this);?>

      </td><td>
	<?php echo $this->_tpl_vars['ErrorPage']['webserver']; ?>

      </td>
    </tr>
    <?php if (isset ( $this->_tpl_vars['ErrorPage']['dbType'] )): ?>
    <tr>
      <td>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Database'), $this);?>

      </td><td>
	<?php echo $this->_tpl_vars['ErrorPage']['dbType']; ?>
 <?php echo $this->_tpl_vars['ErrorPage']['dbVersion']; ?>

      </td>
    </tr>
    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['ErrorPage']['toolkits'] )): ?>
    <tr>
      <td>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Toolkits'), $this);?>

      </td><td>
	<?php echo $this->_tpl_vars['ErrorPage']['toolkits']; ?>

      </td>
    </tr>
    <?php endif; ?>
    <tr>
      <td>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Operating system'), $this);?>

      </td><td>
	<?php echo $this->_tpl_vars['ErrorPage']['php_uname']; ?>

      </td>
    </tr><tr>
      <td>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Browser'), $this);?>

      </td><td>
	<?php echo $this->_tpl_vars['ErrorPage']['browser']; ?>

      </td>
    </tr></table>
  </div>
  <?php endif; ?>

  <?php if (isset ( $this->_tpl_vars['ErrorPage']['debug'] )): ?>
  <div class="gbBlock">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:templates/debug.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
  <?php endif; ?>

  <?php if (isset ( $this->_tpl_vars['ErrorPage']['profile'] )): ?>
  <div class="gbBlock">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:templates/profile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
  <?php endif; ?>
</div>
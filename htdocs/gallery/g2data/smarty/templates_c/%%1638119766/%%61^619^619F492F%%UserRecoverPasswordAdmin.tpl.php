<?php /* Smarty version 2.6.10, created on 2007-04-27 18:29:50
         compiled from gallery:modules/core/templates/UserRecoverPasswordAdmin.tpl */ ?>
<?php ob_start(); ?>
    <div class="giDescription">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "In order to proceed with the password reset, we have to verify that you are who you claim.  The best way to be sure is to ask you to make a tiny change in the Gallery directory which will prove that you have the right permissions.  So, we're going to ask that you create a new text file called %s in your gallery2 directory. It must contain the following randomly generated characters:",'arg1' => "<strong>login.txt</strong>"), $this);?>

    </div>
    <h2>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "%s",'arg1' => $this->_tpl_vars['UserRecoverPasswordAdmin']['authString']), $this);?>

    </h2>
    <div class="giDescription">
      <?php ob_start(); ?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.UserRecoverPasswordDownload",'forceDirect' => true), $this);?>
">
      <?php $this->_smarty_vars['capture']['downloadUrl'] = ob_get_contents(); ob_end_clean(); ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "As a convenience to you, we've prepared a %scorrect version of login.txt%s for you.  Download that and copy it into your install directory and you're all set.",'arg1' => $this->_smarty_vars['capture']['downloadUrl'],'arg2' => "</a>"), $this);?>

     </div>
     <div class="giDescription">
       <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Once you've uploaded the file, click refresh to continue."), $this);?>

     </div>
<?php $this->_smarty_vars['capture']['loginDescriptionDownload'] = ob_get_contents(); ob_end_clean(); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Emergency Administrator Password Reset'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This page can be used by a system administrator to securely reset the password on any account."), $this);?>

</div>

  <?php if (isset ( $this->_tpl_vars['UserRecoverPasswordAdmin']['status']['authString']['correct'] )): ?>
  <div class="gbBlock">
    <h2 class="giSuccess">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Authorization Confirmed'), $this);?>

    </h2>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your authorization has been confirmed.  Please enter your new password below.  After setting your new password you will be taken to the login page."), $this);?>

  </div>

  <div class="gbBlock">
    <h4><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Recover Password for Username'), $this);?>
</h4>

    <input type="text" size="20" autocomplete="off" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[userName]"), $this);?>
"
     id="giFormUsername" value="<?php echo $this->_tpl_vars['UserRecoverPasswordAdmin']['status']['userName']; ?>
"/>

    <script type="text/javascript">
      document.getElementById('userAdminForm')['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[userName]"), $this);?>
'].focus();
    </script>

    <?php if (isset ( $this->_tpl_vars['form']['error']['userName']['missing'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter a username to recover the password for."), $this);?>

    </div>
    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['form']['error']['userName']['incorrect'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The username you entered does not exist."), $this);?>

    </div>
    <?php endif; ?>
  </div>

  <div class="gbBlock">
    <h4><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'New Password'), $this);?>
</h4>

    <input type="password" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[password1]"), $this);?>
"/>
    <?php if (isset ( $this->_tpl_vars['form']['error']['password']['missing'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a new password'), $this);?>

    </div>
    <?php endif; ?>
  </div>

  <div class="gbBlock">
    <h4><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Verify New Password'), $this);?>
</h4>

    <input type="password" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[password2]"), $this);?>
"/>
  </div>

  <?php if (isset ( $this->_tpl_vars['form']['error']['password']['mismatch'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'The passwords you entered did not match'), $this);?>

  </div>
  <?php endif; ?>

  <?php elseif (isset ( $this->_tpl_vars['UserRecoverPasswordAdmin']['error']['authString']['incorrect'] ) || isset ( $this->_tpl_vars['error']['authString']['incorrect'] )): ?>
  <div class="gbBlock">
    <?php if (! isset ( $this->_tpl_vars['UserRecoverPasswordAdmin']['status']['firstLoad'] )): ?>
    <h2 class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Authorization Incorrect'), $this);?>

    </h2>
    <?php else: ?>
    <h2 class="giSuccess">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Recovery Instructions'), $this);?>

    </h2>
    <?php endif; ?>
    <?php echo $this->_smarty_vars['capture']['loginDescriptionDownload']; ?>

  </div>
  <?php elseif (isset ( $this->_tpl_vars['UserRecoverPasswordAdmin']['error']['authFile']['missing'] )): ?>
  <div class="gbBlock">
    <h2 class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'AuthFile Missing'), $this);?>

    </h2>
    <?php echo $this->_smarty_vars['capture']['loginDescriptionDownload']; ?>

  </div>
  <?php elseif (isset ( $this->_tpl_vars['UserRecoverPasswordAdmin']['error']['authFile']['unreadable'] )): ?>
  <div class="gbBlock">
    <h2 class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'AuthFile Unreadable'), $this);?>

    </h2>
    <h2>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your %s file is not readable. Please give Gallery read permissions on the file.",'arg1' => "<strong>login.txt</strong>"), $this);?>

    </h2>
  </div>
  <?php endif; ?>

<div class="gbBlock gcBackground1">
  <?php if (isset ( $this->_tpl_vars['UserRecoverPasswordAdmin']['status']['authString']['correct'] )): ?>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][recover]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Recover'), $this);?>
"/>
  <?php else: ?>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][refresh]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Refresh'), $this);?>
"/>
  <?php endif; ?>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>
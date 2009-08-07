<?php /* Smarty version 2.6.10, created on 2006-04-02 13:12:14
         compiled from gallery:modules/core/templates/AdminGroups.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'gallery:modules/core/templates/AdminGroups.tpl', 111, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Group Management'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <?php if (! empty ( $this->_tpl_vars['status'] )): ?>
  <h3 class="giSuccess">
    <?php if (isset ( $this->_tpl_vars['status']['deletedGroup'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Removed group '%s'",'arg1' => $this->_tpl_vars['status']['deletedGroup']), $this);?>

    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['status']['createdGroup'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Created group '%s'",'arg1' => $this->_tpl_vars['status']['createdGroup']), $this);?>

    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['status']['modifiedGroup'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Modified group '%s'",'arg1' => $this->_tpl_vars['status']['modifiedGroup']), $this);?>

    <?php endif; ?>
  </h3>
  <?php endif; ?>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('one' => "There is %d group in the system.",'many' => "There are %d total groups in the system.",'count' => $this->_tpl_vars['AdminGroups']['totalGroupCount'],'arg1' => $this->_tpl_vars['AdminGroups']['totalGroupCount']), $this);?>

  </p>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Edit Group'), $this);?>
 </h3>

  <input type="text" id="giFormGroupname" size="20" autocomplete="off"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[text][groupName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['text']['groupName']; ?>
"/>
  <?php $this->_tag_stack[] = array('autoComplete', array('element' => 'giFormGroupname'), $this); $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
    <?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SimpleCallback",'arg2' => "command=lookupGroupname",'arg3' => "prefix=__VALUE__",'forJavascript' => 'true'), $this);?>

  <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>


  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][editFromText]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Edit'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][deleteFromText]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Delete'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][addRemoveUsersFromText]"), $this);?>
"
   value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Add/Remove Users"), $this);?>
"/>

  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['noSuchGroup'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Group '%s' does not exist.",'arg1' => $this->_tpl_vars['form']['text']['groupName']), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['noGroupSpecified'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a group name'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['cantDeleteGroup'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You cannot delete that group'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['cantEditGroupUsers'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You cannot edit that group's users"), $this);?>

  </div>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Edit Group (by list)"), $this);?>
 </h3>

  <?php if (( $this->_tpl_vars['form']['list']['maxPages'] > 1 )): ?>
  <div style="margin-bottom: 10px"><span class="gcBackground1" style="padding: 5px">
    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[list][page]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['list']['page']; ?>
"/>
    <input type="hidden"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[list][maxPages]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['list']['maxPages']; ?>
"/>

    <?php if (( $this->_tpl_vars['form']['list']['page'] > 1 )): ?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminGroups",'arg3' => "form[list][page]=1"), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; first"), $this);?>
</a>
      &nbsp;
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminGroups",'arg3' => "form[list][page]=".($this->_tpl_vars['form']['list']['backPage'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; back"), $this);?>
</a>
    <?php endif; ?>

    &nbsp;
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Viewing page %d of %d",'arg1' => $this->_tpl_vars['form']['list']['page'],'arg2' => $this->_tpl_vars['form']['list']['maxPages']), $this);?>

    &nbsp;

    <?php if (( $this->_tpl_vars['form']['list']['page'] < $this->_tpl_vars['form']['list']['maxPages'] )): ?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminGroups",'arg3' => "form[list][page]=".($this->_tpl_vars['form']['list']['nextPage'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "next &raquo;"), $this);?>
</a>
      &nbsp;
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminGroups",'arg3' => "form[list][page]=".($this->_tpl_vars['form']['list']['maxPages'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "last &raquo;"), $this);?>
</a>
    <?php endif; ?>
  </span></div>
  <?php endif; ?>

  <table class="gbDataTable">
    <tr>
      <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Group Name'), $this);?>
 </th>
      <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Action'), $this);?>
 </th>
    </tr>

    <?php $_from = $this->_tpl_vars['form']['list']['groupNames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['groupId'] => $this->_tpl_vars['group']):
?>
    <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
      <td>
	<?php echo $this->_tpl_vars['group']['groupName']; ?>

      </td>
      <td>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminEditGroup",'arg3' => "groupId=".($this->_tpl_vars['groupId'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'edit'), $this);?>
</a>

	<?php if (( $this->_tpl_vars['group']['can']['delete'] )): ?>
	  &nbsp;
	  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminDeleteGroup",'arg3' => "groupId=".($this->_tpl_vars['groupId'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'delete'), $this);?>
</a>
	<?php endif; ?>

	<?php if (( $this->_tpl_vars['group']['can']['editUsers'] )): ?>
	  &nbsp;
	  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminEditGroupUsers",'arg3' => "groupId=".($this->_tpl_vars['groupId'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'members'), $this);?>
</a>
	<?php endif; ?>
      </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>

  <?php if (! empty ( $this->_tpl_vars['form']['list']['filter'] ) || ( $this->_tpl_vars['form']['list']['maxPages'] > 1 )): ?>
    <input type="text"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[list][filter]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['list']['filter']; ?>
"/>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][filterBySubstring]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Filter'), $this);?>
"/>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][filterClear]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Clear'), $this);?>
"/>
  <?php endif; ?>

  <?php if (( ! empty ( $this->_tpl_vars['form']['list']['filter'] ) )): ?>
    <span style="white-space: nowrap">
      &nbsp;
      <?php echo $this->_reg_objects['g'][0]->text(array('one' => "%d group matches your filter",'many' => "%d groups match your filter",'count' => $this->_tpl_vars['form']['list']['count'],'arg1' => $this->_tpl_vars['form']['list']['count']), $this);?>

    </span>
  <?php endif; ?>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][create]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Create Group'), $this);?>
"/>
</div>
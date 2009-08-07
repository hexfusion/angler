<?php /* Smarty version 2.6.10, created on 2006-03-08 18:01:26
         compiled from gallery:modules/core/templates/ItemMove.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:modules/core/templates/ItemMove.tpl', 149, false),array('modifier', 'repeat', 'gallery:modules/core/templates/ItemMove.tpl', 220, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Move an Item'), $this);?>
 </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['moved'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php echo $this->_reg_objects['g'][0]->text(array('one' => "Successfully moved %d item",'many' => "Successfully moved %d items",'count' => $this->_tpl_vars['status']['moved']['count'],'arg1' => $this->_tpl_vars['status']['moved']['count']), $this);?>

</h2></div>
<?php endif;  if (! empty ( $this->_tpl_vars['form']['error'] )): ?>
<div class="gbBlock"><h2 class="giError">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "There was a problem processing your request."), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
<?php if (empty ( $this->_tpl_vars['ItemMove']['peers'] )): ?>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This album contains no items to move."), $this);?>

  </p>
<?php else: ?>
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Source'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Choose the items you want to move'), $this);?>

    <?php if (( $this->_tpl_vars['ItemMove']['numPages'] > 1 )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(page %d of %d)",'arg1' => $this->_tpl_vars['ItemMove']['page'],'arg2' => $this->_tpl_vars['ItemMove']['numPages']), $this);?>

      <br/>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Items selected here will remain selected when moving between pages."), $this);?>

      <?php if (! empty ( $this->_tpl_vars['ItemMove']['selectedIds'] )): ?>
	<br/>
	<?php echo $this->_reg_objects['g'][0]->text(array('one' => "One item selected on other pages.",'many' => "%d items selected on other pages.",'count' => $this->_tpl_vars['ItemMove']['selectedIdCount'],'arg1' => $this->_tpl_vars['ItemMove']['selectedIdCount']), $this);?>

      <?php endif; ?>
    <?php endif; ?>
  </p>

  <script type="text/javascript">
    //<![CDATA[
    // Validation code.  This Javascript snippet validates the source and destination
    // information to make sure that you don't attempt to do something that you shouldn't,
    // ie, it will help you to avoid the situation where you try to move an item into
    // an album where you don't have the right permissions.	 This is only a hint to the
    // UI -- we perform the same permission checks on the server side (so circumventing
    // or disabling this javascript won't allow you to do something that you don't have
    // permission to do anyway).

    // The user can add data items to these albums
    permission = new Array();
    permission['addDataItem'] = new Array();
    <?php $_from = $this->_tpl_vars['ItemMove']['albumIds']['addDataItem']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id']):
?>
      permission['addDataItem'][<?php echo $this->_tpl_vars['id']; ?>
] = 1;
    <?php endforeach; endif; unset($_from); ?>

    // The user can add album items to these albums
    permission['addAlbumItem'] = new Array();
    <?php $_from = $this->_tpl_vars['ItemMove']['albumIds']['addAlbumItem']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id']):
?>
      permission['addAlbumItem'][<?php echo $this->_tpl_vars['id']; ?>
] = 1;
    <?php endforeach; endif; unset($_from); ?>

    // Check what the destination album accepts.  If it can handle data items and
    // album items then we're done.	 Else, scan the selected items and make sure that
    // we haven't selected something that we can't handle.	If we have, then remove
    // the selection and alert the user.
    function checkPermissions(form, quiet) {
      destinationId = form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[destination]"), $this);?>
'].value;
      if (permission['addDataItem'][destinationId] && permission['addAlbumItem'][destinationId]) {
	<?php $_from = $this->_tpl_vars['ItemMove']['peerTypes']['album']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['unused']):
?>
	  form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].disabled = 0;
	<?php endforeach; endif; unset($_from); ?>
	<?php $_from = $this->_tpl_vars['ItemMove']['peerTypes']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['unused']):
?>
	  form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].disabled = 0;
	<?php endforeach; endif; unset($_from); ?>
	return;
      }

      changed = 0;
      if (permission['addDataItem'][destinationId]) {
	<?php $_from = $this->_tpl_vars['ItemMove']['peerTypes']['album']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['unused']):
?>
	  if (form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].checked) {
	    form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].checked = 0;
	    changed = 1;
	  }
	  form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].disabled = 1;
	<?php endforeach; endif; unset($_from); ?>
	<?php $_from = $this->_tpl_vars['ItemMove']['peerTypes']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['unused']):
?>
	  form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].disabled = 0;
	<?php endforeach; endif; unset($_from); ?>
	if (changed && !quiet) {
	  alert("<?php echo $this->_reg_objects['g'][0]->text(array('text' => "The destination you chose does not accept sub-albums, so all sub-albums have been deselected."), $this);?>
");
	}
      } else {
	<?php $_from = $this->_tpl_vars['ItemMove']['peerTypes']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['unused']):
?>
	  if (form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].checked) {
	    form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].checked = 0;
	    changed = 1;
	  }
	  form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].disabled = 1;
	<?php endforeach; endif; unset($_from); ?>
	<?php $_from = $this->_tpl_vars['ItemMove']['peerTypes']['album']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['unused']):
?>
	  form.elements['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['id'])."]"), $this);?>
'].disabled = 0;
	<?php endforeach; endif; unset($_from); ?>
	if (changed && !quiet) {
	  alert("<?php echo $this->_reg_objects['g'][0]->text(array('text' => "The destination you chose only accepts sub-albums, so all non-albums have been deselected."), $this);?>
");
	}
      }
    }

    function setCheck(val) {
      var frm = document.getElementById('itemAdminForm');
      <?php $_from = $this->_tpl_vars['ItemMove']['peers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['peer']):
?>
	frm.elements['g2_form[selectedIds][<?php echo $this->_tpl_vars['peer']['id']; ?>
]'].checked = val;
      <?php endforeach; endif; unset($_from); ?>
    }
    function invertCheck(val) {
      var frm = document.getElementById('itemAdminForm');
      <?php $_from = $this->_tpl_vars['ItemMove']['peers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['peer']):
?>
	frm.elements['g2_form[selectedIds][<?php echo $this->_tpl_vars['peer']['id']; ?>
]'].checked = !frm.elements['g2_form[selectedIds][<?php echo $this->_tpl_vars['peer']['id']; ?>
]'].checked;
      <?php endforeach; endif; unset($_from); ?>
    }
    //]]>
  </script>

  <table>
    <colgroup width="60"/>
    <?php $_from = $this->_tpl_vars['ItemMove']['peers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['peer']):
?>
    <?php $this->assign('peerItemId', $this->_tpl_vars['peer']['id']); ?>
    <tr>
      <td align="center">
	<?php if (isset ( $this->_tpl_vars['peer']['thumbnail'] )): ?>
	  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['peerItemId'])), $this);?>
">
	    <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['peer'],'image' => $this->_tpl_vars['peer']['thumbnail'],'maxSize' => 50,'class' => 'giThumbnail'), $this);?>

	  </a>
	<?php else: ?>
	  &nbsp;
	<?php endif; ?>
      </td><td>
	<input type="checkbox" id="cb_<?php echo $this->_tpl_vars['peerItemId']; ?>
"<?php if ($this->_tpl_vars['peer']['selected']): ?> checked="checked"<?php endif; ?>
	 name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['peerItemId'])."]"), $this);?>
"/>
      </td><td>
	<label for="cb_<?php echo $this->_tpl_vars['peerItemId']; ?>
">
	  <?php echo ((is_array($_tmp=@$this->_tpl_vars['peer']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['peer']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['peer']['pathComponent'])); ?>

	</label>
	<i>
	  <?php if (isset ( $this->_tpl_vars['ItemMove']['peerTypes']['data'][$this->_tpl_vars['peerItemId']] )): ?>
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(data)"), $this);?>

	  <?php endif; ?>
	  <?php if (isset ( $this->_tpl_vars['ItemMove']['peerTypes']['album'][$this->_tpl_vars['peerItemId']] )): ?>
	    <?php if (isset ( $this->_tpl_vars['ItemMove']['peerDescendentCounts'][$this->_tpl_vars['peerItemId']] )): ?>
	      <?php echo $this->_reg_objects['g'][0]->text(array('one' => "(album containing %d item)",'many' => "(album containing %d items)",'count' => $this->_tpl_vars['ItemMove']['peerDescendentCounts'][$this->_tpl_vars['peerItemId']],'arg1' => $this->_tpl_vars['ItemMove']['peerDescendentCounts'][$this->_tpl_vars['peerItemId']]), $this);?>

	    <?php else: ?>
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(empty album)"), $this);?>

	    <?php endif; ?>
	  <?php endif; ?>
	</i>

	<?php if (! empty ( $this->_tpl_vars['form']['error']['source'][$this->_tpl_vars['peerItemId']]['permission']['delete'] )): ?>
	<div class="giError">
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You are not allowed to move this item away from here."), $this);?>
<br/>
	</div>
	<?php endif; ?>
	<?php if (! empty ( $this->_tpl_vars['form']['error']['source'][$this->_tpl_vars['peerItemId']]['permission']['addAlbumItem'] )): ?>
	<div class="giError">
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You are not allowed to move an album to the chosen destination."), $this);?>
<br/>
	</div>
	<?php endif; ?>
	<?php if (! empty ( $this->_tpl_vars['form']['error']['source'][$this->_tpl_vars['peerItemId']]['permission']['addDataItem'] )): ?>
	<div class="giError">
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You are not allowed to move an item to the chosen destination."), $this);?>
<br/>
	</div>
	<?php endif; ?>
	<?php if (! empty ( $this->_tpl_vars['form']['error']['source'][$this->_tpl_vars['peerItemId']]['selfMove'] )): ?>
	<div class="giError">
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You cannot move an album into its own subtree."), $this);?>
<br/>
	</div>
	<?php endif; ?>
      </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>

  <input type="button" class="inputTypeButton" onclick="setCheck(1)"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][checkall]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Check All'), $this);?>
"/>
  <input type="button" class="inputTypeButton" onclick="setCheck(0)"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][checknone]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Check None'), $this);?>
"/>
  <input type="button" class="inputTypeButton" onclick="invertCheck()"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][invert]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invert'), $this);?>
"/>

  <?php if (( $this->_tpl_vars['ItemMove']['page'] > 1 )): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][previous]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Previous Page'), $this);?>
"/>
  <?php endif; ?>
  <?php if (( $this->_tpl_vars['ItemMove']['page'] < $this->_tpl_vars['ItemMove']['numPages'] )): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][next]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Next Page'), $this);?>
"/>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Destination'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Choose a new album for them'), $this);?>

  </p>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[destination]"), $this);?>
"
   onchange="checkPermissions(this.form)">
    <?php $_from = $this->_tpl_vars['ItemMove']['albumTree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['album']):
?>
      <option value="<?php echo $this->_tpl_vars['album']['data']['id']; ?>
"
	      <?php if (( $this->_tpl_vars['album']['data']['id'] == $this->_tpl_vars['form']['destination'] )): ?>selected="selected"<?php endif; ?>>
	<?php echo ((is_array($_tmp="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")) ? $this->_run_mod_handler('repeat', true, $_tmp, $this->_tpl_vars['album']['depth']) : smarty_modifier_repeat($_tmp, $this->_tpl_vars['album']['depth'])); ?>
--
	<?php echo ((is_array($_tmp=@$this->_tpl_vars['album']['data']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['album']['data']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['album']['data']['pathComponent'])); ?>

      </option>
    <?php endforeach; endif; unset($_from); ?>
  </select>

  <?php if (! empty ( $this->_tpl_vars['form']['error']['destination']['permission'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You don't have the permission to add items in this album"), $this);?>

  </div>
  <?php endif; ?>
  <?php if (! empty ( $this->_tpl_vars['form']['error']['destination']['empty'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No destination chosen'), $this);?>

  </div>
  <?php endif; ?>
</div>

<div class="gbBlock gcBackground1">
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'page'), $this);?>
" value="<?php echo $this->_tpl_vars['ItemMove']['page']; ?>
"/>
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[numPerPage]"), $this);?>
" value="<?php echo $this->_tpl_vars['ItemMove']['numPerPage']; ?>
"/>
  <?php $_from = $this->_tpl_vars['ItemMove']['selectedIds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['selectedId']):
?>
    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['selectedId'])."]"), $this);?>
" value="on"/>
  <?php endforeach; endif; unset($_from); ?>

  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][move]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Move'), $this);?>
"/>
  <?php if ($this->_tpl_vars['ItemMove']['canCancel']): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
  <?php endif;  endif; ?>
</div>
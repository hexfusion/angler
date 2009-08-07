<?php /* Smarty version 2.6.10, created on 2006-03-08 17:30:49
         compiled from gallery:modules/core/templates/ItemDelete.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:modules/core/templates/ItemDelete.tpl', 82, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Delete Items'), $this);?>
 </h2>
</div>

<?php if (( isset ( $this->_tpl_vars['status']['deleted'] ) )): ?>
<div class="gbBlock">
  <?php if (( $this->_tpl_vars['status']['deleted']['count'] == 0 )): ?>
    <h2 class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No items were selected for deletion'), $this);?>

  <?php else: ?>
    <h2 class="giSuccess">
      <?php echo $this->_reg_objects['g'][0]->text(array('one' => "Successfully deleted %d item",'many' => "Successfully deleted %d items",'count' => $this->_tpl_vars['status']['deleted']['count'],'arg1' => $this->_tpl_vars['status']['deleted']['count']), $this);?>

  <?php endif; ?>
</h2></div>
<?php endif; ?>

<div class="gbBlock">
<?php if (empty ( $this->_tpl_vars['ItemDelete']['peers'] )): ?>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'This album contains no items to delete'), $this);?>

  </p>
<?php else: ?>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Choose the items you want to delete'), $this);?>

    <?php if (( $this->_tpl_vars['ItemDelete']['numPages'] > 1 )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(page %d of %d)",'arg1' => $this->_tpl_vars['ItemDelete']['page'],'arg2' => $this->_tpl_vars['ItemDelete']['numPages']), $this);?>

      <br/>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Items selected here will remain selected when moving between pages."), $this);?>

      <?php if (! empty ( $this->_tpl_vars['ItemDelete']['selectedIds'] )): ?>
	 <br/>
	 <?php echo $this->_reg_objects['g'][0]->text(array('one' => "One item selected on other pages.",'many' => "%d items selected on other pages.",'count' => $this->_tpl_vars['ItemDelete']['selectedIdCount'],'arg1' => $this->_tpl_vars['ItemDelete']['selectedIdCount']), $this);?>

      <?php endif; ?>
    <?php endif; ?>
  </p>

  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'page'), $this);?>
" value="<?php echo $this->_tpl_vars['ItemDelete']['page']; ?>
"/>
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[formname]"), $this);?>
" value="DeleteItem"/>

  <script type="text/javascript">
    // <![CDATA[
    function setCheck(val) {
      var frm = document.getElementById('itemAdminForm');
      <?php $_from = $this->_tpl_vars['ItemDelete']['peers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['peer']):
?>
	frm.elements['g2_form[selectedIds][<?php echo $this->_tpl_vars['peer']['id']; ?>
]'].checked = val;
      <?php endforeach; endif; unset($_from); ?>
    }
    function invertCheck(val) {
      var frm = document.getElementById('itemAdminForm');
      <?php $_from = $this->_tpl_vars['ItemDelete']['peers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['peer']):
?>
	frm.elements['g2_form[selectedIds][<?php echo $this->_tpl_vars['peer']['id']; ?>
]'].checked =
	    !frm.elements['g2_form[selectedIds][<?php echo $this->_tpl_vars['peer']['id']; ?>
]'].checked;
      <?php endforeach; endif; unset($_from); ?>
    }
    // ]]>
  </script>

  <table>
    <colgroup width="60"/>
    <?php $_from = $this->_tpl_vars['ItemDelete']['peers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['peer']):
?>
    <tr>
      <td align="center">
	<?php if (isset ( $this->_tpl_vars['peer']['thumbnail'] )): ?>
	  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['peer']['id'])), $this);?>
">
	    <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['peer'],'image' => $this->_tpl_vars['peer']['thumbnail'],'maxSize' => 50,'class' => 'giThumbnail'), $this);?>

	  </a>
	<?php else: ?>
	  &nbsp;
	<?php endif; ?>
      </td><td>
	<input type="checkbox" id="cb_<?php echo $this->_tpl_vars['peer']['id']; ?>
"<?php if ($this->_tpl_vars['peer']['selected']): ?> checked="checked"<?php endif; ?>
	 name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['peer']['id'])."]"), $this);?>
"/>
      </td><td>
	<label for="cb_<?php echo $this->_tpl_vars['peer']['id']; ?>
">
	  <?php echo ((is_array($_tmp=@$this->_tpl_vars['peer']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['peer']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['peer']['pathComponent'])); ?>

	</label>
	<i>
	  <?php if (isset ( $this->_tpl_vars['ItemDelete']['peerTypes']['data'][$this->_tpl_vars['peer']['id']] )): ?>
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(data)"), $this);?>

	  <?php endif; ?>
	  <?php if (isset ( $this->_tpl_vars['ItemDelete']['peerTypes']['album'][$this->_tpl_vars['peer']['id']] )): ?>
	    <?php if (isset ( $this->_tpl_vars['ItemDelete']['peerDescendentCounts'][$this->_tpl_vars['peer']['id']] )): ?>
	      <?php echo $this->_reg_objects['g'][0]->text(array('one' => "(album containing %d item)",'many' => "(album containing %d items)",'count' => $this->_tpl_vars['ItemDelete']['peerDescendentCounts'][$this->_tpl_vars['peer']['id']],'arg1' => $this->_tpl_vars['ItemDelete']['peerDescendentCounts'][$this->_tpl_vars['peer']['id']]), $this);?>

	    <?php else: ?>
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(empty album)"), $this);?>

	    <?php endif; ?>
	  <?php endif; ?>
	</i>
      </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>

  <?php $_from = $this->_tpl_vars['ItemDelete']['selectedIds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['selectedId']):
?>
    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[selectedIds][".($this->_tpl_vars['selectedId'])."]"), $this);?>
" value="on"/>
  <?php endforeach; endif; unset($_from); ?>
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[numPerPage]"), $this);?>
" value="<?php echo $this->_tpl_vars['ItemDelete']['numPerPage']; ?>
"/>

  <br/>

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

  <?php if (( $this->_tpl_vars['ItemDelete']['page'] > 1 )): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][previous]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Previous Page'), $this);?>
"/>
  <?php endif; ?>
  <?php if (( $this->_tpl_vars['ItemDelete']['page'] < $this->_tpl_vars['ItemDelete']['numPages'] )): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][next]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Next Page'), $this);?>
"/>
  <?php endif; ?>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][delete]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Delete'), $this);?>
"/>
  <?php if ($this->_tpl_vars['ItemDelete']['canCancel']): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
  <?php endif;  endif; ?>
</div>
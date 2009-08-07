<?php /* Smarty version 2.6.10, created on 2006-04-02 13:10:50
         compiled from gallery:modules/core/templates/ItemEditCaptions.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Edit Captions'), $this);?>

    <?php if (( $this->_tpl_vars['ItemEditCaptions']['numPages'] > 1 )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(page %d of %d)",'arg1' => $this->_tpl_vars['ItemEditCaptions']['page'],'arg2' => $this->_tpl_vars['ItemEditCaptions']['numPages']), $this);?>

    <?php endif; ?>
  </h2>
</div>

<?php if (! empty ( $this->_tpl_vars['status'] )): ?>
<div class="gbBlock">
  <?php if ($this->_tpl_vars['status']['errorCount'] > 0): ?>
  <h2 class="giError">
    <?php if ($this->_tpl_vars['status']['successCount'] > 0): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'There were errors saving some items'), $this);?>

    <?php else: ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'There were errors saving all items'), $this);?>

    <?php endif; ?>
  </h2>
  <?php elseif ($this->_tpl_vars['status']['successCount'] > 0): ?>
  <h2 class="giSuccess">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Successfully saved all items'), $this);?>

  </h2>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if (empty ( $this->_tpl_vars['form']['items'] )): ?>
  <div class="gbBlock">
    <p class="giDescription">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'This album contains no items'), $this);?>

    </p>
  </div>
<?php else: ?>

<input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'page'), $this);?>
" value="<?php echo $this->_tpl_vars['ItemEditCaptions']['page']; ?>
"/>
<input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[formname]"), $this);?>
" value="EditCaption"/>
<input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[numPerPage]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['numPerPage']; ?>
"/>

<?php $_from = $this->_tpl_vars['form']['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['itemLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['itemLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['itemLoop']['iteration']++;
?>
<div class="gbBlock">
  <input type="hidden"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[items][".($this->_tpl_vars['item']['id'])."][serialNumber]"), $this);?>
" value="<?php echo $this->_tpl_vars['item']['serialNumber']; ?>
"/>

  <?php if (isset ( $this->_tpl_vars['item']['thumbnail'] )):  echo '<div style="float: right"><a href="';  echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['item']['id'])), $this); echo '">';  echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['item'],'image' => $this->_tpl_vars['item']['thumbnail'],'maxSize' => 150,'class' => 'giThumbnail'), $this); echo '</a></div>';  endif; ?>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Title'), $this);?>
 </h4>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/core/templates/MarkupBar.tpl", 'smarty_include_vars' => array('viewL10domain' => 'modules_core','element' => "title_".($this->_tpl_vars['item']['id']),'firstMarkupBar' => ($this->_foreach['itemLoop']['iteration'] <= 1))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <input type="text" id="title_<?php echo $this->_tpl_vars['item']['id']; ?>
" size="60"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[items][".($this->_tpl_vars['item']['id'])."][title]"), $this);?>
" value="<?php echo $this->_tpl_vars['item']['title']; ?>
"/>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Summary'), $this);?>
 </h4>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/core/templates/MarkupBar.tpl", 'smarty_include_vars' => array('viewL10domain' => 'modules_core','element' => "summary_".($this->_tpl_vars['item']['id']),'firstMarkupBar' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <input type="text" id="summary_<?php echo $this->_tpl_vars['item']['id']; ?>
" size="60"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[items][".($this->_tpl_vars['item']['id'])."][summary]"), $this);?>
" value="<?php echo $this->_tpl_vars['item']['summary']; ?>
"/>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Keywords'), $this);?>
 </h4>
  <textarea id="keywords_<?php echo $this->_tpl_vars['item']['id']; ?>
" rows="2" cols="60"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[items][".($this->_tpl_vars['item']['id'])."][keywords]"), $this);?>
"><?php echo $this->_tpl_vars['item']['keywords']; ?>
</textarea>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Description'), $this);?>
 </h4>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/core/templates/MarkupBar.tpl", 'smarty_include_vars' => array('viewL10domain' => 'modules_core','element' => "description_".($this->_tpl_vars['item']['id']),'firstMarkupBar' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <textarea id="description_<?php echo $this->_tpl_vars['item']['id']; ?>
" rows="4" cols="60"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[items][".($this->_tpl_vars['item']['id'])."][description]"), $this);?>
"><?php echo $this->_tpl_vars['item']['description']; ?>
</textarea>

  <?php if (isset ( $this->_tpl_vars['status'][$this->_tpl_vars['item']['id']]['saved'] )): ?>
  <div class="giSuccess">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Saved successfully."), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status'][$this->_tpl_vars['item']['id']]['obsolete'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This item was modified by somebody else at the same time.  Your changes were lost."), $this);?>

  </div>
  <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['status'][$this->_tpl_vars['item']['id']]['permissionDenied'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You do not have permissions to modify this item."), $this);?>

  </div>
  <?php endif; ?>
</div>
<?php endforeach; endif; unset($_from); ?>

<div class="gbBlock gcBackground1">
  <?php if ($this->_tpl_vars['ItemEditCaptions']['canCancel']): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save][done]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save and Done'), $this);?>
"/>
  <?php else: ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save][stay]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <?php endif; ?>

  <?php if (( $this->_tpl_vars['ItemEditCaptions']['page'] > 1 )): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save][previous]"), $this);?>
"
     value="&laquo; <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Save and Edit Previous %s",'arg1' => $this->_tpl_vars['form']['numPerPage']), $this);?>
"/>
  <?php endif; ?>

  <?php if (( $this->_tpl_vars['ItemEditCaptions']['page'] < $this->_tpl_vars['ItemEditCaptions']['numPages'] )): ?>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save][next]"), $this);?>
"
     value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Save and Edit Next %s",'arg1' => $this->_tpl_vars['form']['numPerPage']), $this);?>
 &raquo;"/>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['ItemEditCaptions']['canCancel']): ?>
      <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
  <?php endif; ?>
</div>
<?php endif; ?>
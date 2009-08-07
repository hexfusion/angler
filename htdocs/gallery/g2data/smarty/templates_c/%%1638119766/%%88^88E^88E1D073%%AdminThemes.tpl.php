<?php /* Smarty version 2.6.10, created on 2006-03-08 18:05:21
         compiled from gallery:modules/core/templates/AdminThemes.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'gallery:modules/core/templates/AdminThemes.tpl', 91, false),array('function', 'html_options', 'gallery:modules/core/templates/AdminThemes.tpl', 185, false),array('modifier', 'default', 'gallery:modules/core/templates/AdminThemes.tpl', 263, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Gallery Themes'), $this);?>
 </h2>
</div>

<?php if (! empty ( $this->_tpl_vars['status'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php if (isset ( $this->_tpl_vars['status']['activated'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully activated theme %s",'arg1' => $this->_tpl_vars['status']['activated']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['deactivated'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully deactivated theme %s",'arg1' => $this->_tpl_vars['status']['deactivated']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['installed'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully installed theme %s",'arg1' => $this->_tpl_vars['status']['installed']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['uninstalled'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully uninstalled theme %s",'arg1' => $this->_tpl_vars['status']['uninstalled']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['upgraded'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully upgraded theme %s",'arg1' => $this->_tpl_vars['status']['upgraded']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['savedTheme'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Successfully saved theme settings'), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['savedDefaults'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Successfully saved default album settings'), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['restoredTheme'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Restored theme settings'), $this);?>

  <?php endif; ?>
</h2></div>
<?php endif; ?>

<div class="gbTabBar">
  <?php if (( $this->_tpl_vars['AdminThemes']['mode'] == 'config' )): ?>
    <span class="giSelected o"><span>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'All Themes'), $this);?>

    </span></span>
  <?php else: ?>
    <span class="o"><span>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminThemes",'arg3' => "mode=config"), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'All Themes'), $this);?>
</a>
    </span></span>
  <?php endif; ?>

  <?php if (( $this->_tpl_vars['AdminThemes']['mode'] == 'defaults' )): ?>
    <span class="giSelected o"><span>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Defaults'), $this);?>

    </span></span>
  <?php else: ?>
    <span class="o"><span>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminThemes",'arg3' => "mode=defaults"), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Defaults'), $this);?>
</a>
    </span></span>
  <?php endif; ?>

  <?php $_from = $this->_tpl_vars['AdminThemes']['themes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['themeId'] => $this->_tpl_vars['theme']):
?>
  <?php if ($this->_tpl_vars['theme']['active']): ?>
    <?php if (( $this->_tpl_vars['AdminThemes']['mode'] == 'editTheme' ) && ( $this->_tpl_vars['AdminThemes']['themeId'] == $this->_tpl_vars['themeId'] )): ?>
      <span class="giSelected o"><span>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['name'],'l10Domain' => $this->_tpl_vars['theme']['l10Domain']), $this);?>

      </span></span>
    <?php else: ?>
      <span class="o"><span>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminThemes",'arg3' => "mode=editTheme",'arg4' => "themeId=".($this->_tpl_vars['themeId'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['name'],'l10Domain' => $this->_tpl_vars['theme']['l10Domain']), $this);?>
</a>
      </span></span>
    <?php endif; ?>
  <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</div>

<?php if (( $this->_tpl_vars['AdminThemes']['mode'] == 'config' )): ?>
<div class="gbBlock">
  <table class="gbDataTable"><tr>
    <th> &nbsp; </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Theme Name'), $this);?>
 </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Version'), $this);?>
 </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Installed'), $this);?>
 </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Description'), $this);?>
 </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Actions'), $this);?>
 </th>
  </tr>

  <?php $_from = $this->_tpl_vars['AdminThemes']['themes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['themeId'] => $this->_tpl_vars['theme']):
?>
    <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
      <td>
	<?php if ($this->_tpl_vars['theme']['state'] == 'install'): ?>
	<img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-install.gif"), $this);?>
" width="13" height="13"
	 alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Not Installed"), $this);?>
" />
	<?php endif; ?>
	<?php if ($this->_tpl_vars['theme']['state'] == 'active'): ?>
	<img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-active.gif"), $this);?>
" width="13" height="13"
	 alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Active"), $this);?>
" />
	<?php endif; ?>
	<?php if ($this->_tpl_vars['theme']['state'] == 'inactive'): ?>
	<img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-inactive.gif"), $this);?>
" width="13" height="13"
	 alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Inactive"), $this);?>
" />
	<?php endif; ?>
	<?php if ($this->_tpl_vars['theme']['state'] == 'upgrade'): ?>
	<img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-upgrade.gif"), $this);?>
" width="13" height="13"
	 alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Upgrade Required (Inactive)"), $this);?>
" />
	<?php endif; ?>
	<?php if ($this->_tpl_vars['theme']['state'] == 'incompatible'): ?>
	<img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-incompatible.gif"), $this);?>
" width="13" height="13"
	 alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Incompatible Theme (Inactive)"), $this);?>
" />
	<?php endif; ?>
      </td>

      <td<?php if (( $this->_tpl_vars['themeId'] == $this->_tpl_vars['AdminThemes']['defaultThemeId'] )): ?> style="font-weight: bold"<?php endif; ?>>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['name'],'l10Domain' => $this->_tpl_vars['theme']['l10Domain']), $this);?>

      </td>

      <td align="center"<?php if (( $this->_tpl_vars['themeId'] == $this->_tpl_vars['AdminThemes']['defaultThemeId'] )): ?> style="font-weight: bold"<?php endif; ?>>
	<?php echo $this->_tpl_vars['theme']['version']; ?>

      </td>

      <td align="center"<?php if (( $this->_tpl_vars['themeId'] == $this->_tpl_vars['AdminThemes']['defaultThemeId'] )): ?> style="font-weight: bold"<?php endif; ?>>
	<?php echo $this->_tpl_vars['theme']['installedVersion']; ?>

      </td>

      <td<?php if (( $this->_tpl_vars['themeId'] == $this->_tpl_vars['AdminThemes']['defaultThemeId'] )): ?> style="font-weight: bold"<?php endif; ?>>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['description'],'l10Domain' => $this->_tpl_vars['theme']['l10Domain']), $this);?>

	<?php if ($this->_tpl_vars['theme']['state'] == 'incompatible'): ?>
	  <br/>
	  <span class="giError">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Incompatible theme!"), $this);?>

	    <?php if ($this->_tpl_vars['theme']['api']['required']['core'] != $this->_tpl_vars['theme']['api']['provided']['core']): ?>
	      <br/>
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Core API Required: %s (available: %s)",'arg1' => $this->_tpl_vars['theme']['api']['required']['core'],'arg2' => $this->_tpl_vars['theme']['api']['provided']['core']), $this);?>

	    <?php endif; ?>
	    <?php if ($this->_tpl_vars['theme']['api']['required']['theme'] != $this->_tpl_vars['theme']['api']['provided']['theme']): ?>
	      <br/>
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Theme API Required: %s (available: %s)",'arg1' => $this->_tpl_vars['theme']['api']['required']['theme'],'arg2' => $this->_tpl_vars['theme']['api']['provided']['theme']), $this);?>

	    <?php endif; ?>
	  </span>
	<?php endif; ?>
      </td>

      <td<?php if (( $this->_tpl_vars['themeId'] == $this->_tpl_vars['AdminThemes']['defaultThemeId'] )): ?> style="font-weight: bold"<?php endif; ?>>
	<?php if (( $this->_tpl_vars['themeId'] == $this->_tpl_vars['AdminThemes']['defaultThemeId'] )): ?>
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(default)"), $this);?>

	<?php endif; ?>
	<?php if (( ! empty ( $this->_tpl_vars['theme']['action'] ) )): ?>
	  <?php $_from = $this->_tpl_vars['theme']['action']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['actions'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['actions']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['action']):
        $this->_foreach['actions']['iteration']++;
 echo '';  if (! ($this->_foreach['actions']['iteration'] <= 1)):  echo '<br/>';  endif;  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['action']['params']), $this); echo '">';  echo $this->_tpl_vars['action']['text'];  echo '</a>';  endforeach; endif; unset($_from); ?>
	<?php else: ?>
	  &nbsp;
	<?php endif; ?>
      </td>
    </tr>
  <?php endforeach; endif; unset($_from); ?>
  </table>
</div>
<?php endif; ?>

<?php if (( $this->_tpl_vars['AdminThemes']['mode'] == 'defaults' )): ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Defaults'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "These are default display settings for albums in your gallery.  They can be overridden in each album."), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Default sort order'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][orderBy]"), $this);?>
" onchange="pickOrder()">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminThemes']['orderByList'],'selected' => $this->_tpl_vars['form']['default']['orderBy']), $this);?>

      </select>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][orderDirection]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminThemes']['orderDirectionList'],'selected' => $this->_tpl_vars['form']['default']['orderDirection']), $this);?>

      </select>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'with'), $this);?>

      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][presort]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminThemes']['presortList'],'selected' => $this->_tpl_vars['form']['default']['presort']), $this);?>

      </select>
      <script type="text/javascript">
	// <![CDATA[
	function pickOrder() {
	  var list = '<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][orderBy]"), $this);?>
';
	  var frm = document.getElementById('siteAdminForm');
	  var index = frm.elements[list].selectedIndex;
	  list = '<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][orderDirection]"), $this);?>
';
	  frm.elements[list].disabled = (index == 0) ?1:0;
	  list = '<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][presort]"), $this);?>
';
	  frm.elements[list].disabled = (index == 0) ?1:0;
	}
	pickOrder();
	// ]]>
      </script>
    </td>
  </tr>
  <tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Default theme'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][theme]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminThemes']['themeList'],'selected' => $this->_tpl_vars['form']['default']['theme']), $this);?>

      </select>
     </td>
  </tr>
  <tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'New albums'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][newAlbumsUseDefaults]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminThemes']['newAlbumsUseDefaultsList'],'selected' => $this->_tpl_vars['form']['default']['newAlbumsUseDefaults']), $this);?>

      </select>
    </td>
  </tr></table>
</div>

<div class="gbBlock gcBackground1">
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'mode'), $this);?>
" value="defaults"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][saveDefaults]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
</div>
<?php endif; ?>

<?php if (( $this->_tpl_vars['AdminThemes']['mode'] == 'editTheme' )): ?>
<div class="gbBlock">
  <h3>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "%s Theme Settings",'arg1' => $this->_tpl_vars['AdminThemes']['themes'][$this->_tpl_vars['AdminThemes']['themeId']]['name']), $this);?>

  </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "These are the global settings for the theme.  They can be overridden at the album level."), $this);?>

  </p>

  <?php if (isset ( $this->_tpl_vars['AdminThemes']['customTemplate'] )): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['AdminThemes']['customTemplate']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['AdminThemes']['themes'][$this->_tpl_vars['AdminThemes']['themeId']]['l10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>

  <?php if (! empty ( $this->_tpl_vars['AdminThemes']['settings'] )): ?>
    <table class="gbDataTable">
      <?php $_from = $this->_tpl_vars['AdminThemes']['settings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['setting']):
?>
	<tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
	  <td>
	    <?php echo $this->_tpl_vars['setting']['name']; ?>

	  </td>
	  <td>
	    <?php if (( $this->_tpl_vars['setting']['type'] == 'text-field' )): ?>
	      <input type="text" size="<?php echo ((is_array($_tmp=@$this->_tpl_vars['setting']['typeParams']['size'])) ? $this->_run_mod_handler('default', true, $_tmp, 6) : smarty_modifier_default($_tmp, 6)); ?>
"
	       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[key][".($this->_tpl_vars['setting']['key'])."]"), $this);?>
"
	       value="<?php echo $this->_tpl_vars['form']['key'][$this->_tpl_vars['setting']['key']]; ?>
"/>
	    <?php elseif (( $this->_tpl_vars['setting']['type'] == 'single-select' )): ?>
	      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[key][".($this->_tpl_vars['setting']['key'])."]"), $this);?>
">
		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['setting']['choices'],'selected' => $this->_tpl_vars['form']['key'][$this->_tpl_vars['setting']['key']]), $this);?>

	      </select>
	    <?php elseif (( $this->_tpl_vars['setting']['type'] == 'checkbox' )): ?>
	      <input type="checkbox"<?php if (! empty ( $this->_tpl_vars['setting']['value'] )): ?> checked="checked"<?php endif; ?>
	       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[key][".($this->_tpl_vars['setting']['key'])."]"), $this);?>
" />
	    <?php elseif (( $this->_tpl_vars['setting']['type'] == 'block-list' )): ?>
	      <table>
		<tr>
		  <td style="text-align: right;">
		    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Available'), $this);?>

		  </td>
		  <td>
		    <select id="blocksAvailableList_<?php echo $this->_tpl_vars['setting']['key']; ?>
"
			    onchange="bsw_selectToUse('<?php echo $this->_tpl_vars['setting']['key']; ?>
');">
		      <option value=""><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Choose a block'), $this);?>
</option>
		    </select>
		  </td>
		  <td class="bsw_BlockCommands">
		    <span id="bsw_AddButton_<?php echo $this->_tpl_vars['setting']['key']; ?>
"
			  onclick="bsw_addBlock('<?php echo $this->_tpl_vars['setting']['key']; ?>
');" class="bsw_ButtonDisabled">
		      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add'), $this);?>

		    </span>
		  </td>
		</tr>

		<tr>
		  <td style="text-align: right; vertical-align: top;">
		    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Selected'), $this);?>

		  </td>
		  <td id="bsw_UsedBlockList_<?php echo $this->_tpl_vars['setting']['key']; ?>
">
		    <select id="blocksUsedList_<?php echo $this->_tpl_vars['setting']['key']; ?>
" size="10"
			    onchange="bsw_selectToChange('<?php echo $this->_tpl_vars['setting']['key']; ?>
');">
		      <option value=""></option> 		    </select>
		  </td>
		  <td class="bsw_BlockCommands">
		    <span style="display: block"
			  id="bsw_RemoveButton_<?php echo $this->_tpl_vars['setting']['key']; ?>
"
			  onclick="bsw_removeBlock('<?php echo $this->_tpl_vars['setting']['key']; ?>
');"
			  class="bsw_ButtonDisabled">
		      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Remove'), $this);?>

		    </span>

		    <span style="display: block"
			  id="bsw_MoveUpButton_<?php echo $this->_tpl_vars['setting']['key']; ?>
"
			  onclick="bsw_moveUp('<?php echo $this->_tpl_vars['setting']['key']; ?>
');"
			  class="bsw_ButtonDisabled">
		      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Move Up'), $this);?>

		    </span>

		    <span style="display: block"
			  id="bsw_MoveDownButton_<?php echo $this->_tpl_vars['setting']['key']; ?>
"
			  onclick="bsw_moveDown('<?php echo $this->_tpl_vars['setting']['key']; ?>
');"
			  class="bsw_ButtonDisabled">
		      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Move Down'), $this);?>

		    </span>
		  </td>
		</tr>
		<tr>
		  <td id="bsw_BlockOptions_<?php echo $this->_tpl_vars['setting']['key']; ?>
" colspan="3">
		  </td>
		</tr>
	      </table>
	      <input type="hidden"
		     id="albumBlockValue_<?php echo $this->_tpl_vars['setting']['key']; ?>
" size="60"
		     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[key][".($this->_tpl_vars['setting']['key'])."]"), $this);?>
"
		     value="<?php echo $this->_tpl_vars['form']['key'][$this->_tpl_vars['setting']['key']]; ?>
"/>

	      <script type="text/javascript">
		// <![CDATA[
		var block;
		var tmp;
		<?php $_from = $this->_tpl_vars['AdminThemes']['availableBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['moduleId'] => $this->_tpl_vars['blocks']):
?>
		  <?php $_from = $this->_tpl_vars['blocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['blockName'] => $this->_tpl_vars['block']):
?>
		    block = bsw_addAvailableBlock("<?php echo $this->_tpl_vars['setting']['key']; ?>
", "<?php echo $this->_tpl_vars['moduleId']; ?>
.<?php echo $this->_tpl_vars['blockName']; ?>
",
			    "<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['block']['description'],'l10Domain' => "modules_".($this->_tpl_vars['moduleId'])), $this);?>
");
		    <?php if (! empty ( $this->_tpl_vars['block']['vars'] )): ?>
		      <?php $_from = $this->_tpl_vars['block']['vars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['varKey'] => $this->_tpl_vars['varInfo']):
?>
			tmp = new Array();
			<?php if (( $this->_tpl_vars['varInfo']['type'] == 'choice' )): ?>
			  <?php $_from = $this->_tpl_vars['varInfo']['choices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['choiceKey'] => $this->_tpl_vars['choiceValue']):
?>
			    tmp["<?php echo $this->_tpl_vars['choiceKey']; ?>
"] = "<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['choiceValue'],'l10Domain' => "modules_".($this->_tpl_vars['moduleId'])), $this);?>
";
			  <?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			block.addVariable("<?php echo $this->_tpl_vars['varKey']; ?>
", "<?php echo $this->_tpl_vars['varInfo']['default']; ?>
",
			  "<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['varInfo']['description'],'l10Domain' => "modules_".($this->_tpl_vars['moduleId'])), $this);?>
",
			  "<?php echo $this->_tpl_vars['varInfo']['type']; ?>
", tmp);
	                <?php if (! empty ( $this->_tpl_vars['varInfo']['overrides'] )): ?>
	                <?php $_from = $this->_tpl_vars['varInfo']['overrides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['override']):
?>
	                block.addVariableOverride("<?php echo $this->_tpl_vars['varKey']; ?>
", "<?php echo $this->_tpl_vars['override']; ?>
");
                        <?php endforeach; endif; unset($_from); ?>
	                <?php endif; ?>
		      <?php endforeach; endif; unset($_from); ?>
		    <?php endif; ?>
		  <?php endforeach; endif; unset($_from); ?>
		<?php endforeach; endif; unset($_from); ?>
				bsw_initAdminForm("<?php echo $this->_tpl_vars['setting']['key']; ?>
", "<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Parameter'), $this);?>
",
						    "<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Value'), $this);?>
");
		// ]]>
	      </script>
	    <?php endif; ?>
	  </td>
	</tr>

	<?php if (isset ( $this->_tpl_vars['form']['error']['key'][$this->_tpl_vars['setting']['key']]['invalid'] )): ?>
	<tr>
	  <td colspan="2" class="giError">
	    <?php echo $this->_tpl_vars['form']['errorMessage'][$this->_tpl_vars['setting']['key']]; ?>

	  </td>
	</tr>
	<?php endif; ?>
      <?php endforeach; endif; unset($_from); ?>
    </table>
  <?php elseif (! isset ( $this->_tpl_vars['AdminThemes']['customTemplate'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'There are no settings for this theme'), $this);?>

  <?php endif; ?>
</div>

<?php if (isset ( $this->_tpl_vars['AdminThemes']['customTemplate'] ) || ! empty ( $this->_tpl_vars['AdminThemes']['settings'] )): ?>
<div class="gbBlock gcBackground1">
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'themeId'), $this);?>
" value="<?php echo $this->_tpl_vars['AdminThemes']['themeId']; ?>
"/>
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'mode'), $this);?>
" value="editTheme"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][saveTheme]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][undoTheme]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
</div>
<?php endif;  endif; ?>
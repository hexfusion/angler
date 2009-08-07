<?php /* Smarty version 2.6.10, created on 2006-03-08 18:06:41
         compiled from gallery:themes/hybrid/templates/sidebar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:themes/hybrid/templates/sidebar.tpl', 25, false),array('modifier', 'markup', 'gallery:themes/hybrid/templates/sidebar.tpl', 25, false),)), $this); ?>
<div id="gsSidebar" class="gcBorder1">

    <div class="gbBlock">
    <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.SystemLinks",'order' => "core.SiteAdmin core.YourAccount core.Login core.Logout",'othersAt' => 4), $this);?>

  </div>

    <?php if (! empty ( $this->_tpl_vars['theme']['parents'] )): ?>
  <div class="gbBlock">
    <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Navigation'), $this);?>
 </h3>
    <ul>
    <?php $_from = $this->_tpl_vars['theme']['parents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['parent']):
?>
      <li>
	&raquo;
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['parent']['id'])), $this);?>
">
	  <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['parent']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['parent']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['parent']['pathComponent'])))) ? $this->_run_mod_handler('markup', true, $_tmp, 'strip') : smarty_modifier_markup($_tmp, 'strip')); ?>

	</a>
      </li>
    <?php endforeach; endif; unset($_from); ?>
    </ul>
  </div>
  <?php endif; ?>

    <div class="gbBlock">
    <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Display Options'), $this);?>
 </h3>
    <ul>
      <li>
	<a id="dtl_link" href="" onclick="album_detailsonoff();this.blur();return false">
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide Details'), $this);?>

	</a>
      </li>
      <li>
	<a id="lnk_link" href="" onclick="album_itemlinksonoff();this.blur();return false">
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide Item Links'), $this);?>

	</a>
      </li>
      <?php if ($this->_tpl_vars['user']['isRegisteredUser']): ?>
      <li>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'View'), $this);?>
:&nbsp;
	<?php if (( $this->_tpl_vars['theme']['guestPreviewMode'] )): ?>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=core.ShowItem",'arg2' => "guestPreviewMode=0",'arg3' => "return=1"), $this);?>
"><?php echo $this->_tpl_vars['user']['userName']; ?>
</a> | <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'guest'), $this);?>

	<?php else: ?>
	<?php echo $this->_tpl_vars['user']['userName']; ?>
 | <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=core.ShowItem",'arg2' => "guestPreviewMode=1",'arg3' => "return=1"), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'guest'), $this);?>
</a>
	<?php endif; ?>
      </li>
      <?php endif; ?>
    </ul>
    <ul style="margin-top: 4px">
      <li>
	<strong><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Slideshow Options'), $this);?>
:</strong>
      </li>
      <li>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Delay'), $this);?>
:&nbsp;
	<select id="slide_delay" onchange="slide_setdelay(this.value)">
	 <option value="3"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '3 seconds'), $this);?>
</option>
	 <option selected="selected" value="5"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '5 seconds'), $this);?>
</option>
	 <option value="7"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '7 seconds'), $this);?>
</option>
	 <option value="10"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '10 seconds'), $this);?>
</option>
	 <option value="15"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '15 seconds'), $this);?>
</option>
	 <option value="20"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '20 seconds'), $this);?>
</option>
	</select>
	<br/>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Direction'), $this);?>
:&nbsp;
	<select id="slide_order" onchange="slide_setorder(this.value)">
	 <option selected="selected" value="1"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'forward'), $this);?>
</option>
	 <option value="-1"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'reverse'), $this);?>
</option>
	 <option value="0"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'random'), $this);?>
</option>
	</select>
      </li>
    </ul>
  </div>

    <?php $_from = $this->_tpl_vars['theme']['params']['sidebarBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
    <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1'],'class' => 'gbBlock'), $this);?>

  <?php endforeach; endif; unset($_from); ?>

    <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.EmergencyEditItemLink",'class' => 'gbBlock','checkSidebarBlocks' => true,'checkAlbumBlocks' => true), $this);?>

</div>
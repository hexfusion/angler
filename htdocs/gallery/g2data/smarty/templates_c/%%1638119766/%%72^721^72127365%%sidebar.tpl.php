<?php /* Smarty version 2.6.10, created on 2006-03-08 18:18:12
         compiled from gallery:themes/PGtheme/templates/sidebar.tpl */ ?>

<?php if ($this->_tpl_vars['theme']['params']['sidebar']): ?>
  <div id="gsSidebar" class="gcBorder1">
    <?php $_from = $this->_tpl_vars['theme']['params']['sidebarBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
      <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1'],'class' => 'gbBlock'), $this);?>

    <?php endforeach; endif; unset($_from); ?>
    <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.NavigationLinks",'class' => 'gbBlock'), $this);?>

  </div>
<?php else: ?>
  <table class="sidebarF">
    <tr>
      <td style="text-align: center">
        <div id="gsSidebarF" class="gcBackground1 gcBorder1">
          <?php $_from = $this->_tpl_vars['theme']['params']['sidebarBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
            <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1'],'class' => 'gbBlock'), $this);?>

          <?php endforeach; endif; unset($_from); ?>
        </div>
      </td>
    </tr>
  </table>
<?php endif; ?>
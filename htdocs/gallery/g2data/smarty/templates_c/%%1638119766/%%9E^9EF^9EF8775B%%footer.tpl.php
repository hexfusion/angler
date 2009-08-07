<?php /* Smarty version 2.6.10, created on 2006-03-08 18:18:12
         compiled from gallery:themes/PGtheme/templates/footer.tpl */ ?>
<table width="100%">
  <tr>
    <td nowrap="nowrap">
      <div>
        <div style="position:relative; float:left">
<?php if (! empty ( $this->_tpl_vars['theme']['params']['valbtn'] )): ?>
          <?php echo $this->_reg_objects['g'][0]->logoButton(array('type' => 'validation'), $this);?>

<?php endif; ?>
<?php if (! empty ( $this->_tpl_vars['theme']['params']['g2btn'] )): ?>
          <?php echo $this->_reg_objects['g'][0]->logoButton(array('type' => 'gallery2'), $this);?>

<?php endif; ?>
<?php if (! empty ( $this->_tpl_vars['theme']['params']['g2verbtn'] )): ?>
          <?php echo $this->_reg_objects['g'][0]->logoButton(array('type' => "gallery2-version"), $this);?>

<?php endif; ?>
<?php if (! empty ( $this->_tpl_vars['theme']['params']['donbtn'] )): ?>
          <?php echo $this->_reg_objects['g'][0]->logoButton(array('type' => 'donate'), $this);?>

<?php endif; ?>
<?php if (! empty ( $this->_tpl_vars['theme']['params']['pgbtn'] )): ?>
          <a href="http://www.pedrogilberto.net/gallery2/theme.html" title="(c) Theme by www.PedroGilberto.net, download here (version:'1.0RC7')">
            <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "themes/PGtheme/images/pgtheme.gif"), $this);?>
" alt="PG Theme" longdesc="PG Theme" style="border: 0" title="(c) Theme by www.PedroGilberto.net, download here (version:'1.0RC7')"/></a>
<?php endif; ?>
<?php if (! empty ( $this->_tpl_vars['theme']['params']['pgcpbtn'] )): ?>
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
          <a href="http://www.pedrogilberto.net/gallery2/theme.html" title="Colorpack - <?php echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
, by www. PedroGilberto.net (included on PG Theme)">
            <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/colorpack/packs/"), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/cpack.gif" alt="(<?php echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
 Colorpack)" longdesc="Colorpack (<?php echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
)" style="border: 0" title="Colorpack - <?php echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
, by www. PedroGilberto.net (included on PG Theme)"/></a>
          <?php endif; ?>
<?php endif; ?>
        </div>
        <div style="position:relative; float:right">
          <font  size='1' face='arial'>(C) 2005 -</font> 
          <a href="mailto:<?php echo $this->_tpl_vars['theme']['params']['email']; ?>
" title="<?php echo $this->_tpl_vars['theme']['params']['email']; ?>
"><?php echo $this->_tpl_vars['theme']['params']['site']; ?>
</a>
          <font size='1' face='arial'> (all rights reserved)</font>
        </div>
      </div>
    </td>
  </tr>
</table>
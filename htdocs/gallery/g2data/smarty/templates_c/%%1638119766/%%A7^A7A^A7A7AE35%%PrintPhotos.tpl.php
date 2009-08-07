<?php /* Smarty version 2.6.10, created on 2006-03-11 14:25:18
         compiled from gallery:modules/shutterfly/templates/PrintPhotos.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>shutterfly</title>
    <script type="text/javascript"><?php echo '
    // <![CDATA[
    function go() {
      if (document.cookie.indexOf(\'G2_shutterfly=\') >= 0) {
	var d = new Date();
	d.setTime(d.getTime() - 10000);
	document.cookie = \'G2_shutterfly=0;expires=\' + d.toUTCString();
	document.getElementById(\'shutterflyForm\').submit();
      } else {
	history.back();
      }
    }
   // ]]>
  '; ?>
</script>
</head>
<body onload="go()">
<form action="http://www.shutterfly.com/c4p/UpdateCart.jsp" method="POST" id="shutterflyForm">
  <input type="hidden" name="protocol" value="SFP,100"/>
  <input type="hidden" name="pid" value="C4PP"/>
  <input type="hidden" name="psid" value="GALL"/>
  <input type="hidden" name="referid" value="gallery"/>
  <input type="hidden" name="returl" value="<?php echo $this->_tpl_vars['PrintPhotos']['returnUrl']; ?>
"/>
  <input type="hidden" name="addim" value="1"/>
  <input type="hidden" name="imnum" value="<?php echo $this->_tpl_vars['PrintPhotos']['count']; ?>
"/>
  <?php $_from = $this->_tpl_vars['PrintPhotos']['entries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['entry']):
?>
    <input type="hidden" name="imraw-<?php echo $this->_tpl_vars['index']; ?>
" value="<?php echo $this->_tpl_vars['entry']['imageUrl']; ?>
"/>
    <input type="hidden" name="imrawwidth-<?php echo $this->_tpl_vars['index']; ?>
" value="<?php echo $this->_tpl_vars['entry']['imageWidth']; ?>
"/>
    <input type="hidden" name="imrawheight-<?php echo $this->_tpl_vars['index']; ?>
" value="<?php echo $this->_tpl_vars['entry']['imageHeight']; ?>
"/>
    <?php if (isset ( $this->_tpl_vars['entry']['thumbUrl'] )): ?>
      <input type="hidden" name="imthumb-<?php echo $this->_tpl_vars['index']; ?>
" value="<?php echo $this->_tpl_vars['entry']['thumbUrl']; ?>
"/>
      <input type="hidden" name="imthumbwidth-<?php echo $this->_tpl_vars['index']; ?>
" value="<?php echo $this->_tpl_vars['entry']['thumbWidth']; ?>
"/>
      <input type="hidden" name="imthumbheight-<?php echo $this->_tpl_vars['index']; ?>
" value="<?php echo $this->_tpl_vars['entry']['thumbHeight']; ?>
"/>
    <?php endif; ?>
    <?php if (! empty ( $this->_tpl_vars['entry']['item']['title'] )): ?>
      <input type="hidden" name="imbkprnta-<?php echo $this->_tpl_vars['index']; ?>
" value="<?php echo $this->_tpl_vars['entry']['item']['title']; ?>
"/>
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</form>
<noscript>
  <input type="submit"/>
</noscript>
</body>
</html>
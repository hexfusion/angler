<?php /* Smarty version 2.6.10, created on 2006-03-08 18:21:33
         compiled from gallery:themes/PGtheme/templates/navigatorThumbs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:themes/PGtheme/templates/navigatorThumbs.tpl', 8, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['theme']['params']['MTends'] )): ?>
<?php echo $this->_reg_objects['g'][0]->callback(array('type' => "core.LoadPeers",'item' => ((is_array($_tmp=@$this->_tpl_vars['item'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['item']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['item'])),'windowSize' => ((is_array($_tmp=@$this->_tpl_vars['theme']['params']['peerWindowSize'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)),'loadThumbnails' => true), $this);?>

<?php else: ?>
<?php echo $this->_reg_objects['g'][0]->callback(array('type' => "core.LoadPeers",'item' => ((is_array($_tmp=@$this->_tpl_vars['item'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['item']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['item'])),'windowSize' => ((is_array($_tmp=@$this->_tpl_vars['theme']['params']['peerWindowSize'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)),'loadThumbnails' => true,'addEnds' => false), $this);?>

<?php endif; ?>

<?php $this->assign('data', $this->_tpl_vars['block']['core']['LoadPeers']); ?>

<?php $this->assign('over', $this->_tpl_vars['theme']['params']['MToversize']); ?>
<?php $this->assign('MTbg', $this->_tpl_vars['theme']['params']['MTbg']); ?>
<?php $this->assign('MTbgborder', $this->_tpl_vars['theme']['params']['MTbgborder']); ?>
<?php $this->assign('MToff', $this->_tpl_vars['theme']['params']['opacityMT']); ?>
<?php $this->assign('MTover', $this->_tpl_vars['theme']['params']['opacityMTover']); ?>
<?php $this->assign('thumbsCol', $this->_tpl_vars['theme']['params']['columnsMT']); ?>
<?php $this->assign('thumbsSize', $this->_tpl_vars['theme']['params']['sizeMT']); ?>
<?php $this->assign('thumbsSizeOver', $this->_tpl_vars['thumbsSize']+$this->_tpl_vars['over']); ?>
<?php $this->assign('tdThumbs', $this->_tpl_vars['thumbsSizeOver']+6); ?>
<?php $this->assign('trThumbs', $this->_tpl_vars['thumbsCol']); ?>

<?php $this->assign('tableThumbs', $this->_tpl_vars['thumbsCol']*$this->_tpl_vars['tdThumbs']+4+$this->_tpl_vars['thumbsCol']*4); ?>

<?php $this->assign('lastIndex', 0); ?>
<?php $this->assign('Index', 0); ?>
<table><tr><td>
<table class="gcBackground2 gcBorder2" style="border: 1px solid #<?php echo $this->_tpl_vars['MTbgborder']; ?>
; background-color: #<?php echo $this->_tpl_vars['MTbg']; ?>
;" width="<?php echo $this->_tpl_vars['tableThumbs']; ?>
" align="center">
  <tr>

  <?php if (! empty ( $this->_tpl_vars['data']['peers'] )): ?>
    <?php $_from = $this->_tpl_vars['data']['peers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['peer']):
?>
      <?php $this->assign('nome', $this->_tpl_vars['peer']['id']); ?>
      <?php $this->assign('largura', "pic".($this->_tpl_vars['nome']).".width"); ?>
      <?php $this->assign('altura', "pic".($this->_tpl_vars['nome']).".height"); ?>
      <?php $this->assign('title', $this->_tpl_vars['peer']['title']); ?>

      <?php if (! empty ( $this->_tpl_vars['theme']['params']['MTmore'] )): ?>
        <?php if (empty ( $this->_tpl_vars['theme']['params']['MTends'] )): ?>
          <?php if (( $this->_tpl_vars['peer']['peerIndex'] - $this->_tpl_vars['lastIndex'] ) != 1): ?>
            <?php $this->assign('first', 1); ?>
          <?php endif; ?>
          <?php if (( $this->_tpl_vars['peer']['id'] != $this->_tpl_vars['theme']['navigator']['last']['item']['id'] ) && ( $this->_tpl_vars['peer']['peerIndex'] != $this->_tpl_vars['data']['thisPeerIndex'] )): ?>
            <?php $this->assign('last', 1); ?>
          <?php else: ?>
            <?php $this->assign('last', 0); ?>
          <?php endif; ?>
        <?php else: ?>
          <?php if (( $this->_tpl_vars['peer']['peerIndex'] - $this->_tpl_vars['lastIndex'] > 1 )): ?>
            <?php if (( $this->_tpl_vars['lastIndex'] == 1 ) && ( $this->_tpl_vars['peer']['id'] != $this->_tpl_vars['theme']['navigator']['first']['item']['id'] ) && ( $this->_tpl_vars['peer']['peerIndex'] != $this->_tpl_vars['data']['thisPeerIndex'] )): ?>
              <?php $this->assign('first', 1); ?>
            <?php else: ?>
              <?php $this->assign('last', 1); ?>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (( $this->_tpl_vars['peer']['peerIndex'] == $this->_tpl_vars['data']['thisPeerIndex'] )): ?>
    <td height="<?php echo $this->_tpl_vars['tdThumbs']; ?>
" width="<?php echo $this->_tpl_vars['tdThumbs']; ?>
" style="text-align:center">
      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['peer'],'image' => $this->_tpl_vars['peer']['thumbnail'],'name' => 'active','maxSize' => $this->_tpl_vars['thumbsSize'],'alt' => ($this->_tpl_vars['title']),'longdesc' => ($this->_tpl_vars['title']),'class' => 'thumbSmallSelected','style' => "text-align:center",'title' => ($this->_tpl_vars['title'])), $this);?>

    </td>
      <?php else: ?>
    <td height="<?php echo $this->_tpl_vars['tdThumbs']; ?>
" width="<?php echo $this->_tpl_vars['tdThumbs']; ?>
" style="text-align:center">
          <a onmouseover="pic<?php echo $this->_tpl_vars['nome']; ?>
.width=pic<?php echo $this->_tpl_vars['nome']; ?>
.width=<?php echo $this->_tpl_vars['largura']; ?>
+<?php echo $this->_tpl_vars['over']; ?>
; pic<?php echo $this->_tpl_vars['nome']; ?>
.height=<?php echo $this->_tpl_vars['altura']; ?>
+<?php echo $this->_tpl_vars['over']; ?>
; pic<?php echo $this->_tpl_vars['nome']; ?>
.className='thumbSmallOn opacity<?php echo $this->_tpl_vars['MTover']; ?>
'" onmouseout="pic<?php echo $this->_tpl_vars['nome']; ?>
.width=<?php echo $this->_tpl_vars['largura']; ?>
-<?php echo $this->_tpl_vars['over']; ?>
; pic<?php echo $this->_tpl_vars['nome']; ?>
.height=<?php echo $this->_tpl_vars['altura']; ?>
-<?php echo $this->_tpl_vars['over']; ?>
; pic<?php echo $this->_tpl_vars['nome']; ?>
.className='thumbSmall opacity<?php echo $this->_tpl_vars['MToff']; ?>
'"
onclick="pic<?php echo $this->_tpl_vars['nome']; ?>
.width=<?php echo $this->_tpl_vars['largura']; ?>
; pic<?php echo $this->_tpl_vars['nome']; ?>
.height=<?php echo $this->_tpl_vars['altura']; ?>
;
pic<?php echo $this->_tpl_vars['nome']; ?>
.className='thumbSmallClik opacity<?php echo $this->_tpl_vars['MToff']; ?>
'"
href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['peer']['id'])), $this);?>
">
            <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['peer'],'image' => $this->_tpl_vars['peer']['thumbnail'],'name' => "pic".($this->_tpl_vars['nome']),'maxSize' => $this->_tpl_vars['thumbsSize'],'alt' => ($this->_tpl_vars['title']),'longdesc' => ($this->_tpl_vars['title']),'title' => ($this->_tpl_vars['title']),'class' => "thumbSmall opacity".($this->_tpl_vars['MToff']),'style' => "text-align:center"), $this);?>
</a>
    </td>
      <?php endif; ?>

      <?php $this->assign('Index', $this->_tpl_vars['Index']+1); ?>
      <?php $this->assign('lastIndex', $this->_tpl_vars['peer']['peerIndex']); ?>

      <?php if (( $this->_tpl_vars['Index'] == $this->_tpl_vars['thumbsCol'] )): ?>
  </tr>
  <tr>
      <?php $this->assign('Index', '0'); ?>

      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  </tr>
</table>
</td></tr><tr><td>
<?php if (! empty ( $this->_tpl_vars['theme']['params']['MTmorepics'] )): ?>
<table width="100%">
  <tr>
    <td width="50%" style="text-align:left">
      <?php if (( $this->_tpl_vars['first'] == 1 )): ?>
        <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
          <a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['first']['urlParams']), $this);?>
" onmouseover="firstpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
firston.gif'" onmouseout="firstpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
first.gif'">
            <img name="firstpicMT" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
first.gif" border="0" alt="first" title="first" longdesc="first" id="firstpicMT" class="navpic"/></a>
        <?php else: ?>
          <a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['first']['urlParams']), $this);?>
" onmouseover="firstpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/firston.gif'" onmouseout="firstpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/first.gif'">
            <img name="firstpicMT" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/first.gif" border="0" alt="first" title="first" longdesc="first" id="firstpicMT" class="navpic"/></a>
        <?php endif; ?>
        <?php echo $this->_tpl_vars['theme']['params']['MTmoretext']; ?>

      <?php endif; ?>
    </td>
    <td style="text-align:right">
      <?php if (( $this->_tpl_vars['last'] == 1 )): ?>
        <?php echo $this->_tpl_vars['theme']['params']['MTmoretext']; ?>

        <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
          <a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['last']['urlParams']), $this);?>
" onmouseover="lastpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
laston.gif'" onmouseout="lastpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
last.gif'">
            <img name="lastpicMT" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
last.gif" border="0" alt="last" title="last" longdesc="last" id="lastpicMT" class="navpic"/></a>
        <?php else: ?>
          <a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['last']['urlParams']), $this);?>
" onmouseover="lastpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/laston.gif'" onmouseout="lastpicMT.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/last.gif'">
            <img name="lastpicMT" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/last.gif" border="0" alt="last" title="last" longdesc="last" id="lastpicMT" class="navpic"/></a>
        <?php endif; ?>
      <?php endif; ?>
    </td>
  </tr>
</table>
<?php else: ?>
<table width="100%">
  <tr>
    <td width="50%" style="text-align:left">
      <?php if (( $this->_tpl_vars['first'] == 1 )): ?>
        << <?php echo $this->_tpl_vars['theme']['params']['MTmoretext']; ?>

      <?php endif; ?>
    </td>
    <td style="text-align:right">
      <?php if (( $this->_tpl_vars['last'] == 1 )): ?>
        <?php echo $this->_tpl_vars['theme']['params']['MTmoretext']; ?>
 >>
      <?php endif; ?>
    </td>
  </tr>
</table>
<?php endif; ?>
</td></tr></table>
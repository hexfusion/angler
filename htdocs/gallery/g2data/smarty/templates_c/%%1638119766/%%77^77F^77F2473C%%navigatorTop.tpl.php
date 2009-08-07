<?php /* Smarty version 2.6.10, created on 2006-03-08 18:21:15
         compiled from gallery:themes/PGtheme/templates/navigatorTop.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'markup', 'gallery:themes/PGtheme/templates/navigatorTop.tpl', 93, false),)), $this); ?>
<?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>

<?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>     
<?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>

  <table align="right" border="0" width="100%"  style="height:25px">
    <tr>
      <td width="50%" style="text-align:right; vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom">

              <div class="actions">
  <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumActions'] ) && ( $this->_tpl_vars['photoItem'] != 1 )): ?>
    <?php if ($this->_tpl_vars['user']['isAdmin']): ?>
      <?php if ($this->_tpl_vars['theme']['guestPreviewMode'] != 1): ?>
      <div style="height:25px">
      <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemLinks",'item' => $this->_tpl_vars['child'],'links' => $this->_tpl_vars['child']['itemLinks']), $this);?>

      </div>
      <?php endif; ?>
    <?php else: ?>
      <?php if ($this->_tpl_vars['theme']['cart'] == 1): ?>
                <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_carton.gif'"
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
        <?php else: ?>
title="Add to Cart"
        <?php endif; ?>
>
                  <img id="cart<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
        <?php else: ?>
title="Add to Cart"
        <?php endif; ?>
class="navtoppic"/></a>
      <?php endif; ?>      <?php endif; ?>   <?php endif; ?> 
  <?php if ($this->_tpl_vars['photoItem'] != 1): ?>
    <?php if (( $this->_tpl_vars['theme']['comments'] == 1 )): ?>
      <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddAlbumComments'] ) && isset ( $this->_tpl_vars['theme']['permissions']['comment_add'] )): ?>                <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcommentson.gif' "
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
        <?php else: ?>
title="Add a Comment"
        <?php endif; ?>
>
                  <img id="addcom<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
        <?php else: ?>
title="Add a Comment"
        <?php endif; ?>
class="navtoppic" /></a>
      <?php endif; ?>       <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumComments'] ) && isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] )): ?>              <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>

        <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>
        <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                <a href="javascript:void(0)"
onmouseout= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif'" onclick="ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','visible')"
onmouseover= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
commentson.gif'; ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','hidden')"
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
          <?php else: ?>
title="View Comments"
          <?php endif; ?>
>
                  <img id="blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif" alt="View Comments" longdesc="View Comments" 
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
          <?php else: ?>
title="View Comments"
          <?php endif; ?>
class="navtoppic"/></a>
        <?php endif; ?>

                                       <div id="blocks<?php echo $this->_tpl_vars['item']['id']; ?>
" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blocks<?php echo $this->_tpl_vars['item']['id']; ?>
')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             <?php echo $this->_reg_objects['g'][0]->block(array('type' => "comment.ViewComments",'item' => $this->_tpl_vars['theme']['item']), $this);?>

                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                                 <a onclick="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                                               <?php else: ?>
                                                 <a onclick="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                                               <?php endif; ?>

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>


      <?php endif; ?>     <?php endif; ?>   <?php endif; ?>               </div> 

</td></tr></table>
</td>
      <td style="text-align:right;vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom" nowrap="nowrap" >

        <?php unset($this->_sections['parent']);
$this->_sections['parent']['name'] = 'parent';
$this->_sections['parent']['loop'] = is_array($_loop=$this->_tpl_vars['theme']['parents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['parent']['show'] = true;
$this->_sections['parent']['max'] = $this->_sections['parent']['loop'];
$this->_sections['parent']['step'] = 1;
$this->_sections['parent']['start'] = $this->_sections['parent']['step'] > 0 ? 0 : $this->_sections['parent']['loop']-1;
if ($this->_sections['parent']['show']) {
    $this->_sections['parent']['total'] = $this->_sections['parent']['loop'];
    if ($this->_sections['parent']['total'] == 0)
        $this->_sections['parent']['show'] = false;
} else
    $this->_sections['parent']['total'] = 0;
if ($this->_sections['parent']['show']):

            for ($this->_sections['parent']['index'] = $this->_sections['parent']['start'], $this->_sections['parent']['iteration'] = 1;
                 $this->_sections['parent']['iteration'] <= $this->_sections['parent']['total'];
                 $this->_sections['parent']['index'] += $this->_sections['parent']['step'], $this->_sections['parent']['iteration']++):
$this->_sections['parent']['rownum'] = $this->_sections['parent']['iteration'];
$this->_sections['parent']['index_prev'] = $this->_sections['parent']['index'] - $this->_sections['parent']['step'];
$this->_sections['parent']['index_next'] = $this->_sections['parent']['index'] + $this->_sections['parent']['step'];
$this->_sections['parent']['first']      = ($this->_sections['parent']['iteration'] == 1);
$this->_sections['parent']['last']       = ($this->_sections['parent']['iteration'] == $this->_sections['parent']['total']);
?>
          <?php if (! $this->_sections['parent']['last']): ?>
            <?php if ($this->_sections['parent']['first']): ?>
	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id'])), $this);?>
"
onmouseout="gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
gal.gif'" onmouseover="gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
galon.gif'"
title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
                <img id="gal" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
gal.gif" alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>
            <?php else: ?>
	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id'])), $this);?>
"
onmouseout="album<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
album.gif'"
onmouseover="album<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
albumon.gif'" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
	        <img id="album<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
album.gif"  alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>
            <?php endif; ?>
          <?php else: ?>
            <?php if (! $this->_sections['parent']['first']): ?>
 	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id'])), $this);?>
" onmouseout= "thumb.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
thumbnails.gif'"
onmouseover= "thumb.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
thumbnailson.gif'" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
                <img id="thumb" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
thumbnails.gif" alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>
              <?php if (( $this->_tpl_vars['theme']['params']['Showslideshow'] && $this->_tpl_vars['theme']['slideshow'] )): ?>
                <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=slideshow:Slideshow",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id']),'arg4' => "xx='exp'",'arg5' => "assign='yy'",'arg6' => "yy='22'"), $this);?>
"
onmouseout= "slide.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
slideshow.gif'"
onmouseover= "slide.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
slideshowon.gif' " title="SlideShow">
                  <img id="slide" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
slideshow.gif" alt="SlideShow" longdesc="SlideShow" title="SlideShow" class="navtoppic"/></a>


              <?php endif; ?>
            <?php else: ?>
	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']),'arg3' => "highlightId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index_next']]['id'])), $this);?>
"
onmouseout= "gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
gal.gif'"
onmouseover= "gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
galon.gif'
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
	        <img id="gal" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
gal.gif" style='border: 0' alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>

            <?php endif; ?>
          <?php endif; ?>
        <?php endfor; endif; ?>
</td></tr></table>
      </td>
    </tr>
  </table>

<?php else: ?>       
  <table align="right" border="0" width="100%"  style="height:25px">
    <tr>
      <td width="50%" style="text-align:right; vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom">

              <div class="actions">
  <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumActions'] ) && ( $this->_tpl_vars['photoItem'] != 1 )): ?>
    <?php if ($this->_tpl_vars['user']['isAdmin']): ?>
      <?php if ($this->_tpl_vars['theme']['guestPreviewMode'] != 1): ?>
      <div style="height:25px">
      <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemLinks",'item' => $this->_tpl_vars['child'],'links' => $this->_tpl_vars['child']['itemLinks']), $this);?>

      </div>
      <?php endif; ?>
    <?php else: ?>
      <?php if ($this->_tpl_vars['theme']['cart'] == 1): ?>
                <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_carton.gif'"
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
        <?php else: ?>
title="Add to Cart"
        <?php endif; ?>
>
                  <img id="cart<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
        <?php else: ?>
title="Add to Cart"
        <?php endif; ?>
class="navtoppic"/></a>
      <?php endif; ?>      <?php endif; ?>   <?php endif; ?> 
  <?php if ($this->_tpl_vars['photoItem'] != 1): ?>
    <?php if (( $this->_tpl_vars['theme']['comments'] == 1 )): ?>
      <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddAlbumComments'] ) && isset ( $this->_tpl_vars['theme']['permissions']['comment_add'] )): ?>                <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcommentson.gif'         "        <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
        <?php else: ?>
title="Add a Comment"
        <?php endif; ?>
>
                  <img id="addcom<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
        <?php else: ?>
title="Add a Comment"
        <?php endif; ?>
class="navtoppic" /></a>
      <?php endif; ?>       <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumComments'] ) && isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] )): ?>              <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>

        <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>
        <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                <a href="javascript:void(0)"
onmouseout= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif'" onclick="ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','visible')"
onmouseover= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/commentson.gif'; ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','hidden')"
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
          <?php else: ?>
title="View Comments"
          <?php endif; ?>>
                  <img id="blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif" alt="View Comments" longdesc="View Comments" 
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
          <?php else: ?>
title="View Comments"
          <?php endif; ?>
class="navtoppic"/></a>
        <?php endif; ?>





                                       <div id="blocks<?php echo $this->_tpl_vars['item']['id']; ?>
" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blocks<?php echo $this->_tpl_vars['item']['id']; ?>
')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             <?php echo $this->_reg_objects['g'][0]->block(array('type' => "comment.ViewComments",'item' => $this->_tpl_vars['theme']['item']), $this);?>

                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                                 <a onclick="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                                               <?php else: ?>
                                                 <a onclick="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                                               <?php endif; ?>

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>

      <?php endif; ?>     <?php endif; ?>   <?php endif; ?>               </div> 

</td></tr></table>
</td>
      <td style="text-align:right; vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom" nowrap="nowrap" >

        <?php unset($this->_sections['parent']);
$this->_sections['parent']['name'] = 'parent';
$this->_sections['parent']['loop'] = is_array($_loop=$this->_tpl_vars['theme']['parents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['parent']['show'] = true;
$this->_sections['parent']['max'] = $this->_sections['parent']['loop'];
$this->_sections['parent']['step'] = 1;
$this->_sections['parent']['start'] = $this->_sections['parent']['step'] > 0 ? 0 : $this->_sections['parent']['loop']-1;
if ($this->_sections['parent']['show']) {
    $this->_sections['parent']['total'] = $this->_sections['parent']['loop'];
    if ($this->_sections['parent']['total'] == 0)
        $this->_sections['parent']['show'] = false;
} else
    $this->_sections['parent']['total'] = 0;
if ($this->_sections['parent']['show']):

            for ($this->_sections['parent']['index'] = $this->_sections['parent']['start'], $this->_sections['parent']['iteration'] = 1;
                 $this->_sections['parent']['iteration'] <= $this->_sections['parent']['total'];
                 $this->_sections['parent']['index'] += $this->_sections['parent']['step'], $this->_sections['parent']['iteration']++):
$this->_sections['parent']['rownum'] = $this->_sections['parent']['iteration'];
$this->_sections['parent']['index_prev'] = $this->_sections['parent']['index'] - $this->_sections['parent']['step'];
$this->_sections['parent']['index_next'] = $this->_sections['parent']['index'] + $this->_sections['parent']['step'];
$this->_sections['parent']['first']      = ($this->_sections['parent']['iteration'] == 1);
$this->_sections['parent']['last']       = ($this->_sections['parent']['iteration'] == $this->_sections['parent']['total']);
?>
          <?php if (! $this->_sections['parent']['last']): ?>
            <?php if ($this->_sections['parent']['first']): ?>
	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id'])), $this);?>
"
onmouseout="gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/gal.gif'" onmouseover="gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/galon.gif'" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
                <img id="gal" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/gal.gif" alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>
            <?php else: ?>
	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id'])), $this);?>
"
onmouseout="album<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/album.gif'"
onmouseover="album<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/albumon.gif'" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
	        <img id="album<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/album.gif"  alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>
            <?php endif; ?>
          <?php else: ?>
            <?php if (! $this->_sections['parent']['first']): ?>
 	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id'])), $this);?>
" onmouseout= "thumb.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/thumbnails.gif'"
onmouseover= "thumb.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/thumbnailson.gif' " title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
                <img id="thumb" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/thumbnails.gif" alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>
              <?php if (( $this->_tpl_vars['theme']['params']['Showslideshow'] && $this->_tpl_vars['theme']['slideshow'] )): ?>
                <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=slideshow:Slideshow",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id']),'arg4' => "xx='exp'",'arg5' => "assign='yy'",'arg6' => "yy='22'"), $this);?>
"
onmouseout= "slide.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/slideshow.gif'"
onmouseover= "slide.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/slideshowon.gif' " title="SlideShow">
                  <img id="slide" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/slideshow.gif" alt="SlideShow" longdesc="SlideShow" title="SlideShow" class="navtoppic"/></a>


              <?php endif; ?>
            <?php else: ?>
	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core:ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']),'arg3' => "highlightId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index_next']]['id'])), $this);?>
"
onmouseout= "gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/gal.gif'"
onmouseover= "gal.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/galon.gif'
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
">
	        <img id="gal" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/gal.gif" style='border: 0' alt="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" longdesc="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" title="<?php echo $this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title']; ?>
" class="navtoppic"/></a>

            <?php endif; ?>
          <?php endif; ?>
        <?php endfor; endif; ?>
</td></tr></table>
      </td>
    </tr>
  </table>
<?php endif; ?>
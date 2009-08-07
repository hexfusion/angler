<?php /* Smarty version 2.6.10, created on 2006-03-08 18:21:34
         compiled from gallery:themes/PGtheme/templates/navigatorPhotoBottom.tpl */ ?>

<?php $this->assign('imagewidth', $this->_tpl_vars['theme']['imageViews'][$this->_tpl_vars['theme']['imageViewsIndex']]['width']); ?>

          <?php $_from = $this->_tpl_vars['theme']['params']['photoBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
          <?php if (empty ( $this->_tpl_vars['item'] )):  $this->assign('item', $this->_tpl_vars['theme']['item']);  endif; ?>
          <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id']), $this);?>

          <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "exif.LoadExifInfo",'itemId' => $this->_tpl_vars['item']['id']), $this);?>


          <?php if (( $this->_tpl_vars['block']['0'] == "comment.ViewComments" )): ?>
            <?php if (isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] ) && ( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
              <?php $this->assign('showblockCpic', '1'); ?>
            <?php else: ?>
              <?php $this->assign('showblockCpic', '0'); ?>
            <?php endif; ?>
          <?php endif; ?>

          <?php if (( $this->_tpl_vars['block']['0'] == "exif.ExifInfo" )): ?>
            <?php if (! empty ( $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'] )): ?>
              <?php $this->assign('showblockEpic', '1'); ?>
            <?php else: ?>
              <?php $this->assign('showblockEpic', '0'); ?>
            <?php endif; ?>
          <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>

<?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>

  <table border="0" width="<?php echo $this->_tpl_vars['imagewidth']; ?>
" align="center">
    <tr>
      <td style="text-align:left; width:150px">
        <?php echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['first'] ) || isset ( $this->_tpl_vars['theme']['navigator']['back'] )):  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['first'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['first']['urlParams']), $this); echo '" onmouseover="firstpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'firston.gif\'" onmouseout="firstpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'first.gif\'" title="first"><img name="firstpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'first.gif" alt="first" title="first" longdesc="first" id="firstpicB" class="navpic"/></a>';  endif;  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['back'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['back']['urlParams']), $this); echo '" onmouseover="prevpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'prevon.gif\'" onmouseout="prevpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'prev.gif\'" title="previous"><img name="prevpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'prev.gif" alt="previous" title="previous" longdesc="previous" id="prevpicB" class="navpic"/></a>';  endif;  echo '';  endif;  echo ''; ?>

      </td>
      <td style="text-align:center">
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavPop'] ) && ! empty ( $this->_tpl_vars['theme']['sourceImage']['width'] )): ?>
          <?php if ($this->_tpl_vars['imagewidth'] != $this->_tpl_vars['theme']['item']['width']): ?>
            <a href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" 
onmouseover="popupB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
popupon.gif'" onmouseout="popupB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
popup.gif'" title="full size image popup">
              <img name="popupB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupB" class="navtoppic"/></a> 
          <?php else: ?>
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavPopEven'] )): ?>
            <a href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" 
onmouseover="popupBe.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
popupon.gif'" onmouseout="popupBe.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
popup.gif'" title="full size image popup">
                <img name="popupBe" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupBe" class="navtoppic"/></a>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>


        <?php if (isset ( $this->_tpl_vars['theme']['permissions']['comment_add'] ) && ( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['AddPhotoComments'] )): ?>
            <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcomB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif'"
onmouseover= "addcomB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcommentson.gif'"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
            <?php else: ?>
title="Add a Comment"
            <?php endif; ?>
>
              <img id="addcomB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
            <?php else: ?>
title="Add a Comment"
            <?php endif; ?>
class="navtoppic"/></a>
        <?php endif; ?>

        <?php if (isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] ) && ( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['PhotoComments'] )): ?>

        <?php if (empty ( $this->_tpl_vars['item'] )):  $this->assign('item', $this->_tpl_vars['theme']['item']);  endif; ?>
        <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>


          <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
            <?php if (( $this->_tpl_vars['showblockCpic'] == 0 )): ?>
            <a href="javascript:void(0)" onclick="ShowLayer('comments','visible')" 
onmouseout= "commentsB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif'"
onmouseover= "commentsB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
commentson.gif'; ShowLayer('comments','hidden')"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
            <?php else: ?>
title="View Comments"
            <?php endif; ?>
>
              <img id="commentsB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif" alt="View Comments" longdesc="View Comments"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
            <?php else: ?>
title="View Comments"
            <?php endif; ?>
class="navtoppic"/></a> 
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>


        <?php if (( $this->_tpl_vars['theme']['exif'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['PhotoExif'] )): ?>
          <?php if (empty ( $this->_tpl_vars['item'] )):  $this->assign('item', $this->_tpl_vars['theme']['item']);  endif; ?>
          <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "exif.LoadExifInfo",'itemId' => $this->_tpl_vars['item']['id']), $this);?>

          <?php if (! empty ( $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'] )): ?>
            <?php if (( $this->_tpl_vars['showblockEpic'] == 0 )): ?>
            <a href="javascript:void(0)" onclick="ShowLayer('exif','visible')" 
onmouseout= "exifB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exif.gif'"
onmouseover= "exifB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exifon.gif' ;ShowLayer('exif','hidden')"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
            <?php else: ?>
title="Show Photo Exif" 
            <?php endif; ?>
>
              <img id="exifB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exif.gif" alt="Show Photo EXIF" longdesc="Show Photo EXIF" 
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
            <?php else: ?>
title="Show Photo Exif" 
            <?php endif; ?>
class="navtoppic"/></a> 
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>


        <?php if (! empty ( $this->_tpl_vars['theme']['params']['photoBlocks'] ) && ! empty ( $this->_tpl_vars['theme']['params']['OtherBlocksBtn'] )): ?>
            <?php $this->assign('showblockpic', '0'); ?>

          <?php $_from = $this->_tpl_vars['theme']['params']['photoBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>

          <?php if (( $this->_tpl_vars['block']['0'] == "comment.ViewComments" )): ?>
            <?php if (( $this->_tpl_vars['showblockCpic'] == 1 )): ?>
              <?php $this->assign('showblockpic', '1'); ?>
            <?php endif; ?>
          <?php elseif (( $this->_tpl_vars['block']['0'] == "exif.ExifInfo" )): ?>
            <?php if (( $this->_tpl_vars['showblockEpic'] == 1 )): ?>
              <?php $this->assign('showblockpic', '1'); ?>
            <?php endif; ?>
          <?php else: ?>
              <?php $this->assign('showblockpic', '1'); ?>
          <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>

          <?php if (( $this->_tpl_vars['showblockpic'] == 1 )): ?>
            <a href="javascript:void(0)" onclick="ShowLayer('blocks','visible')" 
onmouseout= "blocksB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
block.gif'"
onmouseover= "blocksB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
blockon.gif' ;ShowLayer('blocks','hidden')"
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['BlocksText']), $this);?>
"
              <?php else: ?>
title="Photo Blocks" 
              <?php endif; ?>
>
              <img id="blocksB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
block.gif" alt="Photo Blocks" longdesc="OtherBlocks" 
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['BlocksText']), $this);?>
"
              <?php else: ?>
title="Photo Blocks" 
              <?php endif; ?>
class="navtoppic"/></a> 
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['user']['isAdmin'] && ( $this->_tpl_vars['theme']['guestPreviewMode'] != 1 )): ?>
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoActions'] )): ?>
        <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemLinks",'item' => $this->_tpl_vars['child'],'links' => $this->_tpl_vars['child']['itemLinks']), $this);?>

          <?php endif; ?>
        <?php else: ?>
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoCart'] )): ?>
          <?php if ($this->_tpl_vars['theme']['cart'] == 1): ?>
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
              <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif'"
onmouseover= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_carton.gif'"
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
>
                <img id="cartB<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
class="navtoppic"/></a>
            <?php else: ?>
              <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif'"
onmouseover= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_carton.gif'"
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
>
                <img id="cartB<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
class="navtoppic"/></a>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?> 
        <?php endif; ?> 
      </td>
      <td style="text-align:right; width:150px">
        <?php echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['next'] ) || isset ( $this->_tpl_vars['theme']['navigator']['last'] )):  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['next'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['next']['urlParams']), $this); echo '" onmouseover="nextpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'nexton.gif\'" onmouseout="nextpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'next.gif\'" title="next"><img name="nextpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'next.gif" alt="next" title="next" longdesc="next" id="nextpicB" class="navpic"/></a>';  endif;  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['last'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['last']['urlParams']), $this); echo '" onmouseover="lastpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'laston.gif\'" onmouseout="lastpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'last.gif\'" title="last"><img name="lastpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this); echo 'last.gif" alt="last" title="last" longdesc="last" id="lastpicB" class="navpic"/></a>';  endif;  echo '';  endif;  echo ''; ?>

      </td>
    </tr>
  </table>
<?php else: ?>
  <table border="0" width="<?php echo $this->_tpl_vars['imagewidth']; ?>
" align="center">
    <tr>
      <td style="text-align:left; width:150px">
        <?php echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['first'] ) || isset ( $this->_tpl_vars['theme']['navigator']['back'] )):  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['first'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['first']['urlParams']), $this); echo '" onmouseover="firstpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/firston.gif\'" onmouseout="firstpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/first.gif\'" title="first"><img name="firstpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/first.gif" alt="first" title="first" longdesc="first" id="firstpicB" class="navpic"/></a>';  endif;  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['back'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['back']['urlParams']), $this); echo '" onmouseover="prevpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/prevon.gif\'" onmouseout="prevpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/prev.gif\'" title="previous"><img name="prevpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/prev.gif" alt="previous" title="previous" longdesc="previous" id="prevpicB" class="navpic"/></a>';  endif;  echo '';  endif;  echo ''; ?>

      </td>
      <td style="text-align:center">
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavPop'] ) && ! empty ( $this->_tpl_vars['theme']['sourceImage']['width'] )): ?>
          <?php if ($this->_tpl_vars['imagewidth'] != $this->_tpl_vars['theme']['item']['width']): ?>
            <a href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;"
onmouseover="popupB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/popupon.gif'" onmouseout="popupB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/popup.gif'" title="full size image popup">
              <img name="popupB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupB" class="navtoppic"/></a> 
          <?php else: ?>
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavPopEven'] )): ?>
            <a href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;"
onmouseover="popupBe.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/popupon.gif'" onmouseout="popupBe.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/popup.gif'" title="full size image popup">
                <img name="popupBe" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupBe" class="navtoppic"/></a>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (isset ( $this->_tpl_vars['theme']['permissions']['comment_add'] ) && ( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['AddPhotoComments'] )): ?>
            <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcomB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif'"
onmouseover= "addcomB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcommentson.gif'"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
            <?php else: ?>
title="Add a Comment"
            <?php endif; ?>
>
              <img id="addcomB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
            <?php else: ?>
title="Add a Comment"
            <?php endif; ?>
class="navtoppic"/></a>
        <?php endif; ?>

        <?php if (isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] ) && ( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['PhotoComments'] )): ?>

        <?php if (empty ( $this->_tpl_vars['item'] )):  $this->assign('item', $this->_tpl_vars['theme']['item']);  endif; ?>
        <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>


          <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
            <?php if (( $this->_tpl_vars['showblockCpic'] == 0 )): ?>
            <a href="javascript:void(0)" onclick="ShowLayer('comments','visible')" 
onmouseout= "commentsB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif'"
onmouseover= "commentsB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/commentson.gif'; ShowLayer('comments','hidden')"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
            <?php else: ?>
title="View Comments"
            <?php endif; ?>
>
              <img id="commentsB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif" alt="View Comments" longdesc="View Comments"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
            <?php else: ?>
title="View Comments"
            <?php endif; ?>
class="navtoppic"/></a> 
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (( $this->_tpl_vars['theme']['exif'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['PhotoExif'] )): ?>
          <?php if (empty ( $this->_tpl_vars['item'] )):  $this->assign('item', $this->_tpl_vars['theme']['item']);  endif; ?>
          <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "exif.LoadExifInfo",'itemId' => $this->_tpl_vars['item']['id']), $this);?>

          <?php if (! empty ( $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'] )): ?>
            <?php if (( $this->_tpl_vars['showblockEpic'] == 0 )): ?>
            <a href="javascript:void(0)" onclick="ShowLayer('exif','visible')" 
onmouseout= "exifB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/exif.gif'"
onmouseover= "exifB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/exifon.gif' ;ShowLayer('exif','hidden')"
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
            <?php else: ?>
title="Show Photo Exif" 
            <?php endif; ?>
>
              <img id="exifB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/exif.gif" alt="Show Photo EXIF" longdesc="Show Photo EXIF" 
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
            <?php else: ?>
title="Show Photo Exif" 
            <?php endif; ?>
class="navtoppic"/></a> 
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>


        <?php if (! empty ( $this->_tpl_vars['theme']['params']['photoBlocks'] ) && ! empty ( $this->_tpl_vars['theme']['params']['OtherBlocksBtn'] )): ?>
            <?php $this->assign('showblockpic', '0'); ?>

          <?php $_from = $this->_tpl_vars['theme']['params']['photoBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>

          <?php if (( $this->_tpl_vars['block']['0'] == "comment.ViewComments" )): ?>
            <?php if (( $this->_tpl_vars['showblockCpic'] == 1 )): ?>
              <?php $this->assign('showblockpic', '1'); ?>
            <?php endif; ?>
          <?php elseif (( $this->_tpl_vars['block']['0'] == "exif.ExifInfo" )): ?>
            <?php if (( $this->_tpl_vars['showblockEpic'] == 1 )): ?>
              <?php $this->assign('showblockpic', '1'); ?>
            <?php endif; ?>
          <?php else: ?>
              <?php $this->assign('showblockpic', '1'); ?>
          <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?>

          <?php if (( $this->_tpl_vars['showblockpic'] == 1 )): ?>
            <a href="javascript:void(0)" onclick="ShowLayer('blocks','visible')" 
onmouseout= "blocksB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/block.gif'"
onmouseover= "blocksB.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/blockon.gif' ;ShowLayer('blocks','hidden')"
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['BlocksText']), $this);?>
"
              <?php else: ?>
title="Photo Blocks" 
              <?php endif; ?>
>
              <img id="blocksB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/block.gif" alt="Photo Blocks" longdesc="OtherBlocks" 
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['BlocksText']), $this);?>
"
              <?php else: ?>
title="Photo Blocks" 
              <?php endif; ?>
class="navtoppic"/></a> 
          <?php endif; ?>
        <?php endif; ?>

        
        <?php if ($this->_tpl_vars['user']['isAdmin'] && ( $this->_tpl_vars['theme']['guestPreviewMode'] != 1 )): ?>
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoActions'] )): ?>
        <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemLinks",'item' => $this->_tpl_vars['child'],'links' => $this->_tpl_vars['child']['itemLinks']), $this);?>

          <?php endif; ?>
        <?php else: ?>
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoCart'] )): ?>
          <?php if ($this->_tpl_vars['theme']['cart'] == 1): ?>
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
              <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif'"
onmouseover= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_carton.gif'"
               <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
>
                <img id="cartB<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart"
               <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
class="navtoppic"/></a>
            <?php else: ?>
              <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif'"
onmouseover= "cartB<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_carton.gif'"
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
>
                <img id="cartB<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
              <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
              <?php else: ?>
title="Add to Cart"
              <?php endif; ?>
class="navtoppic"/></a>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?> 
        <?php endif; ?>       </td>
      <td style="text-align:right; width:150px">
        <?php echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['next'] ) || isset ( $this->_tpl_vars['theme']['navigator']['last'] )):  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['next'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['next']['urlParams']), $this); echo '" onmouseover="nextpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/nexton.gif\'" onmouseout="nextpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/next.gif\'" title="next"><img name="nextpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/next.gif"  alt="next" title="next" longdesc="next" id="nextpicB" class="navpic"/></a>';  endif;  echo '';  if (isset ( $this->_tpl_vars['theme']['navigator']['last'] )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['last']['urlParams']), $this); echo '" onmouseover="lastpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/laston.gif\'" onmouseout="lastpicB.src=\'';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/last.gif\'" title="last"><img name="lastpicB" src="';  echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo '';  echo $this->_tpl_vars['theme']['params']['colorpack'];  echo '/images/last.gif" alt="last" title="last" longdesc="last" id="lastpicB" class="navpic"/></a>';  endif;  echo '';  endif;  echo ''; ?>

      </td>
    </tr>
  </table>
<?php endif; ?>
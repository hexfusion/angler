<?php /* Smarty version 2.6.10, created on 2006-03-08 18:20:50
         compiled from gallery:themes/PGtheme/templates/album.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'markup', 'gallery:themes/PGtheme/templates/album.tpl', 326, false),array('modifier', 'default', 'gallery:themes/PGtheme/templates/album.tpl', 636, false),array('modifier', 'entitytruncate', 'gallery:themes/PGtheme/templates/album.tpl', 680, false),)), $this); ?>
<table width="100%">
  <tr>
    <td valign="top">
      <?php if ($this->_tpl_vars['theme']['params']['sidebar'] && ! empty ( $this->_tpl_vars['theme']['params']['sidebarBlocks'] )): ?>
        <table cellspacing="0" cellpadding="0" align="left">
          <tr valign="top">
            <td>
              <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "sidebar.tpl"), $this);?>

            </td>
          </tr>
        </table>
    </td>
    <td valign="top">
      <?php endif; ?>
      <table width="92%" cellspacing="0" cellpadding="0" class="gcBackground1 gcBorder2" align="center">
        <tr valign="top">
            <?php if (empty ( $this->_tpl_vars['theme']['parents'] )): ?>
              <?php if ($this->_tpl_vars['theme']['params']['showOwnerContact']): ?>
          <td style="width: 55%" class="gcBackground1">
            <table class="gcBackground1">
              <tr>
                <td>
                  <table width="100%" cellspacing="5" class="gcBackground1 .gcBorder1">
                    <tr>
                      <td>
                        <p class="author">
                          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "author.tpl"), $this);?>

                        </p>
                        <br/><br/>
                        <table width="100%">
                          <tr>
                            <td style="text-align:left">
                              <p class="authorlink">
                                <a href="#"  title="Click to hide..." onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink1','','show','authorlink2','','hide')" style="color:#dfdfdf">
                                <?php echo $this->_tpl_vars['theme']['params']['authorlink1']; ?>

                                </a>
                              </p>
                            </td>
                            <td style="text-align:right">
                              <p class="author">
                                <a href="#" onclick="MM_showHideLayers('authorlink2','','hide')" onmouseover="MM_showHideLayers('authorlink2','','show','authorlink1','','hide')"  style="color:#dfdfdf" title="Click to hide..." >
                                <?php echo $this->_tpl_vars['theme']['params']['authorlink2']; ?>

                                </a>
                              </p>
                            </td>
                          </tr>
                        </table>
                        <div align="left">
                          <p>
                            <a href="mailto:<?php echo $this->_tpl_vars['theme']['params']['email']; ?>
" title="<?php echo $this->_tpl_vars['theme']['params']['email']; ?>
" class="authoremail">
                              <?php echo $this->_tpl_vars['theme']['params']['email']; ?>

                            </a>
                          </p>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <table align="center" class="tableacpic">
                          <tr>
                           <td>
                             <div class="actions">
                 <?php if ($this->_tpl_vars['theme']['params']['AuthorActions']): ?>
                   <?php if ($this->_tpl_vars['user']['isAdmin']): ?>
                     <?php if ($this->_tpl_vars['theme']['guestPreviewMode'] != 1): ?>
                               <div style="height:30px">
                                <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemLinks",'item' => $this->_tpl_vars['child'],'links' => $this->_tpl_vars['child']['itemLinks']), $this);?>

                               </div>
                     <?php endif; ?>
                   <?php endif; ?>
                               <table>
                                 <tr>
                   <?php if ($this->_tpl_vars['user']['isGuest'] || ( $this->_tpl_vars['user']['isAdmin'] && ( $this->_tpl_vars['theme']['guestPreviewMode'] == 1 ) )): ?>
                     <?php if ($this->_tpl_vars['theme']['cart'] == 1): ?>
                                   <td>
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                     <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_carton.gif'"
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                         <?php else: ?>
title="Add to Cart"
                         <?php endif; ?>
>
                                       <img id="cart<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                         <?php else: ?>
title="Add to Cart"
                         <?php endif; ?>
class="navtoppic" /></a>
                                   </td>
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                   <td>
<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_carton.gif'"
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                         <?php else: ?>
title="Add to Cart"
                         <?php endif; ?>
>
                                     <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
</a>
                                   </td>
                                 </tr><tr>
                         <?php endif; ?>
                       <?php else: ?>
                                      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" onmouseout="cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif'" onmouseover="cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_carton.gif'"
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                         <?php else: ?>
title="Add to Cart"
                         <?php endif; ?>
>
                                        <img id="cart<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                         <?php else: ?>
title="Add to Cart"
                         <?php endif; ?>
 class="navtoppic"/></a>
                                   </td>
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                   <td>
                                      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_carton.gif'">
                           <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>

                           <?php else: ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add to Cart'), $this);?>

                           <?php endif; ?>
                                     </a>
                                   </td>
                                 </tr><tr>
                         <?php endif; ?>
                       <?php endif; ?>
                     <?php else: ?>
                                   <td></td>
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                 </tr><tr>
                         <?php endif; ?>
                     <?php endif; ?>                    <?php endif; ?>                  <?php endif; ?> 
                 <?php if (empty ( $this->_tpl_vars['theme']['params']['AuthorActions'] ) && ! empty ( $this->_tpl_vars['theme']['params']['AuthorComments'] )): ?>
                                 <table>
                                   <tr>
                 <?php endif; ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['AuthorComments'] ) && ( $this->_tpl_vars['theme']['comments'] == 1 )): ?>
                   <?php if (isset ( $this->_tpl_vars['theme']['permissions']['comment_add'] )): ?>                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                     <td>
                                       <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcommentson.gif' "
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                       <?php else: ?>
title="Add a Comment"
                       <?php endif; ?>
>
                                        <img id="addcom<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                       <?php else: ?>
title="Add a Comment"
                       <?php endif; ?>
class="navtoppic" /></a>
                                     </td>
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                     <td>
                                       <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcommentson.gif' "
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>

                         <?php else: ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add a Comment'), $this);?>

                         <?php endif; ?>
>
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>

                         <?php else: ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add a Comment'), $this);?>

                         <?php endif; ?>
                                       </a>
                                     </td>
                                    </tr><tr>
                       <?php endif; ?>
                     <?php else: ?>
                                     <td>
                                       <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcommentson.gif'"
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                       <?php else: ?>
title="Add a Comment"
                       <?php endif; ?>
>
                                        <img id="addcom<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                       <?php else: ?>
title="Add a Comment"
                       <?php endif; ?>
class="navtoppic"/></a>
                                     </td>
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                     <td>
                                       <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcommentson.gif' ">
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>

                         <?php else: ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add a Comment'), $this);?>

                         <?php endif; ?>
                                       </a>
                                     </td>
                                   </tr><tr>
                       <?php endif; ?>
                     <?php endif; ?>
                   <?php endif; ?> 
                   <?php if (isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] )): ?>                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                     <td>
                       <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>
                                        <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                       <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                       <a href="javascript:void(0)"
onmouseout= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif'" onclick="ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','visible')"
onmouseover= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/commentson.gif';ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','hidden') "
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
                         <?php else: ?>
title="View Comments"
                         <?php endif; ?>
>
                                       <img id="blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif" alt="View Comments" longdesc="View Comments"
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
                         <?php else: ?>
title="View Comments"
                         <?php endif; ?>
class="navtoppic" /></a>
                       <?php endif; ?>

                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                     </td><td>
                         <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>
                                        <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                         <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                       <a href="javascript:void(0)"
onmouseout= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif'" onclick="ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','visible')"
onmouseover= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/commentson.gif';ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','hidden') "
                         <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
                         <?php else: ?>
title="View Comments"
                         <?php endif; ?>
>
                           <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>

                           <?php else: ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'View Comments'), $this);?>

                           <?php endif; ?>
                                          </a>
                         <?php endif; ?>
                                      </td>
                                    </tr><tr>
                       <?php endif; ?>
                     <?php else: ?>
                                     <td>
                       <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>
                                      <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                       <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                       <a href="javascript:void(0)"
onmouseout= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif'" onclick="ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','visible')"
onmouseover= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
commentson.gif';ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','hidden') "
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
class="navtoppic" /></a>
                       <?php endif; ?>
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                     </td><td>
                         <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>
                                        <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['item']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                         <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                       <a href="javascript:void(0)"
onmouseout= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif'" onclick="ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','visible')"
onmouseover= "blockspic<?php echo $this->_tpl_vars['item']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
commentson.gif'; ShowLayer('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','hidden')">
                           <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>

                           <?php else: ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'View Comments'), $this);?>

                           <?php endif; ?>
                                       </a>
                         <?php endif; ?>
                       <?php endif; ?>
                     <?php endif; ?>
                                       <div id="blocks<?php echo $this->_tpl_vars['item']['id']; ?>
" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blocks<?php echo $this->_tpl_vars['item']['id']; ?>
')"  class="BlockOpacity">
                                         <table  width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "<?php echo ((is_array($_tmp=$this->_tpl_vars['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             <?php echo $this->_reg_objects['g'][0]->block(array('type' => "comment.ViewComments",'item' => $this->_tpl_vars['child']), $this);?>

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
                                      </td>
                     <?php endif; ?>                    <?php endif; ?>                    <?php if (( $this->_tpl_vars['theme']['comments'] == 1 )): ?>
                     <?php if (empty ( $this->_tpl_vars['theme']['params']['AuthorActions'] ) && ! empty ( $this->_tpl_vars['theme']['params']['AuthorComments'] )): ?>
                       <?php if ($this->_tpl_vars['user']['isAdmin']): ?>
                                     <td>
                                     </td>
                       <?php endif; ?>
                     <?php endif; ?>
                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['AuthorActions'] ) && empty ( $this->_tpl_vars['theme']['params']['AuthorComments'] )): ?>
                       <?php if ($this->_tpl_vars['user']['isAdmin']): ?>
                                     <td>
                                     </td>
                       <?php endif; ?>
                     <?php endif; ?>
                     <?php if (empty ( $this->_tpl_vars['theme']['params']['AuthorActions'] ) && empty ( $this->_tpl_vars['theme']['params']['AuthorComments'] )): ?>
                                      <table>
                                        <tr>
                                          <td>
                                          </td>
                     <?php endif; ?>
                   <?php endif; ?>
                   <?php if (( $this->_tpl_vars['theme']['comments'] != 1 )): ?>
                                      <table>
                                        <tr>
                                          <td>
                                          </td>
                   <?php endif; ?>
                                        </tr>
                                      </table>
                                    </div>                                   </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>

                        <div id="authorlink1" style="position:absolute; height:216px; z-index:10; left: 175px; top: 25px;border: 0px solid #999999; visibility:hidden"
onmousedown="dragStart(event, 'authorlink1')">
                        <table cellpadding="6" class="gcBackground2 gcBorder2">
                          <tr>
                            <td style="height:5; text-align:right">
                   <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink1','','hide')" title="Close">
                              <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close" class="navpic"/>
                            </a>
                   <?php else: ?>
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink1','','hide')" title="Close">
                              <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close" class="navpic"/>
                            </a>
                   <?php endif; ?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                        <?php echo $this->_reg_objects['g'][0]->theme(array('include' => $this->_tpl_vars['theme']['params']['authorlink1url']), $this);?>

                            </td>
                          </tr>
                          <tr>
                            <td style="height=5"></td>
                          </tr>
                        </table><br/>
                        </div>
                        <div id="authorlink2" style="position:absolute; height:216px; z-index:10; left: 25px; top: 25px;border: 0px solid #999999; visibility:hidden" onmousedown="dragStart(event, 'authorlink2')">
                        <table cellpadding="6" class="gcBackground2 gcBorder2">
                          <tr>
                            <td style="height:5; text-align:right">
                   <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink2','','hide')" title="Close">
                              <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close" class="navpic"/>
                            </a>
                   <?php else: ?>
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink2','','hide')" title="Close">
                              <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close" class="navpic"/>
                            </a>
                   <?php endif; ?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                        <?php echo $this->_reg_objects['g'][0]->theme(array('include' => $this->_tpl_vars['theme']['params']['authorlink2url']), $this);?>

                            </td>
                          </tr>
                          <tr>
                            <td style="height=5"></td>
                          </tr>
                        </table><br />
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td>
                 <?php else: ?>
                <td id="gsSidebarCol">
               <?php endif; ?>
             <?php else: ?>
                <td id="gsSidebarCol">
             <?php endif; ?>
                <div id="gsContent" class="gcBackground1 gcBorder1">
             <?php if (empty ( $this->_tpl_vars['theme']['parents'] )): ?>
                  <div class="gbBlock gcBackground1">
                  <table style="width: 100%" border="0">
                    <tr>
                      <td style="width: 70%" >
               <?php if ($this->_tpl_vars['theme']['params']['1stTitle']): ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['item']['title'] )): ?>
                            <h2> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </h2>
                 <?php endif; ?>
               <?php endif; ?>
               <?php if ($this->_tpl_vars['theme']['params']['1stDescription']): ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['item']['description'] )): ?>
                            <p class="giDescription">
                              <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                            </p>
                 <?php endif; ?>
               <?php endif; ?>
                      </td>
               <?php if ($this->_tpl_vars['theme']['params']['InfoGallery']): ?>
                      <td style="width: 17%" valign="top">
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['showAlbumOwner'] )): ?>
                            <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showOwner' => true,'class' => 'giInfo'), $this);?>

                 <?php endif; ?>
                          <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showDate' => true,'showSize' => true,'showViewCount' => true,'class' => 'giInfo'), $this);?>

                      </td>
               <?php endif; ?>
             <?php else: ?>
                  <div class="gbBlock gcBackground1" >
                  <table style="width: 100%; height: 40px" border="0">
                    <tr>
                      <td style="width: 60%; vertical-align:top" >
               <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumTitle'] ) && ! empty ( $this->_tpl_vars['theme']['params']['AlbumTitleTop'] )): ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['item']['title'] )): ?>
                            <h2> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </h2>
                 <?php endif; ?>
               <?php endif; ?>
               <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumDescription'] ) && ! empty ( $this->_tpl_vars['theme']['params']['AlbumDescriptionTop'] )): ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['item']['description'] )): ?>
                            <p class="giDescription">
                              <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                            </p>
                 <?php endif; ?>
               <?php endif; ?>
                      </td>
               <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumInfo'] ) && ! empty ( $this->_tpl_vars['theme']['params']['AlbumInfoTop'] )): ?>
                      <td style="width: 20%; vertical-align:top">
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['showAlbumOwner'] )): ?>
                            <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showOwner' => true,'class' => 'giInfo'), $this);?>

                 <?php endif; ?>
                            <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showDate' => true,'showSize' => true,'showViewCount' => true,'class' => 'giInfo'), $this);?>

                      </td>
               <?php endif; ?>
                      <td style="width: 20%; text-align:right; vertical-align:top">
                        <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorTop.tpl"), $this);?>

                      </td>
             <?php endif; ?>
                    </tr>
                  </table>
                  </div>

             <?php if (! count ( $this->_tpl_vars['theme']['children'] )): ?>
                  <div class="gbBlock giDescription gbEmptyAlbum">
                      <h1 class="emptyAlbum">
	                <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This album is empty."), $this);?>

	       <?php if (isset ( $this->_tpl_vars['theme']['permissions']['core_addDataItem'] )): ?>
                          <br/>
                          <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ItemAdmin",'arg2' => "subView=core.ItemAdd",'arg3' => "itemId=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Add a photo!"), $this);?>

                          </a>
	       <?php endif; ?>
                      </h1>
                  </div>
             <?php else: ?>
                  <table width="100%" align="center" border="0">
                    <tr><td></td>
                      <td>
               <?php if ($this->_tpl_vars['theme']['params']['NavAlbumTop']): ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['navigator'] )): ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['jumpRange'] )): ?>
                          <div class="gcBackground1 gbNavigator">
                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorAlbumTop.tpl"), $this);?>

                          </div>
                   <?php endif; ?>
                 <?php endif; ?>
               <?php endif; ?>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
               <?php if (! empty ( $this->_tpl_vars['theme']['parents'] )): ?>
                        <table width="95%" border="0" align="center">
                          <tr>
                            <td style="vertical-align:top">
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumTitle'] ) && empty ( $this->_tpl_vars['theme']['params']['AlbumTitleTop'] )): ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['item']['title'] )): ?>
                                      <br/>
                                      <h2> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </h2>
                   <?php endif; ?>
                 <?php endif; ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumDescription'] ) && empty ( $this->_tpl_vars['theme']['params']['AlbumDescriptionTop'] )): ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['item']['description'] )): ?>
                                      <p class="giDescription">
                                        <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                                      </p>
                                      <div class="descSeparator"></div>
                   <?php endif; ?>
                 <?php endif; ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['AlbumInfo'] ) && empty ( $this->_tpl_vars['theme']['params']['AlbumInfoTop'] )): ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['showAlbumOwner'] )): ?>
                                      <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showOwner' => true,'class' => 'giInfo'), $this);?>

                   <?php endif; ?>
                                      <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showDate' => true,'showSize' => true,'showViewCount' => true,'class' => 'giInfo'), $this);?>

                                    <div class="descSeparator"></div>
                 <?php endif; ?>
                            </td>
                          </tr>
                        </table>
               <?php endif; ?>
                      </td>
                      <td valign="top">
                        <?php $this->assign('childrenInColumnCount', 0); ?>
                        <div class="gbBlock">
                          <table id="gsThumbMatrix" width="100%">
                            <tr valign="top">
                              <?php $_from = $this->_tpl_vars['theme']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['child']):
?>
               <?php if (empty ( $this->_tpl_vars['theme']['parents'] )): ?>
                 <?php if (( $this->_tpl_vars['childrenInColumnCount'] == $this->_tpl_vars['theme']['params']['columns1st'] )): ?>
                            </tr>
                            <tr valign="top">
                                    <?php $this->assign('childrenInColumnCount', 0); ?>
                 <?php endif; ?>
                                    <?php $this->assign('nome', $this->_tpl_vars['child']['id']); ?>
                                    <?php $this->assign('Toff', $this->_tpl_vars['theme']['params']['opacityT']); ?>
                                    <?php $this->assign('Tover', $this->_tpl_vars['theme']['params']['opacityTover']); ?>
                                    <?php $this->assign('childrenInColumnCount', ($this->_tpl_vars['childrenInColumnCount']+1)); ?>
                              <td class="<?php if ($this->_tpl_vars['child']['canContainChildren']): ?>giAlbumCell gcBackground1<?php else: ?>giItemCell<?php endif; ?>">
                 <?php if ($this->_tpl_vars['child']['canContainChildren']): ?>
                                    <?php $this->assign('frameType', 'albumFrame'); ?>
                 <?php else: ?>
                                    <?php $this->assign('frameType', 'itemFrame'); ?>
                 <?php endif; ?>
                                  <br/><br/><br/>

               <?php else: ?> 
                 <?php if (( $this->_tpl_vars['childrenInColumnCount'] == $this->_tpl_vars['theme']['params']['columns'] )): ?>
                            </tr>
                            <tr valign="top">
                                    <?php $this->assign('childrenInColumnCount', 0); ?>
                 <?php endif; ?>
                                    <?php $this->assign('nome', $this->_tpl_vars['child']['id']); ?>
                                    <?php $this->assign('Toff', $this->_tpl_vars['theme']['params']['opacityT']); ?>
                                    <?php $this->assign('Tover', $this->_tpl_vars['theme']['params']['opacityTover']); ?>
                                    <?php $this->assign('childrenInColumnCount', ($this->_tpl_vars['childrenInColumnCount']+1)); ?>
                              <td class="<?php if ($this->_tpl_vars['child']['canContainChildren']): ?>giAlbumCell gcBackground1<?php else: ?>giItemCell<?php endif; ?>">
                 <?php if ($this->_tpl_vars['child']['canContainChildren']): ?>
                                  <?php $this->assign('frameType', 'albumFrame'); ?>
                 <?php else: ?>
                                  <?php $this->assign('frameType', 'itemFrame'); ?>
                 <?php endif; ?>
               <?php endif; ?>
                                <div>
               <?php if (empty ( $this->_tpl_vars['theme']['parents'] ) && ( $this->_tpl_vars['theme']['imageblock'] == 1 ) && ( $this->_tpl_vars['child']['canContainChildren'] ) && ( $this->_tpl_vars['theme']['params']['AlbumBlock'] )): ?>
                 <?php $this->assign('itemId', $this->_tpl_vars['child']['id']); ?>
                 <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "imageblock.LoadImageBlock",'blocks' => ((is_array($_tmp=@$this->_tpl_vars['blocks'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)),'itemId' => ((is_array($_tmp=@$this->_tpl_vars['itemId'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null))), $this);?>

                 <?php if (! empty ( $this->_tpl_vars['ImageBlockData'] )): ?>
                                <div class="<?php echo $this->_tpl_vars['class']; ?>
">
                                  <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "imageblockPG.tpl"), $this);?>

                                </div>
                 <?php endif; ?>
               <?php else: ?>
               <?php if (isset ( $this->_tpl_vars['theme']['params'][$this->_tpl_vars['frameType']] ) && isset ( $this->_tpl_vars['child']['thumbnail'] )): ?>
                                     <?php $this->_tag_stack[] = array('container', array('type' => "imageframe.ImageFrame",'frame' => $this->_tpl_vars['theme']['params'][$this->_tpl_vars['frameType']]), $this); $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>

	                      <a onmouseover="pic<?php echo $this->_tpl_vars['nome']; ?>
.className='%CLASS% giThumbnail opacity<?php echo $this->_tpl_vars['Tover']; ?>
'" onmouseout="pic<?php echo $this->_tpl_vars['nome']; ?>
.className='%CLASS% giThumbnail opacity<?php echo $this->_tpl_vars['Toff']; ?>
'" 
href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['child']['id'])), $this);?>
">
		        <?php echo $this->_reg_objects['g'][0]->image(array('id' => "%ID%",'item' => $this->_tpl_vars['child'],'image' => $this->_tpl_vars['child']['thumbnail'],'class' => "%CLASS% giThumbnail opacity".($this->_tpl_vars['Toff']),'name' => "pic".($this->_tpl_vars['nome'])), $this);?>
</a>
                                    <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>



               <?php elseif (isset ( $this->_tpl_vars['child']['thumbnail'] )): ?>
                                     <a onmouseover="pic<?php echo $this->_tpl_vars['nome']; ?>
.className='%CLASS% giThumbnail opacity<?php echo $this->_tpl_vars['Tover']; ?>
'" onmouseout="pic<?php echo $this->_tpl_vars['nome']; ?>
.className='%CLASS% giThumbnail opacity<?php echo $this->_tpl_vars['Toff']; ?>
'"
href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['child']['id'])), $this);?>
">
		      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['child'],'image' => $this->_tpl_vars['child']['thumbnail'],'class' => "giThumbnail opacity".($this->_tpl_vars['Toff']),'name' => "pic".($this->_tpl_vars['nome'])), $this);?>
</a>
               <?php else: ?>
		    <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['child']['id'])), $this);?>
" class="giMissingThumbnail">
		      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'no thumbnail'), $this);?>
</a>
               <?php endif; ?>
               <?php endif; ?>


               <?php if (! empty ( $this->_tpl_vars['theme']['params']['BtnAfter'] )): ?>
               <?php if ($this->_tpl_vars['theme']['params']['ItemsTitle']): ?>
                 <?php if (! empty ( $this->_tpl_vars['child']['title'] )): ?>
                                    <p class="giTitle">
                   <?php if ($this->_tpl_vars['child']['canContainChildren']): ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => "&#9642; %s &#9642;",'arg1' => ((is_array($_tmp=$this->_tpl_vars['child']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp))), $this);?>

                   <?php else: ?>
                                        <?php echo ((is_array($_tmp=$this->_tpl_vars['child']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                   <?php endif; ?>
                                    </p>
                 <?php endif; ?>
               <?php endif; ?>
               <?php if (! empty ( $this->_tpl_vars['theme']['params']['ItemsDesc'] )): ?>
                 <?php if (! empty ( $this->_tpl_vars['child']['summary'] )): ?>
                                    <p class="giDescription">
                                      <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['child']['summary'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)))) ? $this->_run_mod_handler('entitytruncate', true, $_tmp, 256) : smarty_modifier_entitytruncate($_tmp, 256)); ?>

                                    </p>
                 <?php endif; ?>
               <?php endif; ?>
               <?php if (( $this->_tpl_vars['theme']['item']['canContainChildren'] && $this->_tpl_vars['theme']['params']['showAlbumOwner'] ) || ( ! $this->_tpl_vars['theme']['item']['canContainChildren'] && $this->_tpl_vars['theme']['params']['showImageOwner'] )): ?>
                                  <?php $this->assign('showOwner', true); ?>
               <?php else: ?>
                                  <?php $this->assign('showOwner', false); ?>
               <?php endif; ?>
               <?php if ($this->_tpl_vars['theme']['params']['ItemsInfo']): ?>
                                  <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['child'],'showDate' => true,'showOwner' => $this->_tpl_vars['showOwner'],'showSize' => true,'showViewCount' => true,'showSummaries' => true,'class' => 'giInfo'), $this);?>

                              <table><tr><td style="height:2px"></td></tr></table>
               <?php endif; ?>

               <?php endif; ?>

                                </div>

                              <div class="actions">

               <?php if ($this->_tpl_vars['theme']['params']['ItemsActions']): ?>
                 <?php if (( $this->_tpl_vars['user']['isAdmin'] ) && ( $this->_tpl_vars['theme']['guestPreviewMode'] != 1 )): ?>
                                    <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemLinks",'item' => $this->_tpl_vars['child'],'links' => $this->_tpl_vars['child']['itemLinks']), $this);?>

                 <?php endif; ?>
               <?php endif; ?>


                                  <table border="0" class="tableacpic" style="height:25px">
                                    <tr>
                                      <td>

               <?php if (( $this->_tpl_vars['theme']['cart'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['ItemsActions'] )): ?>
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_carton.gif'"
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                   <?php else: ?>
title="Add to Cart"
                   <?php endif; ?>
>
                                          <img id="cart<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                   <?php else: ?>
title="Add to Cart"
                   <?php endif; ?>
class="navtoppic" /></a>
                                      </td>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                      <td>
<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/add_carton.gif'"
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                   <?php else: ?>
title="Add to Cart"
                   <?php endif; ?>
>
                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
                                            <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>

                     <?php else: ?>
                                            <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add to Cart'), $this);?>

                     <?php endif; ?>
                                          </a>
                                      </td>
                                    </tr><tr>
                   <?php endif; ?>
                 <?php else: ?>
                                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" onmouseout="cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif'" onmouseover="cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_carton.gif'"
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                   <?php else: ?>
title="Add to Cart"
                   <?php endif; ?>
>
                                          <img id="cart<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>
"
                   <?php else: ?>
title="Add to Cart"
                   <?php endif; ?>
class="navtoppic"/></a>
                                      </td>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                      <td>
                                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=cart.AddToCart",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_cart.gif'"
onmouseover= "cart<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
add_carton.gif'">
                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['AddCartText'] )): ?>
                                            <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['AddCartText']), $this);?>

                     <?php else: ?>
                                            <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add to Cart'), $this);?>

                     <?php endif; ?>
                                          </a>
                                      </td>
                                    </tr><tr>
                   <?php endif; ?>
                 <?php endif; ?>
               <?php endif; ?> 
                                     
               <?php if (( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['ItemsComments'] ) && isset ( $this->_tpl_vars['theme']['permissions']['comment_add'] )): ?>

               <?php if (! empty ( $this->_tpl_vars['theme']['params']['ItemsActions'] )): ?>
                                      <td>
               <?php endif; ?> 

                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcommentson.gif' "
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                   <?php else: ?>
title="Add a Comment"
                   <?php endif; ?>
>
                                          <img id="addcom<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                   <?php else: ?>
title="Add a Comment"
                   <?php endif; ?>
class="navtoppic" /></a>
                                      </td>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                      <td>
                                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/addcommentson.gif' "
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                   <?php else: ?>
title="Add a Comment"
                   <?php endif; ?>
>
                     <?php if (isset ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>

                     <?php else: ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add a Comment'), $this);?>

                     <?php endif; ?>
                                        </a>
                                      </td>
                                    </tr><tr>
                   <?php endif; ?>
                 <?php else: ?>
                                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcommentson.gif'"
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                   <?php else: ?>
title="Add a Comment"
                   <?php endif; ?>
>
                                          <img id="addcom<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>
"
                   <?php else: ?>
title="Add a Comment"
                   <?php endif; ?>
class="navtoppic"/></a>
                                      </td>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                      <td>
                                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=comment:AddComment",'arg2' => "itemId=".($this->_tpl_vars['child']['id']),'arg3' => "return=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" 
onmouseout= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcomments.gif'"
onmouseover= "addcom<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
addcommentson.gif' ">
                     <?php if (isset ( $this->_tpl_vars['theme']['params']['CommentsText'] )): ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['CommentsText']), $this);?>

                     <?php else: ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add a Comment'), $this);?>

                     <?php endif; ?>
                                        </a>
                                      </td>
                                    </tr><tr>
                   <?php endif; ?>
                 <?php endif; ?>
               <?php endif; ?>

               <?php if (( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['ItemsComments'] ) && isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] )): ?>
                                      <td>
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                        <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['child']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                   <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['child']); ?> <?php endif; ?>
                   <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif'" onclick="ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','visible')"
onmouseover= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/commentson.gif';ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','hidden') "
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
                       <?php else: ?>
title="View Comments"
                       <?php endif; ?>
>
                                          <img id="blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif" alt="Viewo Comments" longdesc="View Comments" 
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
                       <?php else: ?>
title="View Comments"
                       <?php endif; ?>
class="navtoppic" /></a>
                   <?php endif; ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                      </td><td>
<?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['child']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                     <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['child']); ?> <?php endif; ?>
                     <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/comments.gif'" onclick="ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','visible')"
onmouseover= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/commentson.gif';ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','hidden') "
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
                       <?php else: ?>
title="View Comments"
                       <?php endif; ?>
>
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>

                       <?php else: ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'View Comments'), $this);?>

                       <?php endif; ?>
                                          </a>

                                      </td>
                                    </tr><tr>
                     <?php endif; ?>
                   <?php endif; ?>
                 <?php else: ?>
                   <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['child']); ?> <?php endif; ?>
                                        <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['child']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                     <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif'" onclick="ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','visible')"
onmouseover= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
commentson.gif';ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','hidden') "
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>
"
                       <?php else: ?>
title="View Comments"
                       <?php endif; ?>
>
                                          <img id="blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
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
                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                      </td><td>
                       <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['child']); ?> <?php endif; ?>
                                      <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "comment.LoadComments",'itemId' => $this->_tpl_vars['child']['id'],'show' => $this->_tpl_vars['show']), $this);?>

                       <?php if (! empty ( $this->_tpl_vars['block']['comment']['LoadComments']['comments'] )): ?>
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
comments.gif'" onclick="ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','visible')"
onmouseover= "blockICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
commentson.gif';ShowLayer('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','hidden') ">

                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ViewCommentsText'] )): ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ViewCommentsText']), $this);?>

                       <?php else: ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'View Comments'), $this);?>

                       <?php endif; ?>
                                          </a>


                       <?php endif; ?>
                     <?php endif; ?>
                   <?php endif; ?>








                                       <div id="blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "<?php echo ((is_array($_tmp=$this->_tpl_vars['child']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             <?php echo $this->_reg_objects['g'][0]->block(array('type' => "comment.ViewComments",'item' => $this->_tpl_vars['child']), $this);?>

                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                                 <a onclick="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                                               <?php else: ?>
                                                 <a onclick="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                                               <?php endif; ?>

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>






                                      </td>
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                    </tr><tr>
                       <?php endif; ?>

                 <?php endif; ?> 
                 <?php if (! empty ( $this->_tpl_vars['theme']['params']['ItemsExif'] ) && ( $this->_tpl_vars['theme']['exif'] == 1 )): ?>

                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['ItemsActions'] ) || ( ( $this->_tpl_vars['theme']['comments'] == 1 ) && ! empty ( $this->_tpl_vars['theme']['params']['ItemsComments'] ) && ( isset ( $this->_tpl_vars['theme']['permissions']['comment_view'] ) || isset ( $this->_tpl_vars['theme']['permissions']['comment_add'] ) ) )): ?>
                                      <td>
 
                   <?php endif; ?> 

                   <?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['child']); ?> <?php endif; ?>
                                  <?php echo $this->_reg_objects['g'][0]->callback(array('type' => "exif.LoadExifInfo",'itemId' => $this->_tpl_vars['child']['id']), $this);?>

                   <?php if (! empty ( $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'] )): ?>
                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                        <a href="javascript:void(0)"
onmouseout= "exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/exif.gif'" onclick="ShowLayer('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','visible')"
onmouseover= "exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/exifon.gif';ShowLayer('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','hidden')" 
<?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
                       <?php else: ?>
title="Show Photo EXIF"
                       <?php endif; ?>
> 
                                          <img id="exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/exif.gif" alt="EXIF" longdesc="Show Photo EXIF" 
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
                       <?php else: ?>
title="Show Photo EXIF"
                       <?php endif; ?>
class="navtoppic"/></a>
                     <?php else: ?>
                                        <a href="javascript:void(0)"
onmouseout= "exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exif.gif'" onclick="ShowLayer('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','visible')"
onmouseover= "exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exifon.gif';ShowLayer('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','hidden')" 
<?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
                       <?php else: ?>
title="Show Photo EXIF"
                       <?php endif; ?>
> 
                                          <img id="exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exif.gif" alt="EXIF" longdesc="Show Photo EXIF" 
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
                       <?php else: ?>
title="Show Photo EXIF"
                       <?php endif; ?>
class="navtoppic"/></a>
                     <?php if (! empty ( $this->_tpl_vars['theme']['params']['ActionsText'] )): ?>
                                      </td><td>
                                        <a href="javascript:void(0)"
onmouseout= "exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exif.gif'" onclick="ShowLayer('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','visible')"
onmouseover= "exifICpic<?php echo $this->_tpl_vars['child']['id']; ?>
.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
exifon.gif';ShowLayer('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','hidden') "
<?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>
"
                       <?php else: ?>
title="Show Photo EXIF"
                       <?php endif; ?>
> 
                       <?php if (! empty ( $this->_tpl_vars['theme']['params']['ExifText'] )): ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['theme']['params']['ExifText']), $this);?>

                       <?php else: ?>
                                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'View Exif'), $this);?>

                       <?php endif; ?>
                                          </a>
                     <?php endif; ?>
                     <?php endif; ?>

                                        <div id="exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; width:500px; text-align:left;
z-index: 10; visibility: hidden;" onmousedown="dragStart(event, 'exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
')" class="BlockOpacity">
                                          <div id="exifICIn<?php echo $this->_tpl_vars['child']['id']; ?>
" style="position: relative; left: 0px; top: 0px;  
z-index: 10;" class="gcBackground1 gcBorder2">
                                            <div style="text-align:right">
                                              <h2>"<?php echo ((is_array($_tmp=$this->_tpl_vars['child']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
"&nbsp;EXIF</h2>
                                            </div>
                                          <?php echo $this->_reg_objects['g'][0]->block(array('type' => "exif.ExifInfo",'item' => $this->_tpl_vars['child']), $this);?>

                                            <div style="text-align: right; padding:4px">
                     <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                              <a onclick="MM_showHideLayers('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" title="Close">
                                                <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                     <?php else: ?>
                                              <a onclick="MM_showHideLayers('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('exifIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" title="Close">
                                                <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                     <?php endif; ?>
                                            </div>
                                          </div>
                                          <br/>
                                        </div>

                   <?php endif; ?>
                                      </td>
                 <?php endif; ?>
                                    </tr>
                                  </table>
                                </div> 
               <?php if (empty ( $this->_tpl_vars['theme']['params']['BtnAfter'] )): ?>
               <?php if ($this->_tpl_vars['theme']['params']['ItemsTitle']): ?>
                 <?php if (! empty ( $this->_tpl_vars['child']['title'] )): ?>
                                    <p class="giTitle">
                   <?php if ($this->_tpl_vars['child']['canContainChildren']): ?>
                                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => "&#9642; %s &#9642;",'arg1' => ((is_array($_tmp=$this->_tpl_vars['child']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp))), $this);?>

                   <?php else: ?>
                                        <?php echo ((is_array($_tmp=$this->_tpl_vars['child']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                   <?php endif; ?>
                                    </p>
                 <?php endif; ?>
               <?php endif; ?>
               <?php if (! empty ( $this->_tpl_vars['theme']['params']['ItemsDesc'] )): ?>
                 <?php if (! empty ( $this->_tpl_vars['child']['summary'] )): ?>
                                    <p class="giDescription">
                                      <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['child']['summary'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)))) ? $this->_run_mod_handler('entitytruncate', true, $_tmp, 256) : smarty_modifier_entitytruncate($_tmp, 256)); ?>

                                    </p>
                 <?php endif; ?>
               <?php endif; ?>
               <?php if (( $this->_tpl_vars['theme']['item']['canContainChildren'] && $this->_tpl_vars['theme']['params']['showAlbumOwner'] ) || ( ! $this->_tpl_vars['theme']['item']['canContainChildren'] && $this->_tpl_vars['theme']['params']['showImageOwner'] )): ?>
                                  <?php $this->assign('showOwner', true); ?>
               <?php else: ?>
                                  <?php $this->assign('showOwner', false); ?>
               <?php endif; ?>
               <?php if ($this->_tpl_vars['theme']['params']['ItemsInfo']): ?>
                                  <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['child'],'showDate' => true,'showOwner' => $this->_tpl_vars['showOwner'],'showSize' => true,'showViewCount' => true,'showSummaries' => true,'class' => 'giInfo'), $this);?>

               <?php endif; ?>
               <?php endif; ?>

                              </td>
                              <?php endforeach; endif; unset($_from); ?>
                 <?php if (empty ( $this->_tpl_vars['theme']['params']['ItemsCenter'] )): ?>
                                <?php unset($this->_sections['flush']);
$this->_sections['flush']['name'] = 'flush';
$this->_sections['flush']['start'] = (int)$this->_tpl_vars['childrenInColumnCount'];
$this->_sections['flush']['loop'] = is_array($_loop=$this->_tpl_vars['theme']['params']['columns']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['flush']['show'] = true;
$this->_sections['flush']['max'] = $this->_sections['flush']['loop'];
$this->_sections['flush']['step'] = 1;
if ($this->_sections['flush']['start'] < 0)
    $this->_sections['flush']['start'] = max($this->_sections['flush']['step'] > 0 ? 0 : -1, $this->_sections['flush']['loop'] + $this->_sections['flush']['start']);
else
    $this->_sections['flush']['start'] = min($this->_sections['flush']['start'], $this->_sections['flush']['step'] > 0 ? $this->_sections['flush']['loop'] : $this->_sections['flush']['loop']-1);
if ($this->_sections['flush']['show']) {
    $this->_sections['flush']['total'] = min(ceil(($this->_sections['flush']['step'] > 0 ? $this->_sections['flush']['loop'] - $this->_sections['flush']['start'] : $this->_sections['flush']['start']+1)/abs($this->_sections['flush']['step'])), $this->_sections['flush']['max']);
    if ($this->_sections['flush']['total'] == 0)
        $this->_sections['flush']['show'] = false;
} else
    $this->_sections['flush']['total'] = 0;
if ($this->_sections['flush']['show']):

            for ($this->_sections['flush']['index'] = $this->_sections['flush']['start'], $this->_sections['flush']['iteration'] = 1;
                 $this->_sections['flush']['iteration'] <= $this->_sections['flush']['total'];
                 $this->_sections['flush']['index'] += $this->_sections['flush']['step'], $this->_sections['flush']['iteration']++):
$this->_sections['flush']['rownum'] = $this->_sections['flush']['iteration'];
$this->_sections['flush']['index_prev'] = $this->_sections['flush']['index'] - $this->_sections['flush']['step'];
$this->_sections['flush']['index_next'] = $this->_sections['flush']['index'] + $this->_sections['flush']['step'];
$this->_sections['flush']['first']      = ($this->_sections['flush']['iteration'] == 1);
$this->_sections['flush']['last']       = ($this->_sections['flush']['iteration'] == $this->_sections['flush']['total']);
?>
                                  <td>&nbsp;</td>
                                <?php endfor; endif; ?>
                 <?php endif; ?>
                            </tr>
                          </table>
                        </div>
                      </td>
                    </tr>
                 <?php if ($this->_tpl_vars['theme']['params']['NavAlbumBottom']): ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['navigator'] )): ?>
                     <?php if (! empty ( $this->_tpl_vars['theme']['jumpRange'] )): ?>
                    <tr><td></td><td>
                      <div class="gcBackground1 gbNavigator">
                       <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorAlbumBottom.tpl"), $this);?>

                      </div>
                    </td></tr> 
                     <?php endif; ?>
                   <?php endif; ?>
                 <?php endif; ?>
                  </table>
               <?php endif; ?>

                  <table><tr><td style="height:7px"></td></tr></table>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>            
    </table>


<?php if (! empty ( $this->_tpl_vars['theme']['params']['albumBlocks'] )): ?>

<table border="0" width="98%"><tr><td>

          <table width="400" align="<?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksAlign'] )):  echo $this->_tpl_vars['theme']['params']['BlocksAlign'];  else: ?>center<?php endif; ?>">
            <tr><td></td></tr>

              <?php $_from = $this->_tpl_vars['theme']['params']['albumBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
            <tr>
              <td>
                <table align="<?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksInAlign'] )):  echo $this->_tpl_vars['theme']['params']['BlocksInAlign'];  else: ?>center<?php endif; ?>">
                  <tr>
                    <td>
<?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> 

                      <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1']), $this);?>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>
              <?php endforeach; endif; unset($_from); ?>

          </table>
</td></tr></table>
<?php endif; ?>
        <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.GuestPreview",'class' => 'gbBlockBottom'), $this);?>


        	<?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.EmergencyEditItemLink",'class' => 'gbBlockBottom','checkSidebarBlocks' => true,'checkAlbumBlocks' => true), $this);?>

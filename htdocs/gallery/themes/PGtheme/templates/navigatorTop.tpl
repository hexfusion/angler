{*
 * $Revision: 1.06 $ $Date: 2005/12/22$
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{g->callback type="comment.LoadComments" itemId=$item.id show=$show}
{if empty($item)} {assign var=item value=$theme.item} {/if}     
{if empty($theme.params.colorpack)}

  <table align="right" border="0" width="100%"  style="height:25px">
    <tr>
      <td width="50%" style="text-align:right; vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom">

              <div class="actions">
  {if !empty ($theme.params.AlbumActions) && ($photoItem != 1)}
    {if $user.isAdmin}
      {if $theme.guestPreviewMode !=1}
      <div style="height:25px">
      {g->block type="core.ItemLinks" item=$child links=$child.itemLinks}
      </div>
      {/if}
    {else}
      {if $theme.cart ==1}
                <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$item.id}.src='{g->url href='themes/PGtheme/images/'}add_cart.gif'"
onmouseover= "cart{$item.id}.src='{g->url href='themes/PGtheme/images/'}add_carton.gif'"
        {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
        {else}
title="Add to Cart"
        {/if}
>
                  <img id="cart{$item.id}" src="{g->url href='themes/PGtheme/images/'}add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
        {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
        {else}
title="Add to Cart"
        {/if}
class="navtoppic"/></a>
      {/if}  {* cart end *}
    {/if} {* user.isAdmin *}
  {/if} {*album actions end*}

  {if $photoItem != 1}
    {if ($theme.comments==1)}
      {if !empty ($theme.params.AddAlbumComments) && isset ($theme.permissions.comment_add)}{*comments add*}
                <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$item.id}.src='{g->url href='themes/PGtheme/images/'}addcomments.gif'"
onmouseover= "addcom{$item.id}.src='{g->url href='themes/PGtheme/images/'}addcommentson.gif' "
        {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
        {else}
title="Add a Comment"
        {/if}
>
                  <img id="addcom{$item.id}" src="{g->url href='themes/PGtheme/images/'}addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
        {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
        {else}
title="Add a Comment"
        {/if}
class="navtoppic" /></a>
      {/if} {* comments add *}
      {if !empty ($theme.params.AlbumComments) && isset ($theme.permissions.comment_view)}{* comments view *}
              {g->callback type="comment.LoadComments" itemId=$item.id show=$show}
        {if empty($item)} {assign var=item value=$theme.item} {/if}
        {if !empty($block.comment.LoadComments.comments)}
                <a href="javascript:void(0)"
onmouseout= "blockspic{$item.id}.src='{g->url href='themes/PGtheme/images/'}comments.gif'" onclick="ShowLayer('blocks{$item.id}','visible')"
onmouseover= "blockspic{$item.id}.src='{g->url href='themes/PGtheme/images/'}commentson.gif'; ShowLayer('blocks{$item.id}','hidden')"
          {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
          {else}
title="View Comments"
          {/if}
>
                  <img id="blockspic{$item.id}" src="{g->url href='themes/PGtheme/images/'}comments.gif" alt="View Comments" longdesc="View Comments" 
          {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
          {else}
title="View Comments"
          {/if}
class="navtoppic"/></a>
        {/if}

                                       <div id="blocks{$item.id}" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blocks{$item.id}')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "{$item.title|markup}"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             {g->block type="comment.ViewComments" item=$theme.item }
                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               {if empty($theme.params.colorpack)}
                                                 <a onclick="MM_showHideLayers('blocks{$item.id}','','hide')" onmouseover="MM_showHideLayers('blocks{$item.id}','','hide')" title="Close">
                                                   <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/></a>
                                               {else}
                                                 <a onclick="MM_showHideLayers('blocks{$item.id}','','hide')" onmouseover="MM_showHideLayers('blocks{$item.id}','','hide')" title="Close">
                                                   <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close"/></a>
                                               {/if}

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>


      {/if} {* comments view end*}
    {/if} {* comments end *}
  {/if} {* photo item end *}
              </div> {* comments end *}


</td></tr></table>
</td>
      <td style="text-align:right;vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom" nowrap="nowrap" >

        {section name=parent loop=$theme.parents}
          {if !$smarty.section.parent.last}
            {if $smarty.section.parent.first}
	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"}"
onmouseout="gal.src='{g->url href='themes/PGtheme/images/'}gal.gif'" onmouseover="gal.src='{g->url href='themes/PGtheme/images/'}galon.gif'"
title="{$theme.parents[parent].title}">
                <img id="gal" src="{g->url href='themes/PGtheme/images/'}gal.gif" alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>
            {else}
	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"}"
onmouseout="album{$theme.parents[parent].id}.src='{g->url href='themes/PGtheme/images/'}album.gif'"
onmouseover="album{$theme.parents[parent].id}.src='{g->url href='themes/PGtheme/images/'}albumon.gif'" title="{$theme.parents[parent].title}">
	        <img id="album{$theme.parents[parent].id}" src="{g->url href='themes/PGtheme/images/'}album.gif"  alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>
            {/if}
          {else}
            {if !$smarty.section.parent.first}
 	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"}" onmouseout= "thumb.src='{g->url href='themes/PGtheme/images/'}thumbnails.gif'"
onmouseover= "thumb.src='{g->url href='themes/PGtheme/images/'}thumbnailson.gif'" title="{$theme.parents[parent].title}">
                <img id="thumb" src="{g->url href='themes/PGtheme/images/'}thumbnails.gif" alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>
              {if ($theme.params.Showslideshow && $theme.slideshow)}
                <a href="{g->url arg1="view=slideshow:Slideshow" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`" arg4="xx='exp'" arg5="assign='yy'" arg6="yy='22'"}"
onmouseout= "slide.src='{g->url href='themes/PGtheme/images/'}slideshow.gif'"
onmouseover= "slide.src='{g->url href='themes/PGtheme/images/'}slideshowon.gif' " title="SlideShow">
                  <img id="slide" src="{g->url href='themes/PGtheme/images/'}slideshow.gif" alt="SlideShow" longdesc="SlideShow" title="SlideShow" class="navtoppic"/></a>


              {/if}
            {else}
	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"
	 arg3="highlightId=`$theme.parents[parent.index_next].id`"}"
onmouseout= "gal.src='{g->url href='themes/PGtheme/images/'}gal.gif'"
onmouseover= "gal.src='{g->url href='themes/PGtheme/images/'}galon.gif'
" title="{$theme.parents[parent].title}">
	        <img id="gal" src="{g->url href='themes/PGtheme/images/'}gal.gif" style='border: 0' alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>

            {/if}
          {/if}
        {/section}
</td></tr></table>
      </td>
    </tr>
  </table>

{else}       
  <table align="right" border="0" width="100%"  style="height:25px">
    <tr>
      <td width="50%" style="text-align:right; vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom">

              <div class="actions">
  {if !empty ($theme.params.AlbumActions) && ($photoItem != 1)}
    {if $user.isAdmin}
      {if $theme.guestPreviewMode !=1}
      <div style="height:25px">
      {g->block type="core.ItemLinks" item=$child links=$child.itemLinks}
      </div>
      {/if}
    {else}
      {if $theme.cart ==1}
                <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif'"
onmouseover= "cart{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_carton.gif'"
        {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
        {else}
title="Add to Cart"
        {/if}
>
                  <img id="cart{$item.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
        {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
        {else}
title="Add to Cart"
        {/if}
class="navtoppic"/></a>
      {/if}  {* cart end *}
    {/if} {* user.isAdmin *}
  {/if} {*album actions end*}

  {if $photoItem != 1}
    {if ($theme.comments==1)}
      {if !empty ($theme.params.AddAlbumComments) && isset ($theme.permissions.comment_add)}{*comments add*}
                <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif'"
onmouseover= "addcom{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcommentson.gif'         "        {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
        {else}
title="Add a Comment"
        {/if}
>
                  <img id="addcom{$item.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
        {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
        {else}
title="Add a Comment"
        {/if}
class="navtoppic" /></a>
      {/if} {* comments add *}
      {if !empty ($theme.params.AlbumComments) && isset ($theme.permissions.comment_view)}{* comments view *}
              {g->callback type="comment.LoadComments" itemId=$item.id show=$show}
        {if empty($item)} {assign var=item value=$theme.item} {/if}
        {if !empty($block.comment.LoadComments.comments)}
                <a href="javascript:void(0)"
onmouseout= "blockspic{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif'" onclick="ShowLayer('blocks{$item.id}','visible')"
onmouseover= "blockspic{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/commentson.gif'; ShowLayer('blocks{$item.id}','hidden')"
          {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
          {else}
title="View Comments"
          {/if}>
                  <img id="blockspic{$item.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif" alt="View Comments" longdesc="View Comments" 
          {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
          {else}
title="View Comments"
          {/if}
class="navtoppic"/></a>
        {/if}





                                       <div id="blocks{$item.id}" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blocks{$item.id}')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "{$item.title|markup}"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             {g->block type="comment.ViewComments" item=$theme.item }
                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               {if empty($theme.params.colorpack)}
                                                 <a onclick="MM_showHideLayers('blocks{$item.id}','','hide')" onmouseover="MM_showHideLayers('blocks{$item.id}','','hide')" title="Close">
                                                   <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/></a>
                                               {else}
                                                 <a onclick="MM_showHideLayers('blocks{$item.id}','','hide')" onmouseover="MM_showHideLayers('blocks{$item.id}','','hide')" title="Close">
                                                   <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close"/></a>
                                               {/if}

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>

      {/if} {* comments view end*}
    {/if} {* comments end *}
  {/if} {* photo item end *}
              </div> {* comments end *}


</td></tr></table>
</td>
      <td style="text-align:right; vertical-align:top">
        <table border="0" align="right" style="height:25px">
          <tr>
            <td style="vertical-align:bottom" nowrap="nowrap" >

        {section name=parent loop=$theme.parents}
          {if !$smarty.section.parent.last}
            {if $smarty.section.parent.first}
	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"}"
onmouseout="gal.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/gal.gif'" onmouseover="gal.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/galon.gif'" title="{$theme.parents[parent].title}">
                <img id="gal" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/gal.gif" alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>
            {else}
	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"}"
onmouseout="album{$theme.parents[parent].id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/album.gif'"
onmouseover="album{$theme.parents[parent].id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/albumon.gif'" title="{$theme.parents[parent].title}">
	        <img id="album{$theme.parents[parent].id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/album.gif"  alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>
            {/if}
          {else}
            {if !$smarty.section.parent.first}
 	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"}" onmouseout= "thumb.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/thumbnails.gif'"
onmouseover= "thumb.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/thumbnailson.gif' " title="{$theme.parents[parent].title}">
                <img id="thumb" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/thumbnails.gif" alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>
              {if ($theme.params.Showslideshow && $theme.slideshow)}
                <a href="{g->url arg1="view=slideshow:Slideshow" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`" arg4="xx='exp'" arg5="assign='yy'" arg6="yy='22'"}"
onmouseout= "slide.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/slideshow.gif'"
onmouseover= "slide.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/slideshowon.gif' " title="SlideShow">
                  <img id="slide" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/slideshow.gif" alt="SlideShow" longdesc="SlideShow" title="SlideShow" class="navtoppic"/></a>


              {/if}
            {else}
	      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.parents[parent].id`"
	 arg3="highlightId=`$theme.parents[parent.index_next].id`"}"
onmouseout= "gal.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/gal.gif'"
onmouseover= "gal.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/galon.gif'
" title="{$theme.parents[parent].title}">
	        <img id="gal" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/gal.gif" style='border: 0' alt="{$theme.parents[parent].title}" longdesc="{$theme.parents[parent].title}" title="{$theme.parents[parent].title}" class="navtoppic"/></a>

            {/if}
          {/if}
        {/section}
</td></tr></table>
      </td>
    </tr>
  </table>
{/if}
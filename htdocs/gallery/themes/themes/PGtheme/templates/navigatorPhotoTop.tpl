{*
 * $Revision: 1.05 $ $Date: 2005/12/19 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}

{assign var="imagewidth" value=$theme.imageViews[$theme.imageViewsIndex].width}

          {foreach from=$theme.params.photoBlocks item=block}
          {if empty($item)}{assign var=item value=$theme.item}{/if}
          {g->callback type="comment.LoadComments" itemId=$item.id}
          {g->callback type="exif.LoadExifInfo" itemId=$item.id}

          {if ($block.0 == "comment.ViewComments")}
            {if isset ($theme.permissions.comment_view) && ($theme.comments ==1) && !empty($block.comment.LoadComments.comments)}
              {assign var="showblockCpic" value="1"}
            {else}
              {assign var="showblockCpic" value="0"}
            {/if}
          {/if}

          {if ($block.0 == "exif.ExifInfo")}
            {if !empty($block.exif.LoadExifInfo.exifData)}
              {assign var="showblockEpic" value="1"}
            {else}
              {assign var="showblockEpic" value="0"}
            {/if}
          {/if}
          {/foreach}

{if empty($theme.params.colorpack)}

  <table border="0" width="{$imagewidth}" align="center">
    <tr>
      <td style="text-align:left; width:150px">
        {strip}
          {if isset($theme.navigator.first) || isset($theme.navigator.back)}
            {if isset($theme.navigator.first)}
              <a href="{g->url params=$theme.navigator.first.urlParams}" onmouseover="firstpicT.src='{g->url href='themes/PGtheme/images/'}firston.gif'" onmouseout="firstpicT.src='{g->url href='themes/PGtheme/images/'}first.gif'" title="first">
                <img name="firstpicT" src="{g->url href='themes/PGtheme/images/'}first.gif" alt="first" title="first" longdesc="first" id="firstpicT" class="navpic"/></a>
            {/if}
            {if isset($theme.navigator.back)}
              <a href="{g->url params=$theme.navigator.back.urlParams}" onmouseover="prevpicT.src='{g->url href='themes/PGtheme/images/'}prevon.gif'" onmouseout="prevpicT.src='{g->url href='themes/PGtheme/images/'}prev.gif'" title="previous">
                <img name="prevpicT" src="{g->url href='themes/PGtheme/images/'}prev.gif" alt="previous" title="previous" longdesc="previous" id="prevpicT" class="navpic"/></a>
            {/if}
          {/if}
        {/strip}
      </td>
      <td style="text-align:center">
        {if !empty($theme.params.NavPop) && !empty($theme.sourceImage.width)}
          {if $imagewidth != $theme.item.width}
            <a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" 
onmouseover="popupT.src='{g->url href='themes/PGtheme/images/'}popupon.gif'" onmouseout="popupT.src='{g->url href='themes/PGtheme/images/'}popup.gif'" title="full size image popup">
              <img name="popupT" src="{g->url href='themes/PGtheme/images/'}popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupT" class="navtoppic"/></a> 
          {else}
            {if !empty($theme.params.NavPopEven)}
            <a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" 
onmouseover="popupTe.src='{g->url href='themes/PGtheme/images/'}popupon.gif'" onmouseout="popupTe.src='{g->url href='themes/PGtheme/images/'}popup.gif'" title="full size image popup">
                <img name="popupTe" src="{g->url href='themes/PGtheme/images/'}popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupTe" class="navtoppic"/></a>
            {/if}
          {/if}
        {/if}

        {if isset ($theme.permissions.comment_add) && ($theme.comments ==1) && !empty ($theme.params.AddPhotoComments)}
            <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcomT.src='{g->url href='themes/PGtheme/images/'}addcomments.gif'"
onmouseover= "addcomT.src='{g->url href='themes/PGtheme/images/'}addcommentson.gif'"
            {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
            {else}
title="Add a Comment"
            {/if}
>
              <img id="addcomT" src="{g->url  href='themes/PGtheme/images/'}addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
            {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
            {else}
title="Add a Comment"
            {/if}
class="navtoppic"/></a>
        {/if}

        {if isset ($theme.permissions.comment_view) && ($theme.comments ==1) && !empty ($theme.params.PhotoComments)}

        {if empty($item)}{assign var=item value=$theme.item}{/if}
{* Load up the Comments data *}
        {g->callback type="comment.LoadComments" itemId=$item.id show=$show}

          {if !empty($block.comment.LoadComments.comments)}
            {if ($showblockCpic ==0)}
            <a href="javascript:void(0)" onclick="ShowLayer('comments','visible')" 
onmouseout= "commentsT.src='{g->url href='themes/PGtheme/images/'}comments.gif'"
onmouseover= "commentsT.src='{g->url href='themes/PGtheme/images/'}commentson.gif'; ShowLayer('comments','hidden')"
{if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
            {else}
title="View Comments"
            {/if}
>
              <img id="commentsT" src="{g->url href='themes/PGtheme/images/'}comments.gif" alt="View Comments" longdesc="View Comments"
            {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
            {else}
title="View Comments"
            {/if}
class="navtoppic"/></a> 
            {/if}
          {/if}
        {/if}

        {if ($theme.exif ==1) && !empty($theme.params.PhotoExif)}
          {if empty($item)}{assign var=item value=$theme.item}{/if}
{* Load up the EXIF data *}
          {g->callback type="exif.LoadExifInfo" itemId=$item.id}
          {if !empty($block.exif.LoadExifInfo.exifData)}

            {if ($showblockEpic ==0)}
            <a href="javascript:void(0)" onclick="ShowLayer('exif','visible')" 
onmouseout= "exifT.src='{g->url href='themes/PGtheme/images/'}exif.gif'"
onmouseover= "exifT.src='{g->url href='themes/PGtheme/images/'}exifon.gif' ;ShowLayer('exif','hidden')"
{if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
            {else}
title="Show Photo Exif" 
            {/if}
>
              <img id="exifT" src="{g->url href='themes/PGtheme/images/'}exif.gif" alt="Show Photo EXIF" longdesc="Show Photo EXIF" 
            {if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
            {else}
title="Show Photo Exif" 
            {/if}
class="navtoppic"/></a> 
            {/if}
          {/if}
        {/if}

        {if !empty ($theme.params.photoBlocks) && !empty ($theme.params.OtherBlocksBtn)}
            {assign var="showblockpic" value="0"}

          {foreach from=$theme.params.photoBlocks item=block}

          {if ($block.0 == "comment.ViewComments")}
            {if ($showblockCpic ==1)}
              {assign var="showblockpic" value="1"}
            {/if}
          {elseif ($block.0 == "exif.ExifInfo")}
            {if ($showblockEpic ==1)}
              {assign var="showblockpic" value="1"}
            {/if}
          {else}
              {assign var="showblockpic" value="1"}
          {/if}
          {/foreach}



          {if ($showblockpic ==1)}
            <a href="javascript:void(0)" onclick="ShowLayer('blocks','visible')" 
onmouseout= "blocksT.src='{g->url href='themes/PGtheme/images/'}block.gif'"
onmouseover= "blocksT.src='{g->url href='themes/PGtheme/images/'}blockon.gif' ;ShowLayer('blocks','hidden')"
              {if !empty ($theme.params.BlocksText)}
title="{g->text text=$theme.params.BlocksText}"
              {else}
title="Photo Blocks" 
              {/if}
>
              <img id="blocksT" src="{g->url href='themes/PGtheme/images/'}block.gif" alt="Photo Blocks" longdesc="OtherBlocks" 
              {if !empty ($theme.params.BlocksText)}
title="{g->text text=$theme.params.BlocksText}"
              {else}
title="Photo Blocks" 
              {/if}
class="navtoppic"/></a> 
          {/if}
        {/if}


        {if $user.isAdmin && ($theme.guestPreviewMode !=1)}
          {if !empty ($theme.params.PhotoActions)}
        {g->block type="core.ItemLinks" item=$child links=$child.itemLinks}
          {/if}
        {else}
        {if !empty ($theme.params.PhotoCart)}
          {if $theme.cart ==1}
            {if !empty($theme.params.colorpack)}
              <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cartT{$item.id}.src='{g->url href='themes/PGtheme/images/'}add_cart.gif'"
onmouseover= "cartT{$item.id}.src='{g->url href='themes/PGtheme/images/'}add_carton.gif'"
              {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
>
                <img id="cartT{$item.id}" src="{g->url href='themes/PGtheme/images/'}add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
              {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
class="navtoppic"/></a>
            {else}
              <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cartT{$item.id}.src='{g->url href='themes/PGtheme/images/'}add_cart.gif'"
onmouseover= "cartT{$item.id}.src='{g->url href='themes/PGtheme/images/'}add_carton.gif'"
              {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
>
                <img id="cartT{$item.id}" src="{g->url href='themes/PGtheme/images/'}add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
              {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
class="navtoppic"/></a>
            {/if}
          {/if}
        {/if} 
        {/if} {* cart end *}

      </td>
      <td style="text-align:right; width:150px">
        {strip}
          {if isset($theme.navigator.next) || isset($theme.navigator.last)}
            {if isset($theme.navigator.next)}
              <a href="{g->url params=$theme.navigator.next.urlParams}" onmouseover="nextpicT.src='{g->url href='themes/PGtheme/images/'}nexton.gif'" onmouseout="nextpicT.src='{g->url href='themes/PGtheme/images/'}next.gif'" title="next">
                <img name="nextpicT" src="{g->url href='themes/PGtheme/images/'}next.gif" alt="next" title="next" longdesc="next" id="nextpicT" class="navpic"/></a>
            {/if}
            {if isset($theme.navigator.last)}
              <a href="{g->url params=$theme.navigator.last.urlParams}" onmouseover="lastpicT.src='{g->url href='themes/PGtheme/images/'}laston.gif'" onmouseout="lastpicT.src='{g->url href='themes/PGtheme/images/'}last.gif'" title="last">
                <img name="lastpicT" src="{g->url href='themes/PGtheme/images/'}last.gif" alt="last" title="last" longdesc="last" id="lastpicT" class="navpic"/></a>
            {/if}
          {/if}
        {/strip}
      </td>
    </tr>
  </table>
{else}
  <table border="0" width="{$imagewidth}" align="center">
    <tr>
      <td style="text-align:left; width:150px">
        {strip}
          {if isset($theme.navigator.first) || isset($theme.navigator.back)}
            {if isset($theme.navigator.first)}
              <a href="{g->url params=$theme.navigator.first.urlParams}" onmouseover="firstpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/firston.gif'" onmouseout="firstpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/first.gif'" title="first">
                <img name="firstpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/first.gif" alt="first" title="first" longdesc="first" id="firstpicT" class="navpic"/></a>
           {/if}
            {if isset($theme.navigator.back)}
              <a href="{g->url params=$theme.navigator.back.urlParams}" onmouseover="prevpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/prevon.gif'" onmouseout="prevpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/prev.gif'" title="previous">
                <img name="prevpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/prev.gif" alt="previous" title="previous" longdesc="previous" id="prevpicT" class="navpic"/></a>
            {/if}
          {/if}
        {/strip}
      </td>
      <td style="text-align:center">
        {if !empty($theme.params.NavPop) && !empty($theme.sourceImage.width)}
          {if $imagewidth != $theme.item.width}
            <a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;"
onmouseover="popupT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/popupon.gif'" onmouseout="popupT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/popup.gif'" title="full size image popup">
              <img name="popupT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupT" class="navtoppic"/></a> 
          {else}
            {if !empty($theme.params.NavPopEven)}
            <a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;"
onmouseover="popupTe.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/popupon.gif'" onmouseout="popupTe.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/popup.gif'" title="full size image popup">
                <img name="popupTe" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/popup.gif" alt="full size image popup" title="full size image popup" longdesc="full size image popup" id="popupTe" class="navtoppic"/></a>
            {/if}
          {/if}
        {/if}

        {if isset ($theme.permissions.comment_add) && ($theme.comments ==1) && !empty ($theme.params.AddPhotoComments)}
            <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcomT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif'"
onmouseover= "addcomT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcommentson.gif'"
            {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
            {else}
title="Add a Comment"
            {/if}
>
              <img id="addcomT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
            {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
            {else}
title="Add a Comment"
            {/if}
class="navtoppic"/></a>
        {/if}

        {if isset ($theme.permissions.comment_view) && ($theme.comments ==1) && !empty ($theme.params.PhotoComments)}
        {if empty($item)}{assign var=item value=$theme.item}{/if}
{* Load up the Comments data *}
        {g->callback type="comment.LoadComments" itemId=$item.id show=$show}

          {if !empty($block.comment.LoadComments.comments)}

            {if ($showblockCpic ==0)}

            <a href="javascript:void(0)" onclick="ShowLayer('comments','visible')" 
onmouseout= "commentsT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif'"
onmouseover= "commentsT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/commentson.gif'; ShowLayer('comments','hidden')"
             {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
            {else}
title="View Comments"
            {/if}
>
              <img id="commentsT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif" alt="View Comments" longdesc="View Comments"
            {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
            {else}
title="View Comments"
            {/if}
class="navtoppic"/></a> 
            {/if}

          {/if}
        {/if}

        {if ($theme.exif ==1) && !empty($theme.params.PhotoExif)}
          {if empty($item)}{assign var=item value=$theme.item}{/if}
{* Load up the EXIF data *}
          {g->callback type="exif.LoadExifInfo" itemId=$item.id}
          {if !empty($block.exif.LoadExifInfo.exifData)}

            {if ($showblockEpic ==0)}
            <a href="javascript:void(0)" onclick="ShowLayer('exif','visible')" 
onmouseout= "exifT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/exif.gif'"
onmouseover= "exifT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/exifon.gif' ;ShowLayer('exif','hidden')"
            {if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
            {else}
title="Show Photo Exif" 
            {/if}
>
              <img id="exifT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/exif.gif" alt="Show Photo EXIF" longdesc="Show Photo EXIF" 
            {if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
            {else}
title="Show Photo Exif" 
            {/if}
class="navtoppic"/></a> 
            {/if}
          {/if}
        {/if}

        {if !empty ($theme.params.photoBlocks) && !empty ($theme.params.OtherBlocksBtn)}

            {assign var="showblockpic" value="0"}

          {foreach from=$theme.params.photoBlocks item=block}

          {if ($block.0 == "comment.ViewComments")}
            {if ($showblockCpic ==1)}
              {assign var="showblockpic" value="1"}
            {/if}
          {elseif ($block.0 == "exif.ExifInfo")}
            {if ($showblockEpic ==1)}
              {assign var="showblockpic" value="1"}
            {/if}
          {else}
              {assign var="showblockpic" value="1"}
          {/if}
          {/foreach}



          {if ($showblockpic ==1)}
            <a href="javascript:void(0)" onclick="ShowLayer('blocks','visible')" 
onmouseout= "blocksT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/block.gif'"
onmouseover= "blocksT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/blockon.gif' ;ShowLayer('blocks','hidden')"
              {if !empty ($theme.params.BlocksText)}
title="{g->text text=$theme.params.BlocksText}"
              {else}
title="Photo Blocks" 
              {/if}
>
              <img id="blocksT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/block.gif" alt="Photo Blocks" longdesc="OtherBlocks" 
              {if !empty ($theme.params.BlocksText)}
title="{g->text text=$theme.params.BlocksText}"
              {else}
title="Photo Blocks" 
              {/if}
class="navtoppic"/></a> 
          {/if}
        {/if}


        
        {if $user.isAdmin && ($theme.guestPreviewMode !=1)}
          {if !empty ($theme.params.PhotoActions)}
        {g->block type="core.ItemLinks" item=$child links=$child.itemLinks}
          {/if}
        {else}
        {if !empty ($theme.params.PhotoCart)}
          {if $theme.cart ==1}
            {if !empty($theme.params.colorpack)}
              <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cartT{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif'"
onmouseover= "cartT{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_carton.gif'"
               {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
>
                <img id="cartT{$item.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart"
               {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
class="navtoppic"/></a>
            {else}
              <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cartT{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif'"
onmouseover= "cartT{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_carton.gif'"
              {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
>
                <img id="cartT{$item.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
              {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
              {else}
title="Add to Cart"
              {/if}
class="navtoppic"/></a>
            {/if}
          {/if}
        {/if} 
        {/if} {* cart end *}
      </td>
      <td style="text-align:right; width:150px">
        {strip}
          {if isset($theme.navigator.next) || isset($theme.navigator.last)}
            {if isset($theme.navigator.next)}
              <a href="{g->url params=$theme.navigator.next.urlParams}" onmouseover="nextpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/nexton.gif'" onmouseout="nextpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/next.gif'" title="next">
                <img name="nextpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/next.gif"  alt="next" title="next" longdesc="next" id="nextpicT" class="navpic"/></a>
            {/if}
            {if isset($theme.navigator.last)}
              <a href="{g->url params=$theme.navigator.last.urlParams}" onmouseover="lastpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/laston.gif'" onmouseout="lastpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/last.gif'" title="last">
                <img name="lastpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/last.gif" alt="last" title="last" longdesc="last" id="lastpicT" class="navpic"/></a>                     
            {/if}
          {/if}
        {/strip}
      </td>
    </tr>
  </table>
{/if}
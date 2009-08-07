{*
 * $Revision: 1.04 $Date: 2005/11/24 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="{$class}">
   {if count($theme.imageViews) > 1}
     {g->text text="Size: "}
     <select onchange="{literal}if (this.value) { newLocation = this.value; this.options[0].selected = true; location.href= newLocation; }{/literal}">
     {section name=imageView loop=$theme.imageViews}
       <option value="{g->url arg1="view=core.ShowItem" arg2="itemId=`$theme.item.id`"
	arg3="imageViewsIndex=`$smarty.section.imageView.index`"}"{if
	$smarty.section.imageView.index==$theme.imageViewsIndex} selected="selected"{/if}>
	 {if empty($theme.imageViews[imageView].width)}
	   {if isset($theme.imageViews[imageView].isSource)}
	     {g->text text="Source"}
	   {else}
	     {g->text text="Unknown"}
	   {/if}
	 {else}
	   {g->text text="%dx%d" arg1=$theme.imageViews[imageView].width
	       arg2=$theme.imageViews[imageView].height}
	 {/if}
       </option>
     {/section}
     </select>
     <br/>
   {/if}


   {if !empty($theme.sourceImage)}
     {if empty($theme.sourceImage.width)}
       {$theme.sourceImage.itemTypeName.0}
     {else}
       {if count($theme.imageViews) > 1}
         {g->text text="Full size: "}
               <a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" title="{g->text text='popup window'}">
             {g->text text="%dx%d" arg1=$theme.sourceImage.width
	     arg2=$theme.sourceImage.height}
           </a>
       {else}
         {if !empty($theme.params.InfoSizeEven)}
           {if !empty($theme.params.PopSizeEven)}
             {g->text text="Full size: "}
               <a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;">
                 {g->text text="%dx%d" arg1=$theme.sourceImage.width
	     arg2=$theme.sourceImage.height}
               </a>
           {else}
             {g->text text="Full size: "}
               {g->text text="%dx%d" arg1=$theme.sourceImage.width
	     arg2=$theme.sourceImage.height}
           {/if}
         {/if}
       {/if}
     {/if}
     <br/>
   {/if}
</div>
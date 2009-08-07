{*
 * $Revision: 1.04 $ $Date: 2005/11/17 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}

{foreach from=$ImageBlockData.blocks item=block}
<div class="one-image">

                                    {assign var="nome" value=$child.id}
                                    {assign var="Toff" value=$theme.params.opacityT}
                                    {assign var="Tover" value=$theme.params.opacityTover}

  {capture name="link"}



	                      <a onmouseover="pic{$nome}.className='%CLASS% giThumbnail opacity{$Tover}'" 
onmouseout="pic{$nome}.className='%CLASS% giThumbnail opacity{$Toff}'" 
href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$block.id`"}">
  {/capture}

  {if $block.item.canContainChildren}
    {assign var=frameType value="albumFrame"}
  {else}
    {assign var=frameType value="itemFrame"}
  {/if}

  {assign var=imageItem value=$block.item}
  {if isset($block.forceItem)}{assign var=imageItem value=$block.thumb}{/if}
  {if isset($theme.params.$frameType)}
    {g->container type="imageframe.ImageFrame" frame=$theme.params.$frameType}
      {$smarty.capture.link}
	{g->image item=$imageItem image=$block.thumb id="%ID%" class="%CLASS% giThumbnail opacity`$Toff`" name="pic$nome"}
      </a>
    {/g->container}
  {else}
    {$smarty.capture.link}
	{g->image item=$imageItem image=$block.thumb id="%ID%" class="%CLASS% giThumbnail opacity`$Toff`" name="pic$nome"}
    </a>
  {/if}

 </div>
{/foreach}
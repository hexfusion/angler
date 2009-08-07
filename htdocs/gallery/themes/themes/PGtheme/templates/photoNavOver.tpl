{*
 * $Revision: 1.04 $ $Date: 2005/12/08 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}

<div style="position:relative; left:0px; top: 0px; width:{$imagewidth}px; height:{$imageheight}px;">

{if $theme.params.imageFadin} 
  {g->image id="foto" item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" longdesc="%ID%" usemap="#fotomap"}
{else}

  {g->image id="foto" item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" longdesc="%ID%" usemap="#fotomap" onload="MM_showHideLayers('prevOT','','hide','prevOB','','hide','nextOT','','hide','nextOB','','hide','popupOT','','hide','popupOB','','hide')"}
{/if}


  {assign var="navwidthT" value=$imagewidth}
  {assign var="navwidth" value=$navwidthT/2}
  {assign var="navheight" value=$imageheight}
  {assign var="navnextleft" value=$navwidthT-120}
  {assign var="navcenter" value=$navwidth-50}
  {assign var="navheightbottom" value=$navheight-50}


  <div id="prevOT" style="z-index:10; position:absolute; left:20px; top: 20px; width:150px; height:10px; filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="previous photo">
<a href="{g->url params=$theme.navigator.back.urlParams}" title="previous photo" onmouseover="MM_showHideLayers('prevOT','','show')" onmouseout="MM_showHideLayers('prevOT','','hide')"> 
    <img name="prevOT" src="{g->url href='themes/PGtheme/images/'}prevphoto.gif" border="0" alt="previous photo" title="previous photo" longdesc="previous photo" id="prevphotoT"/> 
</a>
  </div>

  <div id="prevOB" style="z-index:10; position:absolute; left:20px; top: {$navheightbottom}px; width:150px; height:10px; 
filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="previous photo">
<a href="{g->url params=$theme.navigator.back.urlParams}" title="previous photo" onmouseover="MM_showHideLayers('prevOB','','show')" onmouseout="MM_showHideLayers('prevOB','','hide')"> 
    <img name="prevOB" src="{g->url href='themes/PGtheme/images/'}prevphoto.gif" border="0" alt="previous photo" title="previous photo" longdesc="previous photo" id="prevphotoB"/> 
</a>
  </div>

  <div id="nextOT"  style="z-index:10; position:absolute; left:{$navnextleft}px; top: 20px; width:150px; height:10px; filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="next">
<a href="{g->url params=$theme.navigator.next.urlParams}" title="next photo" onmouseover="MM_showHideLayers('nextOT','','show')" onmouseout="MM_showHideLayers('nextOT','','hide')">
    <img name="nextOT" src="{g->url href='themes/PGtheme/images/'}nextphoto.gif" border="0" alt="next photo" title="next photo" longdesc="next photo" id="nextphotoT"/>
</a>
  </div>

  <div id="nextOB" style="z-index:10; position:absolute; left:{$navnextleft}px; top: {$navheightbottom}px; width:150px; height:10px; filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="next photo">
<a href="{g->url params=$theme.navigator.next.urlParams}" title="next photo" onmouseover="MM_showHideLayers('nextOB','','show')" onmouseout="MM_showHideLayers('nextOB','','hide')">
    <img name="nextOB" src="{g->url href='themes/PGtheme/images/'}nextphoto.gif" border="0" alt="next photo" title="next photo" longdesc="next photo" id="nextphotoB"/>
</a>
  </div>

  <div id="popupOT" style="z-index:10; position:absolute; left:{$navcenter}px; top: 20px; width:150px; height:10px; 
filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="popup">
<a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOT','','show')" onmouseout="MM_showHideLayers('popupOT','','hide')" title="full image popup">
    <img name="popupOT" src="{g->url href='themes/PGtheme/images/'}fullphotoover.gif" border="0" alt="full image popup" title="full image popup" longdesc="full image popup" id="popupphotoT"/>
</a>
  </div>

  <div id="popupOB" style="z-index:10; position:absolute; left:{$navcenter}px; top: {$navheightbottom}px; width:150px; height:10px; 
filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="popup">
<a href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOB','','show')" onmouseout="MM_showHideLayers('popupOB','','hide')" title="full image popup">
    <img name="popupOB" src="{g->url href='themes/PGtheme/images/'}fullphotoover.gif" border="0" alt="full image popup" title="full image popup" longdesc="full image popup" id="popupphotoB"/>
</a>
  </div>


  {if !empty($theme.params.NavOverTop)}
    {if !empty($theme.params.NavOverBottom)} 
      <map id="fotomap" name="fotomap">
        {if isset($theme.navigator.back)}
          <area shape="rect" coords="0,0,{$navwidth-100},{$navheight}" 
href="{g->url params=$theme.navigator.back.urlParams}" title="previous photo" onmouseover="MM_showHideLayers('prevOB','','show','prevOT','','show')" onmouseout="MM_showHideLayers('prevOB','','hide','prevOT','','hide')" alt="previous photo" />
        {/if}
        {if isset($theme.navigator.next)}
          <area shape="rect" coords="{$navwidth+100},0,{$navwidthT},{$navheight}" href="{g->url params=$theme.navigator.next.urlParams}" title="next photo" onmouseover="MM_showHideLayers('nextOB','','show','nextOT','','show')" onmouseout="MM_showHideLayers('nextOB','','hide','nextOT','','hide')" alt="next photo"/>
        {/if}
        {if !empty($theme.params.NavOverPopup) && !empty($theme.sourceImage.width)}
          {if $imagewidth != $theme.item.width}
            <area shape="rect"
 coords="{$navwidth-100},0,{$navwidth+100},{$navheight}" 
href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOB','','show','popupOT','','show')" onmouseout="MM_showHideLayers('popupOB','','hide','popupOT','','hide')" alt="full image popup" title="full image popup"/>
          {else}
            {if !empty($theme.params.NavOverPopupEven)}
              <area shape="rect"
 coords="{$navwidth-100},0,{$navwidth+100},{$navheight}" href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOB','','show','popupOT','','show')" onmouseout="MM_showHideLayers('popupOB','','hide','popupOT','','hide')" alt="full image popup" title="full image popup"/>
            {/if}
          {/if}
        {/if}
      </map>
    {else}
      <map id="fotomap" name="fotomap">
        {if isset($theme.navigator.back)}
          <area shape="rect" coords="0,0,{$navwidth-100},{$navheight}" 
href="{g->url params=$theme.navigator.back.urlParams}" title="previous photo" onmouseover="MM_showHideLayers('prevOT','','show')" onmouseout="MM_showHideLayers('prevOT','','hide')" alt="previous photo" />
        {/if}
        {if isset($theme.navigator.next)}
          <area shape="rect" coords="{$navwidth+100},0,{$navwidthT},{$navheight}" href="{g->url params=$theme.navigator.next.urlParams}" title="next photo" onmouseover="MM_showHideLayers('nextOT','','show')" onmouseout="MM_showHideLayers('nextOT','','hide')" alt="next photo"/>
        {/if}
        {if !empty($theme.params.NavOverPopup) && !empty($theme.sourceImage.width)}
          {if $imagewidth != $theme.item.width}
            <area shape="rect"
 coords="{$navwidth-100},0,{$navwidth+100},{$navheight}" href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOT','','show')" onmouseout="MM_showHideLayers('popupOT','','hide')" alt="full image popup" title="full image popup"/>
          {else}
            {if !empty($theme.params.NavOverPopupEven)}
              <area shape="rect"
 coords="{$navwidth-100},0,{$navwidth+100},{$navheight}" href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOT','','show')" onmouseout="MM_showHideLayers('popupOT','','hide')" alt="full image popup" title="full image popup"/>
            {/if}
          {/if}
        {/if}
      </map>
    {/if}
  {else}
    {if !empty($theme.params.NavOverBottom)} 
      <map id="fotomap" name="fotomap">
        {if isset($theme.navigator.back)}
          <area shape="rect"  coords="0,0,{$navwidth-100},{$navheight}" 
href="{g->url params=$theme.navigator.back.urlParams}" title="previous photo" onmouseover="MM_showHideLayers('prevOB','','show')" onmouseout="MM_showHideLayers('prevOB','','hide')" alt="previous photo" />
        {/if}
          {if isset($theme.navigator.next)}
            <area shape="rect"  coords="{$navwidth+100},0,{$navwidthT},{$navheight}" href="{g->url params=$theme.navigator.next.urlParams}" title="next photo" onmouseover="MM_showHideLayers('nextOB','','show')" onmouseout="MM_showHideLayers('nextOB','','hide')" alt="next photo"/>
        {/if}
        {if !empty($theme.params.NavOverPopup)}
          {if $imagewidth != $theme.item.width}
            <area shape="rect"
 coords="{$navwidth-100},0,{$navwidth+100},{$navheight}" href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOB','','show')" onmouseout="MM_showHideLayers('popupOB','','hide')" alt="full image popup" title="full image popup"/>
          {else}
            {if !empty($theme.params.NavOverPopupEven)}
              <area shape="rect"
 coords="{$navwidth-100},0,{$navwidth+100},{$navheight}" href="javascript:void(0);" onclick="fsizeopen('{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.sourceImage.id`"}'); return false;" onmouseover="MM_showHideLayers('popupOB','','show')" onmouseout="MM_showHideLayers('popupOB','','hide')" alt="full image popup" title="full image popup"/>
            {/if}
          {/if}
        {/if}
      </map>
    {/if}
  {/if}

</div>

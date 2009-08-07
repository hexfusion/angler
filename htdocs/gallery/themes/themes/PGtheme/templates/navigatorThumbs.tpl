{*
 * $Revision: 1.01 $ $Date: 2005/10/12 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{if !empty ($theme.params.MTends)}
{g->callback type="core.LoadPeers" item=$item|default:$theme.item
 windowSize=$theme.params.peerWindowSize|default:null loadThumbnails=true}
{else}
{g->callback type="core.LoadPeers" item=$item|default:$theme.item
 windowSize=$theme.params.peerWindowSize|default:null loadThumbnails=true addEnds=false}
{/if}

{assign var="data" value=$block.core.LoadPeers}

{assign var="over" value=$theme.params.MToversize}
{assign var="MTbg" value=$theme.params.MTbg}
{assign var="MTbgborder" value=$theme.params.MTbgborder}
{assign var="MToff" value=$theme.params.opacityMT}
{assign var="MTover" value=$theme.params.opacityMTover}
{assign var="thumbsCol" value=$theme.params.columnsMT}
{assign var="thumbsSize" value=$theme.params.sizeMT}
{assign var="thumbsSizeOver" value=$thumbsSize+$over}
{assign var="tdThumbs" value=$thumbsSizeOver+6}
{assign var="trThumbs" value=$thumbsCol}

{assign var="tableThumbs" value=$thumbsCol*$tdThumbs+4+$thumbsCol*4}

{assign var="lastIndex" value=0}
{assign var="Index" value=0}
<table><tr><td>
<table class="gcBackground2 gcBorder2" style="border: 1px solid #{$MTbgborder}; background-color: #{$MTbg};" width="{$tableThumbs}" align="center">
  <tr>

  {if !empty($data.peers)}
    {foreach from=$data.peers item=peer}
      {assign var="nome" value=$peer.id}
      {assign var="largura" value=pic$nome.width}
      {assign var="altura" value=pic$nome.height}
      {assign var="title" value=$peer.title}

      {if !empty ($theme.params.MTmore)}
        {if empty ($theme.params.MTends)}
          {if ($peer.peerIndex - $lastIndex) != 1}
            {assign var="first" value=1 }
          {/if}
          {if ($peer.id != $theme.navigator.last.item.id) && ($peer.peerIndex != $data.thisPeerIndex)}
            {assign var="last" value=1 }
          {else}
            {assign var="last" value=0 }
          {/if}
        {else}
          {if ($peer.peerIndex - $lastIndex > 1)}
            {if ($lastIndex==1) && ($peer.id != $theme.navigator.first.item.id) && ($peer.peerIndex != $data.thisPeerIndex)}
              {assign var="first" value=1 }
            {else}
              {assign var="last" value=1}
            {/if}
          {/if}
        {/if}
      {/if}

      {if ($peer.peerIndex == $data.thisPeerIndex)}
    <td height="{$tdThumbs}" width="{$tdThumbs}" style="text-align:center">
      {g->image item=$peer image=$peer.thumbnail name="active"  maxSize=$thumbsSize alt="$title" longdesc="$title" class="thumbSmallSelected" style="text-align:center" title="$title"}
    </td>
      {else}
    <td height="{$tdThumbs}" width="{$tdThumbs}" style="text-align:center">
          <a onmouseover="pic{$nome}.width=pic{$nome}.width={$largura}+{$over}; pic{$nome}.height={$altura}+{$over}; pic{$nome}.className='thumbSmallOn opacity{$MTover}'" onmouseout="pic{$nome}.width={$largura}-{$over}; pic{$nome}.height={$altura}-{$over}; pic{$nome}.className='thumbSmall opacity{$MToff}'"
onclick="pic{$nome}.width={$largura}; pic{$nome}.height={$altura};
pic{$nome}.className='thumbSmallClik opacity{$MToff}'"
href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$peer.id`"}">
            {g->image item=$peer image=$peer.thumbnail name=pic$nome maxSize=$thumbsSize alt="$title" longdesc="$title" title="$title" class="thumbSmall opacity`$MToff`" style="text-align:center"}</a>
    </td>
      {/if}

      {assign var="Index" value=$Index+1}
      {assign var="lastIndex" value=$peer.peerIndex}

      {if ($Index == $thumbsCol)}
  </tr>
  <tr>
      {assign var="Index" value="0"}

      {/if}
    {/foreach}
  {/if}
  </tr>
</table>
</td></tr><tr><td>
{if !empty ($theme.params.MTmorepics)}
<table width="100%">
  <tr>
    <td width="50%" style="text-align:left">
      {if ($first==1)}
        {if empty($theme.params.colorpack)}
          <a href="{g->url params=$theme.navigator.first.urlParams}" onmouseover="firstpicMT.src='{g->url href='themes/PGtheme/images/'}firston.gif'" onmouseout="firstpicMT.src='{g->url href='themes/PGtheme/images/'}first.gif'">
            <img name="firstpicMT" src="{g->url href='themes/PGtheme/images/'}first.gif" border="0" alt="first" title="first" longdesc="first" id="firstpicMT" class="navpic"/></a>
        {else}
          <a href="{g->url params=$theme.navigator.first.urlParams}" onmouseover="firstpicMT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/firston.gif'" onmouseout="firstpicMT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/first.gif'">
            <img name="firstpicMT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/first.gif" border="0" alt="first" title="first" longdesc="first" id="firstpicMT" class="navpic"/></a>
        {/if}
        {$theme.params.MTmoretext}
      {/if}
    </td>
    <td style="text-align:right">
      {if ($last==1)}
        {$theme.params.MTmoretext}
        {if empty($theme.params.colorpack)}
          <a href="{g->url params=$theme.navigator.last.urlParams}" onmouseover="lastpicMT.src='{g->url href='themes/PGtheme/images/'}laston.gif'" onmouseout="lastpicMT.src='{g->url href='themes/PGtheme/images/'}last.gif'">
            <img name="lastpicMT" src="{g->url href='themes/PGtheme/images/'}last.gif" border="0" alt="last" title="last" longdesc="last" id="lastpicMT" class="navpic"/></a>
        {else}
          <a href="{g->url params=$theme.navigator.last.urlParams}" onmouseover="lastpicMT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/laston.gif'" onmouseout="lastpicMT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/last.gif'">
            <img name="lastpicMT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/last.gif" border="0" alt="last" title="last" longdesc="last" id="lastpicMT" class="navpic"/></a>
        {/if}
      {/if}
    </td>
  </tr>
</table>
{else}
<table width="100%">
  <tr>
    <td width="50%" style="text-align:left">
      {if ($first==1)}
        << {$theme.params.MTmoretext}
      {/if}
    </td>
    <td style="text-align:right">
      {if ($last==1)}
        {$theme.params.MTmoretext} >>
      {/if}
    </td>
  </tr>
</table>
{/if}
</td></tr></table>
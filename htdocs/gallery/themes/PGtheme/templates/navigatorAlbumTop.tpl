{*
 * $Revision: 1.05 $ $Date: 2005/12/19 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}

{if empty($theme.params.colorpack)}
  <table border="0" width="99%">
    <tr>
      <td width="33%">
        {strip}
          {if isset($theme.navigator.first) || isset($theme.navigator.back)}
            {if isset($theme.navigator.first)}
              <a href="{g->url params=$theme.navigator.first.urlParams}" onmouseover="firstpicT.src='{g->url href='themes/PGtheme/images/'}firston.gif'" onmouseout="firstpicT.src='{g->url href='themes/PGtheme/images/'}first.gif'" title="first">
                <img name="firstpicT" src="{g->url href='themes/PGtheme/images/'}first.gif" border="0" alt="first" title="first" longdesc="first" id="firstpicT" class="navpic"/>
              </a>
            {/if}
            {if isset($theme.navigator.back)}
              <a href="{g->url params=$theme.navigator.back.urlParams}" onmouseover="prevpicT.src='{g->url href='themes/PGtheme/images/'}prevon.gif'" onmouseout="prevpicT.src='{g->url href='themes/PGtheme/images/'}prev.gif'" title="previous">
                <img name="prevpicT" src="{g->url href='themes/PGtheme/images/'}prev.gif" border="0" alt="previous" title="previous" longdesc="previous" id="prevpicT" class="navpic"/>
              </a>
            {/if}
          {/if}
        {/strip}
      </td>
      <td width="33%" style="text-align:center">
        {strip}
          {if !empty($theme.jumpRange)}
            <div id="gsPagesT">
              {g->text text="<font class='giInfo'>Page:</font>"}
              {assign var="lastPage" value=0}
              {foreach name=jumpRange from=$theme.jumpRange item=page}
              {if ($page - $lastPage >= 2)}
	        <span>
                  {if ($page - $lastPage == 2)}
                    <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.item.id`"
	     arg3="page=`$page-1`"}">{$page-1}</a>
                  {else}
                    ...
                  {/if}
	        </span>
              {/if}
	      <span>&nbsp;
                {if ($theme.currentPage == $page)}
                  <font class='giInfo'>
                    {$page}
                  </font>
                {else} 
                  <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.item.id`"
	   arg3="page=$page"}">
                    {$page}
                  </a>
                {/if}
	      </span>
              {assign var="lastPage" value=$page}
              {/foreach}
            </div>
          {/if}
        {/strip}
      </td>
      <td width="33%" style="text-align:right">
        {strip}
          {if isset($theme.navigator.next) || isset($theme.navigator.last)}
            {if isset($theme.navigator.next)}
              <a href="{g->url params=$theme.navigator.next.urlParams}" onmouseover="nextpicT.src='{g->url href='themes/PGtheme/images/'}nexton.gif'" onmouseout="nextpicT.src='{g->url href='themes/PGtheme/images/'}next.gif'" title="next" >
                <img name="nextpicT" src="{g->url href='themes/PGtheme/images/'}next.gif" border="0" alt="next" title="next" longdesc="next" id="nextpicT" class="navpic"/>
              </a>
            {/if}
            {if isset($theme.navigator.last)}
              <a href="{g->url params=$theme.navigator.last.urlParams}" onmouseover="lastpicT.src='{g->url href='themes/PGtheme/images/'}laston.gif'" onmouseout="lastpicT.src='{g->url href='themes/PGtheme/images/'}last.gif'" title="last">
                <img name="lastpicT" src="{g->url href='themes/PGtheme/images/'}last.gif" border="0" alt="last" title="last" longdesc="last" id="lastpicT" class="navpic"/>
              </a>
            {/if}
          {/if}
        {/strip}
      </td>
    </tr>
  </table>
{else}
  <table border="0" width="100%">
    <tr>
      <td width="33%">
        {strip}
          {if isset($theme.navigator.first) || isset($theme.navigator.back)}
            {if isset($theme.navigator.first)}
              <a href="{g->url params=$theme.navigator.first.urlParams}" onmouseover="firstpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/firston.gif'" onmouseout="firstpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/first.gif'" title="first">
                <img name="firstpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/first.gif" border="0" alt="first" title="first" longdesc="first" id="firstpicT" class="navpic"/>
              </a>
            {/if}
            {if isset($theme.navigator.back)}
              <a href="{g->url params=$theme.navigator.back.urlParams}" onmouseover="prevpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/prevon.gif'" onmouseout="prevpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/prev.gif'" title="previous">
                <img name="prevpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/prev.gif" border="0" alt="previous" title="previous" longdesc="previous" id="prevpicT" class="navpic"/>
              </a>
            {/if}
          {/if}
        {/strip}
      </td>
      <td width="33%" style="text-align:center">
        {strip}
          {if !empty($theme.jumpRange)}
            <div id="gsPagesT">
              {g->text text="<font class='giInfo'>Page:</font>"}
              {assign var="lastPage" value=0}
              {foreach name=jumpRange from=$theme.jumpRange item=page}
                {if ($page - $lastPage >= 2)}
	          <span>
                    {if ($page - $lastPage == 2)}
                      <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.item.id`"
	     arg3="page=`$page-1`"}">
                        {$page-1}
                      </a>
                    {else}
                      ...
                    {/if}
	          </span>
                {/if}
	        <span>&nbsp;
                  {if ($theme.currentPage == $page)}
                    <font class='giInfo'>
                      {$page}
                    </font>
                  {else} 
                    <a href="{g->url arg1="view=core:ShowItem" arg2="itemId=`$theme.item.id`"
	   arg3="page=$page"}">
                      {$page}
                    </a>
                  {/if}
	        </span>
                {assign var="lastPage" value=$page}
              {/foreach}
            </div>
          {/if}
        {/strip}
      </td>
      <td width="33%" style="text-align:right">
        {strip}
          {if isset($theme.navigator.next) || isset($theme.navigator.last)}
            {if isset($theme.navigator.next)}
              <a href="{g->url params=$theme.navigator.next.urlParams}" onmouseover="nextpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/nexton.gif'" onmouseout="nextpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/next.gif'" title="next">
                <img name="nextpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/next.gif" border="0" alt="next" title="next" longdesc="next" id="nextpicT" class="navpic"/>
              </a>
            {/if}
            {if isset($theme.navigator.last)}
              <a href="{g->url params=$theme.navigator.last.urlParams}" onmouseover="lastpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/laston.gif'" onmouseout="lastpicT.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/last.gif'" title="last">
                <img name="lastpicT" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/last.gif" border="0" alt="last" title="last" longdesc="last" id="lastpicT" class="navpic"/>
              </a>
            {/if}
          {/if}
        {/strip}
      </td>
    </tr>
  </table>
{/if}
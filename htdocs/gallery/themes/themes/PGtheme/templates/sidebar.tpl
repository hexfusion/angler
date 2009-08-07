{*
 * $Revision: 1.02 $ $Date: 2005/10/20 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}

{if $theme.params.sidebar}
  <div id="gsSidebar" class="gcBorder1">
{* Show the sidebar blocks chosen for this theme *}
    {foreach from=$theme.params.sidebarBlocks item=block}
      {g->block type=$block.0 params=$block.1 class="gbBlock"}
    {/foreach}
    {g->block type="core.NavigationLinks" class="gbBlock"}
  </div>
{else}
  <table class="sidebarF">
    <tr>
      <td style="text-align: center">
        <div id="gsSidebarF" class="gcBackground1 gcBorder1">
{* Show the sidebar blocks chosen for this theme *}
          {foreach from=$theme.params.sidebarBlocks item=block}
            {g->block type=$block.0 params=$block.1 class="gbBlock"}
          {/foreach}
        </div>
      </td>
    </tr>
  </table>
{/if}

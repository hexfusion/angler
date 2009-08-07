{*
 * $Revision: 1.02 $ $Date: 2005/10/20 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{** Feel free to costumize this file but Please mantain a reference to my site www.pedrogilberto.net, Thanks**}
<table width="100%">
  <tr>
    <td nowrap="nowrap">
      <div>
        <div style="position:relative; float:left">
{if !empty ($theme.params.valbtn)}
          {g->logoButton type="validation"}
{/if}
{if !empty ($theme.params.g2btn)}
          {g->logoButton type="gallery2"}
{/if}
{if !empty ($theme.params.g2verbtn)}
          {g->logoButton type="gallery2-version"}
{/if}
{if !empty ($theme.params.donbtn)}
          {g->logoButton type="donate"}
{/if}
{if !empty ($theme.params.pgbtn)}
          <a href="http://www.pedrogilberto.net/gallery2/theme.html" title="(c) Theme by www.PedroGilberto.net, download here (version:'1.0RC7')">
            <img src="{g->url href="themes/PGtheme/images/pgtheme.gif"}" alt="PG Theme" longdesc="PG Theme" style="border: 0" title="(c) Theme by www.PedroGilberto.net, download here (version:'1.0RC7')"/></a>
{/if}
{if !empty ($theme.params.pgcpbtn)}
          {if !empty($theme.params.colorpack)}
          <a href="http://www.pedrogilberto.net/gallery2/theme.html" title="Colorpack - {$theme.params.colorpack}, by www. PedroGilberto.net (included on PG Theme)">
            <img src="{g->url href="modules/colorpack/packs/"}{$theme.params.colorpack}/images/cpack.gif" alt="({$theme.params.colorpack} Colorpack)" longdesc="Colorpack ({$theme.params.colorpack})" style="border: 0" title="Colorpack - {$theme.params.colorpack}, by www. PedroGilberto.net (included on PG Theme)"/></a>
          {/if}
{/if}
        </div>
        <div style="position:relative; float:right">
          <font  size='1' face='arial'>(C) 2005 -</font> 
          <a href="mailto:{$theme.params.email}" title="{$theme.params.email}">{$theme.params.site}</a>
          <font size='1' face='arial'> (all rights reserved)</font>
        </div>
      </div>
    </td>
  </tr>
</table>

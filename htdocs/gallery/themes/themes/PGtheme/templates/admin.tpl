{*
 * $Revision: 2.0 $ $Date: 2005/07/26 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{if $user.isAdmin && $theme.guestPreviewMode !=1}
<table border="0" align="center" width="100%"><tr><td>
<table id="helplink" align="right"><tr><td>
<a href="javascript:void(0)" onclick="javascript:openHelp()" title="{g->text text='How to set PG Theme'}">
{g->text text=" PG Theme HELP "}
</a>
</td></tr></table></td></tr></table>
{/if}
{include file="gallery:`$theme.adminTemplate`" l10Domain=$theme.adminL10Domain}
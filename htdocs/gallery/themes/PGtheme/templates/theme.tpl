{*
 * $Revision: 1.04 $ $Date: 2005/11/24 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?xml version="1.0" encoding="UTF-8"?>
<html>
  <head>
    {* Let Gallery print out anything it wants to put into the <head> element *}
    {g->head}

    {* If Gallery doesn't provide a header, we use the album/photo title (or filename) *}
{if empty($head.title)}
 {if !isset($SlideShow)}
<title>
   {if isset($theme.params.site)}
{$theme.params.site} - 
   {/if}
{$theme.item.title|default:$theme.item.pathComponent|markup:strip}</title>
{/if}
{/if}

<meta http-equiv="imagetoolbar" content="no"/>
{if !isset($SlideShow)}
<meta name="keywords" content="{$theme.item.keywords}" />
<meta name="description" content="{$theme.item.description|markup:strip}" />
{/if}

    {* Include this theme's style sheet *}
    <link rel="stylesheet" type="text/css" href="{g->theme url="theme.css"}"/>
    <script type="text/javascript" src="{g->url href='themes/PGtheme/theme.js'}"></script>
  </head>
  <body class="gallery">
    <div {g->mainDivAttributes}>
      {*
       * Some module views (eg slideshow) want the full screen.  So for those, we
       * don't draw a header, footer, navbar, etc.  Those views are responsible for
       * drawing everything.
       *}

      {if $theme.useFullScreen}
	{include file="gallery:`$theme.moduleTemplate`" l10Domain=$theme.moduleL10Domain}
      {else}
        {if $theme.params.iestatus}
        {literal}
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
            function wst(){
        {/literal}
              window.status="{$theme.params.site}";
        {literal}
              window.setTimeout("wst()",1);
            }
           wst()
          //-->
          //]]>
          </script>
        {/literal}
        {/if}

        {if $theme.params.expand}
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
            expand()
          //-->
          //]]>
          </script>
        {/if}

        {if !empty ($theme.params.RC)}
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
               document.onmousedown=rightdisable;
               if (document.layers) window.captureEvents(Event.MOUSEDOWN);
               window.onmousedown=rightdisable;
          //-->
          //]]>
          </script>
        {else}
          {if !empty ($theme.params.RCalert) && !empty ($theme.params.site)}
            <script type="text/JavaScript">
            //<![CDATA[
            <!--
              var msgmouse="(c) Copyright - {$theme.params.site}" 
              //Mouse Rigt click 'msg'
                document.onmousedown=rightalert;
                if (document.layers) window.captureEvents(Event.MOUSEDOWN);
                window.onmousedown=rightalert;
            //-->
            //]]>
            </script>
          {/if}
          {if !empty ($theme.params.RCoptions) && empty ($theme.params.sidebar)}
            <script type="text/JavaScript">
            //<![CDATA[
            <!--
               document.onmousedown=rightoptions;
               if (document.layers) window.captureEvents(Event.MOUSEDOWN);
               window.onmousedown=rightoptions;
            //-->
            //]]>
            </script>
          {/if}
        {/if}

        <div id="gsHeader">
          <table width="100%">
            <tr>
              <td>
                {assign var="separator" value=$theme.params.MenuSeparator}
                {if empty($theme.params.colorpack)}
                  <a href="{g->url}" 
onmouseover="logo.src='{g->url href='themes/PGtheme/images/logoon.gif'}'"  
onmouseout="logo.src='{g->url href='themes/PGtheme/images/logo.gif'}'">
                    <img id="logo" src="{g->url href="themes/PGtheme/images/logo.gif"}" alt="home" longdesc="home" /></a>                                
                {else}
                  <a href="{g->url}" 
onmouseover="logo.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/logoon.gif'"  
onmouseout="logo.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/logo.gif'">
                    <img id="logo" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/logo.gif" alt="home" longdesc="home" /></a>
                {/if}
              </td>
              <td style="text-align:right" valign="top">
                {if $theme.params.expandBtn}
                  {if empty($theme.params.colorpack)}
                    <a href="javascript:expand()" 
onmouseover="full.src='{g->url href='themes/PGtheme/images/fullon.gif'}'"  
onmouseout="full.src='{g->url href='themes/PGtheme/images/full.gif'}'">
                      <img id="full" src="{g->url href="themes/PGtheme/images/full.gif"}" alt="Full Screen" title="Full Screen" longdesc="Full Screen" class="navpic"/></a>
                  {else}
                    <a href="javascript:expand()" 
onmouseover="full.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/fullon.gif'"  
onmouseout="full.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/full.gif'">
                      <img id="full" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/full.gif" alt="Full Screen" longdesc="Full Screen" title="Full Screen" class="navpic"/></a>
                  {/if}
                {/if}
              </td>
            </tr>
          </table>
        </div>
        <table width="100%">
          <tr>
            {if $theme.params.BreadCrumb}
            <td style="width:50%;text-align:left">
              {g->block type="core.BreadCrumb"}
            </td>
            {/if}
            <td style="width:50%;text-align:right">
              {g->block type="core.SystemLinks"
		    order="core.SiteAdmin core.YourAccount core.Login core.Logout"
		    othersAt=4}
              {if $theme.params.link1}
                <a href="{$theme.params.link1url}">{$theme.params.link1}</a>
              {/if}

              {if $theme.params.link2}
                   {if !empty ($theme.params.link1)}
                   {$separator}
                   {/if}
                <a href="{$theme.params.link2url}">{$theme.params.link2}</a>
              {/if}

              {if empty($theme.params.sidebar)}
                   {if !empty ($theme.params.link1) || !empty ($theme.params.link2)}
                   {$separator}
                   {/if}
                <a href="#" onclick="ShowLayer('actions','visible')" 
onmouseover="ShowLayer('actions','hidden')">{g->text text="Options"}</a>
              {/if}
            </td>

          </tr>
        </table>
        <br/>
        {* Include the appropriate content type for the page we want to draw. *}
        {if $theme.pageType == 'album'}
          {g->theme include="album.tpl"}
        {elseif $theme.pageType == 'photo'}
          {g->theme include="photo.tpl"}
        {elseif $theme.pageType == 'admin'}
          {g->theme include="admin.tpl"}
        {elseif $theme.pageType == 'module'}
          {g->theme include="module.tpl"}
        {elseif $theme.pageType == 'progressbar'}
          {g->theme include="progressbar.tpl"}
        {/if}

        <div id="actions" style="position: absolute; left:{$theme.params.sidebarL}px; top: {$theme.params.sidebarT}px;  
z-index: 1; visibility: hidden;">
          <div id="actionsIn" style="position: relative; left: 0px; top: 0px;  
z-index: 2;" class="gcBackground1 gcBorder2">
          <div id="move" style="position: relative; left: 0px; top: 0px;  
z-index: 2;" class="gcBackground2" onmousedown="dragStart(event, 'actions')" title="click on this bar, drag and drop to move">
            <table class="Sidebar">
              <tr>
                <td style="text-align: right">
                  {if empty($theme.params.colorpack)}
                    <a onclick="MM_showHideLayers('actions','','hide')" onmouseover="MM_showHideLayers('actions','','hide')" title="Close">
                      <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/>
                    </a>
                  {else}
                    <a onclick="MM_showHideLayers('actions','','hide')" onmouseover="MM_showHideLayers('actions','','hide')" title="Close">
                      <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close" id="close"/>
                    </a>
                  {/if}
                </td>
              </tr>
            </table>
          </div>
            {g->theme include="sidebar.tpl"}
          </div>
          <br/>
        </div>

        <div id="gsFooter">
        <br/>
          {g->theme include="footer.tpl"}
        </div>
      {/if}  {* end of full screen check *}
    </div>

    {*
     * Give Gallery a chance to output any cleanup code, like javascript that
     * needs to be run at the end of the <body> tag.  If you take this out, some
     * code won't work properly.
     *}

    {g->trailer}
    {* Put any debugging output here, if debugging is enabled *}
    {g->debug}
  </body>
</html>
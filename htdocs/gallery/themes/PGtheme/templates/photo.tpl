{*
 * $Revision: 1.06 $ $Date: 2005/12/22 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{if !empty($theme.imageViews)}
  {assign var="image" value=$theme.imageViews[$theme.imageViewsIndex]}
  {assign var="imagewidth" value=$theme.imageViews[$theme.imageViewsIndex].width}
  {assign var="imageheight" value=$theme.imageViews[$theme.imageViewsIndex].height}
{/if}

{if empty($theme.params.colorpack)}
{literal}
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
function fsizeopen(url,title)
{
css = {/literal}"<link rel='stylesheet' type='text/css' href='{g->theme url='theme.css'}'/>"

{else}
{literal}
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
function fsizeopen(url,title)
{
css = {/literal}"<link rel='stylesheet' type='text/css' href='{g->theme url='theme.css'}'/><link rel='stylesheet' type='text/css' href='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/color.css'/>"

{/if}
{literal}
wsource = {/literal}{$theme.sourceImage.width}{literal}
witem = {/literal}{$theme.item.width}{literal}
w = screen.width;
h = screen.height;
title = {/literal}"{$theme.params.site}-{$theme.item.title}"{literal}
title1 = {/literal}"{$theme.item.title}"{literal}
desc = {/literal}"{$theme.item.description|markup}"{literal}
separ = {/literal}"{$theme.params.MenuSeparator}"{literal}


 var win = window.open(url,
  'popup',
  'width=' + w + ', height=' + h + ', ' +
  'location=no, menubar=no, ' +
  'status=no, toolbar=no, scrollbars=yes, resizable=no');
 win.moveTo(0,0);
 win.resizeTo(w, h);
 win.focus();
win.document.write("<html><head><title>");
win.document.write(title);
win.document.write("</title>");
win.document.write(css);
win.document.write("</head><body class='gallery' style='margin:0;padding:0'>");
win.document.write("<div id='gallery' style='margin:0;padding:0'>");
win.document.write("<center>");
win.document.write("<div class='giTitle'><h3>");
win.document.write(title1);
win.document.write("</h3></div>");
win.document.write("<div class='giDescription'>");
win.document.write(desc);
win.document.write("</div>");
win.document.write("<br/>");
if (witem > w) {
win.document.write("<div style='position: absolute; top:10; left:10'><a href='javascript:void(0)' onclick='photo.width=");
win.document.write(witem);
win.document.write("'>Full Size</a>");
win.document.write("&nbsp;");
win.document.write(separ);
win.document.write("&nbsp;");
win.document.write("<a href='javascript:void(0)' onclick='photo.width=");
win.document.write(w-30);
win.document.write("'>Screen Size</a> image</div>");
;}
win.document.write("<img id='photo' src='");
win.document.write(url);
win.document.write("' width='");
if (witem > w) {win.document.write(w-30);}else{win.document.write(witem);}
win.document.write("'usemap='#fotomap'/>");
win.document.write("</center>");
win.document.write("</div></body></html>");

win.document.close();
}
          //-->
          //]]>
          </script>
        {/literal}

<table width="100%">
  <tr>
    <td valign="top">
      {if $theme.params.sidebar && !empty($theme.params.sidebarBlocks)}
        <table cellspacing="0" cellpadding="0" align="left">
          <tr valign="top">
            <td id="gsSidebarCol">
              {g->theme include="sidebar.tpl"}
            </td>
          </tr>
        </table>
    </td>
    <td valign="top">
      {/if}

      <table width="92%" cellspacing="0" cellpadding="0" class="gcBackground1 gcBorder2" align="center">
        <tr valign="top">
          <td>
            <div id="gsContent" class="gcBackground1 gcBorder1">

{****** Title, Description, Info, Size, Navigation pics *}

              <div class="gbBlock gcBackground1">
                <table width="100%" border="0">
                  <tr>
                    <td style="width: 65%" valign="top">
                      {if !empty ($theme.params.PhotoTitle) && !empty ($theme.params.PhotoTitleTop)}
                        {if !empty($theme.item.title)}
                          <h2> {$theme.item.title|markup} </h2>
                        {/if}
                      {/if}
                      {if !empty ($theme.params.PhotoDescription) && !empty ($theme.params.PhotoDescriptionTop)}
                        {if !empty($theme.item.description)}
                          <p class="giDescription">
                            {$theme.item.description|markup}
                          </p>
                        {/if}
                      {/if}
                    </td>
                    <td style="width:5px"></td>
                    <td style="text-align:left" valign="top">
                      {if !empty ($theme.params.InfoPhoto) && !empty ($theme.params.InfoPhotoTop)}
                        {if !empty($theme.params.showImageOwner)}
                          {g->block type="core.ItemInfo"
                          item=$theme.item
                          showOwner=true
                          class="giInfo"}
                        {/if}
                          {g->block type="core.ItemInfo"
                          item=$theme.item
                          showDate=true
       		          showViewCount=true
                          class="giInfo"}
                        {if $theme.params.InfoSize}
                          {if !empty($theme.params.PopSize)}
                            {g->theme include="photoSizes.tpl" class="giInfo"}
                          {else}
                            {g->block type="core.PhotoSizes" class="giInfo"}
                          {/if}
                        {/if}
                      {/if}
                    </td>
                    <td style="text-align:right" valign="top">
                        {if !empty($theme.navigator)}


                         {assign var="photoItem" value="1"}
                          {g->theme include="navigatorTop.tpl"}
                        {/if}
                    </td>
                  </tr>
                </table>
              </div>

{************** Microthumbs right***********}

              {if $theme.params.MTposition}
              <table id="photo" align="center" border="0" width="98%">
                <tr>
                  <td style="text-align:center" width="75%" valign="top">
                      {if !empty($theme.navigator) && !empty($theme.params.NavPhotoTop)}
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center" width="100%">
                            <tr>
                              <td>
                                {g->theme include="navigatorPhotoTop.tpl"}
                              </td>
                            </tr>
                          </table>
                        </div>
                      {/if}
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align:top">
                    <div id="gsImageView" class="gbBlock">
                      {if !empty($theme.imageViews)}
                        {capture name="fallback"}
	          <a href="{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.item.id`"}">
	            {g->text text="Download %s" arg1=$theme.sourceImage.itemTypeName.1}
	          </a>
	        {/capture}
                          <table border="0" align="center">
                            <tr>
                              <td>
	                {if ($image.viewInline)}
	                  {if isset($theme.photoFrame)}
		    {g->container type="imageframe.ImageFrame" frame=$theme.photoFrame}
                                      {if $theme.params.imageFadin} 
                                        <div class="gsSingleImage" id="gsSingleImageId" style="width:{$imagewidth} height:{$imageheight}">
                                          {if !empty($theme.params.NavOverTop) || !empty($theme.params.NavOverBottom)}
                                            {g->theme include="photoNavOver.tpl"}
                                          {else}
		            {g->image item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" id="foto" longdesc="%ID%"}
                                          {/if}
                                        </div>
                                      {else}
                                        <div class="gsSingleImageNoF" style="width:{$imagewidth} height:{$imageheight}">
                                          {if !empty($theme.params.NavOverTop) || !empty($theme.params.NavOverBottom)}
                                            {g->theme include="photoNavOver.tpl"}
                                          {else}
		            {g->image item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" id="foto" longdesc="%ID%"}
                                          {/if}
                                        </div> 
                                      {/if}
  		    {/g->container}
	                  {else}
                                      {if $theme.params.imageFadin} 
                                        <div class="gsSingleImage" id="gsSingleImageId" style="width:{$imagewidth} height:{$imageheight}">
                                          {if !empty($theme.params.NavOverTop) || !empty($theme.params.NavOverBottom)}
                                            {g->theme include="photoNavOver.tpl"}
                                          {else}
		            {g->image item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" id="foto" longdesc="%ID%" style="border:0"}
                                          {/if}
                                        </div>
                                      {else}
                                        <div class="gsSingleImageNoF" style="width:{$imagewidth} height:{$imageheight}">
                                          {if !empty($theme.params.NavOverTop) || !empty($theme.params.NavOverBottom)}
                                            {g->theme include="photoNavOver.tpl"}
                                          {else}
		            {g->image item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" id="foto" longdesc="%ID%"}
                                          {/if}
                                        </div> 
                                      {/if}
	                  {/if}
	                {else}
	                  {$smarty.capture.fallback}
	                {/if}
                            </td>
                          </tr>
                        </table>
                      {else}
                        {g->text text="There is nothing to view for this item."}
                      {/if}
                    </div>
{* Download link for item in original format *}
                    {if !empty($theme.sourceImage) && $theme.sourceImage.mimeType != $theme.item.mimeType}
                      <div class="gbBlock">
                        <a href="{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.item.id`"}">
                          {g->text text="Download %s in original format" arg1=$theme.sourceImage.itemTypeName.1}
                        </a>
                      </div>
                    {/if}
                  </td>

                {if !empty ($theme.params.showMicroThumbs) || (!empty ($theme.params.PhotoTitle) && empty ($theme.params.PhotoTitleTop)) || (!empty ($theme.params.PhotoDescription) && empty ($theme.params.PhotoDescriptionTop)) || (!empty ($theme.params.InfoPhoto) && empty ($theme.params.InfoPhotoTop))}
                  <td style="text-align:center" valign="top">
                      <table align="center">
                    {if $theme.params.showMicroThumbs}
                        <tr>
                          <td style="text-align:center" valign="top">
                            {g->theme include="navigatorThumbs.tpl"}
                          </td>
                        </tr>
                    {/if}
                        <tr>
                          <td style="text-align:left" valign="top">
                            {if !empty ($theme.params.PhotoTitle) && empty ($theme.params.PhotoTitleTop)}
                              {if !empty($theme.item.title)}
                                <br/>
                                <div class="descSeparator"></div>
                                    <h2> {$theme.item.title|markup} </h2>
                              {/if}
                            {/if}
                            {if !empty ($theme.params.PhotoDescription) && empty ($theme.params.PhotoDescriptionTop)}
                              {if !empty($theme.item.description)}
                                  <p class="giDescription">
                                    {$theme.item.description|markup}
                                  </p>
                                <div class="descSeparator"></div>
                              {/if}
                            {/if}
                            {if !empty ($theme.params.InfoPhoto) && empty ($theme.params.InfoPhotoTop)}
                              {if !empty($theme.params.showImageOwner)}
                                 {g->block type="core.ItemInfo"
                                    item=$theme.item
                                    showOwner=true
                                    class="giInfo"}
                              {/if}
                                  {g->block type="core.ItemInfo"
                                    item=$theme.item
                                    showDate=true
       		                    showViewCount=true
                                    class="giInfo"}
                              {if $theme.params.InfoSize}
                                {if !empty($theme.params.PopSize)}
                                  {g->theme include="photoSizes.tpl" class="giInfo"}
                                {else}
                                  {g->block type="core.PhotoSizes" class="giInfo"}
                                {/if}
                              {/if}
                                <div class="descSeparator"></div>
                            {/if}
                          </td>
                        </tr>
                      </table>
                  </td>
                {/if}
                </tr>
                <tr>
                  <td style="text-align:center">
                      {if !empty($theme.navigator)  && !empty($theme.params.NavPhotoBottom)}
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center" width="100%">
                            <tr>
                              <td>
                                {g->theme include="navigatorPhotoBottom.tpl"}
                              </td>
                            </tr>
                          </table>
                        </div>
                      {/if}

                  </td>
                </tr>
              </table>
 
{************** Microthumbs left***********}

              {else}
                <table id="photo" align="center" border="0" width="98%">
                  <tr>
                    <td>
                    </td>
                    <td style="text-align:center" width="75%" valign="top">
                      {if !empty($theme.navigator) && !empty($theme.params.NavPhotoTop)}
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center" width="100%">
                            <tr>
                              <td>
                                {g->theme include="navigatorPhotoTop.tpl"}
                              </td>
                            </tr>
                          </table>
                        </div>
                      {/if}
                    </td>
                  </tr>
                  <tr>
                  {if !empty ($theme.params.showMicroThumbs) || (!empty ($theme.params.PhotoTitle) && empty ($theme.params.PhotoTitleTop)) || (!empty ($theme.params.PhotoDescription) && empty ($theme.params.PhotoDescriptionTop)) || (!empty ($theme.params.InfoPhoto) && empty ($theme.params.InfoPhotoTop))}
                    <td style="text-align:center" valign="top">
                      <table align="center">
                        {if $theme.params.showMicroThumbs}
                        <tr>
                          <td style="text-align:center" valign="top">
                            {g->theme include="navigatorThumbs.tpl"}
                          </td>
                        </tr>
                        {/if}
                        <tr>
                          <td style="text-align:left" valign="top">
                            {if !empty ($theme.params.PhotoTitle) && empty ($theme.params.PhotoTitleTop)}
                              {if !empty($theme.item.title)}
                                <br/>
                                <div class="descSeparator"></div>
                                <h2> {$theme.item.title|markup} </h2>
                              {/if}
                            {/if}
                            {if !empty ($theme.params.PhotoDescription) && empty ($theme.params.PhotoDescriptionTop)}
                              {if !empty($theme.item.description)}
                                <p class="giDescription">
                                  {$theme.item.description|markup}
                                </p>
                                <div class="descSeparator"></div>
                              {/if}
                            {/if}
                            {if !empty ($theme.params.InfoPhoto) && empty ($theme.params.InfoPhotoTop)}
                              {if !empty($theme.params.showImageOwner)}
                                {g->block type="core.ItemInfo"
                                       item=$theme.item
                                       showOwner=true
                                       class="giInfo"}
                              {/if}
                                {g->block type="core.ItemInfo"
                                       item=$theme.item
                                       showDate=true
       		                       showViewCount=true
                                       class="giInfo"}
                              {if $theme.params.InfoSize}
                                {if !empty($theme.params.PopSize)}
                                  {g->theme include="photoSizes.tpl" class="giInfo"}
                                {else}
                                  {g->block type="core.PhotoSizes" class="giInfo"}
                                {/if}
                              {/if}
                              <div class="descSeparator"></div>
                            {/if}
                          </td>
                        </tr>
                      </table>
                    </td>
                  {/if}
                    <td style="vertical-align:top">
                      <div id="gsImageView" class="gbBlock">
                        {if !empty($theme.imageViews)}
	          {capture name="fallback"}
	            <a href="{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.item.id`"}">
	             {g->text text="Download %s" arg1=$theme.sourceImage.itemTypeName.1}
	            </a>
	          {/capture}
                          <table border="0" align="center" style="vertical-align:top">
                            <tr>
                              <td>
	                {if ($image.viewInline)}
	                  {if isset($theme.photoFrame)}
		            {g->container type="imageframe.ImageFrame" frame=$theme.photoFrame}
                              {if $theme.params.imageFadin} 
                                <div class="gsSingleImage" id="gsSingleImageId" style="width:{$imagewidth}px; height:{$imageheight}px;">
                                  {if !empty($theme.params.NavOverTop) || !empty($theme.params.NavOverBottom)}
                                    {g->theme include="photoNavOver.tpl"}
                                  {else}
		                    {g->image id=%ID% item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" longdesc="%ID%"}
                                  {/if}
                                </div>
                              {else}
                                <div class="gsSingleImageNoF" style="width:{$imagewidth}px; height:{$imageheight}px;">
                                  {if !empty($theme.params.NavOverTop) || !empty($theme.params.NavOverBottom)}
                                    {g->theme include="photoNavOver.tpl"}
                                  {else}
		                    {g->image id=%ID% item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" longdesc="%ID%"}
                                  {/if}
                                </div> 
                              {/if}
  		            {/g->container}
	                  {else}
                            {if $theme.params.imageFadin} 
                              <div class="gsSingleImage" id="gsSingleImageId" style="width:{$imagewidth}px; height:{$imageheight}px;">
                                {if !empty($theme.params.NavOverTop) || !empty($theme.params.NavOverBottom)}
                                  {g->theme include="photoNavOver.tpl"}
                                {else}
		                  {g->image id=%ID% item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" longdesc="%ID%"}
                                {/if}
                              </div>
                            {else}

                              <div style="width:{$imagewidth}px; height:{$imageheight}px;">
                                {if !empty ($theme.params.NavOverTop)}
                                  {g->theme include="photoNavOver.tpl"}
                                {else}

                                  {if !empty ($theme.params.NavOverBottom)}
                                    {g->theme include="photoNavOver.tpl"}
                                  {else}
		                    {g->image id=%ID% item=$theme.item image=$image fallback=$smarty.capture.fallback class="%CLASS%" longdesc="%ID%"}
                                  {/if}
                                {/if}
                              </div> 

                            {/if}
	                  {/if}
	                {else}
	                  {$smarty.capture.fallback}
	                {/if}
                              </td>
                            </tr>
                          </table>
                        {else}
                          {g->text text="There is nothing to view for this item."}
                        {/if}
                      </div>
{* Download link for item in original format *}
                      {if !empty($theme.sourceImage) && $theme.sourceImage.mimeType != $theme.item.mimeType}
                        <div class="gbBlock">
                          <a href="{g->url arg1="view=core.DownloadItem" arg2="itemId=`$theme.item.id`"}">
                            {g->text text="Download %s in original format" arg1=$theme.sourceImage.itemTypeName.1}
                          </a>
                        </div>
                      {/if}
                    </td>
                  </tr>
                  <tr>
                    <td>
                    </td>
                    <td style="text-align:center">
                      {if !empty($theme.navigator)  && !empty($theme.params.NavPhotoBottom)}
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center"  width="100%">
                            <tr>
                              <td>
                                {g->theme include="navigatorPhotoBottom.tpl"}
                              </td>
                            </tr>
                          </table>
                        </div>
                      {/if}
                    </td>
                  </tr>
                </table>
              {/if}

            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

        {g->block type="core.GuestPreview" class="gbBlock"}

        {* Our emergency edit link, if the user all blocks containing edit links *}
	{g->block type="core.EmergencyEditItemLink" class="gbBlock"
                  checkSidebarBlocks=true
                  checkPhotoBlocks=true}

                                       <div id="comments" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'comments')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td>
                                             {g->block type="comment.ViewComments" item=$child }
                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               {if empty($theme.params.colorpack)}
                                                 <a onclick="MM_showHideLayers('comments','','hide')" onmouseover="MM_showHideLayers('comments','','hide')" title="Close">
                                                   <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/></a>
                                               {else}
                                                 <a onclick="MM_showHideLayers('comments','','hide')" onmouseover="MM_showHideLayers('comments','','hide')" title="Close">
                                                   <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close"/></a>
                                               {/if}

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>


                                        <div id="exif" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; width:500px; text-align:left;
z-index: 10; visibility: hidden;" onmousedown="dragStart(event, 'exif')" class="BlockOpacity">
                                          <div id="exifIn" style="position: relative; left: 0px; top: 0px;  
z-index: 10;" class="gcBackground1 gcBorder2">

                                          {g->block type="exif.ExifInfo" item=$child }
                                            <div style="text-align: right; padding:4px">
                                            {if empty($theme.params.colorpack)}
                                              <a onclick="MM_showHideLayers('exif','','hide')" onmouseover="MM_showHideLayers('exif','','hide')" title="Close">
                                                <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/></a>
                                            {else}
                                              <a onclick="MM_showHideLayers('exif','','hide')" onmouseover="MM_showHideLayers('exif','','hide')" title="Close">
                                                <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close"/></a>
                                            {/if}
                                            </div>

                                          </div>
                                          <br/>
                                        </div>



                                       <div id="blockIC{$child.id}" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blockIC{$child.id}')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "{$child.title|markup}"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             {g->block type="comment.ViewComments" item=$child }
                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               {if empty($theme.params.colorpack)}
                                                 <a onclick="MM_showHideLayers('blockIC{$child.id}','','hide')" onmouseover="MM_showHideLayers('blockIC{$child.id}','','hide')" title="Close">
                                                   <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/></a>
                                               {else}
                                                 <a onclick="MM_showHideLayers('blockIC{$child.id}','','hide')" onmouseover="MM_showHideLayers('blockIC{$child.id}','','hide')" title="Close">
                                                   <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close"/></a>
                                               {/if}

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>


                                        <div id="blocks{$item.id}" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; text-align:left; z-index: 10; visibility: hidden;" onmousedown="dragStart(event, 'blocks{$item.id}')" class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td>
                                          
<table><tr><td height="10px"></td></tr></table>
          <table width="100%" align="{if !empty ($theme.params.BlocksAlign)}{$theme.params.BlocksAlign}{else}center{/if}">
            <tr><td></td></tr>
              {foreach from=$theme.params.photoBlocks item=block}
            <tr>
              <td>
                <table align="{if !empty ($theme.params.BlocksInAlign)}{$theme.params.BlocksInAlign}{else}center{/if}">
                  <tr>
                    <td>
                      {g->block type=$block.0 params=$block.1}
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
              {/foreach}
          </table>

                                           </td></tr>
                                           <tr><td style="text-align:right">

                                            {if empty($theme.params.colorpack)}
                                              <a onclick="MM_showHideLayers('blocks{$item.id}','','hide')" onmouseover="MM_showHideLayers('blocks{$item.id}','','hide')" title="Close">
                                                <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/></a>
                                            {else}
                                              <a onclick="MM_showHideLayers('blocks{$item.id}','','hide')" onmouseover="MM_showHideLayers('blocks{$item.id}','','hide')" title="Close">
                                                <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close"/></a>
                                            {/if}

                                           </td></tr>
                                         </table>
                                          <br/>
                                        </div>


{if !empty ($theme.params.photoBlocks) && empty ($theme.params.OtherBlocksBtn)}

{* Show any other album blocks (comments, exif etc) *}
<table border="0" width="98%"><tr><td>

          <table width="400" align="{if !empty ($theme.params.BlocksAlign)}{$theme.params.BlocksAlign}{else}center{/if}">
            <tr><td></td></tr>
              {foreach from=$theme.params.photoBlocks item=block}
            <tr>
              <td>
                <table align="{if !empty ($theme.params.BlocksInAlign)}{$theme.params.BlocksInAlign}{else}center{/if}">
                  <tr>
                    <td>
                      {g->block type=$block.0 params=$block.1}
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
              {/foreach}

          </table>
</td></tr></table>
{/if}
<script type="text/javascript">
//<![CDATA[
start();
//]]>
</script>
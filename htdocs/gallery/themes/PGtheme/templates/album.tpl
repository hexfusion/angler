{*
 * $Revision: 1.06 $ $Date: 2005/12/22 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<table width="100%">
  <tr>
    <td valign="top">
      {if $theme.params.sidebar && !empty($theme.params.sidebarBlocks)}
        <table cellspacing="0" cellpadding="0" align="left">
          <tr valign="top">
            <td>
              {g->theme include="sidebar.tpl"}
            </td>
          </tr>
        </table>
    </td>
    <td valign="top">
      {/if}
      <table width="92%" cellspacing="0" cellpadding="0" class="gcBackground1 gcBorder2" align="center">
        <tr valign="top">
            {if empty($theme.parents)}
              {if $theme.params.showOwnerContact}
          <td style="width: 55%" class="gcBackground1">
            <table class="gcBackground1">
              <tr>
                <td>
                  <table width="100%" cellspacing="5" class="gcBackground1 .gcBorder1">
                    <tr>
                      <td>
                        <p class="author">
                          {g->theme include="author.tpl"}
                        </p>
                        <br/><br/>
                        <table width="100%">
                          <tr>
                            <td style="text-align:left">
                              <p class="authorlink">
                                <a href="#"  title="Click to hide..." onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink1','','show','authorlink2','','hide')" style="color:#dfdfdf">
                                {$theme.params.authorlink1}
                                </a>
                              </p>
                            </td>
                            <td style="text-align:right">
                              <p class="author">
                                <a href="#" onclick="MM_showHideLayers('authorlink2','','hide')" onmouseover="MM_showHideLayers('authorlink2','','show','authorlink1','','hide')"  style="color:#dfdfdf" title="Click to hide..." >
                                {$theme.params.authorlink2}
                                </a>
                              </p>
                            </td>
                          </tr>
                        </table>
                        <div align="left">
                          <p>
                            <a href="mailto:{$theme.params.email}" title="{$theme.params.email}" class="authoremail">
                              {$theme.params.email}
                            </a>
                          </p>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <table align="center" class="tableacpic">
                          <tr>
                           <td>
                             <div class="actions">
                 {if $theme.params.AuthorActions}
                   {if $user.isAdmin}
                     {if $theme.guestPreviewMode !=1}
                               <div style="height:30px">
                                {g->block type="core.ItemLinks" item=$child links=$child.itemLinks}
                               </div>
                     {/if}
                   {/if}
                               <table>
                                 <tr>
                   {if $user.isGuest || ($user.isAdmin && ($theme.guestPreviewMode ==1))}
                     {if $theme.cart ==1}
                                   <td>
                       {if !empty($theme.params.colorpack)}
                                     <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif'"
onmouseover= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_carton.gif'"
                         {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                         {else}
title="Add to Cart"
                         {/if}
>
                                       <img id="cart{$child.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                         {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                         {else}
title="Add to Cart"
                         {/if}
class="navtoppic" /></a>
                                   </td>
                         {if !empty ($theme.params.ActionsText)}
                                   <td>
<a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif'"
onmouseover= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_carton.gif'"
                         {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                         {else}
title="Add to Cart"
                         {/if}
>
                                     {g->text text=$theme.params.AddCartText}</a>
                                   </td>
                                 </tr><tr>
                         {/if}
                       {else}
                                      <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" onmouseout="cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_cart.gif'" onmouseover="cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_carton.gif'"
                         {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                         {else}
title="Add to Cart"
                         {/if}
>
                                        <img id="cart{$child.id}" src="{g->url href='themes/PGtheme/images/'}add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                         {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                         {else}
title="Add to Cart"
                         {/if}
 class="navtoppic"/></a>
                                   </td>
                         {if !empty ($theme.params.ActionsText)}
                                   <td>
                                      <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_cart.gif'"
onmouseover= "cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_carton.gif'">
                           {if !empty ($theme.params.AddCartText)}
                                        {g->text text=$theme.params.AddCartText}
                           {else}
                                        {g->text text='Add to Cart'}
                           {/if}
                                     </a>
                                   </td>
                                 </tr><tr>
                         {/if}
                       {/if}
                     {else}
                                   <td></td>
                         {if !empty ($theme.params.ActionsText)}
                                 </tr><tr>
                         {/if}
                     {/if} {* cart end *}
                   {/if} {* user.isGuest end *}
                 {/if} {* $AuthorActions end *}

                 {if empty ($theme.params.AuthorActions) && !empty ($theme.params.AuthorComments)}
                                 <table>
                                   <tr>
                 {/if}
                 {if !empty ($theme.params.AuthorComments) && ($theme.comments ==1)}
                   {if isset ($theme.permissions.comment_add)}{*comments add*}
                     {if !empty ($theme.params.colorpack)}
                                     <td>
                                       <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcommentson.gif' "
                       {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                       {else}
title="Add a Comment"
                       {/if}
>
                                        <img id="addcom{$child.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                       {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                       {else}
title="Add a Comment"
                       {/if}
class="navtoppic" /></a>
                                     </td>
                       {if !empty ($theme.params.ActionsText)}
                                     <td>
                                       <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcommentson.gif' "
                         {if !empty ($theme.params.CommentsText)}
                                        {g->text text=$theme.params.CommentsText}
                         {else}
                                        {g->text text='Add a Comment'}
                         {/if}
>
                         {if !empty ($theme.params.CommentsText)}
                                        {g->text text=$theme.params.CommentsText}
                         {else}
                                        {g->text text='Add a Comment'}
                         {/if}
                                       </a>
                                     </td>
                                    </tr><tr>
                       {/if}
                     {else}
                                     <td>
                                       <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcommentson.gif'"
                       {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                       {else}
title="Add a Comment"
                       {/if}
>
                                        <img id="addcom{$child.id}" src="{g->url href='themes/PGtheme/images/'}addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                       {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                       {else}
title="Add a Comment"
                       {/if}
class="navtoppic"/></a>
                                     </td>
                       {if !empty ($theme.params.ActionsText)}
                                     <td>
                                       <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$theme.item.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcommentson.gif' ">
                         {if !empty ($theme.params.CommentsText)}
                                        {g->text text=$theme.params.CommentsText}
                         {else}
                                        {g->text text="Add a Comment"}
                         {/if}
                                       </a>
                                     </td>
                                   </tr><tr>
                       {/if}
                     {/if}
                   {/if} {* comments add *}

                   {if isset ($theme.permissions.comment_view)}{* comments view *}
                     {if !empty($theme.params.colorpack)}
                                     <td>
                       {if empty($item)} {assign var=item value=$theme.item} {/if}
                                        {g->callback type="comment.LoadComments" itemId=$item.id show=$show}
                       {if !empty($block.comment.LoadComments.comments)}
                                       <a href="javascript:void(0)"
onmouseout= "blockspic{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif'" onclick="ShowLayer('blocks{$item.id}','visible')"
onmouseover= "blockspic{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/commentson.gif';ShowLayer('blocks{$item.id}','hidden') "
                         {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                         {else}
title="View Comments"
                         {/if}
>
                                       <img id="blockspic{$item.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif" alt="View Comments" longdesc="View Comments"
                         {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                         {else}
title="View Comments"
                         {/if}
class="navtoppic" /></a>
                       {/if}

                       {if !empty ($theme.params.ActionsText)}
                                     </td><td>
                         {if empty($item)} {assign var=item value=$theme.item} {/if}
                                        {g->callback type="comment.LoadComments" itemId=$item.id show=$show}
                         {if !empty($block.comment.LoadComments.comments)}
                                       <a href="javascript:void(0)"
onmouseout= "blockspic{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif'" onclick="ShowLayer('blocks{$item.id}','visible')"
onmouseover= "blockspic{$item.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/commentson.gif';ShowLayer('blocks{$item.id}','hidden') "
                         {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                         {else}
title="View Comments"
                         {/if}
>
                           {if !empty ($theme.params.ViewCommentsText)}
                                          {g->text text=$theme.params.ViewCommentsText}
                           {else}
                                          {g->text text='View Comments'}
                           {/if}
                                          </a>
                         {/if}
                                      </td>
                                    </tr><tr>
                       {/if}
                     {else}
                                     <td>
                       {if empty($item)} {assign var=item value=$theme.item} {/if}
                                      {g->callback type="comment.LoadComments" itemId=$item.id show=$show}
                       {if !empty($block.comment.LoadComments.comments)}
                                       <a href="javascript:void(0)"
onmouseout= "blockspic{$item.id}.src='{g->url href='themes/PGtheme/images/'}comments.gif'" onclick="ShowLayer('blocks{$item.id}','visible')"
onmouseover= "blockspic{$item.id}.src='{g->url href='themes/PGtheme/images/'}commentson.gif';ShowLayer('blocks{$item.id}','hidden') "
                         {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                         {else}
title="View Comments"
                         {/if}
>
                                         <img id="blockspic{$item.id}" src="{g->url href='themes/PGtheme/images/'}comments.gif" alt="View Comments" longdesc="View Comments"  
                         {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                         {else}
title="View Comments"
                         {/if}
class="navtoppic" /></a>
                       {/if}
                       {if !empty ($theme.params.ActionsText)}
                                     </td><td>
                         {if empty($item)} {assign var=item value=$theme.item} {/if}
                                        {g->callback type="comment.LoadComments" itemId=$item.id show=$show}
                         {if !empty($block.comment.LoadComments.comments)}
                                       <a href="javascript:void(0)"
onmouseout= "blockspic{$item.id}.src='{g->url href='themes/PGtheme/images/'}comments.gif'" onclick="ShowLayer('blocks{$item.id}','visible')"
onmouseover= "blockspic{$item.id}.src='{g->url href='themes/PGtheme/images/'}commentson.gif'; ShowLayer('blocks{$item.id}','hidden')">
                           {if !empty ($theme.params.ViewCommentsText)}
                                          {g->text text=$theme.params.ViewCommentsText}
                           {else}
                                          {g->text text='View Comments'}
                           {/if}
                                       </a>
                         {/if}
                       {/if}
                     {/if}
                                       <div id="blocks{$item.id}" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blocks{$item.id}')"  class="BlockOpacity">
                                         <table  width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "{$item.title|markup}"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             {g->block type="comment.ViewComments" item=$child }
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
                                      </td>
                     {/if} {* comments view *}
                   {/if} {* comments end *}
                   {if ($theme.comments ==1)}
                     {if empty ($theme.params.AuthorActions) && !empty ($theme.params.AuthorComments)}
                       {if $user.isAdmin}
                                     <td>
                                     </td>
                       {/if}
                     {/if}
                     {if !empty ($theme.params.AuthorActions) && empty ($theme.params.AuthorComments)}
                       {if $user.isAdmin}
                                     <td>
                                     </td>
                       {/if}
                     {/if}
                     {if empty ($theme.params.AuthorActions) && empty ($theme.params.AuthorComments)}
                                      <table>
                                        <tr>
                                          <td>
                                          </td>
                     {/if}
                   {/if}
                   {if  ($theme.comments !=1)}
                                      <table>
                                        <tr>
                                          <td>
                                          </td>
                   {/if}
                                        </tr>
                                      </table>
                                    </div> {* comments end *}
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>

                        <div id="authorlink1" style="position:absolute; height:216px; z-index:10; left: 175px; top: 25px;border: 0px solid #999999; visibility:hidden"
onmousedown="dragStart(event, 'authorlink1')">
                        <table cellpadding="6" class="gcBackground2 gcBorder2">
                          <tr>
                            <td style="height:5; text-align:right">
                   {if empty($theme.params.colorpack)}
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink1','','hide')" title="Close">
                              <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close" class="navpic"/>
                            </a>
                   {else}
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink1','','hide')" title="Close">
                              <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close" class="navpic"/>
                            </a>
                   {/if}
                            </td>
                          </tr>
                          <tr>
                            <td>
                        {g->theme include=$theme.params.authorlink1url}
                            </td>
                          </tr>
                          <tr>
                            <td style="height=5"></td>
                          </tr>
                        </table><br/>
                        </div>
                        <div id="authorlink2" style="position:absolute; height:216px; z-index:10; left: 25px; top: 25px;border: 0px solid #999999; visibility:hidden" onmousedown="dragStart(event, 'authorlink2')">
                        <table cellpadding="6" class="gcBackground2 gcBorder2">
                          <tr>
                            <td style="height:5; text-align:right">
                   {if empty($theme.params.colorpack)}
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink2','','hide')" title="Close">
                              <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close" class="navpic"/>
                            </a>
                   {else}
                            <a onclick="MM_showHideLayers('authorlink1','','hide')" onmouseover="MM_showHideLayers('authorlink2','','hide')" title="Close">
                              <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close" class="navpic"/>
                            </a>
                   {/if}
                            </td>
                          </tr>
                          <tr>
                            <td>
                        {g->theme include=$theme.params.authorlink2url}
                            </td>
                          </tr>
                          <tr>
                            <td style="height=5"></td>
                          </tr>
                        </table><br />
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td>
                 {else}
                <td id="gsSidebarCol">
               {/if}
             {else}
                <td id="gsSidebarCol">
             {/if}
                <div id="gsContent" class="gcBackground1 gcBorder1">
             {if empty($theme.parents)}
                  <div class="gbBlock gcBackground1">
                  <table style="width: 100%" border="0">
                    <tr>
                      <td style="width: 70%" >
               {if $theme.params.1stTitle}
                 {if !empty($theme.item.title)}
                            <h2> {$theme.item.title|markup} </h2>
                 {/if}
               {/if}
               {if $theme.params.1stDescription}
                 {if !empty($theme.item.description)}
                            <p class="giDescription">
                              {$theme.item.description|markup}
                            </p>
                 {/if}
               {/if}
                      </td>
               {if $theme.params.InfoGallery}
                      <td style="width: 17%" valign="top">
                 {if !empty($theme.params.showAlbumOwner)}
                            {g->block type="core.ItemInfo"
                                item=$theme.item
                                showOwner=true
                                class="giInfo"}
                 {/if}
                          {g->block type="core.ItemInfo"
                               item=$theme.item
                               showDate=true
                               showSize=true
       		               showViewCount=true
                               class="giInfo"}
                      </td>
               {/if}
             {else}
                  <div class="gbBlock gcBackground1" >
{**********************}
                  <table style="width: 100%; height: 40px" border="0">
                    <tr>
                      <td style="width: 60%; vertical-align:top" >
               {if !empty ($theme.params.AlbumTitle) && !empty ($theme.params.AlbumTitleTop)}
                 {if !empty($theme.item.title)}
                            <h2> {$theme.item.title|markup} </h2>
                 {/if}
               {/if}
               {if !empty ($theme.params.AlbumDescription) && !empty ($theme.params.AlbumDescriptionTop)}
                 {if !empty($theme.item.description)}
                            <p class="giDescription">
                              {$theme.item.description|markup}
                            </p>
                 {/if}
               {/if}
                      </td>
               {if !empty ($theme.params.AlbumInfo) && !empty ($theme.params.AlbumInfoTop)}
                      <td style="width: 20%; vertical-align:top">
                 {if !empty($theme.params.showAlbumOwner)}
                            {g->block type="core.ItemInfo"
                                 item=$theme.item
                                 showOwner=true
                                 class="giInfo"}
                 {/if}
                            {g->block type="core.ItemInfo"
                                item=$theme.item
                                showDate=true
                                showSize=true
       		                showViewCount=true
                                class="giInfo"}
                      </td>
               {/if}
                      <td style="width: 20%; text-align:right; vertical-align:top">
                        {g->theme include="navigatorTop.tpl"}
                      </td>
             {/if}
                    </tr>
                  </table>
                  </div>

             {if !count($theme.children)}
                  <div class="gbBlock giDescription gbEmptyAlbum">
                      <h1 class="emptyAlbum">
	                {g->text text="This album is empty."}
	       {if isset($theme.permissions.core_addDataItem)}
                          <br/>
                          <a href="{g->url arg1="view=core.ItemAdmin" arg2="subView=core.ItemAdd" arg3="itemId=`$theme.item.id`"}"> {g->text text="Add a photo!"}
                          </a>
	       {/if}
                      </h1>
                  </div>
             {else}
                  <table width="100%" align="center" border="0">
                    <tr><td></td>
                      <td>
               {if $theme.params.NavAlbumTop}
                 {if !empty($theme.navigator)}
                   {if !empty($theme.jumpRange)}
                          <div class="gcBackground1 gbNavigator">
                            {g->theme include="navigatorAlbumTop.tpl"}
                          </div>
                   {/if}
                 {/if}
               {/if}
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">
{*************************}
               {if !empty($theme.parents)}
                        <table width="95%" border="0" align="center">
                          <tr>
                            <td style="vertical-align:top">
                 {if !empty ($theme.params.AlbumTitle) && empty ($theme.params.AlbumTitleTop)}
                   {if !empty($theme.item.title)}
                                      <br/>
                                      <h2> {$theme.item.title|markup} </h2>
                   {/if}
                 {/if}
                 {if !empty ($theme.params.AlbumDescription) && empty ($theme.params.AlbumDescriptionTop)}
                   {if !empty($theme.item.description)}
                                      <p class="giDescription">
                                        {$theme.item.description|markup}
                                      </p>
                                      <div class="descSeparator"></div>
                   {/if}
                 {/if}
                 {if !empty ($theme.params.AlbumInfo) && empty ($theme.params.AlbumInfoTop)}
                   {if !empty($theme.params.showAlbumOwner)}
                                      {g->block type="core.ItemInfo"
                                           item=$theme.item
                                           showOwner=true
                                           class="giInfo"}
                   {/if}
                                      {g->block type="core.ItemInfo"
                                          item=$theme.item
                                          showDate=true
                                          showSize=true
       		                          showViewCount=true
                                          class="giInfo"}
                                    <div class="descSeparator"></div>
                 {/if}
                            </td>
                          </tr>
                        </table>
               {/if}
                      </td>
                      <td valign="top">
                        {assign var="childrenInColumnCount" value=0}
                        <div class="gbBlock">
                          <table id="gsThumbMatrix" width="100%">
                            <tr valign="top">
                              {foreach from=$theme.children item=child}
{* Move to a new row *}
               {if empty($theme.parents)}
                 {if ($childrenInColumnCount == $theme.params.columns1st)}
                            </tr>
                            <tr valign="top">
                                    {assign var="childrenInColumnCount" value=0}
                 {/if}
                                    {assign var="nome" value=$child.id}
                                    {assign var="Toff" value=$theme.params.opacityT}
                                    {assign var="Tover" value=$theme.params.opacityTover}
                                    {assign var=childrenInColumnCount value="`$childrenInColumnCount+1`"}
                              <td class="{if $child.canContainChildren}giAlbumCell gcBackground1{else}giItemCell{/if}">
                 {if $child.canContainChildren}
                                    {assign var=frameType value="albumFrame"}
                 {else}
                                    {assign var=frameType value="itemFrame"}
                 {/if}
                                  <br/><br/><br/>

               {else} 
                 {if ($childrenInColumnCount == $theme.params.columns)}
                            </tr>
                            <tr valign="top">
                                    {assign var="childrenInColumnCount" value=0}
                 {/if}
                                    {assign var="nome" value=$child.id}
                                    {assign var="Toff" value=$theme.params.opacityT}
                                    {assign var="Tover" value=$theme.params.opacityTover}
                                    {assign var=childrenInColumnCount value="`$childrenInColumnCount+1`"}
                              <td class="{if $child.canContainChildren}giAlbumCell gcBackground1{else}giItemCell{/if}">
                 {if $child.canContainChildren}
                                  {assign var=frameType value="albumFrame"}
                 {else}
                                  {assign var=frameType value="itemFrame"}
                 {/if}
               {/if}
                                <div>
               {if empty($theme.parents) && ($theme.imageblock==1) &&  ($child.canContainChildren) && ($theme.params.AlbumBlock)}
                 {assign var=itemId value=$child.id}
                 {g->callback type="imageblock.LoadImageBlock"
	                blocks=$blocks|default:null 
	         itemId=$itemId|default:null }
                 {if !empty($ImageBlockData)}
                                <div class="{$class}">
                                  {g->theme include="imageblockPG.tpl"}
                                </div>
                 {/if}
               {else}
               {if isset($theme.params.$frameType) && isset($child.thumbnail)}
                                     {g->container type="imageframe.ImageFrame" frame=$theme.params.$frameType}

	                      <a onmouseover="pic{$nome}.className='%CLASS% giThumbnail opacity{$Tover}'" onmouseout="pic{$nome}.className='%CLASS% giThumbnail opacity{$Toff}'" 
href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$child.id`"}">
		        {g->image id="%ID%" item=$child image=$child.thumbnail class="%CLASS% giThumbnail opacity`$Toff`" name="pic$nome"}</a>
                                    {/g->container}


               {elseif isset($child.thumbnail)}
                                     <a onmouseover="pic{$nome}.className='%CLASS% giThumbnail opacity{$Tover}'" onmouseout="pic{$nome}.className='%CLASS% giThumbnail opacity{$Toff}'"
href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$child.id`"}">
		      {g->image item=$child image=$child.thumbnail class="giThumbnail opacity`$Toff`" name="pic$nome"}</a>
               {else}
		    <a href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$child.id`"}" class="giMissingThumbnail">
		      {g->text text="no thumbnail"}</a>
               {/if}
               {/if}


               {if !empty($theme.params.BtnAfter)}
               {if $theme.params.ItemsTitle}
                 {if !empty($child.title)}
                                    <p class="giTitle">
                   {if $child.canContainChildren}
                                        {g->text text="&#9642; %s &#9642;" arg1=$child.title|markup}
                   {else}
                                        {$child.title|markup}
                   {/if}
                                    </p>
                 {/if}
               {/if}
               {if !empty ($theme.params.ItemsDesc)}
                 {if !empty($child.summary)}
                                    <p class="giDescription">
                                      {$child.summary|markup|entitytruncate:256}
                                    </p>
                 {/if}
               {/if}
               {if ($theme.item.canContainChildren && $theme.params.showAlbumOwner) ||
                    (!$theme.item.canContainChildren && $theme.params.showImageOwner)}
                                  {assign var="showOwner" value=true}
               {else}
                                  {assign var="showOwner" value=false}
               {/if}
               {if $theme.params.ItemsInfo}
                                  {g->block type="core.ItemInfo"
                                    item=$child
                                    showDate=true
			            showOwner=$showOwner
                                    showSize=true
       		                    showViewCount=true
                                    showSummaries=true
                                   class="giInfo"}
                              <table><tr><td style="height:2px"></td></tr></table>
               {/if}

               {/if}

                                </div>

                              <div class="actions">

               {if $theme.params.ItemsActions}
                 {if ($user.isAdmin) && ($theme.guestPreviewMode !=1)}
                                    {g->block type="core.ItemLinks" item=$child links=$child.itemLinks}
                 {/if}
               {/if}

{***********************}

                                  <table border="0" class="tableacpic" style="height:25px">
                                    <tr>
                                      <td>

               {if ($theme.cart ==1) && !empty ($theme.params.ItemsActions)}
                 {if !empty($theme.params.colorpack)}
                                        <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif'"
onmouseover= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_carton.gif'"
                   {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                   {else}
title="Add to Cart"
                   {/if}
>
                                          <img id="cart{$child.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                   {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                   {else}
title="Add to Cart"
                   {/if}
class="navtoppic" /></a>
                                      </td>
                   {if !empty ($theme.params.ActionsText)}
                                      <td>
<a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_cart.gif'"
onmouseover= "cart{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/add_carton.gif'"
                   {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                   {else}
title="Add to Cart"
                   {/if}
>
                     {if !empty ($theme.params.AddCartText)}
                                            {g->text text=$theme.params.AddCartText}
                     {else}
                                            {g->text text='Add to Cart'}
                     {/if}
                                          </a>
                                      </td>
                                    </tr><tr>
                   {/if}
                 {else}
                                        <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" onmouseout="cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_cart.gif'" onmouseover="cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_carton.gif'"
                   {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                   {else}
title="Add to Cart"
                   {/if}
>
                                          <img id="cart{$child.id}" src="{g->url href='themes/PGtheme/images/'}add_cart.gif" alt="Add to Cart" longdesc="Add to Cart" 
                   {if !empty ($theme.params.AddCartText)}
title="{g->text text=$theme.params.AddCartText}"
                   {else}
title="Add to Cart"
                   {/if}
class="navtoppic"/></a>
                                      </td>
                   {if !empty ($theme.params.ActionsText)}
                                      <td>
                                        <a href="{g->url arg1="controller=cart.AddToCart" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_cart.gif'"
onmouseover= "cart{$child.id}.src='{g->url href='themes/PGtheme/images/'}add_carton.gif'">
                     {if !empty ($theme.params.AddCartText)}
                                            {g->text text=$theme.params.AddCartText}
                     {else}
                                            {g->text text='Add to Cart'}
                     {/if}
                                          </a>
                                      </td>
                                    </tr><tr>
                   {/if}
                 {/if}
               {/if} {* !empty ($theme.params.ItemsActions) end *}

                                     
               {if ($theme.comments ==1) && !empty ($theme.params.ItemsComments) && isset ($theme.permissions.comment_add)}

               {if !empty ($theme.params.ItemsActions)}
                                      <td>
               {/if} 

                 {if !empty ($theme.params.colorpack)}
                                        <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcommentson.gif' "
                   {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                   {else}
title="Add a Comment"
                   {/if}
>
                                          <img id="addcom{$child.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                   {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                   {else}
title="Add a Comment"
                   {/if}
class="navtoppic" /></a>
                                      </td>
                   {if !empty ($theme.params.ActionsText)}
                                      <td>
                                        <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/addcommentson.gif' "
                   {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                   {else}
title="Add a Comment"
                   {/if}
>
                     {if isset($theme.params.CommentsText)}
                                          {g->text text=$theme.params.CommentsText}
                     {else}
                                          {g->text text='Add a Comment'}
                     {/if}
                                        </a>
                                      </td>
                                    </tr><tr>
                   {/if}
                 {else}
                                        <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcommentson.gif'"
                   {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                   {else}
title="Add a Comment"
                   {/if}
>
                                          <img id="addcom{$child.id}" src="{g->url href='themes/PGtheme/images/'}addcomments.gif" alt="Add a Comment" longdesc="Add a Comment" 
                   {if !empty ($theme.params.CommentsText)}
title="{g->text text=$theme.params.CommentsText}"
                   {else}
title="Add a Comment"
                   {/if}
class="navtoppic"/></a>
                                      </td>
                   {if !empty ($theme.params.ActionsText)}
                                      <td>
                                        <a href="{g->url arg1="view=comment:AddComment" arg2="itemId=`$child.id`" arg3="return=`$theme.item.id`"}" 
onmouseout= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcomments.gif'"
onmouseover= "addcom{$child.id}.src='{g->url href='themes/PGtheme/images/'}addcommentson.gif' ">
                     {if isset($theme.params.CommentsText)}
                                          {g->text text=$theme.params.CommentsText}
                     {else}
                                          {g->text text='Add a Comment'}
                     {/if}
                                        </a>
                                      </td>
                                    </tr><tr>
                   {/if}
                 {/if}
               {/if}

               {if ($theme.comments ==1) && !empty ($theme.params.ItemsComments) && isset ($theme.permissions.comment_view)}
                                      <td>
                 {if !empty($theme.params.colorpack)}
                                        {g->callback type="comment.LoadComments" itemId=$child.id show=$show}
                   {if empty($item)} {assign var=item value=$child} {/if}
                   {if !empty($block.comment.LoadComments.comments)}
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif'" onclick="ShowLayer('blockIC{$child.id}','visible')"
onmouseover= "blockICpic{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/commentson.gif';ShowLayer('blockIC{$child.id}','hidden') "
                       {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                       {else}
title="View Comments"
                       {/if}
>
                                          <img id="blockICpic{$child.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif" alt="Viewo Comments" longdesc="View Comments" 
                       {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                       {else}
title="View Comments"
                       {/if}
class="navtoppic" /></a>
                   {/if}
                   {if !empty ($theme.params.ActionsText)}
                                      </td><td>
{g->callback type="comment.LoadComments" itemId=$child.id show=$show}
                     {if empty($item)} {assign var=item value=$child} {/if}
                     {if !empty($block.comment.LoadComments.comments)}
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/comments.gif'" onclick="ShowLayer('blockIC{$child.id}','visible')"
onmouseover= "blockICpic{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/commentson.gif';ShowLayer('blockIC{$child.id}','hidden') "
                       {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                       {else}
title="View Comments"
                       {/if}
>
                       {if !empty ($theme.params.ViewCommentsText)}
                                          {g->text text=$theme.params.ViewCommentsText}
                       {else}
                                          {g->text text='View Comments'}
                       {/if}
                                          </a>

                                      </td>
                                    </tr><tr>
                     {/if}
                   {/if}
                 {else}
                   {if empty($item)} {assign var=item value=$child} {/if}
                                        {g->callback type="comment.LoadComments" itemId=$child.id show=$show}
                     {if !empty($block.comment.LoadComments.comments)}
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic{$child.id}.src='{g->url href='themes/PGtheme/images/'}comments.gif'" onclick="ShowLayer('blockIC{$child.id}','visible')"
onmouseover= "blockICpic{$child.id}.src='{g->url href='themes/PGtheme/images/'}commentson.gif';ShowLayer('blockIC{$child.id}','hidden') "
                       {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                       {else}
title="View Comments"
                       {/if}
>
                                          <img id="blockICpic{$child.id}" src="{g->url href='themes/PGtheme/images/'}comments.gif" alt="View Comments" longdesc="View Comments"  
                       {if !empty ($theme.params.ViewCommentsText)}
title="{g->text text=$theme.params.ViewCommentsText}"
                       {else}
title="View Comments"
                       {/if}
class="navtoppic"/></a>
                     {/if}
                     {if !empty ($theme.params.ActionsText)}
                                      </td><td>
                       {if empty($item)} {assign var=item value=$child} {/if}
                                      {g->callback type="comment.LoadComments" itemId=$child.id show=$show}
                       {if !empty($block.comment.LoadComments.comments)}
                                        <a href="javascript:void(0)"
onmouseout= "blockICpic{$child.id}.src='{g->url href='themes/PGtheme/images/'}comments.gif'" onclick="ShowLayer('blockIC{$child.id}','visible')"
onmouseover= "blockICpic{$child.id}.src='{g->url href='themes/PGtheme/images/'}commentson.gif';ShowLayer('blockIC{$child.id}','hidden') ">

                       {if !empty ($theme.params.ViewCommentsText)}
                                          {g->text text=$theme.params.ViewCommentsText}
                       {else}
                                          {g->text text='View Comments'}
                       {/if}
                                          </a>


                       {/if}
                     {/if}
                   {/if}








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






                                      </td>
                       {if !empty ($theme.params.ActionsText)}
                                    </tr><tr>
                       {/if}

                 {/if} {* comments view *}

                 {if !empty ($theme.params.ItemsExif) && ($theme.exif ==1)}

                   {if !empty ($theme.params.ItemsActions) || (($theme.comments ==1) && !empty ($theme.params.ItemsComments) && (isset ($theme.permissions.comment_view) || isset ($theme.permissions.comment_add)))}
                                      <td>
 
                   {/if} 

                   {if empty($item)} {assign var=item value=$child} {/if}
                                  {g->callback type="exif.LoadExifInfo" itemId=$child.id}
                   {if !empty($block.exif.LoadExifInfo.exifData)}
                     {if !empty ($theme.params.colorpack)}
                                        <a href="javascript:void(0)"
onmouseout= "exifICpic{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/exif.gif'" onclick="ShowLayer('exifIC{$child.id}','visible')"
onmouseover= "exifICpic{$child.id}.src='{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/exifon.gif';ShowLayer('exifIC{$child.id}','hidden')" 
{if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
                       {else}
title="Show Photo EXIF"
                       {/if}
> 
                                          <img id="exifICpic{$child.id}" src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/exif.gif" alt="EXIF" longdesc="Show Photo EXIF" 
                       {if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
                       {else}
title="Show Photo EXIF"
                       {/if}
class="navtoppic"/></a>
                     {else}
                                        <a href="javascript:void(0)"
onmouseout= "exifICpic{$child.id}.src='{g->url href='themes/PGtheme/images/'}exif.gif'" onclick="ShowLayer('exifIC{$child.id}','visible')"
onmouseover= "exifICpic{$child.id}.src='{g->url 
href='themes/PGtheme/images/'}exifon.gif';ShowLayer('exifIC{$child.id}','hidden')" 
{if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
                       {else}
title="Show Photo EXIF"
                       {/if}
> 
                                          <img id="exifICpic{$child.id}" src="{g->url href='themes/PGtheme/images/'}exif.gif" alt="EXIF" longdesc="Show Photo EXIF" 
                       {if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
                       {else}
title="Show Photo EXIF"
                       {/if}
class="navtoppic"/></a>
                     {if !empty ($theme.params.ActionsText)}
                                      </td><td>
                                        <a href="javascript:void(0)"
onmouseout= "exifICpic{$child.id}.src='{g->url href='themes/PGtheme/images/'}exif.gif'" onclick="ShowLayer('exifIC{$child.id}','visible')"
onmouseover= "exifICpic{$child.id}.src='{g->url href='themes/PGtheme/images/'}exifon.gif';ShowLayer('exifIC{$child.id}','hidden') "
{if !empty ($theme.params.ExifText)}
title="{g->text text=$theme.params.ExifText}"
                       {else}
title="Show Photo EXIF"
                       {/if}
> 
                       {if !empty ($theme.params.ExifText)}
                                          {g->text text=$theme.params.ExifText}
                       {else}
                                          {g->text text='View Exif'}
                       {/if}
                                          </a>
                     {/if}
                     {/if}

                                        <div id="exifIC{$child.id}" style="position: absolute; left:{$theme.params.sidebarL+50}px; top: {$theme.params.sidebarT+100}px; width:500px; text-align:left;
z-index: 10; visibility: hidden;" onmousedown="dragStart(event, 'exifIC{$child.id}')" class="BlockOpacity">
                                          <div id="exifICIn{$child.id}" style="position: relative; left: 0px; top: 0px;  
z-index: 10;" class="gcBackground1 gcBorder2">
                                            <div style="text-align:right">
                                              <h2>"{$child.title|markup}"&nbsp;EXIF</h2>
                                            </div>
                                          {g->block type="exif.ExifInfo" item=$child }
                                            <div style="text-align: right; padding:4px">
                     {if empty($theme.params.colorpack)}
                                              <a onclick="MM_showHideLayers('exifIC{$child.id}','','hide')" onmouseover="MM_showHideLayers('exifIC{$child.id}','','hide')" title="Close">
                                                <img src="{g->url href='themes/PGtheme/images/'}close.gif" alt="close"/></a>
                     {else}
                                              <a onclick="MM_showHideLayers('exifIC{$child.id}','','hide')" onmouseover="MM_showHideLayers('exifIC{$child.id}','','hide')" title="Close">
                                                <img src="{g->url href='modules/colorpack/packs/'}{$theme.params.colorpack}/images/close.gif" alt="close"/></a>
                     {/if}
                                            </div>
                                          </div>
                                          <br/>
                                        </div>

                   {/if}
                                      </td>
                 {/if}
                                    </tr>
                                  </table>
                                </div> {* comments end *}

               {if empty($theme.params.BtnAfter)}
               {if $theme.params.ItemsTitle}
                 {if !empty($child.title)}
                                    <p class="giTitle">
                   {if $child.canContainChildren}
                                        {g->text text="&#9642; %s &#9642;" arg1=$child.title|markup}
                   {else}
                                        {$child.title|markup}
                   {/if}
                                    </p>
                 {/if}
               {/if}
               {if !empty ($theme.params.ItemsDesc)}
                 {if !empty($child.summary)}
                                    <p class="giDescription">
                                      {$child.summary|markup|entitytruncate:256}
                                    </p>
                 {/if}
               {/if}
               {if ($theme.item.canContainChildren && $theme.params.showAlbumOwner) ||
                    (!$theme.item.canContainChildren && $theme.params.showImageOwner)}
                                  {assign var="showOwner" value=true}
               {else}
                                  {assign var="showOwner" value=false}
               {/if}
               {if $theme.params.ItemsInfo}
                                  {g->block type="core.ItemInfo"
                                    item=$child
                                    showDate=true
			            showOwner=$showOwner
                                    showSize=true
       		                    showViewCount=true
                                    showSummaries=true
                                   class="giInfo"}
               {/if}
               {/if}

                              </td>
                              {/foreach}
                 {if empty ($theme.params.ItemsCenter)}
{* flush the rest of the row with empty cells *}
                                {section name="flush" start=$childrenInColumnCount loop=$theme.params.columns}
                                  <td>&nbsp;</td>
                                {/section}
                 {/if}
                            </tr>
                          </table>
                        </div>
                      </td>
                    </tr>
                 {if $theme.params.NavAlbumBottom}
                   {if !empty($theme.navigator)}
                     {if !empty($theme.jumpRange)}
                    <tr><td></td><td>
                      <div class="gcBackground1 gbNavigator">
                       {g->theme include="navigatorAlbumBottom.tpl"}
                      </div>
                    </td></tr> 
                     {/if}
                   {/if}
                 {/if}
                  </table>
               {/if}

                  <table><tr><td style="height:7px"></td></tr></table>
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>            
    </table>


{if !empty ($theme.params.albumBlocks)}

{* Show any other album blocks (comments, exif etc) *}
<table border="0" width="98%"><tr><td>

          <table width="400" align="{if !empty ($theme.params.BlocksAlign)}{$theme.params.BlocksAlign}{else}center{/if}">
            <tr><td></td></tr>

              {foreach from=$theme.params.albumBlocks item=block}
            <tr>
              <td>
                <table align="{if !empty ($theme.params.BlocksInAlign)}{$theme.params.BlocksInAlign}{else}center{/if}">
                  <tr>
                    <td>
{assign var=item value=$theme.item} 

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
        {g->block type="core.GuestPreview" class="gbBlockBottom"}

        {* Our emergency edit link, if the user all blocks containing edit links *}
	{g->block type="core.EmergencyEditItemLink" class="gbBlockBottom"
                  checkSidebarBlocks=true
                  checkAlbumBlocks=true}

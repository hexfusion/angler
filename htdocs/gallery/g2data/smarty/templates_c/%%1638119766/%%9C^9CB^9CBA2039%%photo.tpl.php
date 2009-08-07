<?php /* Smarty version 2.6.10, created on 2006-03-08 18:21:33
         compiled from gallery:themes/PGtheme/templates/photo.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'markup', 'gallery:themes/PGtheme/templates/photo.tpl', 39, false),)), $this); ?>
<?php if (! empty ( $this->_tpl_vars['theme']['imageViews'] )): ?>
  <?php $this->assign('image', $this->_tpl_vars['theme']['imageViews'][$this->_tpl_vars['theme']['imageViewsIndex']]); ?>
  <?php $this->assign('imagewidth', $this->_tpl_vars['theme']['imageViews'][$this->_tpl_vars['theme']['imageViewsIndex']]['width']); ?>
  <?php $this->assign('imageheight', $this->_tpl_vars['theme']['imageViews'][$this->_tpl_vars['theme']['imageViewsIndex']]['height']); ?>
<?php endif; ?>

<?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
<?php echo '
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
function fsizeopen(url,title)
{
css = '; ?>
"<link rel='stylesheet' type='text/css' href='<?php echo $this->_reg_objects['g'][0]->theme(array('url' => 'theme.css'), $this);?>
'/>"

<?php else: ?>
<?php echo '
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
function fsizeopen(url,title)
{
css = '; ?>
"<link rel='stylesheet' type='text/css' href='<?php echo $this->_reg_objects['g'][0]->theme(array('url' => 'theme.css'), $this);?>
'/><link rel='stylesheet' type='text/css' href='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/color.css'/>"

<?php endif; ?>
<?php echo '
wsource = ';  echo $this->_tpl_vars['theme']['sourceImage']['width'];  echo '
witem = ';  echo $this->_tpl_vars['theme']['item']['width'];  echo '
w = screen.width;
h = screen.height;
title = '; ?>
"<?php echo $this->_tpl_vars['theme']['params']['site']; ?>
-<?php echo $this->_tpl_vars['theme']['item']['title']; ?>
"<?php echo '
title1 = '; ?>
"<?php echo $this->_tpl_vars['theme']['item']['title']; ?>
"<?php echo '
desc = '; ?>
"<?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
"<?php echo '
separ = '; ?>
"<?php echo $this->_tpl_vars['theme']['params']['MenuSeparator']; ?>
"<?php echo '


 var win = window.open(url,
  \'popup\',
  \'width=\' + w + \', height=\' + h + \', \' +
  \'location=no, menubar=no, \' +
  \'status=no, toolbar=no, scrollbars=yes, resizable=no\');
 win.moveTo(0,0);
 win.resizeTo(w, h);
 win.focus();
win.document.write("<html><head><title>");
win.document.write(title);
win.document.write("</title>");
win.document.write(css);
win.document.write("</head><body class=\'gallery\' style=\'margin:0;padding:0\'>");
win.document.write("<div id=\'gallery\' style=\'margin:0;padding:0\'>");
win.document.write("<center>");
win.document.write("<div class=\'giTitle\'><h3>");
win.document.write(title1);
win.document.write("</h3></div>");
win.document.write("<div class=\'giDescription\'>");
win.document.write(desc);
win.document.write("</div>");
win.document.write("<br/>");
if (witem > w) {
win.document.write("<div style=\'position: absolute; top:10; left:10\'><a href=\'javascript:void(0)\' onclick=\'photo.width=");
win.document.write(witem);
win.document.write("\'>Full Size</a>");
win.document.write("&nbsp;");
win.document.write(separ);
win.document.write("&nbsp;");
win.document.write("<a href=\'javascript:void(0)\' onclick=\'photo.width=");
win.document.write(w-30);
win.document.write("\'>Screen Size</a> image</div>");
;}
win.document.write("<img id=\'photo\' src=\'");
win.document.write(url);
win.document.write("\' width=\'");
if (witem > w) {win.document.write(w-30);}else{win.document.write(witem);}
win.document.write("\'usemap=\'#fotomap\'/>");
win.document.write("</center>");
win.document.write("</div></body></html>");

win.document.close();
}
          //-->
          //]]>
          </script>
        '; ?>


<table width="100%">
  <tr>
    <td valign="top">
      <?php if ($this->_tpl_vars['theme']['params']['sidebar'] && ! empty ( $this->_tpl_vars['theme']['params']['sidebarBlocks'] )): ?>
        <table cellspacing="0" cellpadding="0" align="left">
          <tr valign="top">
            <td id="gsSidebarCol">
              <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "sidebar.tpl"), $this);?>

            </td>
          </tr>
        </table>
    </td>
    <td valign="top">
      <?php endif; ?>

      <table width="92%" cellspacing="0" cellpadding="0" class="gcBackground1 gcBorder2" align="center">
        <tr valign="top">
          <td>
            <div id="gsContent" class="gcBackground1 gcBorder1">


              <div class="gbBlock gcBackground1">
                <table width="100%" border="0">
                  <tr>
                    <td style="width: 65%" valign="top">
                      <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoTitle'] ) && ! empty ( $this->_tpl_vars['theme']['params']['PhotoTitleTop'] )): ?>
                        <?php if (! empty ( $this->_tpl_vars['theme']['item']['title'] )): ?>
                          <h2> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </h2>
                        <?php endif; ?>
                      <?php endif; ?>
                      <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoDescription'] ) && ! empty ( $this->_tpl_vars['theme']['params']['PhotoDescriptionTop'] )): ?>
                        <?php if (! empty ( $this->_tpl_vars['theme']['item']['description'] )): ?>
                          <p class="giDescription">
                            <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                          </p>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                    <td style="width:5px"></td>
                    <td style="text-align:left" valign="top">
                      <?php if (! empty ( $this->_tpl_vars['theme']['params']['InfoPhoto'] ) && ! empty ( $this->_tpl_vars['theme']['params']['InfoPhotoTop'] )): ?>
                        <?php if (! empty ( $this->_tpl_vars['theme']['params']['showImageOwner'] )): ?>
                          <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showOwner' => true,'class' => 'giInfo'), $this);?>

                        <?php endif; ?>
                          <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showDate' => true,'showViewCount' => true,'class' => 'giInfo'), $this);?>

                        <?php if ($this->_tpl_vars['theme']['params']['InfoSize']): ?>
                          <?php if (! empty ( $this->_tpl_vars['theme']['params']['PopSize'] )): ?>
                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoSizes.tpl",'class' => 'giInfo'), $this);?>

                          <?php else: ?>
                            <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.PhotoSizes",'class' => 'giInfo'), $this);?>

                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                    <td style="text-align:right" valign="top">
                        <?php if (! empty ( $this->_tpl_vars['theme']['navigator'] )): ?>


                         <?php $this->assign('photoItem', '1'); ?>
                          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorTop.tpl"), $this);?>

                        <?php endif; ?>
                    </td>
                  </tr>
                </table>
              </div>


              <?php if ($this->_tpl_vars['theme']['params']['MTposition']): ?>
              <table id="photo" align="center" border="0" width="98%">
                <tr>
                  <td style="text-align:center" width="75%" valign="top">
                      <?php if (! empty ( $this->_tpl_vars['theme']['navigator'] ) && ! empty ( $this->_tpl_vars['theme']['params']['NavPhotoTop'] )): ?>
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center" width="100%">
                            <tr>
                              <td>
                                <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorPhotoTop.tpl"), $this);?>

                              </td>
                            </tr>
                          </table>
                        </div>
                      <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align:top">
                    <div id="gsImageView" class="gbBlock">
                      <?php if (! empty ( $this->_tpl_vars['theme']['imageViews'] )): ?>
                        <?php ob_start(); ?>
	          <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
">
	            <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Download %s",'arg1' => $this->_tpl_vars['theme']['sourceImage']['itemTypeName']['1']), $this);?>

	          </a>
	        <?php $this->_smarty_vars['capture']['fallback'] = ob_get_contents(); ob_end_clean(); ?>
                          <table border="0" align="center">
                            <tr>
                              <td>
	                <?php if (( $this->_tpl_vars['image']['viewInline'] )): ?>
	                  <?php if (isset ( $this->_tpl_vars['theme']['photoFrame'] )): ?>
		    <?php $this->_tag_stack[] = array('container', array('type' => "imageframe.ImageFrame",'frame' => $this->_tpl_vars['theme']['photoFrame']), $this); $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
                                      <?php if ($this->_tpl_vars['theme']['params']['imageFadin']): ?> 
                                        <div class="gsSingleImage" id="gsSingleImageId" style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
 height:<?php echo $this->_tpl_vars['imageheight']; ?>
">
                                          <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] ) || ! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                          <?php else: ?>
		            <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'id' => 'foto','longdesc' => "%ID%"), $this);?>

                                          <?php endif; ?>
                                        </div>
                                      <?php else: ?>
                                        <div class="gsSingleImageNoF" style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
 height:<?php echo $this->_tpl_vars['imageheight']; ?>
">
                                          <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] ) || ! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                          <?php else: ?>
		            <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'id' => 'foto','longdesc' => "%ID%"), $this);?>

                                          <?php endif; ?>
                                        </div> 
                                      <?php endif; ?>
  		    <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>

	                  <?php else: ?>
                                      <?php if ($this->_tpl_vars['theme']['params']['imageFadin']): ?> 
                                        <div class="gsSingleImage" id="gsSingleImageId" style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
 height:<?php echo $this->_tpl_vars['imageheight']; ?>
">
                                          <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] ) || ! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                          <?php else: ?>
		            <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'id' => 'foto','longdesc' => "%ID%",'style' => "border:0"), $this);?>

                                          <?php endif; ?>
                                        </div>
                                      <?php else: ?>
                                        <div class="gsSingleImageNoF" style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
 height:<?php echo $this->_tpl_vars['imageheight']; ?>
">
                                          <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] ) || ! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                          <?php else: ?>
		            <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'id' => 'foto','longdesc' => "%ID%"), $this);?>

                                          <?php endif; ?>
                                        </div> 
                                      <?php endif; ?>
	                  <?php endif; ?>
	                <?php else: ?>
	                  <?php echo $this->_smarty_vars['capture']['fallback']; ?>

	                <?php endif; ?>
                            </td>
                          </tr>
                        </table>
                      <?php else: ?>
                        <?php echo $this->_reg_objects['g'][0]->text(array('text' => "There is nothing to view for this item."), $this);?>

                      <?php endif; ?>
                    </div>
                    <?php if (! empty ( $this->_tpl_vars['theme']['sourceImage'] ) && $this->_tpl_vars['theme']['sourceImage']['mimeType'] != $this->_tpl_vars['theme']['item']['mimeType']): ?>
                      <div class="gbBlock">
                        <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
">
                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Download %s in original format",'arg1' => $this->_tpl_vars['theme']['sourceImage']['itemTypeName']['1']), $this);?>

                        </a>
                      </div>
                    <?php endif; ?>
                  </td>

                <?php if (! empty ( $this->_tpl_vars['theme']['params']['showMicroThumbs'] ) || ( ! empty ( $this->_tpl_vars['theme']['params']['PhotoTitle'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoTitleTop'] ) ) || ( ! empty ( $this->_tpl_vars['theme']['params']['PhotoDescription'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoDescriptionTop'] ) ) || ( ! empty ( $this->_tpl_vars['theme']['params']['InfoPhoto'] ) && empty ( $this->_tpl_vars['theme']['params']['InfoPhotoTop'] ) )): ?>
                  <td style="text-align:center" valign="top">
                      <table align="center">
                    <?php if ($this->_tpl_vars['theme']['params']['showMicroThumbs']): ?>
                        <tr>
                          <td style="text-align:center" valign="top">
                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorThumbs.tpl"), $this);?>

                          </td>
                        </tr>
                    <?php endif; ?>
                        <tr>
                          <td style="text-align:left" valign="top">
                            <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoTitle'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoTitleTop'] )): ?>
                              <?php if (! empty ( $this->_tpl_vars['theme']['item']['title'] )): ?>
                                <br/>
                                <div class="descSeparator"></div>
                                    <h2> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </h2>
                              <?php endif; ?>
                            <?php endif; ?>
                            <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoDescription'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoDescriptionTop'] )): ?>
                              <?php if (! empty ( $this->_tpl_vars['theme']['item']['description'] )): ?>
                                  <p class="giDescription">
                                    <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                                  </p>
                                <div class="descSeparator"></div>
                              <?php endif; ?>
                            <?php endif; ?>
                            <?php if (! empty ( $this->_tpl_vars['theme']['params']['InfoPhoto'] ) && empty ( $this->_tpl_vars['theme']['params']['InfoPhotoTop'] )): ?>
                              <?php if (! empty ( $this->_tpl_vars['theme']['params']['showImageOwner'] )): ?>
                                 <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showOwner' => true,'class' => 'giInfo'), $this);?>

                              <?php endif; ?>
                                  <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showDate' => true,'showViewCount' => true,'class' => 'giInfo'), $this);?>

                              <?php if ($this->_tpl_vars['theme']['params']['InfoSize']): ?>
                                <?php if (! empty ( $this->_tpl_vars['theme']['params']['PopSize'] )): ?>
                                  <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoSizes.tpl",'class' => 'giInfo'), $this);?>

                                <?php else: ?>
                                  <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.PhotoSizes",'class' => 'giInfo'), $this);?>

                                <?php endif; ?>
                              <?php endif; ?>
                                <div class="descSeparator"></div>
                            <?php endif; ?>
                          </td>
                        </tr>
                      </table>
                  </td>
                <?php endif; ?>
                </tr>
                <tr>
                  <td style="text-align:center">
                      <?php if (! empty ( $this->_tpl_vars['theme']['navigator'] ) && ! empty ( $this->_tpl_vars['theme']['params']['NavPhotoBottom'] )): ?>
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center" width="100%">
                            <tr>
                              <td>
                                <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorPhotoBottom.tpl"), $this);?>

                              </td>
                            </tr>
                          </table>
                        </div>
                      <?php endif; ?>

                  </td>
                </tr>
              </table>
 

              <?php else: ?>
                <table id="photo" align="center" border="0" width="98%">
                  <tr>
                    <td>
                    </td>
                    <td style="text-align:center" width="75%" valign="top">
                      <?php if (! empty ( $this->_tpl_vars['theme']['navigator'] ) && ! empty ( $this->_tpl_vars['theme']['params']['NavPhotoTop'] )): ?>
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center" width="100%">
                            <tr>
                              <td>
                                <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorPhotoTop.tpl"), $this);?>

                              </td>
                            </tr>
                          </table>
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                  <?php if (! empty ( $this->_tpl_vars['theme']['params']['showMicroThumbs'] ) || ( ! empty ( $this->_tpl_vars['theme']['params']['PhotoTitle'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoTitleTop'] ) ) || ( ! empty ( $this->_tpl_vars['theme']['params']['PhotoDescription'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoDescriptionTop'] ) ) || ( ! empty ( $this->_tpl_vars['theme']['params']['InfoPhoto'] ) && empty ( $this->_tpl_vars['theme']['params']['InfoPhotoTop'] ) )): ?>
                    <td style="text-align:center" valign="top">
                      <table align="center">
                        <?php if ($this->_tpl_vars['theme']['params']['showMicroThumbs']): ?>
                        <tr>
                          <td style="text-align:center" valign="top">
                            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorThumbs.tpl"), $this);?>

                          </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                          <td style="text-align:left" valign="top">
                            <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoTitle'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoTitleTop'] )): ?>
                              <?php if (! empty ( $this->_tpl_vars['theme']['item']['title'] )): ?>
                                <br/>
                                <div class="descSeparator"></div>
                                <h2> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </h2>
                              <?php endif; ?>
                            <?php endif; ?>
                            <?php if (! empty ( $this->_tpl_vars['theme']['params']['PhotoDescription'] ) && empty ( $this->_tpl_vars['theme']['params']['PhotoDescriptionTop'] )): ?>
                              <?php if (! empty ( $this->_tpl_vars['theme']['item']['description'] )): ?>
                                <p class="giDescription">
                                  <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

                                </p>
                                <div class="descSeparator"></div>
                              <?php endif; ?>
                            <?php endif; ?>
                            <?php if (! empty ( $this->_tpl_vars['theme']['params']['InfoPhoto'] ) && empty ( $this->_tpl_vars['theme']['params']['InfoPhotoTop'] )): ?>
                              <?php if (! empty ( $this->_tpl_vars['theme']['params']['showImageOwner'] )): ?>
                                <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showOwner' => true,'class' => 'giInfo'), $this);?>

                              <?php endif; ?>
                                <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.ItemInfo",'item' => $this->_tpl_vars['theme']['item'],'showDate' => true,'showViewCount' => true,'class' => 'giInfo'), $this);?>

                              <?php if ($this->_tpl_vars['theme']['params']['InfoSize']): ?>
                                <?php if (! empty ( $this->_tpl_vars['theme']['params']['PopSize'] )): ?>
                                  <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoSizes.tpl",'class' => 'giInfo'), $this);?>

                                <?php else: ?>
                                  <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.PhotoSizes",'class' => 'giInfo'), $this);?>

                                <?php endif; ?>
                              <?php endif; ?>
                              <div class="descSeparator"></div>
                            <?php endif; ?>
                          </td>
                        </tr>
                      </table>
                    </td>
                  <?php endif; ?>
                    <td style="vertical-align:top">
                      <div id="gsImageView" class="gbBlock">
                        <?php if (! empty ( $this->_tpl_vars['theme']['imageViews'] )): ?>
	          <?php ob_start(); ?>
	            <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
">
	             <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Download %s",'arg1' => $this->_tpl_vars['theme']['sourceImage']['itemTypeName']['1']), $this);?>

	            </a>
	          <?php $this->_smarty_vars['capture']['fallback'] = ob_get_contents(); ob_end_clean(); ?>
                          <table border="0" align="center" style="vertical-align:top">
                            <tr>
                              <td>
	                <?php if (( $this->_tpl_vars['image']['viewInline'] )): ?>
	                  <?php if (isset ( $this->_tpl_vars['theme']['photoFrame'] )): ?>
		            <?php $this->_tag_stack[] = array('container', array('type' => "imageframe.ImageFrame",'frame' => $this->_tpl_vars['theme']['photoFrame']), $this); $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
                              <?php if ($this->_tpl_vars['theme']['params']['imageFadin']): ?> 
                                <div class="gsSingleImage" id="gsSingleImageId" style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
px; height:<?php echo $this->_tpl_vars['imageheight']; ?>
px;">
                                  <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] ) || ! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                    <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                  <?php else: ?>
		                    <?php echo $this->_reg_objects['g'][0]->image(array('id' => "%ID%",'item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'longdesc' => "%ID%"), $this);?>

                                  <?php endif; ?>
                                </div>
                              <?php else: ?>
                                <div class="gsSingleImageNoF" style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
px; height:<?php echo $this->_tpl_vars['imageheight']; ?>
px;">
                                  <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] ) || ! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                    <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                  <?php else: ?>
		                    <?php echo $this->_reg_objects['g'][0]->image(array('id' => "%ID%",'item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'longdesc' => "%ID%"), $this);?>

                                  <?php endif; ?>
                                </div> 
                              <?php endif; ?>
  		            <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>

	                  <?php else: ?>
                            <?php if ($this->_tpl_vars['theme']['params']['imageFadin']): ?> 
                              <div class="gsSingleImage" id="gsSingleImageId" style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
px; height:<?php echo $this->_tpl_vars['imageheight']; ?>
px;">
                                <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] ) || ! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                  <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                <?php else: ?>
		                  <?php echo $this->_reg_objects['g'][0]->image(array('id' => "%ID%",'item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'longdesc' => "%ID%"), $this);?>

                                <?php endif; ?>
                              </div>
                            <?php else: ?>

                              <div style="width:<?php echo $this->_tpl_vars['imagewidth']; ?>
px; height:<?php echo $this->_tpl_vars['imageheight']; ?>
px;">
                                <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] )): ?>
                                  <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                <?php else: ?>

                                  <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?>
                                    <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photoNavOver.tpl"), $this);?>

                                  <?php else: ?>
		                    <?php echo $this->_reg_objects['g'][0]->image(array('id' => "%ID%",'item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'longdesc' => "%ID%"), $this);?>

                                  <?php endif; ?>
                                <?php endif; ?>
                              </div> 

                            <?php endif; ?>
	                  <?php endif; ?>
	                <?php else: ?>
	                  <?php echo $this->_smarty_vars['capture']['fallback']; ?>

	                <?php endif; ?>
                              </td>
                            </tr>
                          </table>
                        <?php else: ?>
                          <?php echo $this->_reg_objects['g'][0]->text(array('text' => "There is nothing to view for this item."), $this);?>

                        <?php endif; ?>
                      </div>
                      <?php if (! empty ( $this->_tpl_vars['theme']['sourceImage'] ) && $this->_tpl_vars['theme']['sourceImage']['mimeType'] != $this->_tpl_vars['theme']['item']['mimeType']): ?>
                        <div class="gbBlock">
                          <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
">
                            <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Download %s in original format",'arg1' => $this->_tpl_vars['theme']['sourceImage']['itemTypeName']['1']), $this);?>

                          </a>
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                    </td>
                    <td style="text-align:center">
                      <?php if (! empty ( $this->_tpl_vars['theme']['navigator'] ) && ! empty ( $this->_tpl_vars['theme']['params']['NavPhotoBottom'] )): ?>
                        <div class="gbBlock gcBackground1 gbNavigator">
                          <table border="0" align="center"  width="100%">
                            <tr>
                              <td>
                                <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "navigatorPhotoBottom.tpl"), $this);?>

                              </td>
                            </tr>
                          </table>
                        </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                </table>
              <?php endif; ?>

            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

        <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.GuestPreview",'class' => 'gbBlock'), $this);?>


        	<?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.EmergencyEditItemLink",'class' => 'gbBlock','checkSidebarBlocks' => true,'checkPhotoBlocks' => true), $this);?>


                                       <div id="comments" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'comments')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td>
                                             <?php echo $this->_reg_objects['g'][0]->block(array('type' => "comment.ViewComments",'item' => $this->_tpl_vars['child']), $this);?>

                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                                 <a onclick="MM_showHideLayers('comments','','hide')" onmouseover="MM_showHideLayers('comments','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                                               <?php else: ?>
                                                 <a onclick="MM_showHideLayers('comments','','hide')" onmouseover="MM_showHideLayers('comments','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                                               <?php endif; ?>

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>


                                        <div id="exif" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; width:500px; text-align:left;
z-index: 10; visibility: hidden;" onmousedown="dragStart(event, 'exif')" class="BlockOpacity">
                                          <div id="exifIn" style="position: relative; left: 0px; top: 0px;  
z-index: 10;" class="gcBackground1 gcBorder2">

                                          <?php echo $this->_reg_objects['g'][0]->block(array('type' => "exif.ExifInfo",'item' => $this->_tpl_vars['child']), $this);?>

                                            <div style="text-align: right; padding:4px">
                                            <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                              <a onclick="MM_showHideLayers('exif','','hide')" onmouseover="MM_showHideLayers('exif','','hide')" title="Close">
                                                <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                                            <?php else: ?>
                                              <a onclick="MM_showHideLayers('exif','','hide')" onmouseover="MM_showHideLayers('exif','','hide')" title="Close">
                                                <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                                            <?php endif; ?>
                                            </div>

                                          </div>
                                          <br/>
                                        </div>



                                       <div id="blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; text-align:left; z-index: 20; visibility: hidden;" onmousedown="dragStart(event, 'blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
')"  class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td style="text-align:right">
                                             <h2>Comments to "<?php echo ((is_array($_tmp=$this->_tpl_vars['child']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
"&nbsp;</h2>
                                           </td></tr>
                                           <tr><td>
                                             <?php echo $this->_reg_objects['g'][0]->block(array('type' => "comment.ViewComments",'item' => $this->_tpl_vars['child']), $this);?>

                                           </td></tr>
                                           <tr><td style="text-align:right">

                                               <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                                 <a onclick="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                                               <?php else: ?>
                                                 <a onclick="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blockIC<?php echo $this->_tpl_vars['child']['id']; ?>
','','hide')" title="Close">
                                                   <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                                               <?php endif; ?>

                                           </td></tr>
                                         </table>
                                         <br/>
                                       </div>


                                        <div id="blocks<?php echo $this->_tpl_vars['item']['id']; ?>
" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']+50; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']+100; ?>
px; text-align:left; z-index: 10; visibility: hidden;" onmousedown="dragStart(event, 'blocks<?php echo $this->_tpl_vars['item']['id']; ?>
')" class="BlockOpacity">
                                         <table width="500px" class="gcBackground1 gcBorder2">
                                           <tr><td>
                                          
<table><tr><td height="10px"></td></tr></table>
          <table width="100%" align="<?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksAlign'] )):  echo $this->_tpl_vars['theme']['params']['BlocksAlign'];  else: ?>center<?php endif; ?>">
            <tr><td></td></tr>
              <?php $_from = $this->_tpl_vars['theme']['params']['photoBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
            <tr>
              <td>
                <table align="<?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksInAlign'] )):  echo $this->_tpl_vars['theme']['params']['BlocksInAlign'];  else: ?>center<?php endif; ?>">
                  <tr>
                    <td>
                      <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1']), $this);?>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>
              <?php endforeach; endif; unset($_from); ?>
          </table>

                                           </td></tr>
                                           <tr><td style="text-align:right">

                                            <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                                              <a onclick="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" title="Close">
                                                <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/></a>
                                            <?php else: ?>
                                              <a onclick="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" onmouseover="MM_showHideLayers('blocks<?php echo $this->_tpl_vars['item']['id']; ?>
','','hide')" title="Close">
                                                <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close"/></a>
                                            <?php endif; ?>

                                           </td></tr>
                                         </table>
                                          <br/>
                                        </div>


<?php if (! empty ( $this->_tpl_vars['theme']['params']['photoBlocks'] ) && empty ( $this->_tpl_vars['theme']['params']['OtherBlocksBtn'] )): ?>

<table border="0" width="98%"><tr><td>

          <table width="400" align="<?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksAlign'] )):  echo $this->_tpl_vars['theme']['params']['BlocksAlign'];  else: ?>center<?php endif; ?>">
            <tr><td></td></tr>
              <?php $_from = $this->_tpl_vars['theme']['params']['photoBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
            <tr>
              <td>
                <table align="<?php if (! empty ( $this->_tpl_vars['theme']['params']['BlocksInAlign'] )):  echo $this->_tpl_vars['theme']['params']['BlocksInAlign'];  else: ?>center<?php endif; ?>">
                  <tr>
                    <td>
                      <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1']), $this);?>

                    </td>
                  </tr>
                </table>
              </td>
            </tr>
              <?php endforeach; endif; unset($_from); ?>

          </table>
</td></tr></table>
<?php endif; ?>
<script type="text/javascript">
//<![CDATA[
start();
//]]>
</script>
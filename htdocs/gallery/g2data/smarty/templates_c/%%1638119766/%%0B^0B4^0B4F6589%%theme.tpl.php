<?php /* Smarty version 2.6.10, created on 2006-03-08 18:18:12
         compiled from gallery:themes/PGtheme/templates/theme.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:themes/PGtheme/templates/theme.tpl', 23, false),array('modifier', 'markup', 'gallery:themes/PGtheme/templates/theme.tpl', 23, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo '<?xml'; ?>
 version="1.0" encoding="UTF-8"<?php echo '?>'; ?>

<html>
  <head>
        <?php echo $this->_reg_objects['g'][0]->head(array(), $this);?>


    <?php if (empty ( $this->_tpl_vars['head']['title'] )): ?>
 <?php if (! isset ( $this->_tpl_vars['SlideShow'] )): ?>
<title>
   <?php if (isset ( $this->_tpl_vars['theme']['params']['site'] )): ?>
<?php echo $this->_tpl_vars['theme']['params']['site']; ?>
 - 
   <?php endif; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['item']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['item']['pathComponent'])))) ? $this->_run_mod_handler('markup', true, $_tmp, 'strip') : smarty_modifier_markup($_tmp, 'strip')); ?>
</title>
<?php endif; ?>
<?php endif; ?>

<meta http-equiv="imagetoolbar" content="no"/>
<?php if (! isset ( $this->_tpl_vars['SlideShow'] )): ?>
<meta name="keywords" content="<?php echo $this->_tpl_vars['theme']['item']['keywords']; ?>
" />
<meta name="description" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp, 'strip') : smarty_modifier_markup($_tmp, 'strip')); ?>
" />
<?php endif; ?>

        <link rel="stylesheet" type="text/css" href="<?php echo $this->_reg_objects['g'][0]->theme(array('url' => "theme.css"), $this);?>
"/>
    <script type="text/javascript" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/theme.js'), $this);?>
"></script>
  </head>
  <body class="gallery">
    <div <?php echo $this->_reg_objects['g'][0]->mainDivAttributes(array(), $this);?>
>
      
      <?php if ($this->_tpl_vars['theme']['useFullScreen']): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['theme']['moduleTemplate']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['theme']['moduleL10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php else: ?>
        <?php if ($this->_tpl_vars['theme']['params']['iestatus']): ?>
        <?php echo '
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
            function wst(){
        '; ?>

              window.status="<?php echo $this->_tpl_vars['theme']['params']['site']; ?>
";
        <?php echo '
              window.setTimeout("wst()",1);
            }
           wst()
          //-->
          //]]>
          </script>
        '; ?>

        <?php endif; ?>

        <?php if ($this->_tpl_vars['theme']['params']['expand']): ?>
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
            expand()
          //-->
          //]]>
          </script>
        <?php endif; ?>

        <?php if (! empty ( $this->_tpl_vars['theme']['params']['RC'] )): ?>
          <script type="text/JavaScript">
          //<![CDATA[
          <!--
               document.onmousedown=rightdisable;
               if (document.layers) window.captureEvents(Event.MOUSEDOWN);
               window.onmousedown=rightdisable;
          //-->
          //]]>
          </script>
        <?php else: ?>
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['RCalert'] ) && ! empty ( $this->_tpl_vars['theme']['params']['site'] )): ?>
            <script type="text/JavaScript">
            //<![CDATA[
            <!--
              var msgmouse="(c) Copyright - <?php echo $this->_tpl_vars['theme']['params']['site']; ?>
" 
              //Mouse Rigt click 'msg'
                document.onmousedown=rightalert;
                if (document.layers) window.captureEvents(Event.MOUSEDOWN);
                window.onmousedown=rightalert;
            //-->
            //]]>
            </script>
          <?php endif; ?>
          <?php if (! empty ( $this->_tpl_vars['theme']['params']['RCoptions'] ) && empty ( $this->_tpl_vars['theme']['params']['sidebar'] )): ?>
            <script type="text/JavaScript">
            //<![CDATA[
            <!--
               document.onmousedown=rightoptions;
               if (document.layers) window.captureEvents(Event.MOUSEDOWN);
               window.onmousedown=rightoptions;
            //-->
            //]]>
            </script>
          <?php endif; ?>
        <?php endif; ?>

        <div id="gsHeader">
          <table width="100%">
            <tr>
              <td>
                <?php $this->assign('separator', $this->_tpl_vars['theme']['params']['MenuSeparator']); ?>
                <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                  <a href="<?php echo $this->_reg_objects['g'][0]->url(array(), $this);?>
" 
onmouseover="logo.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/logoon.gif'), $this);?>
'"  
onmouseout="logo.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/logo.gif'), $this);?>
'">
                    <img id="logo" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "themes/PGtheme/images/logo.gif"), $this);?>
" alt="home" longdesc="home" /></a>                                
                <?php else: ?>
                  <a href="<?php echo $this->_reg_objects['g'][0]->url(array(), $this);?>
" 
onmouseover="logo.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/logoon.gif'"  
onmouseout="logo.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/logo.gif'">
                    <img id="logo" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/logo.gif" alt="home" longdesc="home" /></a>
                <?php endif; ?>
              </td>
              <td style="text-align:right" valign="top">
                <?php if ($this->_tpl_vars['theme']['params']['expandBtn']): ?>
                  <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                    <a href="javascript:expand()" 
onmouseover="full.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/fullon.gif'), $this);?>
'"  
onmouseout="full.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/full.gif'), $this);?>
'">
                      <img id="full" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "themes/PGtheme/images/full.gif"), $this);?>
" alt="Full Screen" title="Full Screen" longdesc="Full Screen" class="navpic"/></a>
                  <?php else: ?>
                    <a href="javascript:expand()" 
onmouseover="full.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/fullon.gif'"  
onmouseout="full.src='<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/full.gif'">
                      <img id="full" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/full.gif" alt="Full Screen" longdesc="Full Screen" title="Full Screen" class="navpic"/></a>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
          </table>
        </div>
        <table width="100%">
          <tr>
            <?php if ($this->_tpl_vars['theme']['params']['BreadCrumb']): ?>
            <td style="width:50%;text-align:left">
              <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.BreadCrumb"), $this);?>

            </td>
            <?php endif; ?>
            <td style="width:50%;text-align:right">
              <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.SystemLinks",'order' => "core.SiteAdmin core.YourAccount core.Login core.Logout",'othersAt' => 4), $this);?>

              <?php if ($this->_tpl_vars['theme']['params']['link1']): ?>
                <a href="<?php echo $this->_tpl_vars['theme']['params']['link1url']; ?>
"><?php echo $this->_tpl_vars['theme']['params']['link1']; ?>
</a>
              <?php endif; ?>

              <?php if ($this->_tpl_vars['theme']['params']['link2']): ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['link1'] )): ?>
                   <?php echo $this->_tpl_vars['separator']; ?>

                   <?php endif; ?>
                <a href="<?php echo $this->_tpl_vars['theme']['params']['link2url']; ?>
"><?php echo $this->_tpl_vars['theme']['params']['link2']; ?>
</a>
              <?php endif; ?>

              <?php if (empty ( $this->_tpl_vars['theme']['params']['sidebar'] )): ?>
                   <?php if (! empty ( $this->_tpl_vars['theme']['params']['link1'] ) || ! empty ( $this->_tpl_vars['theme']['params']['link2'] )): ?>
                   <?php echo $this->_tpl_vars['separator']; ?>

                   <?php endif; ?>
                <a href="#" onclick="ShowLayer('actions','visible')" 
onmouseover="ShowLayer('actions','hidden')"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Options'), $this);?>
</a>
              <?php endif; ?>
            </td>

          </tr>
        </table>
        <br/>
                <?php if ($this->_tpl_vars['theme']['pageType'] == 'album'): ?>
          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "album.tpl"), $this);?>

        <?php elseif ($this->_tpl_vars['theme']['pageType'] == 'photo'): ?>
          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "photo.tpl"), $this);?>

        <?php elseif ($this->_tpl_vars['theme']['pageType'] == 'admin'): ?>
          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "admin.tpl"), $this);?>

        <?php elseif ($this->_tpl_vars['theme']['pageType'] == 'module'): ?>
          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "module.tpl"), $this);?>

        <?php elseif ($this->_tpl_vars['theme']['pageType'] == 'progressbar'): ?>
          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "progressbar.tpl"), $this);?>

        <?php endif; ?>

        <div id="actions" style="position: absolute; left:<?php echo $this->_tpl_vars['theme']['params']['sidebarL']; ?>
px; top: <?php echo $this->_tpl_vars['theme']['params']['sidebarT']; ?>
px;  
z-index: 1; visibility: hidden;">
          <div id="actionsIn" style="position: relative; left: 0px; top: 0px;  
z-index: 2;" class="gcBackground1 gcBorder2">
          <div id="move" style="position: relative; left: 0px; top: 0px;  
z-index: 2;" class="gcBackground2" onmousedown="dragStart(event, 'actions')" title="click on this bar, drag and drop to move">
            <table class="Sidebar">
              <tr>
                <td style="text-align: right">
                  <?php if (empty ( $this->_tpl_vars['theme']['params']['colorpack'] )): ?>
                    <a onclick="MM_showHideLayers('actions','','hide')" onmouseover="MM_showHideLayers('actions','','hide')" title="Close">
                      <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
close.gif" alt="close"/>
                    </a>
                  <?php else: ?>
                    <a onclick="MM_showHideLayers('actions','','hide')" onmouseover="MM_showHideLayers('actions','','hide')" title="Close">
                      <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'modules/colorpack/packs/'), $this); echo $this->_tpl_vars['theme']['params']['colorpack']; ?>
/images/close.gif" alt="close" id="close"/>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            </table>
          </div>
            <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "sidebar.tpl"), $this);?>

          </div>
          <br/>
        </div>

        <div id="gsFooter">
        <br/>
          <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "footer.tpl"), $this);?>

        </div>
      <?php endif; ?>      </div>

    
    <?php echo $this->_reg_objects['g'][0]->trailer(array(), $this);?>

        <?php echo $this->_reg_objects['g'][0]->debug(array(), $this);?>

  </body>
</html>
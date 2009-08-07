<?php /* Smarty version 2.6.10, created on 2006-03-08 18:06:41
         compiled from gallery:themes/hybrid/templates/hybrid.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'markup', 'gallery:themes/hybrid/templates/hybrid.tpl', 97, false),array('modifier', 'default', 'gallery:themes/hybrid/templates/hybrid.tpl', 101, false),array('modifier', 'repeat', 'gallery:themes/hybrid/templates/hybrid.tpl', 206, false),)), $this); ?>
<div id="popup_details" class="gcBorder2"><iframe frameborder="0"></iframe></div>

<div id="popup_titlebar" class="gcBackground1 giDescription">
  <div style="float: right; margin-left: 2px">
    <img src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/down.png" width="18" height="18"
     onclick="popup_vis(0)" alt=""/>
  </div>
  <strong> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Item Details'), $this);?>
 </strong>
</div>

<div id="imageview" class="gcBackground2">
  <div id="imagearea"><div id="imagediv" onclick="image_vis(0)"></div></div>
  <div id="textdiv" class="gcBackground1 gcBorder2">
    <div id="tools_left">
      <img id="text_on" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/up.png"
       width="18" height="18" onclick="text_onoff()"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show text'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show text'), $this);?>
"
     /><img id="text_off" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/down.png"
       width="18" height="18" onclick="text_onoff()" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide text'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide text'), $this);?>
"
     /><img src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/alb.png"
       width="18" height="18" onclick="image_vis(0)"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Back to Album View'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Back to Album View'), $this);?>
"
     /><img id="slide_poz" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/poz.png"
       width="18" height="18" onclick="slide_onoff()" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Pause Slideshow'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Pause Slideshow'), $this);?>
"
     /><img id="slide_fwd" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/fwd.png"
       width="18" height="18" onclick="slide_onoff()"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
"
     /><img id="slide_rev" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/rev.png"
       width="18" height="18" onclick="slide_onoff()" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
"
     /><img id="slide_rand" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/rand.png"
       width="18" height="18" onclick="slide_onoff()" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
"/>
    </div>
    <div id="tools_right">
      <img id="full_size" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/full.png"
       width="18" height="18" onclick="image_zoom(1)" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Full Size'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Full Size'), $this);?>
"
     /><img id="fit_size" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/fit.png"
       width="18" height="18" onclick="image_zoom(0)" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Fit Size'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Fit Size'), $this);?>
"
     /><img src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/info.png"
       width="18" height="18" onclick="popup_info(-1)"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Item Details'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Item Details'), $this);?>
"
     /><img id="prev_off" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/prev-off.png"
       width="18" height="18" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No Previous Image'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No Previous Image'), $this);?>
"
     /><img id="prev_img" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/prev.png"
       width="18" height="18" onclick="image_prev()"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Previous Image'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Previous Image'), $this);?>
"
     /><img id="next_off" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/next-off.png"
       width="18" height="18" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No Next Image'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No Next Image'), $this);?>
"
     /><img id="next_img" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/next.png"
       width="18" height="18" onclick="image_next()"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Next Image'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Next Image'), $this);?>
"/>
    </div>
    <div id="title" class="giTitle"></div>
    <div id="text" class="gcBackground2 gcBorder2"></div>
  </div>
</div>

<table id="hybridMain" border="0" cellspacing="0" cellpadding="0"><tr valign="top">
<td>
  <div id="sidebar" class="gcBorder2">
    <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "sidebar.tpl"), $this);?>

  </div>
</td><td id="gsContent">
  <div id="album_titlebar" class="gcBackground2 gcBorder2">
   <div id="album_tools">
      <img id="sidebar_min" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/left.png"
       width="18" height="18" onclick="sidebar_onoff()"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide sidebar'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide sidebar'), $this);?>
"
     /><img id="sidebar_max" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/right.png"
       width="18" height="18" onclick="sidebar_onoff()" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show sidebar'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show sidebar'), $this);?>
"
     /><img id="slide__fwd" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/fwd.png"
       width="18" height="18" onclick="slide_onoff()"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
"
     /><img id="slide__rev" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/rev.png"
       width="18" height="18" onclick="slide_onoff()" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
"
     /><img id="slide__rand" src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/rand.png"
       width="18" height="18" onclick="slide_onoff()" style="display: none"
       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Start Slideshow'), $this);?>
"/>
    </div>

    <table width="90%"><tr><td style="width: 40%">
      <div class="giTitle"> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </div>
      <div id="album_desc" class="giDescription"> <?php echo ((is_array($_tmp=$this->_tpl_vars['theme']['item']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </div>
    </td><td>
      <div id="album_info" class="giInfo">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Owner: %s",'arg1' => ((is_array($_tmp=@$this->_tpl_vars['theme']['item']['owner']['fullName'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['item']['owner']['userName']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['item']['owner']['userName']))), $this);?>

	<br/>
	<?php echo $this->_reg_objects['g'][0]->text(array('one' => "Viewed: %d time",'many' => "Viewed: %d times",'count' => $this->_tpl_vars['theme']['item']['viewCount'],'arg1' => $this->_tpl_vars['theme']['item']['viewCount']), $this);?>

      </div>
    </td></tr></table>
  </div>
  <div id="gsAlbumContent" class="gcBackground1">
    <table class="content">
    <?php $_from = $this->_tpl_vars['theme']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['hybrid'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['hybrid']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['it']):
        $this->_foreach['hybrid']['iteration']++;
?>
      <?php if (( $this->_tpl_vars['i'] % $this->_tpl_vars['theme']['params']['columns'] == 0 )): ?><tr><?php endif; ?>
      <td class="i">
	<?php if (isset ( $this->_tpl_vars['it']['image'] )): ?>
	  <?php if (isset ( $this->_tpl_vars['it']['renderItem'] )): ?>
	    <a id="img_<?php echo $this->_tpl_vars['it']['imageIndex']; ?>
" href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['it']['id']),'arg3' => "renderId=".($this->_tpl_vars['it']['image']['id'])), $this);?>
"></a>
	  <?php else: ?>
	    <a id="img_<?php echo $this->_tpl_vars['it']['imageIndex']; ?>
" href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['it']['image']['id']),'arg3' => "serialNumber=".($this->_tpl_vars['it']['image']['serialNumber'])), $this);?>
"></a>
	  <?php endif; ?>
	  <a id="info_<?php echo $this->_tpl_vars['it']['imageIndex']; ?>
" href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['it']['id']),'arg3' => "detail=1"), $this);?>
"></a>
	  <?php ob_start(); ?>
	    <a href="" onclick="image_show(<?php echo $this->_tpl_vars['it']['imageIndex']; ?>
);return false">
	  <?php $this->_smarty_vars['capture']['link'] = ob_get_contents(); ob_end_clean(); ?>
	  <?php if (isset ( $this->_tpl_vars['it']['thumbnail'] ) && isset ( $this->_tpl_vars['theme']['params']['itemFrame'] )): ?>
	    <?php $this->_tag_stack[] = array('container', array('type' => "imageframe.ImageFrame",'frame' => $this->_tpl_vars['theme']['params']['itemFrame']), $this); $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
	      <?php echo $this->_smarty_vars['capture']['link']; ?>

	      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['it'],'image' => $this->_tpl_vars['it']['thumbnail'],'id' => "%ID%",'class' => "%CLASS% giThumbnail"), $this);?>

	      </a>
	    <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>

	  <?php else: ?>
	    <?php echo $this->_smarty_vars['capture']['link']; ?>

	    <?php if (isset ( $this->_tpl_vars['it']['thumbnail'] )): ?>
	      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['it'],'image' => $this->_tpl_vars['it']['thumbnail'],'class' => 'giThumbnail'), $this);?>

	    <?php else: ?>
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'no thumbnail'), $this);?>

	    <?php endif; ?>
	    </a>
	  <?php endif; ?>
	<?php elseif (( $this->_tpl_vars['it']['canContainChildren'] )): ?>
	  <?php ob_start(); ?>
	    <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['it']['id'])), $this);?>
">
	  <?php $this->_smarty_vars['capture']['link'] = ob_get_contents(); ob_end_clean(); ?>
	  <?php if (isset ( $this->_tpl_vars['it']['thumbnail'] ) && isset ( $this->_tpl_vars['theme']['params']['albumFrame'] )): ?>
	    <?php $this->_tag_stack[] = array('container', array('type' => "imageframe.ImageFrame",'frame' => $this->_tpl_vars['theme']['params']['albumFrame']), $this); $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
	      <?php echo $this->_smarty_vars['capture']['link']; ?>

	      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['it'],'image' => $this->_tpl_vars['it']['thumbnail'],'id' => "%ID%",'class' => "%CLASS% giThumbnail"), $this);?>

	      </a>
	    <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>

	  <?php else: ?>
	    <?php echo $this->_smarty_vars['capture']['link']; ?>

	    <?php if (isset ( $this->_tpl_vars['it']['thumbnail'] )): ?>
	      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['it'],'image' => $this->_tpl_vars['it']['thumbnail'],'class' => 'giThumbnail'), $this);?>

	    <?php else: ?>
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'no thumbnail'), $this);?>

	    <?php endif; ?>
	    </a>
	  <?php endif; ?>
	<?php else: ?>
	  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['it']['id'])), $this);?>
">
	    <?php if (isset ( $this->_tpl_vars['it']['thumbnail'] )): ?>
	      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['it'],'image' => $this->_tpl_vars['it']['thumbnail'],'class' => 'giThumbnail'), $this);?>

	    <?php else: ?>
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'no thumbnail'), $this);?>

	    <?php endif; ?>
	  </a>
	<?php endif; ?>
      </td>
      <td class="t">
	<table class="itemtext"><tr><td>
	  <div class="title gcBackground2">
	    <?php if (isset ( $this->_tpl_vars['it']['image'] ) || isset ( $this->_tpl_vars['it']['itemLinks'] )): ?>
	      <span style="float: right; margin-left: 2px"><img
	       src="<?php echo $this->_tpl_vars['theme']['themeUrl']; ?>
/images/menu.png" class="popup_button" width="18" height="18"
	       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Item Actions'), $this);?>
" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Item Actions'), $this);?>
"
	       onclick="popup_menu(event,<?php echo $this->_tpl_vars['i']; ?>

		<?php if (isset ( $this->_tpl_vars['it']['image'] )): ?>,<?php echo $this->_tpl_vars['it']['imageIndex']; ?>
)"/></span>
	      <span id="title_<?php echo $this->_tpl_vars['it']['imageIndex']; ?>
" class="giTitle">
		<?php else: ?>,-1)"/></span>
	      <span class="giTitle"><?php endif; ?>
	    <?php else: ?>
	      <span class="giTitle">
	    <?php endif; ?>
	    <?php echo ((is_array($_tmp=$this->_tpl_vars['it']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
</span>
	  </div>
	</td></tr><tr><td class="giDescription">
	  <?php if (isset ( $this->_tpl_vars['it']['summary'] )):  echo ((is_array($_tmp=$this->_tpl_vars['it']['summary'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp));  endif; ?>
	  <?php if (isset ( $this->_tpl_vars['it']['image'] )): ?>
	    <span id="text_<?php echo $this->_tpl_vars['it']['imageIndex']; ?>
" style="display: none"><?php echo ((is_array($_tmp=$this->_tpl_vars['it']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
</span>
	  <?php endif; ?>
	</td></tr></table>
	<?php if (isset ( $this->_tpl_vars['it']['itemLinks'] )): ?>
	  <span id="links_<?php echo $this->_tpl_vars['i']; ?>
" style="display: none">
	  <?php $_from = $this->_tpl_vars['it']['itemLinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
	    <a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['link']['params']), $this);?>
"><?php echo $this->_tpl_vars['link']['text']; ?>
</a><br/>
	  <?php endforeach; endif; unset($_from); ?>
	  </span>
	<?php endif; ?>
      </td>
      <?php if (( ( $this->_tpl_vars['i']+1 ) % $this->_tpl_vars['theme']['params']['columns'] == 0 )): ?></tr>
      <?php elseif (($this->_foreach['hybrid']['iteration'] == $this->_foreach['hybrid']['total'])): ?>
	<?php $this->assign('i', $this->_tpl_vars['i']%$this->_tpl_vars['theme']['params']['columns']); ?>
	<?php $this->assign('i', $this->_tpl_vars['theme']['params']['columns']-$this->_tpl_vars['i']-1); ?>
	<?php echo ((is_array($_tmp="<td></td><td></td>")) ? $this->_run_mod_handler('repeat', true, $_tmp, $this->_tpl_vars['i']) : smarty_modifier_repeat($_tmp, $this->_tpl_vars['i'])); ?>
</tr>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
    <?php if ($this->_tpl_vars['theme']['totalPages'] > 1): ?>
      <tr><td colspan="<?php echo $this->_tpl_vars['theme']['params']['columns']*2; ?>
">
	<?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.Pager",'class' => 'gbBlock gcBackground2'), $this);?>

      </td></tr>
    <?php endif; ?>
    </table>
  </div>
    <?php $_from = $this->_tpl_vars['theme']['params']['albumBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
    <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1'],'class' => 'gbBlock'), $this);?>

  <?php endforeach; endif; unset($_from); ?>
</td>
</tr></table>

<div id="popup_menu" class="gcBackground1 gcBorder2" onmouseover="clearTimeout(popup_timer)"
 onmouseout="popup_timer=setTimeout('ui_vis(\'popup_menu\',0)',1000)">
  <div id="popup_links" onclick="ui_vis('popup_menu',0)"></div>
</div>

<script type="text/javascript">app_init();</script>
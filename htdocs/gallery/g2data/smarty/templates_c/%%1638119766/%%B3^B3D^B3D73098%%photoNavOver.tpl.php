<?php /* Smarty version 2.6.10, created on 2006-03-08 18:21:33
         compiled from gallery:themes/PGtheme/templates/photoNavOver.tpl */ ?>

<div style="position:relative; left:0px; top: 0px; width:<?php echo $this->_tpl_vars['imagewidth']; ?>
px; height:<?php echo $this->_tpl_vars['imageheight']; ?>
px;">

<?php if ($this->_tpl_vars['theme']['params']['imageFadin']): ?> 
  <?php echo $this->_reg_objects['g'][0]->image(array('id' => 'foto','item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'longdesc' => "%ID%",'usemap' => "#fotomap"), $this);?>

<?php else: ?>

  <?php echo $this->_reg_objects['g'][0]->image(array('id' => 'foto','item' => $this->_tpl_vars['theme']['item'],'image' => $this->_tpl_vars['image'],'fallback' => $this->_smarty_vars['capture']['fallback'],'class' => "%CLASS%",'longdesc' => "%ID%",'usemap' => "#fotomap",'onload' => "MM_showHideLayers('prevOT','','hide','prevOB','','hide','nextOT','','hide','nextOB','','hide','popupOT','','hide','popupOB','','hide')"), $this);?>

<?php endif; ?>


  <?php $this->assign('navwidthT', $this->_tpl_vars['imagewidth']); ?>
  <?php $this->assign('navwidth', $this->_tpl_vars['navwidthT']/2); ?>
  <?php $this->assign('navheight', $this->_tpl_vars['imageheight']); ?>
  <?php $this->assign('navnextleft', $this->_tpl_vars['navwidthT']-120); ?>
  <?php $this->assign('navcenter', $this->_tpl_vars['navwidth']-50); ?>
  <?php $this->assign('navheightbottom', $this->_tpl_vars['navheight']-50); ?>


  <div id="prevOT" style="z-index:10; position:absolute; left:20px; top: 20px; width:150px; height:10px; filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="previous photo">
<a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['back']['urlParams']), $this);?>
" title="previous photo" onmouseover="MM_showHideLayers('prevOT','','show')" onmouseout="MM_showHideLayers('prevOT','','hide')"> 
    <img name="prevOT" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
prevphoto.gif" border="0" alt="previous photo" title="previous photo" longdesc="previous photo" id="prevphotoT"/> 
</a>
  </div>

  <div id="prevOB" style="z-index:10; position:absolute; left:20px; top: <?php echo $this->_tpl_vars['navheightbottom']; ?>
px; width:150px; height:10px; 
filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="previous photo">
<a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['back']['urlParams']), $this);?>
" title="previous photo" onmouseover="MM_showHideLayers('prevOB','','show')" onmouseout="MM_showHideLayers('prevOB','','hide')"> 
    <img name="prevOB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
prevphoto.gif" border="0" alt="previous photo" title="previous photo" longdesc="previous photo" id="prevphotoB"/> 
</a>
  </div>

  <div id="nextOT"  style="z-index:10; position:absolute; left:<?php echo $this->_tpl_vars['navnextleft']; ?>
px; top: 20px; width:150px; height:10px; filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="next">
<a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['next']['urlParams']), $this);?>
" title="next photo" onmouseover="MM_showHideLayers('nextOT','','show')" onmouseout="MM_showHideLayers('nextOT','','hide')">
    <img name="nextOT" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
nextphoto.gif" border="0" alt="next photo" title="next photo" longdesc="next photo" id="nextphotoT"/>
</a>
  </div>

  <div id="nextOB" style="z-index:10; position:absolute; left:<?php echo $this->_tpl_vars['navnextleft']; ?>
px; top: <?php echo $this->_tpl_vars['navheightbottom']; ?>
px; width:150px; height:10px; filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="next photo">
<a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['next']['urlParams']), $this);?>
" title="next photo" onmouseover="MM_showHideLayers('nextOB','','show')" onmouseout="MM_showHideLayers('nextOB','','hide')">
    <img name="nextOB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
nextphoto.gif" border="0" alt="next photo" title="next photo" longdesc="next photo" id="nextphotoB"/>
</a>
  </div>

  <div id="popupOT" style="z-index:10; position:absolute; left:<?php echo $this->_tpl_vars['navcenter']; ?>
px; top: 20px; width:150px; height:10px; 
filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="popup">
<a href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOT','','show')" onmouseout="MM_showHideLayers('popupOT','','hide')" title="full image popup">
    <img name="popupOT" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
fullphotoover.gif" border="0" alt="full image popup" title="full image popup" longdesc="full image popup" id="popupphotoT"/>
</a>
  </div>

  <div id="popupOB" style="z-index:10; position:absolute; left:<?php echo $this->_tpl_vars['navcenter']; ?>
px; top: <?php echo $this->_tpl_vars['navheightbottom']; ?>
px; width:150px; height:10px; 
filter: alpha(opacity=60); -moz-opacity: 0.6; opacity: 0.6;" title="popup">
<a href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOB','','show')" onmouseout="MM_showHideLayers('popupOB','','hide')" title="full image popup">
    <img name="popupOB" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => 'themes/PGtheme/images/'), $this);?>
fullphotoover.gif" border="0" alt="full image popup" title="full image popup" longdesc="full image popup" id="popupphotoB"/>
</a>
  </div>


  <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverTop'] )): ?>
    <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?> 
      <map id="fotomap" name="fotomap">
        <?php if (isset ( $this->_tpl_vars['theme']['navigator']['back'] )): ?>
          <area shape="rect" coords="0,0,<?php echo $this->_tpl_vars['navwidth']-100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" 
href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['back']['urlParams']), $this);?>
" title="previous photo" onmouseover="MM_showHideLayers('prevOB','','show','prevOT','','show')" onmouseout="MM_showHideLayers('prevOB','','hide','prevOT','','hide')" alt="previous photo" />
        <?php endif; ?>
        <?php if (isset ( $this->_tpl_vars['theme']['navigator']['next'] )): ?>
          <area shape="rect" coords="<?php echo $this->_tpl_vars['navwidth']+100; ?>
,0,<?php echo $this->_tpl_vars['navwidthT']; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['next']['urlParams']), $this);?>
" title="next photo" onmouseover="MM_showHideLayers('nextOB','','show','nextOT','','show')" onmouseout="MM_showHideLayers('nextOB','','hide','nextOT','','hide')" alt="next photo"/>
        <?php endif; ?>
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverPopup'] ) && ! empty ( $this->_tpl_vars['theme']['sourceImage']['width'] )): ?>
          <?php if ($this->_tpl_vars['imagewidth'] != $this->_tpl_vars['theme']['item']['width']): ?>
            <area shape="rect"
 coords="<?php echo $this->_tpl_vars['navwidth']-100; ?>
,0,<?php echo $this->_tpl_vars['navwidth']+100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" 
href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOB','','show','popupOT','','show')" onmouseout="MM_showHideLayers('popupOB','','hide','popupOT','','hide')" alt="full image popup" title="full image popup"/>
          <?php else: ?>
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverPopupEven'] )): ?>
              <area shape="rect"
 coords="<?php echo $this->_tpl_vars['navwidth']-100; ?>
,0,<?php echo $this->_tpl_vars['navwidth']+100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOB','','show','popupOT','','show')" onmouseout="MM_showHideLayers('popupOB','','hide','popupOT','','hide')" alt="full image popup" title="full image popup"/>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>
      </map>
    <?php else: ?>
      <map id="fotomap" name="fotomap">
        <?php if (isset ( $this->_tpl_vars['theme']['navigator']['back'] )): ?>
          <area shape="rect" coords="0,0,<?php echo $this->_tpl_vars['navwidth']-100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" 
href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['back']['urlParams']), $this);?>
" title="previous photo" onmouseover="MM_showHideLayers('prevOT','','show')" onmouseout="MM_showHideLayers('prevOT','','hide')" alt="previous photo" />
        <?php endif; ?>
        <?php if (isset ( $this->_tpl_vars['theme']['navigator']['next'] )): ?>
          <area shape="rect" coords="<?php echo $this->_tpl_vars['navwidth']+100; ?>
,0,<?php echo $this->_tpl_vars['navwidthT']; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['next']['urlParams']), $this);?>
" title="next photo" onmouseover="MM_showHideLayers('nextOT','','show')" onmouseout="MM_showHideLayers('nextOT','','hide')" alt="next photo"/>
        <?php endif; ?>
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverPopup'] ) && ! empty ( $this->_tpl_vars['theme']['sourceImage']['width'] )): ?>
          <?php if ($this->_tpl_vars['imagewidth'] != $this->_tpl_vars['theme']['item']['width']): ?>
            <area shape="rect"
 coords="<?php echo $this->_tpl_vars['navwidth']-100; ?>
,0,<?php echo $this->_tpl_vars['navwidth']+100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOT','','show')" onmouseout="MM_showHideLayers('popupOT','','hide')" alt="full image popup" title="full image popup"/>
          <?php else: ?>
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverPopupEven'] )): ?>
              <area shape="rect"
 coords="<?php echo $this->_tpl_vars['navwidth']-100; ?>
,0,<?php echo $this->_tpl_vars['navwidth']+100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOT','','show')" onmouseout="MM_showHideLayers('popupOT','','hide')" alt="full image popup" title="full image popup"/>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>
      </map>
    <?php endif; ?>
  <?php else: ?>
    <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverBottom'] )): ?> 
      <map id="fotomap" name="fotomap">
        <?php if (isset ( $this->_tpl_vars['theme']['navigator']['back'] )): ?>
          <area shape="rect"  coords="0,0,<?php echo $this->_tpl_vars['navwidth']-100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" 
href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['back']['urlParams']), $this);?>
" title="previous photo" onmouseover="MM_showHideLayers('prevOB','','show')" onmouseout="MM_showHideLayers('prevOB','','hide')" alt="previous photo" />
        <?php endif; ?>
          <?php if (isset ( $this->_tpl_vars['theme']['navigator']['next'] )): ?>
            <area shape="rect"  coords="<?php echo $this->_tpl_vars['navwidth']+100; ?>
,0,<?php echo $this->_tpl_vars['navwidthT']; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['theme']['navigator']['next']['urlParams']), $this);?>
" title="next photo" onmouseover="MM_showHideLayers('nextOB','','show')" onmouseout="MM_showHideLayers('nextOB','','hide')" alt="next photo"/>
        <?php endif; ?>
        <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverPopup'] )): ?>
          <?php if ($this->_tpl_vars['imagewidth'] != $this->_tpl_vars['theme']['item']['width']): ?>
            <area shape="rect"
 coords="<?php echo $this->_tpl_vars['navwidth']-100; ?>
,0,<?php echo $this->_tpl_vars['navwidth']+100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOB','','show')" onmouseout="MM_showHideLayers('popupOB','','hide')" alt="full image popup" title="full image popup"/>
          <?php else: ?>
            <?php if (! empty ( $this->_tpl_vars['theme']['params']['NavOverPopupEven'] )): ?>
              <area shape="rect"
 coords="<?php echo $this->_tpl_vars['navwidth']-100; ?>
,0,<?php echo $this->_tpl_vars['navwidth']+100; ?>
,<?php echo $this->_tpl_vars['navheight']; ?>
" href="javascript:void(0);" onclick="fsizeopen('<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['sourceImage']['id'])), $this);?>
'); return false;" onmouseover="MM_showHideLayers('popupOB','','show')" onmouseout="MM_showHideLayers('popupOB','','hide')" alt="full image popup" title="full image popup"/>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>
      </map>
    <?php endif; ?>
  <?php endif; ?>

</div>
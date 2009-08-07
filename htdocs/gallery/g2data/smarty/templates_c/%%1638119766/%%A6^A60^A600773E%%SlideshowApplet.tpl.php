<?php /* Smarty version 2.6.10, created on 2006-03-08 18:37:29
         compiled from gallery:modules/slideshowapplet/templates/SlideshowApplet.tpl */ ?>

<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Slideshow'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <?php if (! empty ( $this->_tpl_vars['SlideshowApplet']['NoProtocolError'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This applet relies on a G2 module that is not currently enabled.  Please ask an administrator to enable the 'remote' module."), $this);?>

  </div>
  <?php else: ?>
  <object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
	  codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4-windows-i586.cab#Version=1,4,0,0"
	  width="300" height="430">
    <param name="code" value="<?php echo $this->_tpl_vars['SlideshowApplet']['code']; ?>
"/>
    <param name="archive" value="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/slideshowapplet/applets/GalleryRemoteAppletMini.jar"), $this);?>
,<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/slideshowapplet/applets/GalleryRemoteHTTPClient.jar"), $this);?>
,<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/slideshowapplet/applets/applet_img.jar"), $this);?>
"/>
    <param name="type" value="application/x-java-applet;version=1.4"/>
    <param name="scriptable" value="false"/>
    <param name="progressbar" value="true"/>
    <param name="boxmessage" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Downloading the Gallery Remote Applet'), $this);?>
"/>
    <param name="gr_url" value="<?php echo $this->_tpl_vars['SlideshowApplet']['g2BaseUrl']; ?>
"/>
    <param name="gr_cookie_name" value="<?php echo $this->_tpl_vars['SlideshowApplet']['cookieName']; ?>
"/>
    <param name="gr_cookie_value" value="<?php echo $this->_tpl_vars['SlideshowApplet']['cookieValue']; ?>
"/>
    <param name="gr_cookie_domain" value="<?php echo $this->_tpl_vars['SlideshowApplet']['cookieDomain']; ?>
"/>
    <param name="gr_cookie_path" value="<?php echo $this->_tpl_vars['SlideshowApplet']['cookiePath']; ?>
"/>
    <param name="gr_album" value="<?php echo $this->_tpl_vars['SlideshowApplet']['album']; ?>
"/>
    <param name="gr_user_agent" value="<?php echo $this->_tpl_vars['SlideshowApplet']['userAgent']; ?>
"/>
    <param name="gr_gallery_version" value="<?php echo $this->_tpl_vars['SlideshowApplet']['galleryVersion']; ?>
"/>
    <?php $_from = $this->_tpl_vars['SlideshowApplet']['extra']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
    <param name="<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['value']; ?>
"/>
    <?php endforeach; endif; unset($_from); ?>
    <?php $_from = $this->_tpl_vars['SlideshowApplet']['defaults']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
    <param name="GRDefault_<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['value']; ?>
"/>
    <?php endforeach; endif; unset($_from); ?>
    <?php $_from = $this->_tpl_vars['SlideshowApplet']['overrides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
    <param name="GROverride_<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['value']; ?>
"/>
    <?php endforeach; endif; unset($_from); ?>

    <comment>
      <embed
          type="application/x-java-applet;version=1.4"
          code="<?php echo $this->_tpl_vars['SlideshowApplet']['code']; ?>
"
          archive="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/slideshowapplet/applets/GalleryRemoteAppletMini.jar"), $this);?>
,<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/slideshowapplet/applets/GalleryRemoteHTTPClient.jar"), $this);?>
,<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/slideshowapplet/applets/applet_img.jar"), $this);?>
"
          width="300"
          height="430"
          scriptable="false"
          progressbar="true"
          boxmessage="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Downloading the Gallery Remote Applet'), $this);?>
"
          pluginspage="http://java.sun.com/j2se/1.4.2/download.html"
          gr_url="<?php echo $this->_tpl_vars['SlideshowApplet']['g2BaseUrl']; ?>
"
          gr_cookie_name="<?php echo $this->_tpl_vars['SlideshowApplet']['cookieName']; ?>
"
          gr_cookie_value="<?php echo $this->_tpl_vars['SlideshowApplet']['cookieValue']; ?>
"
          gr_cookie_domain="<?php echo $this->_tpl_vars['SlideshowApplet']['cookieDomain']; ?>
"
          gr_cookie_path="<?php echo $this->_tpl_vars['SlideshowApplet']['cookiePath']; ?>
"
          gr_album="<?php echo $this->_tpl_vars['SlideshowApplet']['album']; ?>
"
          gr_user_agent="<?php echo $this->_tpl_vars['SlideshowApplet']['userAgent']; ?>
"
          gr_gallery_version="<?php echo $this->_tpl_vars['SlideshowApplet']['galleryVersion']; ?>
"
          <?php $_from = $this->_tpl_vars['SlideshowApplet']['extra']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
          <?php echo $this->_tpl_vars['key']; ?>
="<?php echo $this->_tpl_vars['value']; ?>
"
          <?php endforeach; endif; unset($_from); ?>
          <?php $_from = $this->_tpl_vars['SlideshowApplet']['defaults']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
          GRDefault_<?php echo $this->_tpl_vars['key']; ?>
="<?php echo $this->_tpl_vars['value']; ?>
"
          <?php endforeach; endif; unset($_from); ?>
          <?php $_from = $this->_tpl_vars['SlideshowApplet']['overrides']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
          GROverride_<?php echo $this->_tpl_vars['key']; ?>
="<?php echo $this->_tpl_vars['value']; ?>
"
          <?php endforeach; endif; unset($_from); ?>
      >
          <noembed alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your browser doesn't support applets; you should use one of the other upload methods."), $this);?>
">
            <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your browser doesn't support applets; you should use one of the other upload methods."), $this);?>

          </noembed>
      </embed>
    </comment>
  </object>
  <?php endif; ?>
</div>
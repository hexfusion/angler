<?php
/*
 * $RCSfile: theme.inc,v $
 *
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2005 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
/**
 * @version  $Revision: 1.05 $ $Date: 2005/12/18 $
 * @package Gallery
 * @author Pedro Gilberto <theme@pedrogilberto.net>
 * based on Matrix Theme by Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This implements the standard gallery theme
 *
 * @package GalleryTheme
 * @subpackage Theme
 */
class PGthemeTheme extends GalleryTheme {

    /**
     * Constructor
     */
    function PGthemeTheme() {
	global $gallery;

	$this->setId('PGtheme');
	$this->setName($gallery->i18n('PG Theme'));
	$this->setDescription($gallery->i18n('Gallery2 theme from WWW.PEDROGILBERTO.NET'));
	$this->setVersion('1.0.RC7');  /* Update upgrade() also */
	$this->setRequiredCoreApi(array(6, 5));
	$this->setRequiredThemeApi(array(2, 1));
	$this->setStandardSettings(
	    array('rows' => 3, 'columns' => 4,
		  'showImageOwner' => 0, 'showAlbumOwner' => 0,
		  'albumFrame' => '', 'itemFrame' => '', 'photoFrame' => '',
		  'colorpack' => '',
		  'showMicroThumbs' => 1,
		  'sidebarBlocks' => serialize(array(
			array('search.SearchBlock', array('showAdvancedLink' => true)),
			array('core.ItemLinks', array('useDropdown' => false)),
			array('core.PeerList', array()),
			array('imageblock.ImageBlock', array()))),
		  'albumBlocks' => serialize(array(
			array('comment.ViewComments', array()))),
		  'photoBlocks' => serialize(array(
			array('exif.ExifInfo', array()),
			array('comment.ViewComments', array())))));
 }

    /**
     * @see GalleryLayout::getSettings()
     */
    function getSettings($itemId=null) {
	list ($ret, $settings) = parent::getSettings($itemId);
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $params) = $this->fetchParameters($itemId);
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Add in our custom settings */
	$params = array_merge(array(
'site' => '', 'email' => '', 'iestatus' => '1',
'expand' => '', 'expandBtn' => '1',
'RC' => '', 'RCoptions' => '1',  'RCalert' => '', 
'sidebar' => '', 'sidebarT' => '20', 'sidebarL' => '10', 
'BreadCrumb' => '0', 
'MenuSeparator'=>'|', 
'link1' => '', 'link1url' => '', 
'link2' => '', 'link2url' => '', 
'showOwnerContact' => '1', 'authorlink1' =>'','authorlink1url' =>'',
'authorlink2' =>'','authorlink2url' =>'',
'columns1st'=>'3', 
'1stTitle' => '', '1stDescription' => '', 'InfoGallery'=>'', 
'Showslideshow'=> '1',
'AlbumBlock'=> '',

'NavAlbumTop'=>'', 'NavAlbumBottom'=>'1',
'opacityT' => '10', 'opacityTover' => '6',
'AlbumTitle'=> '1', 'AlbumDescription'=> '1', 'AlbumInfo'=>'', 
'AlbumTitleTop'=> '1', 'AlbumDescriptionTop'=> '1', 'AlbumInfoTop'=>'', 

'ItemsCenter'=>'',
'ItemsTitle'=> '1', 'ItemsDesc'=>'', 'ItemsInfo'=>'', 

'NavPhotoTop'=>'', 'NavPhotoBottom'=> '1', 
'NavPop'=> '1', 'NavPopEven'=> '', 
'NavOverTop'=> '1', 'NavOverBottom'=> '',
'NavOverPopup'=> '1','NavOverPopupEven'=> '',
'PhotoTitle'=> '1', 'PhotoDescription'=> '1', 'InfoPhoto'=> '1', 
'PhotoTitleTop'=> '', 'PhotoDescriptionTop'=>'', 'InfoPhotoTop'=>'', 
'InfoSize'=> '1', 'InfoSizeEven'=> '', 'PopSize'=> '1', 'PopSizeEven'=> '',
'imageFadin' => '1', 
'MTposition' => '', 'MTends' => '1', 'MTmorepics' => '1', 'MTmore' => '1', 'MTmoretext' => '', 'peerWindowSize' => '20', 'columnsMT' => '4', 'sizeMT' => '30', 'opacityMT' => '4', 'opacityMTover' => '10', 'MToversize' => '4',
'MTbg' => '', 'MTbgborder' => '',

'BlocksAlign'=> '', 'BlocksInAlign'=> '', 'OtherBlocksBtn'=> '1', 
'AuthorActions'=> '1', 'AuthorComments'=>'1',
'AlbumActions'=> '1', 'AddAlbumComments'=>'1', 'AlbumComments'=>'1',
'ItemsActions'=> '1', 'ItemsComments'=>'1', 'ItemsExif' =>'1',
'BtnAfter'=> '1',
'PhotoActions'=> '1', 'AddPhotoComments'=>'1', 'PhotoComments'=>'1',
'PhotoCart'=>'1',  'PhotoExif'=>'1', 
'ActionsText'=>'', 'AddCartText'=>'', 'ExifText'=>'',
'CommentsText'=>'', 'ViewCommentsText'=>'', 'BlocksText'=>'',

'valbtn'=>'1', 'g2btn'=>'1', 'g2verbtn'=>'1', 'pgbtn'=>'1', 'pgcpbtn'=>'1', 'donbtn'=>'1',


'ss' => '1'
), $params);

	$settings[] = array('key' => 'site',
			    'name' => $this->translate('OVERALL :: Your SITE ID. to display on Copyright Messages'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['site']);
	$settings[] = array('key' => 'email',
			    'name' => $this->translate('OVERALL :: Your EMAIL to display a link on footer Copyright Message'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['email']);
	$settings[] = array('key' => 'iestatus',
			    'name' => $this->translate('OVERALL :: Display Your SITE ID. on Window Status Bar (only for IE)'),
			    'type' => 'checkbox',
			    'value' => $params['iestatus']);

	$settings[] = array('key' => 'expand',
			    'name' => $this->translate('OVERALL :: Expand windows to full screen on loading'),
			    'type' => 'checkbox',
			    'value' => $params['expand']);
	$settings[] = array('key' => 'expandBtn',
			    'name' => $this->translate('OVERALL :: Show expand to full screen button'),
			    'type' => 'checkbox',
			    'value' => $params['expandBtn']);


	$settings[] = array('key' => 'RC',
			    'name' => $this->translate('OVERALL :: Right Click - Disable'),
			    'type' => 'checkbox',
			    'value' => $params['RC']);
	$settings[] = array('key' => 'RCoptions',
			    'name' => $this->translate('OVERALL :: Right Click - Show Options'),
			    'type' => 'checkbox',
			    'value' => $params['RCoptions']);
	$settings[] = array('key' => 'RCalert',
			    'name' => $this->translate('OVERALL :: Right Click - Show Alert'),
			    'type' => 'checkbox',
			    'value' => $params['RCalert']);


	$settings[] = array('key' => 'sidebar',
			    'name' => $this->translate('OVERALL :: SideBar - Show Static Sidebar'),
			    'type' => 'checkbox',
			    'value' => $params['sidebar']);
	$settings[] = array('key' => 'sidebarT',
			    'name' => $this->translate('OVERALL :: SideBar - Top position for Floating Sidebar'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['sidebarT']);
	$settings[] = array('key' => 'sidebarL',
			    'name' => $this->translate('OVERALL :: SideBar - Left position for Floating Sidebar'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['sidebarL']);

	$settings[] = array('key' => 'BreadCrumb',
			    'name' => $this->translate('OVERALL :: Top Navigation - Show where the user is (BreadCrumb)'),
			    'type' => 'checkbox',
			    'value' => $params['BreadCrumb']);

	$settings[] = array('key' => 'MenuSeparator',
			    'name' => $this->translate('OVERALL :: Top Navigation - Separation for Menu items (ex. |)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['MenuSeparator']);	
	$settings[] = array('key' => 'link1',
			    'name' => $this->translate('OVERALL :: Top Navigation - Link'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['link1']);
	$settings[] = array('key' => 'link1url',
			    'name' => $this->translate('OVERALL :: Top Navigation - URL for this Link'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['link1url']);
	$settings[] = array('key' => 'link2',
			    'name' => $this->translate('OVERALL :: Top Navigation - Other Link'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['link2']);
	$settings[] = array('key' => 'link2url',
			    'name' => $this->translate('OVERALL :: Top Navigation - URL for this Link'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['link2url']);

	$settings[] = array('key' => 'showOwnerContact',
			    'name' => $this->translate('ALBUM :: 1st Page - Show author welcome message'),
			    'type' => 'checkbox',
			    'value' => $params['showOwnerContact']);
	$settings[] = array('key' => 'authorlink1',
			    'name' => $this->translate('ALBUM :: 1st Page message - 1st link title (on the left)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['authorlink1']);
	$settings[] = array('key' => 'authorlink1url',
			    'name' => $this->translate('ALBUM :: 1st Page message -  1st link tpl file (ex:equipment.tpl)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['authorlink1url']);
	$settings[] = array('key' => 'authorlink2',
			    'name' => $this->translate('ALBUM :: 1st Page message-  2nd link title (on the right)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['authorlink2']);
	$settings[] = array('key' => 'authorlink2url',
			    'name' => $this->translate('ALBUM :: 1st Page message -  2nd link tpl file (ex:portfolio.tpl)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['authorlink2url']);
	$settings[] = array('key' => 'columns1st',
			    'name' => $this->translate('ALBUM :: 1st Page -  Columns per album page'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['columns1st']);
        $settings[] = array('key' => '1stTitle',
			    'name' => $this->translate('ALBUM :: 1st Page - Show Title on 1st page of the Gallery'),
			    'type' => 'checkbox',
			    'value' => $params['1stTitle']);
        $settings[] = array('key' => '1stDescription',
			    'name' => $this->translate('ALBUM :: 1st Page - Show Description on 1st page of the Gallery'),
			    'type' => 'checkbox',
			    'value' => $params['1stDescription']);
        $settings[] = array('key' => 'InfoGallery',
			    'name' => $this->translate('ALBUM :: 1st Page - Show Information on 1st page of the Gallery'),
			    'type' => 'checkbox',
			    'value' => $params['InfoGallery']);

        $settings[] = array('key' => 'AlbumBlock',
			    'name' => $this->translate('ALBUM :: 1st Page - Link Thumbnail directly to photo page (to work must activate "Image Block" Module)'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumBlock']);




        $settings[] = array('key' => 'NavAlbumTop',
			    'name' => $this->translate('ALBUM ::  Navigation - Show pics on the TOP of the Album'),
			    'type' => 'checkbox',
			    'value' => $params['NavAlbumTop']);
        $settings[] = array('key' => 'NavAlbumBottom',
			    'name' => $this->translate('ALBUM ::  Navigation - Show pics on the BOTTOM of the Album'),
			    'type' => 'checkbox',
			    'value' => $params['NavAlbumBottom']);
	$settings[] = array('key' => 'opacityT',
			    'name' => $this->translate('ALBUM :: Set Opacity for Thumbnails (1 to 10)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['opacityT']);
	$settings[] = array('key' => 'opacityTover',
			    'name' => $this->translate('ALBUM :: Set Opacity for Thumbnails on MouseOver (1 to 10)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['opacityTover']);
        $settings[] = array('key' => 'AlbumTitle',
			    'name' => $this->translate('ALBUM :: Show Title'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumTitle']);
        $settings[] = array('key' => 'AlbumTitleTop',
			    'name' => $this->translate('ALBUM :: Show Title above the Thumbnails (by default: side by side)'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumTitleTop']);
        $settings[] = array('key' => 'AlbumDescription',
			    'name' => $this->translate('ALBUM :: Show Description'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumDescription']);
        $settings[] = array('key' => 'AlbumDescriptionTop',
			    'name' => $this->translate('ALBUM :: Show Description above the Thumbnails bellow the Title (by default: side by side)'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumDescriptionTop']);
        $settings[] = array('key' => 'AlbumInfo',
			    'name' => $this->translate('ALBUM :: Show Information'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumInfo']);
        $settings[] = array('key' => 'AlbumInfoTop',
			    'name' => $this->translate('ALBUM :: Show Information above the Thumbnails after Title and Description (by default: side by side)'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumInfoTop']);
        $settings[] = array('key' => 'ItemsCenter',
			    'name' => $this->translate('ALBUM :: Items - Center Items (by default: left aligned)'),
			    'type' => 'checkbox',
			    'value' => $params['ItemsCenter']);
        $settings[] = array('key' => 'ItemsTitle',
			    'name' => $this->translate('ALBUM :: Items - Show Title'),
			    'type' => 'checkbox',
			    'value' => $params['ItemsTitle']);
        $settings[] = array('key' => 'ItemsDesc',
			    'name' => $this->translate('ALBUM :: Items - Show Description'),
			    'type' => 'checkbox',
        		    'value' => $params['ItemsDesc']);
        $settings[] = array('key' => 'ItemsInfo',
			    'name' => $this->translate('ALBUM :: Items - Show Information'),
			    'type' => 'checkbox',
			    'value' => $params['ItemsInfo']);

        
        $settings[] = array('key' => 'NavPhotoTop',
			    'name' => $this->translate('PHOTO :: Navigation - Show pics ABOVE the Photo'),
			    'type' => 'checkbox',
			    'value' => $params['NavPhotoTop']);

        $settings[] = array('key' => 'NavPhotoBottom',
			    'name' => $this->translate('PHOTO :: Navigation - Show pics BELLOW the Photo'),
			    'type' => 'checkbox',
			    'value' => $params['NavPhotoBottom']);

        $settings[] = array('key' => 'NavPop',
			    'name' => $this->translate('PHOTO :: Navigation - Show full size photo POPUP navigation pic'),
			    'type' => 'checkbox',
			    'value' => $params['NavPop']);
        $settings[] = array('key' => 'NavPopEven',
			    'name' => $this->translate('PHOTO :: Navigation - Show POPUP navigation pic even if the current view is the full size image'),
			    'type' => 'checkbox',
			    'value' => $params['NavPopEven']);
        $settings[] = array('key' => 'NavOverTop',
			    'name' => $this->translate('PHOTO :: Navigation (OnMouseOver) - Show navigation pics ABOVE the Photo'),
			    'type' => 'checkbox',
			    'value' => $params['NavOverTop']);
        $settings[] = array('key' => 'NavOverBottom',
			    'name' => $this->translate('PHOTO :: Navigation (OnMouseOver) - Show navigation pics BELLOW the Photo'),
			    'type' => 'checkbox',
			    'value' => $params['NavOverBottom']);
        $settings[] = array('key' => 'NavOverPopup',
			    'name' => $this->translate('PHOTO :: Navigation (OnMouseOver) - Show full size photo POPUP navigation pic'),
			    'type' => 'checkbox',
			    'value' => $params['NavOverPopup']);
        $settings[] = array('key' => 'NavOverPopupEven',
			    'name' => $this->translate('PHOTO :: Navigation (OnMouseOver) - Show POPUP navigation pic even if the current view is the full size image'),
			    'type' => 'checkbox',
			    'value' => $params['NavOverPopupEven']);
        $settings[] = array('key' => 'PhotoTitle',
			    'name' => $this->translate('PHOTO :: Show Title'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoTitle']);
        $settings[] = array('key' => 'PhotoTitleTop',
			    'name' => $this->translate('PHOTO :: Show Title ABOVE the Photo (by default: side by side)'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoTitleTop']);
        $settings[] = array('key' => 'PhotoDescription',
			    'name' => $this->translate('PHOTO :: Show Description'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoDescription']); 
        $settings[] = array('key' => 'PhotoDescriptionTop',
			    'name' => $this->translate('PHOTO :: Show Description ABOVE the Photo bellow the Title (by default: side by side)'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoDescriptionTop']); 
       $settings[] = array('key' => 'InfoPhoto',
			    'name' => $this->translate('PHOTO :: Show Information'),
			    'type' => 'checkbox',
			    'value' => $params['InfoPhoto']);
       $settings[] = array('key' => 'InfoPhotoTop',
			    'name' => $this->translate('PHOTO :: Show Information above the Photo after Title and Description(by default: side by side)'),
			    'type' => 'checkbox',
			    'value' => $params['InfoPhotoTop']);
       $settings[] = array('key' => 'InfoSize',
			    'name' => $this->translate('PHOTO :: Show Size'),
			    'type' => 'checkbox',
			    'value' => $params['InfoSize']);
       $settings[] = array('key' => 'InfoSizeEven',
			    'name' => $this->translate('PHOTO :: Show Size even on full size image'),
			    'type' => 'checkbox',
			    'value' => $params['InfoSizeEven']);
       $settings[] = array('key' => 'PopSize',
			    'name' => $this->translate('PHOTO :: Clicking on full size link will show the full size image on a POPUP Window (by default: Tradicional full size link)'),
			    'type' => 'checkbox',
			    'value' => $params['PopSize']);
        $settings[] = array('key' => 'PopSizeEven',
			    'name' => $this->translate('PHOTO :: Show POPUP link even if the current view is the full size image'),
			    'type' => 'checkbox',
			    'value' => $params['PopSizeEven']);

	$settings[] = array('key' => 'imageFadin',
			    'name' => $this->translate('PHOTO :: Show Photos fading in'),
			    'type' => 'checkbox',
			    'value' => $params['imageFadin']);
       $settings[] = array('key' => 'MTposition',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Show MicroThumbs on the RIGHT of the image (by default: LEFT)'),
			    'type' => 'checkbox',
			    'value' => $params['MTposition']);

        $settings[] = array('key' => 'MTends',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Show allways first and last MicroThumbs'),
			    'type' => 'checkbox',
			    'value' => $params['MTends']);

        $settings[] = array('key' => 'MTmore',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Show navigation bellow MicroThumbs table, when they are not all visible'),
			    'type' => 'checkbox',
			    'value' => $params['MTmore']);
        $settings[] = array('key' => 'MTmorepics',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Show first and last linked pics bellow MicroThumbs table, when they are not all visible'),
			    'type' => 'checkbox',
			    'value' => $params['MTmorepics']);
        $settings[] = array('key' => 'MTmoretext',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Set text to show width "first" and "last" pics or with "<<" ">>"  (ex. : more)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['MTmoretext']);
	$settings[] = array('key' => 'peerWindowSize',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Set (peerWindowSize) number of MicroThumbs diplayed (plus first and last)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
        		    'value' => $params['peerWindowSize']);	
	$settings[] = array('key' => 'columnsMT',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Set number of columns per MicroThumbs'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['columnsMT']);
	$settings[] = array('key' => 'sizeMT',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Set width for MicroThumbs'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['sizeMT']);
	$settings[] = array('key' => 'opacityMT',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Set opacity for MicroThumbs (1 to 10)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['opacityMT']);
	$settings[] = array('key' => 'opacityMTover',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Set opacity for MicroThumbs on MouseOver (1 to 10)'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['opacityMTover']);
	$settings[] = array('key' => 'MToversize',
			    'name' => $this->translate('PHOTO :: MicroThumbs - Set size increment for MicroThumbs on MouseOver'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['MToversize']);

      $settings[] = array('key' => 'BlocksAlign',
			    'name' => $this->translate('BLOCKS :: Position the Blocks Table that displayed the selected Blocks to show on album and photo pages on the tradicional way (choices: left,center,right).'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['BlocksAlign']);
      $settings[] = array('key' => 'BlocksInAlign',
			    'name' => $this->translate('BLOCKS :: Align the Blocks  displayed inside the Blocks Table (choices: left,center,right).'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['BlocksInAlign']);

       $settings[] = array('key' => 'OtherBlocksBtn',
			    'name' => $this->translate('BLOCKS :: Show a "Blocks" button on photo pages (when clicked Blocks selected to show: containing EXIF, Comments,etc, will be displayed)'),
			    'type' => 'checkbox',
			    'value' => $params['OtherBlocksBtn']);

        $settings[] = array('key' => 'AuthorActions',
			    'name' => $this->translate('ACTIONS :: 1st Page - Show "Gallery" actions bellow author welcome message (on webmaster mode)'),
			    'type' => 'checkbox',
			    'value' => $params['AuthorActions']);
        $settings[] = array('key' => 'AuthorComments',
			    'name' => $this->translate('ACTIONS :: 1st Page - Show "Comments" buttons bellow author welcome message'),
			    'type' => 'checkbox',
			    'value' => $params['AuthorComments']);
        $settings[] = array('key' => 'AlbumActions',
			    'name' => $this->translate('ACTIONS :: Album - Show "Albums" actions on  top navigation bar on webmaster mode (for guests show a "Add to Cart" button if module is active)'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumActions']);
       $settings[] = array('key' => 'AddAlbumComments',
			    'name' => $this->translate('ACTIONS :: Album - display a "Add Comments" button'),
			    'type' => 'checkbox',
			    'value' => $params['AddAlbumComments']);
       $settings[] = array('key' => 'AlbumComments',
			    'name' => $this->translate('ACTIONS :: Album - display a "View Comments" button'),
			    'type' => 'checkbox',
			    'value' => $params['AlbumComments']);
       $settings[] = array('key' => 'ItemsActions',
			    'name' => $this->translate('ACTIONS :: Items - Show "Items" actions below thumbnails on webmaster mode (for guests show a "Add to Cart" button if module is active)'),
			    'type' => 'checkbox',
			    'value' => $params['ItemsActions']);
       $settings[] = array('key' => 'ItemsComments',
			    'name' => $this->translate('ACTIONS :: Items - display "Comments" buttons'),
			    'type' => 'checkbox',
			    'value' => $params['ItemsComments']);
       $settings[] = array('key' => 'ItemsExif',
			    'name' => $this->translate('ACTIONS :: Items - display "Show EXIF" button'),
			    'type' => 'checkbox',
			    'value' => $params['ItemsExif']);
       $settings[] = array('key' => 'BtnAfter',
			    'name' => $this->translate('ACTIONS :: Items - display "Buttons" bellow Title, Description and Information. (by default: between Thumbnail and Title)'),
			    'type' => 'checkbox',
			    'value' => $params['BtnAfter']);



       $settings[] = array('key' => 'PhotoActions',
			    'name' => $this->translate('ACTIONS :: Photo - Show "Photo" actions (on webmaster mode)'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoActions']);
       $settings[] = array('key' => 'AddPhotoComments',
			    'name' => $this->translate('ACTIONS :: Photo - display a "Add Comments" button'),
			    'type' => 'checkbox',
			    'value' => $params['AddPhotoComments']);
       $settings[] = array('key' => 'PhotoComments',
			    'name' => $this->translate('ACTIONS :: Photo - display a "View Comments" button'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoComments']);
       $settings[] = array('key' => 'PhotoCart',
			    'name' => $this->translate('ACTIONS :: Photo - display a "Add to Cart" button'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoCart']);
       $settings[] = array('key' => 'PhotoExif',
			    'name' => $this->translate('ACTIONS :: Photo - display a "Show EXIF" button (only at Photo page)'),
			    'type' => 'checkbox',
			    'value' => $params['PhotoExif']);

        $settings[] = array('key' => 'AddCartText',
			    'name' => $this->translate('ACTIONS :: Set text and title to show on "Add to Cart" button'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['AddCartText']);
        $settings[] = array('key' => 'ExifText',
			    'name' => $this->translate('ACTIONS :: Set text and title to show on "Show EXIF" button'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['ExifText']);

        $settings[] = array('key' => 'CommentsText',
			    'name' => $this->translate('ACTIONS :: Set text and title to show on "Add comment" button'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['CommentsText']);
        $settings[] = array('key' => 'ViewCommentsText',
			    'name' => $this->translate('ACTIONS :: Set text and title to show on "View comments" button'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['ViewCommentsText']);
        $settings[] = array('key' => 'BlocksText',
			    'name' => $this->translate('ACTIONS :: Set text and title to show on "Blocks" button'),
			    'type' => 'text-field',
			    'type-params' => array('size' => 2),
			    'value' => $params['BlocksText']);
        $settings[] = array('key' => 'ActionsText',
			    'name' => $this->translate('ACTIONS :: Show text after "Actions" buttons'),
			    'type' => 'checkbox',
			    'value' => $params['ActionsText']);


	$settings[] = array('key' => 'Showslideshow',
			    'name' => $this->translate('SLIDESHOW :: If module is active display a SlideShow navigation pic'),
			    'type' => 'checkbox',
			    'value' => $params['Showslideshow']);
	$settings[] = array('key' => 'ss',
			    'name' => $this->translate('SLIDESHOW :: Use PGtheme on SlideShow if is the selected theme'),
			    'type' => 'checkbox',
			    'type-params' => array('size' => 2),
			    'value' => $params['ss']);

	$settings[] = array('key' => 'valbtn',
			    'name' => $this->translate('FOOTER :: Show "XHTML 1.0 (validation)" button'),
			    'type' => 'checkbox',
			    'type-params' => array('size' => 2),
			    'value' => $params['valbtn']);
	$settings[] = array('key' => 'g2btn',
			    'name' => $this->translate('FOOTER :: Show "GALLERY 2" button'),
  		            'type' => 'checkbox',
			    'type-params' => array('size' => 2),
			    'value' => $params['g2btn']);
	$settings[] = array('key' => 'g2verbtn',
			    'name' => $this->translate('FOOTER :: Show "G2 Version" button'),
			    'type' => 'checkbox',
			    'type-params' => array('size' => 2),
			    'value' => $params['g2verbtn']);
	$settings[] = array('key' => 'pgbtn',
			    'name' => $this->translate('FOOTER :: Show "PG THEME" button'),
			    'type' => 'checkbox',
			    'type-params' => array('size' => 2),
			    'value' => $params['pgbtn']);
	$settings[] = array('key' => 'pgcpbtn',
			    'name' => $this->translate('FOOTER :: Show "PG ColorPack" button'),
			    'type' => 'checkbox',
			    'type-params' => array('size' => 2),
			    'value' => $params['pgcpbtn']);
	$settings[] = array('key' => 'donbtn',
			    'name' => $this->translate('FOOTER :: Show "Donate" button'),
			    'type' => 'checkbox',
			    'type-params' => array('size' => 2),
			    'value' => $params['donbtn']);


		return array(GalleryStatus::success(), $settings);
    }

    /**
     * @see GalleryTheme::showAlbumPage
     */
    function showAlbumPage(&$template, $item, $params, $childIds) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params, 
array('owner', 'viewCount', 'childCount', 'descendentCount', 'parents', 'peers',  'systemLinks', 'itemLinks', 'itemSummaries', 'permissions', 'thumbnails', 'pageNavigator', 'jumpRange'),
	     $childIds);
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Add in our extra stuff */
	$theme =& $template->getVariableByReference('theme');
	$theme['columnWidthPct'] = floor(100 / $params['columns']);

   list ($ret, $pluginStatus) = GalleryCoreApi::fetchPluginStatus('module');
   if ($ret->isError()) {
       return array($ret->wrap(__FILE__, __LINE__), null);
   }

   if (!empty($pluginStatus['slideshow']['active'])) {
       $theme['slideshow'] = 1;
   } else {
      $theme['slideshow'] =0; 
   } 

   if (!empty($pluginStatus['cart']['active'])) {
       $theme['cart'] = 1;
   } else {
      $theme['cart'] =0; 
   } 

   if (!empty($pluginStatus['comment']['active'])) {
       $theme['comments'] = 1;
   } else {
      $theme['comments'] =0; 
   } 

   if (!empty($pluginStatus['exif']['active'])) {
       $theme['exif'] = 1;
   } else {
      $theme['exif'] =0; 
   } 

   if (!empty($pluginStatus['imageblock']['active'])) {
       $theme['imageblock'] = 1;
   } else {
      $theme['imageblock'] =0; 
   } 

   if (!empty($pluginStatus['register']['active'])) {
       $theme['register'] = 1;
   } else {
      $theme['register'] =0; 
   } 
	return array(GalleryStatus::success(), 'theme.tpl');
   }

    /**
     * @see GalleryTheme::showPhotoPage
     */
    function showPhotoPage(&$template, $item, $params) {
	$dataTypes = array('owner', 'viewCount', 'parents', 'peers', 'systemLinks', 'itemLinks', 'permissions', 'itemLinksDetailed', 'itemNavigator', 'imageViews');
	if (!empty($params['showMicroThumbs'])) {
	    $dataTypes[] = 'navThumbnails';
	}
	$ret = $this->loadCommonTemplateData($template, $item, $params, $dataTypes);
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

   list ($ret, $pluginStatus) = GalleryCoreApi::fetchPluginStatus('module');
   if ($ret->isError()) {
       return array($ret->wrap(__FILE__, __LINE__), null);
   }

$theme =& $template->getVariableByReference('theme');

   if (!empty($pluginStatus['slideshow']['active'])) {
       $theme['slideshow'] = 1;
   } else {
      $theme['slideshow'] =0; 
   } 

   if (!empty($pluginStatus['cart']['active'])) {
       $theme['cart'] = 1;
   } else {
      $theme['cart'] =0; 
   } 

   if (!empty($pluginStatus['comment']['active'])) {
       $theme['comments'] = 1;
   } else {
      $theme['comments'] =0; 
   } 

   if (!empty($pluginStatus['exif']['active'])) {
       $theme['exif'] = 1;
   } else {
      $theme['exif'] =0; 
   }

   if (!empty($pluginStatus['register']['active'])) {
       $theme['register'] = 1;
   } else {
      $theme['register'] =0; 
   } 


	return array(GalleryStatus::success(), 'theme.tpl');
   }


    /**
     * @see GalleryTheme::showModulePage
     */
    function showModulePage(&$template, $item, $params, $templateFile) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params, array('parents', 'systemLinks'));
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(GalleryStatus::success(), 'theme.tpl');
    }

    /**
     * @see GalleryTheme::showAdminPage
     */
    function showAdminPage(&$template, $item, $params, $templateFile) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params, array('parents', 'systemLinks'));
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(GalleryStatus::success(), 'theme.tpl');
    }

    /**
     * @see GalleryTheme::showErrorPage
     */
    function showErrorPage(&$template) {
	return array(GalleryStatus::success(), 'error.tpl');
    }

    /**
     * @see GalleryTheme::showProgressBarPage
     */
    function showProgressBarPage(&$template, $item, $params) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params, array('parents', 'systemLinks'));
	if ($ret->isError()) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(GalleryStatus::success(), 'theme.tpl');
    }

    /**
     * @see GalleryTheme::upgrade
     */
    function upgrade($installedVersion) {
	if (version_compare($installedVersion, '0.9.7', '<')) {
	    /* Older than the big theme changeover.  Set some default blocks */
	    foreach (array(
			 'sidebarBlocks' => serialize(
			     array(
				 array('search.SearchBlock', array('showAdvancedLink' => true)),
				 array('core.ItemLinks', array('useDropdown' => false)),
				 array('core.PeerList', array()),
				 array('imageblock.ImageBlock', array()))),
			 'albumBlocks' => serialize(
			     array(array('comment.ViewComments', array()))),
			 'photoBlocks' => serialize(
			     array(
				 array('exif.ExifInfo', array()),
				 array('comment.ViewComments', array()))))
		     as $key => $value) {
		$ret = $this->setParameter($key, $value);
		if ($ret->isError()) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	    $installedVersion = '0.9.7';
	}

	switch ($installedVersion) {
	case '0.9.7':
	    $ret = $this->setParameter('colorpack', '');
	    if ($ret->isError()) {
		return $ret->wrap(__FILE__, __LINE__);
	    }


        case '1.0.RC2':
	    /* We now require theme API 2.1 */

        case '1.0.RC3':
	    /* We now require theme API 2.1 */

        case '1.0.RC4':
	    /* We now require theme API 2.1 */

        case '1.0.RC5':
	    /* We now require theme API 2.1 */

        case '1.0.RC6':
	    /* We now require theme API 2.1 */

	case 'end of upgrade path':
	    break;

	default:
	    return GalleryStatus::error(ERROR_BAD_PLUGIN, __FILE__, __LINE__,
					sprintf('Unknown theme version %s', $installedVersion));
	}

	return GalleryStatus::success();
    }
}
?>
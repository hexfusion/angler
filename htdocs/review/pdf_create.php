<?php
//include ("body.php");
//include ("config.php");

//Display the header
//BodyHeader("$sitename PDF File Creation","pdf","");

require_once('./pdf/pipeline.factory.class.php');
parse_config_file('./pdf/html2ps.config');

$url = clean($_SERVER['HTTP_REFERER']);

echo "$url is url"; exit;
global $g_config, $url;
$g_config = array(
                  'cssmedia'     => 'screen',
                  'renderimages' => true,
                  'renderforms'  => false,
                  'renderlinks'  => true,
                  'mode'         => 'html',
                  'debugbox'     => false,
                  'draw_page_border' => false
                  );

$media = Media::predefined('A4');
$media->set_landscape(false);
$media->set_margins(array('left'   => 0,
                          'right'  => 0,
                          'top'    => 0,
                          'bottom' => 0));
$media->set_pixels(1024);

global $g_px_scale;
$g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;

global $g_pt_scale;
$g_pt_scale = $g_px_scale * 1.43; 

$pipeline = PipelineFactory::create_default_pipeline("","");
$pipeline->process("$url", $media); 

//BodyFooter();
//exit;
?>


<?php

/**
 * Debugging can be done by adding &html=1 to the end of the URL when viewing the PDF
 * We no longer need to access the file directly.
 */ 
if(!class_exists('FPPDF_Core') ) {
	/* Accessed directly */
    exit;
}

/** 
 * Set up the form ID and lead ID, as well as we want page breaks displayed. 
 * Form ID and Lead ID can be set by passing it to the URL - ?fid=1&lid=10
 */
 FPPDF_Common::setup_ids();

 $stylesheet_location = (file_exists(FP_PDF_TEMPLATE_LOCATION.'template.css')) ? FP_PDF_TEMPLATE_URL_LOCATION.'template.css' : FP_PDF_PLUGIN_URL .'styles/template.css' ;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>    
    <title>Formidable Pro PDF Extended</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
</head>
	<body>
        <?php	

        foreach($lead_ids as $lead_id) {
			$fields = FPPDF_Common::get_form_fields($form_id, $lead_id);						
			
			$form_data = FPPDF_Entry::show_entry(array(
                'id' => $lead_id, 
				'fields' => $fields, 
                'user_info' => false,
				'type' => 'array'		
            ));						
        
			/*
			 * Add &data=1 when viewing the PDF via the admin area to view the $form_data array
			 */
			FPPDF_Common::view_data($form_data);		
			
			?>	<img src="<?php echo FP_PDF_PLUGIN_DIR; ?>/images/formidablepro-logo.jpg" width="311" height="66"  /> 
            
            <style>
            table { border-collapse: collapse; margin-top: 0; text-align: center; }
            td { padding: 0.5em; }
            h1 { margin-bottom: 0; }
            </style>
            <h1>mPDF Images</h1>
            
            <table>
            <tr>
            <td>GIF</td>
            <td>JPG</td>
            <td>JPG (CMYK)</td>
            <td>PNG</td>
            <td>BMP</td>
            <td>WMF</td>
            <td>SVG</td>
            </tr>
            <tr>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.gif" width="80" /></td>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.jpg" width="80" /></td>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tigercmyk.jpg" width="80" /></td>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.png" width="80" /></td>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.bmp" width="80" /></td>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" width="80" /></td>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" width="80" /></td>
            </tr>
            </tr>
            <tr>
            <td colspan="7" style="text-align: left" ><h4>Opacity 50%</h4></td>
            </tr>
            <tr>
            <tr>
            <td><img style="vertical-align: top; opacity: 0.5" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.gif" width="80" /></td>
            <td><img style="vertical-align: top; opacity: 0.5" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.jpg" width="80" /></td>
            <td><img style="vertical-align: top; opacity: 0.5" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tigercmyk.jpg" width="80" /></td>
            <td><img style="vertical-align: top; opacity: 0.5" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.png" width="80" /></td>
            <td><img style="vertical-align: top; opacity: 0.5" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.bmp" width="80" /></td>
            <td><img style="vertical-align: top; opacity: 0.5" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" width="80" /></td>
            <td><img style="vertical-align: top; opacity: 0.5" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" width="80" /></td>
            </tr>
            </table>
            
            <h4>Alpha channel</h4>
            <table>
            <tr>
            <td>PNG</td>
            <td><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/alpha.png" width="85" /></td>
            <td style="background-color:#FFCCFF; "><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/alpha.png" width="85" /></td>
            <td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/alpha.png" width="85" /></td>
            <td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/alpha.png" width="85" /></td>
            <td style="background-color:#CCFFFF; background: transparent url('<?php echo FP_PDF_PLUGIN_DIR; ?>images/bg.jpg') repeat scroll right top;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/alpha.png" width="85" /></td>
            </tr>
            </table>
            <h4>Transparency</h4>
            <table><tr>
            <td>PNG</td>
            <td style="background-color:#FFCCFF; "><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger24trns.png" width="85" /></td>
            <td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger24trns.png" width="85" /></td>
            <td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger24trns.png" width="85" /></td>
            <td style="background-color:#CCFFFF; background: transparent url('<?php echo FP_PDF_PLUGIN_DIR; ?>images/bg.jpg') repeat scroll right top;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger24trns.png" width="85" /></td>
            </tr><tr>
            <td>GIF</td>
            <td style="background-color:#FFCCFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger8trns.gif" width="85" /></td>
            <td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger8trns.gif" width="85" /></td>
            <td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger8trns.gif" width="85" /></td>
            <td style="background-color:#CCFFFF; background: transparent url('<?php echo FP_PDF_PLUGIN_DIR; ?>images/bg.jpg') repeat scroll right top;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger8trns.gif" width="85" /></td>
            </tr><tr>
            <td>WMF</td>
            <td style="background-color:#FFCCFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" width="85" /></td>
            <td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" width="85" /></td>
            <td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" width="85" /></td>
            <td style="background-color:#CCFFFF; background: transparent url('<?php echo FP_PDF_PLUGIN_DIR; ?>images/bg.jpg') repeat scroll right top;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" width="85" /></td>
            </tr><tr>
            <td>SVG</td>
            <td style="background-color:#FFCCFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" width="85" /></td>
            <td style="background-color:#FFFFCC;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" width="85" /></td>
            <td style="background-color:#CCFFFF;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" width="85" /></td>
            <td style="background-color:#CCFFFF; background: transparent url('<?php echo FP_PDF_PLUGIN_DIR; ?>images/bg.jpg') repeat scroll right top;"><img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" width="85" /></td>
            </tr></table>
            
            
            Images returned from tiger.php
            <div>
            GIF <img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_URL; ?>images/tiger.gif" width="85" />
            JPG <img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_URL; ?>images/tiger.jpg" width="85" />
            PNG <img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_URL; ?>images/tiger.png" width="85" />
            WMF <img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_URL; ?>images/tiger.wmf" width="85" />
            SVG <img style="vertical-align: top" src="<?php echo FP_PDF_PLUGIN_URL; ?>images/tiger.svg" width="85" />
            </div>
            
            <pagebreak />
            
            
            <h3>Image Alignment</h3>
            <div>From mPDF version 4.2 onwards, in-line images can be individually aligned (vertically). Most of the values for "vertical-align" are supported: top, bottom, middle, baseline, text-top, and text-bottom. The default value for vertical alignment has been changed to baseline, and the default padding to 0, consistent with most browsers.
            </div>
            <br />
            
            <div style="background-color:#CCFFFF;">
            These images <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img1.png" style="vertical-align: top;" />
            are <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img2.png" style="vertical-align: top;" />
            <b>top</b> <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img3.png" style="vertical-align: top;" />
            aligned <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img4.png" style="vertical-align: middle;" />
            </div>
            <br />
            
            <div style="background-color:#CCFFFF;">
            These images <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img1.png" style="vertical-align: text-top;" />
            are <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img2.png" style="vertical-align: text-top;" />
            <b>text-top</b> <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img3.png" style="vertical-align: text-top;" />
            aligned <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img4.png" style="vertical-align: middle;" />
            </div>
            <br />
            
            <div style="background-color:#CCFFFF;">
            These images <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img1.png" style="vertical-align: bottom;" />
            are <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img2.png" style="vertical-align: bottom;" />
            <b>bottom</b> <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img3.png" style="vertical-align: bottom;" />
            aligned <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img4.png" style="vertical-align: middle;" />
            </div>
            <br />
            
            <div style="background-color:#CCFFFF;">
            These images <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img1.png" style="vertical-align: text-bottom;" />
            are <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img2.png" style="vertical-align: text-bottom;" />
            <b>text-bottom</b> <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img3.png" style="vertical-align: text-bottom;" />
            aligned <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img4.png" style="vertical-align: middle;" />
            </div>
            <br />
            
            <div style="background-color:#CCFFFF;">
            These images <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img1.png" style="vertical-align: baseline;" />
            are <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img2.png" style="vertical-align: baseline;" />
            <b>baseline</b> <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img3.png" style="vertical-align: baseline;" />
            aligned <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img4.png" style="vertical-align: middle;" />
            </div>
            <br />
            
            <div style="background-color:#CCFFFF;">
            These images <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img1.png" style="vertical-align: middle;" />
            are <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img2.png" style="vertical-align: middle;" />
            <b>middle</b> <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img3.png" style="vertical-align: middle;" />
            aligned <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/img5.png" style="vertical-align: bottom;" />
            </div>
            <br />
            
            <h4>Mixed alignment</h4>
            <div style="background-color:#CCFFFF;">
            baseline: <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/sunset.jpg" width="50" style="vertical-align: baseline;" />
            text-bottom: <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/sunset.jpg" width="30" style="vertical-align: text-bottom;" />
            middle: <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/sunset.jpg" width="30" style="vertical-align: middle;" />
            bottom: <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/sunset.jpg" width="80" style="vertical-align: bottom;" />
            text-top: <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/sunset.jpg" width="50" style="vertical-align: text-top;" />
            top: <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/sunset.jpg" width="100" style="vertical-align: top;" />
            </div>
            
            <h3>Image Border and padding</h3>
            From mPDF v4.2, Image padding is supported as well as border and margin.
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/sunset.jpg" width="100" style="border:3px solid #44FF44; padding: 1em;" />
            
            <h3>Rotated Images</h3>
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.png" width="100" /> 
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.png" rotate="90" width="100" /> 
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.png" rotate="180" width="100" /> 
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.png" rotate="-90" width="100" /> 
            <br />
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.jpg" width="100" /> 
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.jpg" rotate="90" width="100" /> 
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.jpg" rotate="180" width="100" /> 
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.jpg" rotate="-90" width="100" /> 
            <br />
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" width="80" /> &nbsp; &nbsp; &nbsp;
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" rotate="90" width="80" /> &nbsp; &nbsp; &nbsp;
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" rotate="180" width="80" /> &nbsp; &nbsp; &nbsp;
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger2.wmf" rotate="-90" width="80" />
            <br />
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" width="100" />&nbsp;
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" rotate="90" width="85" />&nbsp;
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" rotate="180" width="100" />&nbsp;
            <img src="<?php echo FP_PDF_PLUGIN_DIR; ?>images/tiger.svg" rotate="-90" width="85" /> 
            <br />
            
         
            <?php
        }

        ?>
	</body>
</html>
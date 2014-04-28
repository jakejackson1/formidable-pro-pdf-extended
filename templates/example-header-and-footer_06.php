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
			
			?>             
           
    		<!-- defines the headers/footers - this must occur before the headers/footers are set -->

            <!--mpdf
            
            <htmlpageheader name="myHTMLHeader1">
            <table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
            <td width="50%">Left header p <span style="font-size:14pt;">{PAGENO}</span></td>
            <td width="50%" style="text-align: right;"><span style="font-weight: bold;">myHTMLHeader1</span></td>
            </tr></table>
            </htmlpageheader>
            
            <htmlpagefooter name="myFooter1">
            <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt;
                color: #000000; font-weight: bold; font-style: italic;"><tr>
                <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">My document</td>
                </tr></table>
            </htmlpagefooter>
            
            mpdf-->
            
            <!-- set the headers/footers - they will occur from here on in the document -->
            <!--mpdf
            <sethtmlpageheader name="myHTMLHeader1" value="on" show-this-page="1" />
            <sethtmlpagefooter name="myFooter1" value="on" show-this-page="1" />
            mpdf-->

           
            <div>
            
            	<img src="<?php echo FP_PDF_PLUGIN_DIR; ?>/images/formidablepro-logo.jpg" width="311" height="66"  /> 
                
                <h2>Basic Headers</h2>
                    
                <p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
                <pagebreak />
                
                <h2>Basic Headers</h2>
                <p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
                
                <!-- Note the html_ prefix when referencing an HTML header using one of the pagebreaks -->
                <pagebreak />
                
                <h2>Basic Headers</h2>
                <p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
            </div>
            <?php
        }

        ?>
	</body>
</html>
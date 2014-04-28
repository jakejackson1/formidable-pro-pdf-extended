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
 
 global $fppdf;
 $configuration_data = $fppdf->get_config_data($form_id);
 
 $show_html_fields = (isset($configuration_data['default-show-html']) && $configuration_data['default-show-html'] == 1) ? true : false;
 $show_empty_fields = (isset($configuration_data['default-show-empty']) && $configuration_data['default-show-empty'] == 1) ? true : false; 

 $stylesheet_location = (file_exists(FP_PDF_TEMPLATE_LOCATION.'template.css')) ? FP_PDF_TEMPLATE_URL_LOCATION.'template.css' : FP_PDF_PLUGIN_URL .'styles/template.css' ;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <link rel='stylesheet' href='<?php echo $stylesheet_location; ?>' type='text/css' />
    <title>Formidable Pro PDF Extended</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
	<body>   
 
        <?php
		
        foreach($lead_ids as $lead_id) {
			$fields = FPPDF_Common::get_form_fields($form_id, $lead_id);								
			
			echo FPPDF_Entry::show_entry(array(
                'id' => $lead_id, 
				'fields' => $fields, 
                'user_info' => false,
				'include_blank' => $show_empty_fields,
				'show_html' => $show_html_fields			
            ));						
			
        }

        ?>
	</body>
</html>
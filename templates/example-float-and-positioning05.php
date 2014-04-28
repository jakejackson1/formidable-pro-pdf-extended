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
		.gradient {
			border:0.1mm solid #220044; 
			background-color: #f0f2ff;
			background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
		}
		h4 {
			font-family: sans;
			font-weight: bold;
			margin-top: 1em;
			margin-bottom: 0.5em;
		}
		div {
			padding:1em; 
			margin-bottom: 1em;
			text-align:justify; 
		}
		.myfixed1 { position: absolute; 
			overflow: visible; 
			left: 0; 
			bottom: 0; 
			border: 1px solid #880000; 
			background-color: #FFEEDD; 
			background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;  
			padding: 1.5em; 
			font-family:sans; 
			margin: 0;
		}
		.myfixed2 { position: fixed; 
			overflow: auto; 
			right: 0;
			bottom: 0mm; 
			width: 65mm; 
			border: 1px solid #880000; 
			background-color: #FFEEDD; 
			background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;  
			padding: 0.5em; 
			font-family:sans; 
			margin: 0;
			rotate: 90;
		}
		</style>
		
		<h2>Floating & Fixed Position elements</h2>
		
		<h4>CSS "Float"</h4>
		<div class="gradient">
		Block elements can be positioned alongside each other using the CSS property float: left or right. The clear property can also be used, set as left|right|both. Float is only supported on block elements (i.e. not SPAN etc.) and is not fully compliant with the CSS specification. 
		Float only works properly if a width is set for the float, otherwise the width is set to the maximum available (full width, or less if floats already set).
		<br />
		Margin-right can still be set for a float:right and vice-versa.
		<br />
		A block element next to a float has the padding adjusted so that content fits in the remaining width. Text next to a float should wrap correctly, but backgrounds and borders will overlap and/or lie under the floats in a mess.
		<br />
		NB The width that is set defines the width of the content-box. So if you have two floats with width=50% and either of them has padding, margin or border, they will not fit together on the page.
		</div>
		
		<div class="gradient" style="float: right; width: 28%; margin-bottom: 0pt; ">
		<img src="<?php echo FP_PDF_PLUGIN_DIR; ?>/images/tiger.wmf" style="float:right" width="70" />This is text in a &lt;div&gt; element that is set to float:right and width:28%. It also has an image with float:right inside. With this exception, you cannot nest elements with the float property set inside one another.
		</div>
		<div class="gradient" style="float: left; width: 54%; margin-bottom: 0pt; ">
		This is text in a &lt;div&gt; element that is set to float:left and width:54%.
		</div>
		
		<div style="clear: both; margin: 0pt; padding: 0pt; "></div>
		This is text that follows a &lt;div&gt; element that is set to clear:both.
		
		<h4>CSS "Position"</h4>
		At the bottom of the page are two DIV elements with position:fixed and position:absolute set
		
		<div class="myfixed1">1 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.</div>
		
		<div class="myfixed2">2 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.</div>
		 <pagebreak />
		 
		
			 <div style="float: left; width: 29%; background: green">
				 These are all floated left. 1 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
			 </div>
			 <div style="float: left; width: 29%; background: grey">
				2 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
			 </div>
			 <div style="float: left; width: 29%; background: black; color: white;">
				 3 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
			 </div>  
		
		  
		  <div style="clear: both">
			<div style="float: left; width: 20%; background: #EEE;">
			  Floated left with a width of 20%. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
			</div>
			
			<div style="float: right; width: 30%; background: #222222; color: #FFF">
			  Floated right with a width of 30%. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
			</div>    
		  </div>
		  
		  <pagebreak />
		  
			<div style="float: left; width: 20%; background: #EEE;">
			  Floated left with a width of 20%. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
			</div>
			
			<div style="margin-left: 25%">
			  By default this element will be positioned inline with the float. The start of the element should be inline with the top of the float. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
			</div> 
 
            
         
            <?php
        }

        ?>
	</body>
</html>
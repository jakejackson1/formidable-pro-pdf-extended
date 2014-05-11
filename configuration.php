<?php

/* 
 * Formidable Pro PDF Extended Configuration
 */
 
/*
 * Users can assign defaults to forms that aren't configured below. 
 * Note: this will only work if the configuration option FPPDF_SET_DEFAULT_TEMPLATE is set to true (located at the bottom of this file).
 * 
 * Users can use any configuration option like you would for a singular form, including:
 * notifications, template, filename, pdf_size, orientation, security and rtl
 */
 global $fp_pdf_default_configuration;
 
 $fp_pdf_default_configuration = array(
 	'template' => 'default-template.php',
	'pdf_size' => 'A4',	
 ); 
 
 /*
  * ------------------------------------------------------------ 
  * Bare minimum configuration code
  * Usage: Will generate PDF and send to all notifications
  * Remove the comments around the code blocks below to use (/*) 
  * form_id Mixed - Integer or Array. Required. The Formidable Pro ID you are assigning the PDF to.
  * notifications Mixed - Integer, Boolean or Array. 
  *
  * Remove the comments around the code blocks below to use (/*)   
  */
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
	  'notifications' => true,
  );*/
  
 /*
  * ------------------------------------------------------------ 
  * Default template specific configuration code
  * 
  * Usage:
  * 'default-show-html' - This option will display HTMl blocks in your default tempalte. 
  * 'default-show-empty' - All form fields will be displayed in the PDF, regardless of what the user input is.
  *
  */
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
	  'template' => 'default-template.php',		
	  'default-show-html' => true,
  );*/  
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
	  'template' => 'default-template.php',	
	  'default-show-empty' => true,
  );*/   
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
	  'template' => 'default-template.php',		
	  'default-show-html' => true,
	  'default-show-empty' => true,
  );*/   
  
 /*
  * ------------------------------------------------------------ 
  * Notification Options
  * notifications Mixed - String, Boolean or Array.   
  * Notifications can be a string like 'Admin Notifications', an array with multiple notification names or true to send to all.
  * Notification IDs can be found to the very right of the From/Reply to field in the 'Emails' form setting section ( Forms -> Settings -> Emails )
  * See http://formidablepropdfextended.com/faq/find-notification-id/ for a screenshot of the notification ID.
  */
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
	  'notifications' => 1, 
  );*/ 
  
  /*$fp_pdf_config[] = array(
  	'form_id' => '1', 
	  'notifications' => array(1,3,5), 
  );*/  
  
 /*
  * ------------------------------------------------------------ 
  * Custom Template
  * Don't want to use a default template? Just pass the custom template name to the configuration.
  *
  * template String. Default default-template.php. The name of your custom template that's placed in your active theme's FORMIDABLE_PDF_TEMPLATES folder. 
  *
  * For more information about creating custom templates please see http://formidablepropdfextended.com/documentation-v1/templates/
  */
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
	  'notifications' => 1,
		
	  'template' => 'example-float-and-positioning05.php', 
  );*/  
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 2, 
	  'notifications' => 1,
	
	  'template' => 'example-basic-html01.php', 
  );*/    
  
 /*
  * ------------------------------------------------------------ 
  * Multiple Forms
  * If you have multiple forms that use the same PDF template then you can pass the form_id through as an array.
  * WARNING: If using a custom template with this option, your secondary forms should be a duplicate of the original and you shouldn't delete any fields
  *          otherwise the custom template won't show correctly. 
  */
  
  /*$fp_pdf_config[] = array(
  	'form_id' => array(1,5,6), 
	  'notifications' => true,
  );*/
    
 /*
  * ------------------------------------------------------------ 
  * Custom File Name
  * Will change the filename of the PDF which is attached
  * You are also able to use field ID/Keys in filename ([sitename], [ip], [id], [key], [20], [ltzq9])
  */    
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
  	'notifications' => true, 
  	'filename' => 'New PDF Name.pdf', 
  );*/ 
  
  /*$fp_pdf_config[] = array(
  	'form_id' => 1, 
  	'notifications' => true, 
  	'filename' => 'New PDF Name [id].pdf' ,
  );*/   
 
 /*
  * ------------------------------------------------------------
  * Custom PDF Size / Orientation
  * PDF Size can be set to the following:
  *
  *	A0 - A10, B0 - B10, C0 - C10
  *	4A0, 2A0, RA0 - RA4, SRA0 - SRA4
  *	Letter, Legal, Executive, Folio
  *	Demy, Royal  
  *
  * Default: A4
  * You can also pass the PDF size as an array, represented in millimetres - array(width, height).
  * 
  * NOTE: By default the orientation is portrait so you only need to add it for landscape PDFs
  */ 
  
  /* Letter-sized Document */
  /*$fp_pdf_config[] = array(
  	'form_id' => 1,  
  	'notifications' => true,
  		
  	'pdf_size' => 'letter',
  );*/
  
  /* Custom PDF Size */
 /* $fp_pdf_config[] = array(
  	'form_id' => 1, 
  	'attachments' => true,	
  	
  	'pdf_size' => array(50, 200),
  );*/  
  
  /* Change orientation */
 /* $fp_pdf_config[] = array(
  	'form_id' => 1, 
  	'notifications' => true,	
  	
  	'pdf_size' => 'letter',
  	'orientation' => 'landscape',
  );*/  

 /*
  * ------------------------------------------------------------
  * PDF Security
  * Allows you to password protect your PDF document, place a master password on the document which prevents document tampering and restricts user behaviour. 
  *
  * security Boolean. Default false. If true the security settings will be applied.
  * pdf_password String. Default blank.
  * pdf_privileges Array
  * Assign user privileges to the document. Valid privileges include: copy, print, modify, annot-forms, fill-forms, extract, assemble, print-highres
  * pdf_master_password String. Default random generated. Set a master password on the PDF which stops the PDF from being modified.
  * NOTE: As the document is encrypted in 128-bit print will only allow users to print a low resolution copy.
  *       Use print-highres to print full resolution image.  
  * NOTE: The use of print will only allow low-resolution printing from the document; you must specify print-highres to allow full resolution printing.
  * NOTE: If pdf_master_password is omitted a random one will be generated
  * NOTE: Passing a blank array or not passing anything to pdf_privileges will deny all permissions to the user
  */	  
 
 /*
  * Setting security settings with all values
  */  
  
  /*$fp_pdf_config[] = array(
   	'form_id' => 1,
  	'notifications' => true,	 
  	'filename' => 'Test.pdf',
  	
  	'orientation' => 'landscape',
  	 
  	'security' => true, 
  	'pdf_password' => 'myPDFpass', 	
  	'pdf_privileges' => array('copy', 'print', 'modify', 'annot-forms', 'fill-forms', 'extract', 'assemble', 'print-highres'), 	
  	'pdf_master_password' => 'admin password',
  );*/
  
  /*$fp_pdf_config[] = array(
   	'form_id' => 1,
  	'notifications' => true,	 
  	'filename' => 'Test2.pdf',
  	'template' => 'example-template.php',
  	
  	'orientation' => 'landscape',
  	 
  	'security' => true, 
  	'pdf_password' => 'myPDFpass', 	
  	'pdf_privileges' => array('copy', 'print', 'modify', 'annot-forms', 'fill-forms', 'extract', 'assemble', 'print-highres'), 	
  	'pdf_master_password' => 'admin password',
  );*/
 
  /*
   * Set password to PDF.
   * Deny all permissions to user
   * Random master password will be generated 
   */
   /*$fp_pdf_config[] = array(
   	'form_id' => 1,
  	'notifications' => true,	 
  	 
  	'security' => true, 
  	'pdf_password' => 'myPDFpass',
   );*/
  
  /*
   * No password required to open PDF document.
   * Deny all permissions to user
   * Master password set
   */  
   /*$fp_pdf_config[] = array(
   	'form_id' => 1,
  	'notifications' => true,	 
  	 
  	'security' => true, 
  	'pdf_master_password' => 'admin password', 
   );*/   
  
  /*
   * No password required to open PDF document.
   * User can copy, print and modify PDF
   * Random master password will be generated 
   *
   */  
   /*$fp_pdf_config[] = array(
   	'form_id' => 1,
  	'notifications' => true,	 
  	 
  	'security' => true, 
  	'pdf_privileges' => array('copy', 'print', 'modify', 'print-highres'),
   );*/   
   
  /*
  * ------------------------------------------------------------ 
  * Right to Left Language Support
  * We now support RTL languages.
  * rtl Boolean. Default false.
  */  
  
   /*$fp_pdf_config[] = array(
	  'form_id' => 1, 
	  'notifications' => true,
	
	  'rtl' => true,
   );*/
    
  /*
  * ------------------------------------------------------------ 
  * Disable Notifications
  * If you don't need to send notifications and just want a custom PDF generated 
  * via the admin area you can forgo the notifications attribute
  */  
  
   /*$fp_pdf_config[] = array(
	  'form_id' => 1, 
	  'template' => 'example-template.php',		
   );*/    

 /* --------------------------------------------------------------- 
  * CUSTOM PDF SETUP BELOW. 
  * See http://formidablepropdfextended.com/documentation-v1/installation-and-configuration/#constants for more details
  */
 
 /*
  * By default, forms that don't have PDFs assigned through the above configuration
  * will automatically use the default template in the admin area.
  * Set to false to disable this feature.
  */ 
 define('FPPDF_SET_DEFAULT_TEMPLATE', true); 
 
 /*
  * MEMORY ISSUES?
  * Try setting the options below to true to help reduce the memory footprint of the package.
  */ 
 define('FP_PDF_ENABLE_MPDF_LITE', true); /* strip out advanced features like advanced table borders, terms and conditions, columns, index, bookmarks and barcodes. */
 define('FP_PDF_ENABLE_MPDF_TINY', false); /* if your tried the lite version and are still having trouble the tiny version includes the bare minimum features. There's no positioning, float, watermark or form support */
 define('FP_PDF_DISABLE_FONT_SUBSTITUTION', false); /* reduced memory by stopping font substitution */
 define('FP_PDF_ENABLE_SIMPLE_TABLES', false); /* disable the advanced table feature and forces all cells to have the same border, background etc. */
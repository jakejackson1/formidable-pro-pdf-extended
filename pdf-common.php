<?php

class FPPDF_Common
{
	public static function setup_ids()
	{
		global $form_id, $lead_id, $lead_ids;
		
		$form_id 		=  ($form_id) ? $form_id : absint( rgget("fid") );
		$lead_ids 		=  ($lead_id) ? array($lead_id) : explode(',', rgget("lid"));
		
		/**
		 * If form ID and lead ID hasn't been set stop the PDF from attempting to generate
		 */
		if(empty($form_id) || empty($lead_ids))
		{
			trigger_error(__('Form Id and Lead Id are required parameters.', "formidablepropdfextended"));
			return;
		}				
	}
	
	/*
	 * Remove any form fields with pdf_hidden in the class name
	 */
	public static function get_form_fields($form_id)
	{
		global $frm_field;		
		
        $fields = $frm_field->getAll(array('fi.form_id' => $form_id), 'field_order');

        foreach($fields as $k => $f){
			if(strpos($f->field_options['classes'], 'pdf_hidden') !== false)
			{
				unset($fields[$k]);	
			}
        }
        
        return $fields;
	}
	
	 /*
	  * Check if the system is fully installed and return the correct values
	  */
	public static function is_fully_installed() {
		global $frmpro_is_installed;
		 
		if ( ! $frmpro_is_installed ) {
			if ( ! is_callable( 'FrmAppHelper::pro_is_installed' ) || ! FrmAppHelper::pro_is_installed() ){
				return false;
			}
		}
		 
		if( (get_option('fp_pdf_extended_installed') != 'installed') || (!is_dir(FP_PDF_TEMPLATE_LOCATION)) )
		{		
			return false;
		}
		
		if(get_option('fp_pdf_extended_version') != FP_PDF_EXTENDED_VERSION)
		{
			return false;
		}
		
		 if(get_option('fp_pdf_extended_deploy') == 'no' && !rgpost('upgrade') && FP_PDF_DEPLOY === true)		
		 {
			return false; 
		 }
		 
		 if(file_exists(FP_PDF_PLUGIN_DIR .'mPDF.zip'))
		 {
			return false; 
		 }

		 return true;
	 }	
	
	public static function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		{
		  $ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		{
		  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
		  $ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	public static function get_html_template($filename) 
	{
	  global $form_id, $lead_id, $lead_ids;

	  ob_start();
	  require($filename);	
	  
	  $page = ob_get_contents();
	  ob_end_clean();	    
	  
	  return $page;
	}	
	
	/**
	 * Get the name of the PDF based on the Form and the submission
	 */
	public static function get_pdf_filename($form_id, $lead_id)
	{
		return "form-$form_id-entry-$lead_id.pdf";
	}
	
	/*
	* Check if mPDF folder exists.
	* If so, unzip and delete
	* Helps reduce the package file size
	*/		
	public static function unpack_mPDF()
	{
		$file = FP_PDF_PLUGIN_DIR .'mPDF.zip';
		$path = pathinfo(realpath($file), PATHINFO_DIRNAME);
		
		if(file_exists($file))
		{
			/* unzip folder and delete */
			$zip = new ZipArchive;
			$res = $zip->open($file);
			
			if ($res === TRUE) {
  				$zip->extractTo($path);
			    $zip->close();	
				unlink($file);
			}
		}
	}	
	
	/*
	 * We need to validate the PDF name
	 * Check the size limit, if the file name's syntax is correct 
	 * and strip any characters that aren't classed as valid file name characters.
	 */
	public static function validate_pdf_name($name, $form_id = false, $lead_id = false)
	{
		$pdf_name = $name;							
		
		if($form_id > 0)
		{
			$pdf_name = self::do_mergetags($pdf_name, $form_id, $lead_id);	
		}
		
		/*
		 * Limit the size of the filename to 100 characters
		 */
		 if(strlen($pdf_name) > 150)
		 {
			$pdf_name = substr($pdf_name, 0, 150); 
		 }
		 
		/*
		 * Remove extension from the end of the filename so we can replace all '.' 
		 * Will add back before we are finished
		 */		
		if(substr($pdf_name, -4) == '.pdf')
		{
			$pdf_name = substr($pdf_name, 0, -4);	
		}			 
		
		/*
		 * Remove any invalid (mostly Windows) characters from filename
		 */
		 $pdf_name = str_replace('/', '-', $pdf_name);
		 $pdf_name = str_replace('\\', '-', $pdf_name);		
		 $pdf_name = str_replace('"', '-', $pdf_name);				 
		 $pdf_name = str_replace('*', '-', $pdf_name);				 
		 $pdf_name = str_replace('?', '-', $pdf_name);				 		 
		 $pdf_name = str_replace('|', '-', $pdf_name);				 		 		 
		 $pdf_name = str_replace(':', '-', $pdf_name);				 		 		 		 
		 $pdf_name = str_replace('<', '-', $pdf_name);				 		 		 		 
		 $pdf_name = str_replace('>', '-', $pdf_name);				 		 		 		 		 		 
		 $pdf_name = str_replace('.', '_', $pdf_name);				 		 		 		 		 		 		 
		
		 $pdf_name = $pdf_name . '.pdf';
		
		return $pdf_name;
	}
	
	public static function do_mergetags($string, $form_id, $entry_id)
	{		
		global $frm_entry;
		
		/* strip {all_fields} merge tag from $string */
		$string = str_replace('[default-message]', '', $string);
		
		$entry = $frm_entry->getOne($entry_id, true);
        $shortcodes = FrmProDisplaysHelper::get_shortcodes($string, $form_id);
        return FrmProFieldsHelper::replace_shortcodes($string, $entry, $shortcodes);				
	}
	
	public static function view_data($form_data)
	{
		if(isset($_GET['data']) && $_GET['data'] === '1')
		{
			print '<pre>'; 
			print_r($form_data);
			print '</pre>';
			exit;
		}
	}
	
    public static function is_formidable_supported( $version ) {
		if ( ! class_exists('FrmProDisplay') ) {
			return false;
		}

		global $frm_version;
		if ( $frm_version ) {
			/**
			 * Get the plugin version when < 2.0
			 */
			$current_frm_version = $frm_version;
		} else if ( is_callable( 'FrmAppHelper::plugin_version' ) ) {
			/**
			 * Get the plugin version when > 2.0
			 */
			$current_frm_version = FrmAppHelper::plugin_version();
		}

		if ( version_compare( $current_frm_version, $version, '>=' ) === true ) {
			global $frmpro_is_installed;
			if ( $frmpro_is_installed ) {
				return true;
			}

			/**
			 * Check if pro is installed in 2.0+
			 */
			return ( is_callable( 'FrmAppHelper::pro_is_installed' ) && FrmAppHelper::pro_is_installed() );
		}

		return false;
    }	
	
    public static function is_wordpress_supported($version){
		global $wp_version;
		if(version_compare($wp_version, $version, ">=") === true)
		{
			return true;
		}
		return false;
    }	
	
	public static function display_compatibility_error()
	{
		 $message = sprintf(__("Formidable Pro " . FP_PDF_EXTENDED_SUPPORTED_VERSION . " is required to use this plugin. Activate/Upgrade now or %spurchase it today!%s"), "<a href='http://formidablepro.com/index.php?plugin=wafp&controller=links&action=redirect&l=formidable-pro&a=blue%20liquid%20designs'>", "</a>"); 
		 FPPDF_Common::display_plugin_message($message, true);			
	}
	
	public static function display_wp_compatibility_error()
	{
		 $message = "Wordpress " . FP_PDF_EXTENDED_WP_SUPPORTED_VERSION . " or higher is required to use this plugin."; 
		 FPPDF_Common::display_plugin_message($message, true);			
	}	
	
	public static function display_documentation_details()
	{
		 $message = sprintf(__("Please review the %sFormidable Pro PDF Extended documentation%s for comprehensive installation instructions."), "<a href='http://formidablepropdfextended.com/documentation-v1/installation-and-configuration/'>", "</a>"); 
		 FPPDF_Common::display_plugin_message($message);						
	}	
	
	public static function display_plugin_message($message, $is_error = false){

        $style = $is_error ? 'style="background-color: #ffebe8;"' : "";

        echo '</tr><tr class="plugin-update-tr"><td colspan="5" class="plugin-update"><div class="update-message" ' . $style . '>' . $message . '</div></td>';
    }
	
	/* 
	 * New to 3.0.2 we will use WP_Filesystem API to manipulate files instead of using in-built PHP functions	
	 * $post Array the post data to include in the request_filesystem_credntials API	 
	 */
	public static function initialise_WP_filesystem_API($post, $nonce)
	{

		$url = FP_PDF_SETTINGS_URL;	
		
		if (false === ($creds = request_filesystem_credentials($url, '', false, false, $post) ) ) {
			/* 
			 * If we get here, then we don't have correct permissions and we need to get the FTP details.
			 * request_filesystem_credentials will handle all that
			 */			 
			return false; // stop the normal page form from displaying
		}		

		/*
		 * Check if the credentials are no good and display an error
		 */
		if ( ! WP_Filesystem($creds) ) {
			request_filesystem_credentials($url, '', true, false, null);
			return false;
		}		
		
		return true;
				
	}
	
}

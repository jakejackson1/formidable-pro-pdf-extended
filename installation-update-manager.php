<?php

/**
 * Plugin: Formidable Pro PDF Extended
 * File: install-update-manager.php
 * 
 * This file handles the installation and update code that ensures the plugin will be supported.
 */

/**
 * Check to see if Formidable Pro version is supported
 */
 
class FPPDF_InstallUpdater
{
	private static $directory               = FP_PDF_PLUGIN_DIR;
	private static $template_directory      = FP_PDF_TEMPLATE_LOCATION;
	private static $template_save_directory = FP_PDF_SAVE_LOCATION;
	private static $template_font_directory = FP_PDF_FONT_LOCATION;
	
	
	public static function install() {
		if(strlen(get_option('fp_pdf_extended_installed')) == 0)
		{
			update_option('fp_pdf_extended_version', FP_PDF_EXTENDED_VERSION);	
			
			self::pdf_extended_activate();
		}
	}

	/*
	 * Check what the filesystem type is and modify the file paths
	 * appropriately.
	 */
	 public static function update_file_paths()
	 {
		global $wp_filesystem;

		/*
		 * Assume FTP is rooted to the Wordpress install
		 */ 			 	
		self::$directory               = self::get_base_directory(FP_PDF_PLUGIN_DIR);
		self::$template_directory      = self::get_base_directory(FP_PDF_TEMPLATE_LOCATION);
		self::$template_save_directory = self::get_base_directory(FP_PDF_SAVE_LOCATION);
		self::$template_font_directory = self::get_base_directory(FP_PDF_FONT_LOCATION);					 	 					 
		 
	 }
	
	/**
	 * Install everything required
	 */
	public static function pdf_extended_activate() {
	    /*
		 * Initialise the Wordpress Filesystem API
		 */		
		ob_start();
		if(FPPDF_Common::initialise_WP_filesystem_API(array('FP_PDF_DEPLOY'), 'fp-pdf-extended-filesystem') === false)
		{
		    $return = ob_get_contents();
		    ob_end_clean();			
			echo json_encode(array('form' => $return));
			exit;
		}
		ob_end_clean();					

		/*
		 * If we got here we should have $wp_filesystem available
		 */
		global $wp_filesystem;	
		
		/*
		 * We need to set up some filesystem compatibility checkes to work with the different server file management types
		 * Most notably is the FTP options, but SSH may be effected too
		 */
		self::update_file_paths();



		/**
		 * If FP_PDF_TEMPLATE_LOCATION already exists then we will remove the old template files so we can redeploy the new ones
		 */

		 if(FP_PDF_DEPLOY === true && $wp_filesystem->exists(self::$template_directory))
		 {
			/* read all file names into array and unlink from active theme template folder */
			foreach ( glob( FP_PDF_PLUGIN_DIR . 'templates/*.php') as $file ) {
				 	$path_parts = pathinfo($file);					
						if($wp_filesystem->exists(self::$template_directory.$path_parts['basename']))
						{
							$wp_filesystem->delete(self::$template_directory.$path_parts['basename']);
						}
			 }			
			if($wp_filesystem->exists(self::$template_directory.'template.css')) { $wp_filesystem->delete(self::$template_directory.'template.css'); }
		 }
		 

		/* unzip the mPDF file */
		if($wp_filesystem->exists(self::$directory . 'mPDF.zip'))
		{
			/*
			 * The only function that requires the input to be the full path and the export to be the directory used in $wp_filesystem
			 */
			$results = unzip_file( FP_PDF_PLUGIN_DIR . 'mPDF.zip', self::$directory );
		
			if($results !== true)
			{						
				add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_unzip_mpdf_err")); 	
				return 'fail';				
			}			

			/*
			 * Remove the original archive
			 */
			 $wp_filesystem->delete(self::$directory . 'mPDF.zip');
		}	

		/* create new directory in active themes folder*/	
		if(!$wp_filesystem->is_dir(self::$template_directory))
		{
			if($wp_filesystem->mkdir(self::$template_directory) === false)
			{
				add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_template_dir_err")); 	
				return 'fail';
			}
		}
	
		if(!$wp_filesystem->is_dir(self::$template_save_directory))
		{
			/* create new directory in active themes folder*/	
			if($wp_filesystem->mkdir(self::$template_save_directory) === false)
			{
				add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_template_dir_err")); 	
				return 'fail';
			}
		}
		
		if(!$wp_filesystem->is_dir(self::$template_font_directory))
		{
			/* create new directory in active themes folder*/	
			if($wp_filesystem->mkdir(self::$template_font_directory) === false)
			{
				add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_template_dir_err")); 	
				return 'fail';
			}
		}	
		
		/*
		 * Copy entire template folder over to FP_PDF_TEMPLATE_LOCATION
		 */
		 self::pdf_extended_copy_directory( self::$directory . 'templates', self::$template_directory, false );

		if(!$wp_filesystem->exists(self::$template_directory .'configuration.php'))
		{ 
			/* copy template files to new directory */
			if(!$wp_filesystem->copy(self::$directory .'configuration.php', self::$template_directory.'configuration.php'))
			{ 
				add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_template_dir_err")); 	
				return 'fail';
			}
		}
		
		if(!$wp_filesystem->exists(self::$template_directory.'template.css'))
		{ 
			/* copy template files to new directory */
			if(!$wp_filesystem->copy(self::$directory .'styles/template.css', self::$template_directory.'template.css'))
			{ 
				add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_template_dir_err")); 	
				return 'fail';
			}
		}	

		if(!$wp_filesystem->exists(self::$template_save_directory.'.htaccess'))
		{		
			if(!$wp_filesystem->put_contents(self::$template_save_directory.'.htaccess', 'deny from all'))
			{
				add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_template_dir_err")); 	
				return 'fail';
			}	
		}	

		if(self::install_fonts(self::$directory, self::$template_directory, self::$template_font_directory) !== true)
		{
			return 'fail';
		}				 
		
		/* 
		 * Update system to ensure everything is installed correctly.
		 */

		update_option('fp_pdf_extended_installed', 'installed');			
		update_option('fp_pdf_extended_deploy', 'yes');
		delete_option('fppdfe_switch_theme');
		
		return true;	
	}
	
	public static function initialise_fonts()
	{
		global $wp_filesystem;
	    /*
		 * Initialise the Wordpress Filesystem API
		 */
		ob_start();
		if(FPPDF_Common::initialise_WP_filesystem_API(array('FP_PDF_DEPLOY'), 'fp-pdf-extended-filesystem') === false)
		{
		    $return = ob_get_contents();
		    ob_end_clean();			
			echo json_encode(array('form' => $return));
			exit;
		}
		ob_end_clean();		
		
		/*
		 * We need to set up some filesystem compatibility checkes to work with the different server file management types
		 * Most notably is the FTP options, but SSH may be effected too
		 */
		self::update_file_paths();
		
		if(self::install_fonts(self::$directory, self::$template_directory, self::$template_font_directory) === true)
		{
			add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_font_install_success")); 
		}		
		return true;
	}
	
	private static function install_fonts($directory, $template_directory, $fonts_location)
	{

		global $wp_filesystem;	
		$write_to_file = '<?php 
		
			if(!defined("FP_PDF_EXTENDED_VERSION"))
			{
				return;	
			}
		
		';
		
		/*
		 * Search the font folder for .ttf files. If found, move them to the mPDF font folder 
		 * and write the configuration file
		 */

		 /* read all file names into array and unlink from active theme template folder */
		 foreach(glob(FP_PDF_FONT_LOCATION.'*.[tT][tT][fF]') as $file) {
			 	$path_parts = pathinfo($file);	
				
				/*
				 * Check if the files already exist in the mPDF font folder
				 */					
				 if(!$wp_filesystem->exists($directory . 'mPDF/ttfonts/' . $path_parts['basename']))
				 {
					/*
					 * copy ttf file to the mPDF font folder
					 */
					if($wp_filesystem->copy($file, $directory . 'mPDF/ttfonts/' . $path_parts['basename']) === false)
					{ 
						add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_font_err")); 	
						return false;
					}	
				 }
				
				/*
				 * Generate configuration information in preparation to write to file
				 */ 							
				$write_to_file .= '
					$this->fontdata[\''.strtolower($path_parts['filename']).'\'] = array(
								\'R\' => \''.$path_parts['basename'].'\'
					);';
					
		 }					 

		 /*
		  * Remove the old configuration file and put the contents of $write_to_file in a font configuration file
		  */
		  $wp_filesystem->delete($template_directory.'fonts/config.php');
		  if($wp_filesystem->put_contents($template_directory.'fonts/config.php', $write_to_file) === false)
		  {
			  	add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_font_config_err")); 	
				return false;  
		  }			
		 
		 return true;
	}
	
	public function fp_pdf_font_install_success()
	{
		echo '<div class="fppdfe_message updated"><p>';
		echo 'The font files have been successfully installed. A font can be used by adding it\'s file name (without .ttf) in a CSS font-family declaration.';
		echo '</p></div>';
	}	

	public function fp_pdf_font_err()
	{
		echo '<div class="fppdfe_message error"><p>';
		echo 'There was a problem installing the font files. Manually copy your fonts to the mPDF/ttfonts/ folder.';
		echo '</p></div>';
	}	
	
	public function fp_pdf_font_config_err()
	{
		echo '<div class="fppdfe_message error"><p>';
		echo 'Could not create font configuration file. Try initialise again.';
		echo '</p></div>';
	}		
	
	/**
	 * Formidable Pro hasn't been installed so throw error.
	 * We make sure the user hasn't already dismissed the error
	 */
	public function fp_pdf_not_installed()
	{
		echo '<div class="fppdfe_message error"><p>';
		echo 'You need to install <a href="http://formidablepro.com/index.php?plugin=wafp&controller=links&action=redirect&l=formidable-pro&a=blue liquid designs" target="ejejcsingle">Formidable Pro</a> to use the Formidable Pro PDF Extended Plugin.';
		echo '</p></div>';
	}
	
	/**
	 * PDF Extended has been updated but the new template files haven't been deployed yet
	 */
	public function fp_pdf_not_deployed()
	{		
		if( (FP_PDF_DEPLOY === true) && !rgpost('update') )
		{
			if(rgget("page") == 'fp_settings' && rgget('addon') == 'PDF')
			{
				echo '<div class="fppdfe_message error"><p>';
				echo 'You\'ve updated Formidable Pro PDF Extended but are yet to re-initialise the plugin. After initialising, please review the latest updates to ensure your custom templates remain compatible with the latest version.';
				echo '</p></div>';
				
			}
			else
			{
				echo '<div class="fppdfe_message error"><p>';
				echo 'You\'ve updated Formidable Pro PDF Extended but are yet to re-initialise the plugin. Please go to the <a href="'.FP_PDF_SETTINGS_URL.'">plugin\'s settings page</a> to initialise.';
				echo '</p></div>';
			}
		}
	}	
	
	/**
	 * PDF Extended has been freshly installed
	 */
	public static function fp_pdf_not_deployed_fresh() {
		if( (FP_PDF_DEPLOY === true) && !rgpost('update') )
		{
			if(rgget("page") == 'fp_settings' && rgget('addon') == 'PDF')
			{
				echo '<div class="fppdfe_message updated"><p>';
				echo 'Welcome to Formidable Pro PDF Extended. Before you can use the plugin correctly you need to initilise it.';
				echo '</p></div>';
				
			}
			else
			{
				echo '<div class="fppdfe_message updated"><p>';
				echo 'Welcome to Formidable Pro PDF Extended. Before you can use the plugin correctly you need to initilise it. Please go to the <a href="'.FP_PDF_SETTINGS_URL.'">plugin\'s settings page</a> to initialise.';
				echo '</p></div>';
			}
		}
	}	
	
	/**
	 * The Formidable Pro version isn't compatible. Prompt user to upgrade
	 */
	public function fp_pdf_not_supported()
	{
			echo '<div class="fppdfe_message error"><p>';
			echo 'Formidable Pro PDF Extended only works with Formidable Pro version '.FP_PDF_EXTENDED_SUPPORTED_VERSION.' and higher. Please <a href="http://formidablepro.com/index.php?plugin=wafp&controller=links&action=redirect&l=formidable-pro&a=blue liquid designs">upgrade your copy of Formidable Pro</a> to use this plugin.';
			echo '</p></div>';	
	}
								
	
	/**
	 * Cannot create new template folder in active theme directory
	 */
	public function fp_pdf_template_dir_err()
	{
			echo '<div class="fppdfe_message error"><p>';
			echo 'We could not create a template folder in your active theme\'s directory. Please make your theme directory writable by your web server and initialise again.';
			echo '</p></div>';
			
	}
	
	public static function fp_pdf_unzip_mpdf_err()
	{
			echo '<div class="fppdfe_message error"><p>';
			echo 'Could not unzip mPDF.zip (located in the plugin folder). Unzip the file manually, place the extracted mPDF folder in the plugin folder and run the initialisation again.';
			echo '</p></div>';		
	}
	
	/**
	 * Cannot remove old default template files
	 */
	public function fp_pdf_deployment_unlink_error()
	{
			echo '<div class="fppdfe_message error"><p>';
			echo 'We could not remove the default template files from the Formidable Pro PDF Extended folder in your active theme\'s directory. Please manually remove all files starting with \'default-\', the template.css file and then initialise again.';
			echo '</p></div>';
	
	}		
	
	/**
	 * Cannot create new template folder in active theme directory
	 */
	public function fp_pdf_template_move_err()
	{
			echo '<div class="fppdfe_message error"><p>';
			echo 'We could not copy the contents of '.FP_PDF_PLUGIN_DIR.'templates/ to your newly-created FORMIDABLE_PDF_TEMPLATES folder. Please make this directory writable by your web server and initialise again.';
			echo '</p></div>';
	
	}
	
	/*
	 * When switching themes copy over current active theme's PDF_EXTENDED_TEMPLATES (if it exists) to new theme folder
	 */
	public static function fp_pdf_on_switch_theme( $old_theme_name, $old_theme_object ) {
		
		/*
		 * We will store the old pdf dir and new pdf directory and prompt the user to copy the PDF_EXTENDED_TEMPLATES folder
		 */		
		 	 $previous_theme_directory = $old_theme_object->get_stylesheet_directory();
		 			 			
			 $current_theme_array = wp_get_theme(); 
			 $current_theme_directory = $current_theme_array->get_stylesheet_directory();

			 /*
			  * Add the save folder name to the end of the paths
			  */ 
			 $old_pdf_path = $previous_theme_directory . '/' . FP_PDF_SAVE_FOLDER;
			 $new_pdf_path = $current_theme_directory . '/' . FP_PDF_SAVE_FOLDER;
		 	
			 update_option('fppdfe_switch_theme', array('old' => $old_pdf_path, 'new' => $new_pdf_path));
	}
	
	/*
	 * Check if a theme switch has been made recently 
	 * If it has then prompt the user to move the files
	 */
	public static function check_theme_switch()
	{
		$theme_switch = get_option('fppdfe_switch_theme');
		if(isset($theme_switch['old']) && isset($theme_switch['new']))
		{
			/*
			 * Add admin notification hook to move the files
			 */	
			add_action( 'admin_notices', 'FPPDF_InstallUpdater::do_theme_switch_notice' );
			return true;
		}
		return false;		
	}
	
	/*
	 * Prompt user to keep the plugin working
	 */
	public static function do_theme_switch_notice()
	{		
		/*
		 * Check we aren't in the middle of doing the sync
		 */
		 if(isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'gfpdfe_sync_now'))
		 {
			return; 
		 }
		 
			echo '<div id="message" class="error"><p>';
			echo 'Formidable Pro PDF Extended needs to keep the FORMIDABLE_PDF_TEMPLATES folder in sync with your current active theme. <a href="'. wp_nonce_url(FP_PDF_SETTINGS_URL, 'fppdfe_sync_now') . '" class="button">Sync Now</a>';
			echo '</p></div>';		
		 
	}
	
	public static function fp_pdf_theme_sync_success()
	{
			echo '<div id="message" class="updated"><p>';
			echo 'FORMIDABLE_PDF_TEMPLATES folder successfully synced.';
			echo '</p></div>';			
	}
	
	/*
	 * The after_switch_theme hook is too early in the initialisation to use request_filesystem_credentials()
	 * so we have to call this function at a later inteval
	 */
	public static function do_theme_switch( $previous_pdf_path, $current_pdf_path ) {
		/*
		 * Prepare for calling the WP Filesystem
		 * It only allows post data to be added so we have to manually assign them
		 */
		$_POST['previous_pdf_path'] = $previous_pdf_path;
		$_POST['current_pdf_path'] = $current_pdf_path;
		
	    /*
		 * Initialise the Wordpress Filesystem API
		 */
		if(FPPDF_Common::initialise_WP_filesystem_API(array('previous_pdf_path', 'current_pdf_path'), 'gfpdfe_sync_now') === false)
		{
			return false;	
		}				
		
		/*
		 * If we got here we should have $wp_filesystem available
		 */
		global $wp_filesystem;	
		
		
		/*
		 * Assume FTP is rooted to the Wordpress install
		 */ 			 	 
		 $previous_pdf_path = self::get_base_directory($previous_pdf_path);
		 $current_pdf_path = self::get_base_directory($current_pdf_path);			 			 					 
					 
		 
		 if($wp_filesystem->is_dir($previous_pdf_path))
		 {
			 self::pdf_extended_copy_directory( $previous_pdf_path, $current_pdf_path, true, true );
		 }		
		 
		/*
		 * Remove the options key that triggers the switch theme function
		 */ 
		 delete_option('fppdfe_switch_theme');
		 add_action('fppdfe_notices', array("FPPDF_InstallUpdater", "fp_pdf_theme_sync_success")); 	
		 
		 /*
		  * Show success message to user
		  */
		 return true;
	}
	
	/*
	 * Allows you to copy entire folder structures to new location
	 */
	
	public static function pdf_extended_copy_directory( $source, $destination, $copy_base = true, $delete_destination = false ) {
		global $wp_filesystem;		
		
		if ( $wp_filesystem->is_dir( $source ) ) 
		{			
			if($delete_destination === true)
			{
				/*
				 * To ensure everything stays in sync we will remove the destination file structure
				 */
				 $wp_filesystem->delete($destination, true);
			}
			 
			if($copy_base === true)
			{
				$wp_filesystem->mkdir( $destination );
			}
			$directory = $wp_filesystem->dirlist( $source );

			foreach($directory as $name => $data)
			{
							
				$PathDir = $source . '/' . $name; 
				
				if ( $wp_filesystem->is_dir( $PathDir ) ) 
				{
					self::pdf_extended_copy_directory( $PathDir, $destination . '/' . $name );
					continue;
				}
				$wp_filesystem->copy( $PathDir, $destination . '/' . $name );
			}

		}
		else 
		{
			$wp_filesystem->copy( $source, $destination );
		}	
	}
	
	/*
	 * Merge the path array back together from the matched key
	 */	
	private static function merge_path($file_path, $key)
	{
		return '/' .  implode('/', array_slice($file_path, $key)) . '/';
	}
	
	/*
	 * Get the base directory for the current filemanagement type
	 * In this case it is FTP but may be SSH
	 */
	 private static function get_base_directory($path = '')
	 {
		global $wp_filesystem;
		return str_replace(ABSPATH, $wp_filesystem->abspath(), $path);			 		
	 }	

}

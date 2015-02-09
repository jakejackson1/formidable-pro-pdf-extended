<?php

/**
 * Plugin: Formidable Pro PDF Extended
 * File: settings.php
 * 
 * Handles the Formidable Pro Settings page in Wordpress
 */

class FPPDF_Settings
{
	/* 
	 * Check if we're on the settings page 
	 */ 
	public static function settings_page() {
		/*
		 * Initialise Formidable Settings Page
		 */
		if(rgget('page') == 'formidable-settings')
		{
			/*
			 * Add Formidable Settings Page
			 */
			add_filter('frm_add_settings_section', array('FPPDF_Settings', 'add_settings_page'));			
		}
		
	}
	
	public static function add_settings_page($sections)
	{
		$sections['PDF'] = array(
			'class' => 'FPPDF_Settings',
			'function' => 'fppdf_settings_page'
		);
		return $sections;
	}
	
	private static function run_setting_routing()
	{
		/* 
		 * Check if we need to redeploy default PDF templates/styles to the theme folder 
		 */
		 
		if( rgpost('fp_pdf_deploy') && 
		( wp_verify_nonce($_POST['fp_pdf_deploy_nonce'],'fp_pdf_deploy_nonce_action') || wp_verify_nonce($_GET['_wpnonce'],'pdf-extended-filesystem') ) ) {				
			if(rgpost('upgrade'))
			{
				/* 
				 * Deploy new template styles 
				 * If we get false returned Wordpress is trying to get 
				 * access details to update files so don't display anything.
				 */
				if(self::deploy() === false)
				{
					return true;
				}
			}
			elseif(rgpost('font-initialise'))
			{

			}
		}
		
		/*
		 * If the user hasn't requested deployment and there is a _wpnonce check which one it is 
		 * and call appropriate function
		 */	
		 if(isset($_GET['_wpnonce']))
		 {
			 /*
			  * Check if we want to copy the theme files
			  */
			 if(wp_verify_nonce($_GET['_wpnonce'], 'fppdfe_sync_now') )
			 {
				 $themes = get_option('fppdfe_switch_theme');
				 
				 if(isset($themes['old']) && isset($themes['new']) && FPPDF_InstallUpdater::do_theme_switch($themes['old'], $themes['new']) === false)
				 {
					return true; 
				 }
			 }
		 }	

		 return false;	
	}
	
	/*
	 * Shows the GF PDF Extended settings page
	 */		
	public static function fppdf_settings_page() 
	{ 
	    /*
		 * Run the page's configuration/routing options
		 */ 
		if(self::run_setting_routing() === true)
		{
			return;	
		}
		
		/*
		 * Show any messages the plugin might have called
		 * Because we had to run inside the settings page to correctly display the FTP credential form admin_notices was already called.
		 * To get around this we can recall it here.
		 */
		 do_action('fppdfe_notices');
		 
		/* 
		 * Show the settings page deployment form 
		 */
		?>
        
        
        <div id="fppdfextended-setting" class="PDF_settings tabs-panel">
             
                    <div class="leftcolumn">
         <?php 
		  	if(FP_PDF_DEPLOY === true)
			{							
		   ?>
           <h2>Initialise Plugin</h2>
           <p>Fresh installations and users who have just upgraded will need to initialise Formidable Pro PDF Extended to ensure it works correctly.</p>
           
           <p>Initialisation does a number of important things, including:</p>
           
           <ol>
           		<li><strong>Fresh Installation</strong>: Copies all the required template and configuration files to a folder called FORMIDABLE_PDF_TEMPLATES in your active theme's directory.<br />
                	<strong>Upgrading</strong>: Copies the latest default templates and template.css file to the FORMIDABLE_PDF_TEMPLATES folder. <strong>If you modified these files please back them up before re-initialising as they will be removed</strong>.
                </li>
           		<li>Unzips the mPDF package</li>
           		<li>Installs any fonts found in the FORMIDABLE_PDF_TEMPLATES/fonts/ folder</li>                
           </ol>
		  	

                <?php wp_nonce_field('fp_pdf_deploy_nonce_action','fp_pdf_deploy_nonce'); ?>
                <input type="hidden" name="fp_pdf_deploy" value="1">
                <?php 
				
				/*
				 * Remove the cancel feature for the moment
				 *
				
				if(get_option('gf_pdf_extended_deploy') == 'no') { ?>				
                <input type="submit" value="Cancel Deployment" class="button" id="cancelupgrade" name="cancel">                
				<?php } */ ?>                                                
                <input type="submit" value="Initialise Plugin" class="button" id="plugin-initialise" name="plugin-initialise">
                
                <input type="submit" value="Initialise Fonts Only" class="button" id="font-initialise" name="font-initialise">                
  
          <?php } else { ?>     
				<h2>Welcome to Formidable Pro PDF Extended</h2>

				<p>The plugin has successfully installed and is ready to start automating your documents.</p>

				<p><strong>What's next?</strong> Now you've installed the software you need to configure it. <a href="http://formidablepropdfextended.com/documentation-v1/installation-and-configuration/">Please follow our configuration guide for more details</a>.</p>

				<h2>Have a problem with the software?</h2>
	
				<p>Did you switch themes and something went wrong syncing the template folder? Try reinitialise the software.</p>

                <?php wp_nonce_field('fp_pdf_deploy_nonce_action','fp_pdf_deploy_nonce'); ?>
                <input type="hidden" name="fp_pdf_deploy" value="1">

                <input type="submit" value="Initialise Plugin" class="button" id="plugin-initialise" name="plugin-initialise">
                
                <input type="submit" value="Initialise Fonts Only" class="button" id="font-initialise" name="font-initialise">   
          <?php } ?>
               </div>

               <div class="rightcolumn">
               		<h2>Welcome to Formidable Pro PDF Extended Beta!</h2>
                    
                    <p>Your one of the very first to try Formidable Pro PDF Extended but keep in mind, with great power comes great responsibility... This software may contain bugs that weren't picked up during initial development (which is why we are having an open beta). It <strong>should not be used on a production server</strong>.</p>
                    
                    <p>If you have any issues using the software, find a bug or have an idea to make the plugin even better then please <a href="http://formidablepropdfextended.com/support/formidable-pro-pdf-extended/beta-feedbackbug-reports/">head to our support forum</a> and start a new topic. If you don't report it then we can't fix it! </p>
               </div>
        <?php
	}
	
	/*
	 * Deploy the latest template files
	 */
	public static function ajax_deploy()
	{		
		/* check nonce and permission */
		if ( ! wp_verify_nonce( $_POST['nonce'], 'fppdfe_nonce' ) || !current_user_can('frm_edit_forms') )
		{
			print json_encode(array('error' => 'Access denied. Failed to initialise fonts.'));
		}			
	
		$results = FPPDF_InstallUpdater::pdf_extended_activate();
		
		/*
		 * Capture the notices generated if there is an error and display it to the user
		 */
		 if($results !== true)
		 {
		 	ob_start();
		 
		 	do_action('fppdfe_notices');		 
		 	$message = ob_get_contents();
		 
		 	ob_end_clean();
		 	
			print json_encode(array('error' => $message));
		 }
		 else
		 {
			 print json_encode(array('message' => self::gf_fp_pdf_deploy_success()));
		 }
		
		exit;
	}
	
	public static function gf_fp_pdf_deploy_success() {
			$msg = '<div id="fppdfe_message" class="updated"><p>';
			$msg .= 'You\'ve successfully initialised Formidable Pro PDF Extended.';
			$msg .= '</p></div>';		
			
			return $msg;
	}
	
	public static function initialise_fonts() {
		
			/* check nonce and permission */
			if ( ! wp_verify_nonce( $_POST['nonce'], 'fppdfe_nonce' ) || !current_user_can('frm_edit_forms') )
			{
				print json_encode(array('error' => 'Access denied. Failed to initialise fonts.'));
			}				
		
			/*
			 * We only want to reinitialise the font files and configuration
			 */	
			 FPPDF_InstallUpdater::initialise_fonts();
			
			/* 
			 * Output the message back to the user
			 */
			ob_start();
		 
			do_action('fppdfe_notices');		 
			$message = ob_get_contents();
		 
			ob_end_clean();
			
			print json_encode(array('message' => $message));				 		
			
			exit;
	}
}

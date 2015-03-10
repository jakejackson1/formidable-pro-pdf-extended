<?php

/*
 * Securly allow the shortcode [pdf] to be used in custom displays
 */ 
 
 add_filter( 'frm_display_entry_content', 'FP_Custom_Display::entries_content', 10, 6 );
 
 
 class FP_Custom_Display
 {
		private static function format_attrs($attrs)
		{
			$attr_return = array();
			$attr_array = explode(' ', trim($attrs));	

			foreach($attr_array as $attr)
			{
				if(strlen($attr) > 0 && strpos($attr, '=') !== false)
				{
					$attr_block = explode('=', $attr);				
					$attr_return[$attr_block[0]] = str_replace('"', '', str_replace("'", '', $attr_block[1]));
				}
			}
			
			return $attr_return;
		}
		
		public static function entries_content($new_content, $entry, $shortcodes, $display, $show, $odd)
		{						
			/*
			 * Get the form/entry ID
			 */
			 $form_id = $entry->form_id;
			 $lead_id = $entry->id;
			 
			/*
			 * Do a search for our specific shortcode
			 */
			 $pdf_shortcode_search = preg_match_all('/\[pdf( (.+?))?\]((.+?)\[\/pdf\])?/', $new_content, $results);
			 
			 if($pdf_shortcode_search !== false)
			 {				 					 
					/*
					 * We have a match
					 * Loop through the results and generate the correct link
					 */ 
					 foreach($results[0] as $key => $string)
					 {
						$template = $download = $text = false; 
						 
						/*
						 * Check if any attributes are avaliable
						 */ 
						 if(strlen($results[1][$key]) > 0)
						 {						
							extract(self::format_attrs($results[1][$key])); 						
						 }
						 
						 /*
						  * Check if there is defined URL text
						  */ 
						  if(strlen($results[4][$key]) > 0)
						  {
								$text = $results[4][$key];  
						  }
						 								 
						 
						 if($template === false)
						 {
							 global $fppdf;
							 
							 /*
							  * No template used. Get the first template file found in config
							  */
							 $all_indexes = $fppdf->check_configuration($form_id);		
							 $index = $all_indexes[0];
							 $template = $fppdf->get_template($form_id);									 
						 }
						 
						 if($text === false)
						 {
							$text = 'View PDF'; 
						 }
						 
						 $nonce = wp_create_nonce('fppdf_' . $form_id . $lead_id. $template);																 
						 
						 /*
						  * Build URL 
						  */
						  $url = '<a href="'. site_url() . '/?pdf=1&fid='.$form_id.'&lid='.$lead_id.'&template='.$template .'&nonce='. $nonce ;
						  $url .= ($download !== false) ? '&download=1' : '';
						  $url .= ($language !== false) ? '&lang=' . $language : '';
						  $url .= '">'. $text . '</a>';
						  
						  $new_content = str_replace($string, $url, $new_content);
					 }
			 }
			 
			 			 
			 return $new_content;	 
			
		}
 }
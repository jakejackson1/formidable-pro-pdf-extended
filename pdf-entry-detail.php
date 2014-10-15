<?php

	class FPPDF_Entry {
		
		private static $frmplus_action_added = false;
	
    public static function show_entry($atts){
        extract(shortcode_atts(array(
            'id' => false, 
			'fields' => false, 
			'plain_text' => false,
            'user_info' => false, 
			'include_blank' => false,
			'show_html' => false,
            'form_id' => false,
			'hidden' => false,
			'type' => false /* either false or empty (two column table), block (divs), or array ($form_data array) */
        ), $atts));
        
        global $frmpro_settings, $frm_entry;
        
		if(!$id)
		{
			return;
		}
         
        $entry = $frm_entry->getOne($id, true);
        
        if(!$entry) {
			return;
		}
		
		$form_id = $entry->form_id;
		$id = $entry->id;  
        
        if(!$fields or !is_array($fields)){
            global $frm_field;
            $fields = $frm_field->getAll(array('fi.form_id' => $form_id), 'field_order');
        }
        
        $content = ($type == 'return') ? array() : '';
        $odd = true;
            
        if(!$plain_text && $type === false) {
            $content .= "<table cellspacing='0'><tbody>\r\n";
        }		
		
		if($type == 'array')
		{
			$array['form_title'] 				= $entry->form_name;
			$array['form_id']					= $entry->form_id;
			$array['lead_id']					= $entry->id;
			
			$date_created 						= $entry->created_at;
			$date_created_timestamp 			= strtotime($date_created);
			$array['date_created'] 				= date('d/m/Y', $date_created_timestamp);
			$array['date_created_usa'] 			= date('m/d/Y', $date_created_timestamp);	
			
			$array['misc']['created_at'] 		= $entry->created_at;
			$array['misc']['updated_at'] 		= $entry->updated_at;			
			$array['misc']['updated_by'] 		= $entry->updated_by;			
			$array['misc']['user_id'] 			= $entry->user_id;
			$array['misc']['post_id']			= $entry->post_id;						
			$array['misc']['description'] 		= maybe_unserialize($entry->description);			
			$array['misc']['ip'] 				= $entry->ip;						
			$array['misc']['parent_item_id'] 	= $entry->parent_item_id;						

		}
        
        foreach($fields as $f) {

        	/*
        	 * Don't include any fields with the class of 'exclude' if the $type isn't 'array'
        	 */ 
        	if($type != 'array' && stripos($f->field_options['classes'], 'exclude') !== false)
        	{
        		continue; /* skip this field */
        	}

			$fname = $f->name;        
			
			if($hidden === true && $f->type == 'html')
			{
				continue;	
			}
			elseif($f->type == 'html' && $show_html == true)
			{
				if($plain_text){
					$content .= '<div class="html-field">' . $f->description . '</div><br /><br />';
				}
				elseif($type === false)
				{				
					$content .= "<tr class='".(($odd) ? 'odd' : 'even')."'><td colspan='2'>{$f->description}</td></tr>";
					$odd = ($odd) ? false : true;
					unset($f);
					continue;
				}	
				elseif($type == 'block')
				{					
					$content .= "<div class='container ".(($odd) ? 'odd' : 'even')."'>{$f->description}</div>";
					$odd = ($odd) ? false : true;
					unset($f);
					continue;					
				}
				elseif($type == 'array')
				{
					$array[$f->field_key] = $f->description;
					continue;
				}	
				elseif($type == 'return')							
				{
					$content[] = $f->description;
				}
			}

			if($f->type == 'divider')
			{
				if($plain_text){
					$content .= '<div class="section-field"><h2>' . $f->name . '</h2></div><br /><br />';
				}
				elseif($type === false)
				{				
					$content .= "<tr class='".(($odd) ? 'odd' : 'even')." section-field'><td colspan='2'><h2>{$f->name}</h2></td></tr>";
					$odd = ($odd) ? false : true;
					unset($f);
					continue;
				}	
				elseif($type == 'block')
				{					
					$content .= "<div class='container section-field ".(($odd) ? 'odd' : 'even')."'><h2>{$f->name}</h2></div>";
					$odd = ($odd) ? false : true;
					unset($f);
					continue;					
				}
				elseif($type == 'array')
				{
					$array[$f->field_key] = $f->name;
					continue;
				}	
				elseif($type == 'return')							
				{
					$content[] = "<div class='container section-field ".(($odd) ? 'odd' : 'even')."'><h2>{$f->name}</h2></div>";
					$odd = ($odd) ? false : true;
					unset($f);
					continue;
				}				
			}
			
            if(in_array($f->type, array('captcha', 'break')))
                continue;
                
            if(!isset($entry->metas[$f->id])){
                if(!$include_blank && $type != 'array')
                    continue;
                    
                $entry->metas[$f->id] = '' ;
            }
            
            $prev_val = maybe_unserialize($entry->metas[$f->id]);
			
            $meta = array('item_id' => $id, 'field_id' => $f->id, 'meta_value' => $prev_val, 'field_type' => $f->type);
            
			if($f->type != 'signature' && $f->type != 'table')
			{
            	$val = self::email_value($prev_val, (object)$meta, $entry);
			}
			
			if($f->type == 'signature')
			{		
				/*
				 * Don't run signature block if signature field not found
				 */		
				if(class_exists('FrmSigAppController'))
				{
					if( (isset($prev_val['typed']) && strlen($prev_val['typed']) > 0) )
					{
						$val = ($type == 'array') ? $prev_val['typed'] :  '<span class="typed_signature">'. $prev_val['typed'] . '</span>';	
					}
					else
					{
						
						$val = FrmSigAppController::display_signature($prev_val, $f, array('entry_id' => $id));	
	
						if($type == 'array')
						{
							if (preg_match('/<img src="(.*?)" alt="(.*?)" \/>/i', $val, $matches))
							{	

								$array['signature'][$f->field_key]['img'] = $val;							
								$array['signature'][$f->field_key]['url'] = $matches[1];
								$array['signature'][$f->field_key]['path'] = str_replace(home_url(). '/', ABSPATH, $matches[1]);															
							}
							
							continue;
						}
						else
						{
							/* add class to image so we can resize appropriately */
							$val = str_replace('/>', 'class="signature" />', $val);
						}
						
					}		
				}
		
			}
			
			if( ($f->type == 'checkbox' || $f->type == 'select' || $f->type == 'radio' ) )
			{
				/*
				 * Maybe convert the values to options
				 */
				 $new_val = self::convert_values_to_name($val, $f->options);				 
				 
				if($type == 'array')				 
				{
					$fname = $f->name;  

					$array['field'][$f->field_key] = array(
						'title' => $fname,
						'value' => $val,
						'label' => $new_val,
						'string' => implode(', ', $new_val),
						'options' => $f->options, /* included in 1.5 */
						'type' => $f->type, /* included in 1.5 */
					);											
					
					$array['field'][$f->field_key . '.' . $fname] = array(
						'title' => $fname,
						'value' => $val,
						'label' => $new_val,
						'string' => implode(', ', $new_val),
						'options' => $f->options, /* included in 1.5 */
						'type' => $f->type, /* included in 1.5 */
					);						
				}
				else
				{
					/*
					 * $val is an array so implode it into a string
					 */ 
					 $val = implode('<br />', $new_val);					
				}
				 

			}		
			
			if( $f->type == 'table' && method_exists( 'FrmPlusEntryMetaHelper', 'frmplus_display_value_custom' ) )
			{

				/* grab the HTML */
				if ( !self::$frmplus_action_added ){
					add_action( 'frmplus_field_value_checkbox', array('FPPDF_Entry', 'convert_checkboxes_to_image') );
					add_action( 'frmplus_field_value_radio', array('FPPDF_Entry', 'convert_checkboxes_to_image') );
					add_action( 'frmplus_field_value_radioline', array('FPPDF_Entry', 'convert_checkboxes_to_image') );
					self::$frmplus_action_added = true;

					$table_html = FrmPlusEntryMetaHelper::frmplus_display_value_custom( $prev_val, $f, array() );
				}

				if($type == 'array')
				{
					$array['field'][$f->field_key]                = self::get_table_data($f, $table_html, $prev_val);
					$array['field'][$f->field_key . '.' . $fname] = $array['field'][$f->id];
					$array['field'][$f->field_key]                = $array['field'][$f->id];
				}
				else
				{

					$val = $table_html;
				}
			}
						
			$val = (!is_array($val)) ? stripslashes($val) : $val;
			
			if( ( $f->type == 'image' || $f->type == 'url' || $f->type == 'file' ))
			{
				if($type == 'array')				 
				{			
					$array['field'][$f->field_key . '.' . $fname] = $val;
					$array['field'][$f->field_key] = $val;
				}
				elseif(is_array($val))
				{

					/*
					 * $val is an array so implode it into a string
					 */ 
					 $val = '<a href="'.$val['url'].'">'.$val['name'].'</a>';					
				}						
			}
			
			if($f->type == 'tag')
			{
				$val = str_replace(',', ', ', $val);	
			}

            if($f->type == 'textarea')
                $val = str_replace(array("\r\n", "\r", "\n"), ' <br/>', $val);
            
            if (is_array($val))
                $val = implode(', ', $val);
             
             
            if($plain_text){
                $content .= $fname . ': ' . $val . "<br /><br />";
            }
			elseif($type === false)
			{
				if($f->type == 'table')
				{
					$content .= "<tr class='".(($odd) ? 'odd' : 'even')."'><th colspan='2'>" . $fname ."</th></tr>";
					$content .= "<tr class='".(($odd) ? 'odd' : 'even')."'><td class='table-cell' colspan='2'>$val</td></tr>";	
				}
				else
				{
                	$content .= "<tr class='".(($odd) ? 'odd' : 'even')."'><th>" . $fname ."</th><td>$val</td></tr>";
				}
                $odd = ($odd) ? false : true;
            }
			elseif($type == 'block')
			{
                $content .= "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>" . $fname ."</div><div class='value'>$val</div></div>";
                $odd = ($odd) ? false : true;				
			}
			elseif($type == 'array' && $f->type != 'checkbox' && $f->type != 'select' && $f->type != 'radio' && $f->type != 'table' && $f->type != 'image' && $f->type != 'url' && $f->type != 'file')
			{
				
				$array['field'][$f->field_key . '.' . $fname] = array(
					'title' => $fname,
					'value' => $val,
					'type' => $f->type, /* added in 1.5 */
				);		

				$array['field'][$f->field_key] = array(
					'title' => $fname,
					'value' => $val,
					'type' => $f->type, /* added in 1.5 */
				);										
			}	
			elseif($type == 'return')		
			{
                $content[] = "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>" . $fname ."</div><div class='value'>$val</div></div>";
                $odd = ($odd) ? false : true;		
			}
            
            unset($fname);
            unset($f);
        }
        
        if($user_info){
            $data = maybe_unserialize($entry->description);
            if($plain_text){
                $content .= "<br /><br />" . __('User Information', 'formidable') ."<br />";
                $content .= __('IP Address', 'formidable') . ": ". $entry->ip ."<br />";
                $content .= __('User-Agent (Browser/OS)', 'formidable') . ": ". $data['browser']."<br />";
                $content .= __('Referrer', 'formidable') . ": ". $data['referrer']."<br />";
            }
			elseif($type === false)
			{
                $content .= "<tr class='".(($odd) ? 'odd' : 'even')."'><th>". __('IP Address', 'formidable') . "</th><td>". $entry->ip ."</td></tr>";
                $odd = ($odd) ? false : true;
                $content .= "<tr class='".(($odd) ? 'odd' : 'even')."'><th>".__('User-Agent (Browser/OS)', 'formidable') . "</th><td>". $data['browser']."</td></tr>";
                $odd = ($odd) ? false : true;
                $content .= "<tr class='".(($odd) ? 'odd' : 'even')."'><th>".__('Referrer', 'formidable') . "</th><td>". str_replace("\r\n", '<br/>', $data['referrer']) ."</td></tr>";
            }
			elseif($type == 'block')
			{
				$content .= "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>" . $fname ."</div><div class='value'>$val</div></div>";
				
                $content .= "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>". __('IP Address', 'formidable') . "</div><div class='value'>". $entry->ip ."</div></div>";
                $odd = ($odd) ? false : true;
                $content .= "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>".__('User-Agent (Browser/OS)', 'formidable') . "</th><td>". $data['browser']."</div></div>";
                $odd = ($odd) ? false : true;
                $content .= "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>".__('Referrer', 'formidable') . "</th><td>". str_replace("\r\n", '<br/>', $data['referrer']) ."</div></div>";				
			}
			elseif($type == 'array')
			{
				$array['user_info']['ip']			= $entry->ip;
				$array['user_info']['user_agent']	= $data['browser'];
				$array['user_info']['referrer']		= str_replace("\r\n", '<br/>', $data['referrer']);				
			}
			else if($type == 'return')
			{
				$content[] = "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>" . $fname ."</div><div class='value'>$val</div></div>";
				
                $content[] = "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>". __('IP Address', 'formidable') . "</div><div class='value'>". $entry->ip ."</div></div>";
                $odd = ($odd) ? false : true;
                $content[] = "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>".__('User-Agent (Browser/OS)', 'formidable') . "</th><td>". $data['browser']."</div></div>";
                $odd = ($odd) ? false : true;
                $content[] = "<div class='container ".(($odd) ? 'odd' : 'even')."'><div class='title'>".__('Referrer', 'formidable') . "</th><td>". str_replace("\r\n", '<br/>', $data['referrer']) ."</div></div>";								
			}
        }

        if(!$plain_text && $type === false)
		{
            $content .= "</tbody></table>";
		}
		elseif($type == 'array')
		{
			return $array;	
		}
        
        return $content;
    }

    private static function get_table_data($field, $table_html, $table_data)
    {
    	$table_cells = FrmPlusFieldsHelper::get_table_options($field->options);

		return array(
			'title' => $field->name,
			'table_html' => $table_html,
			'table_cells' => $table_cells,
			'table_data' => $table_data,						
		);    	
    }
	
    public static function email_value($value, $meta, $entry){
        global $frm_field, $frm_entry;
        
        if($entry->id != $meta->item_id)
            $entry = $frm_entry->getOne($meta->item_id);
        
        $field = $frm_field->getOne($meta->field_id);
        if(!$field)
            return $value;
            
        $field->field_options = maybe_unserialize($field->field_options);
        
        if(isset($field->field_options['post_field']) and $field->field_options['post_field']){
            $value = FrmProEntryMetaHelper::get_post_or_meta_value($entry, $field, array('truncate' => true));
            $value = maybe_unserialize($value);
        }
        
        switch($field->type){
            case 'user_id':
                $value = FrmProFieldsHelper::get_display_name($value);
                break;
            case 'data':
                if (is_array($value)){
                    $new_value = array();
                    foreach($value as $val)
                        $new_value[] = FrmProFieldsHelper::get_data_value($val, $field);
                    $value = $new_value;
                }else{
                    $value = FrmProFieldsHelper::get_data_value($value, $field);
                }
                break;
            case 'file':
                $value = self::get_file_name($value);                
                break;
            /*case 'date':
                $value = FrmProFieldsHelper::get_date($value);*/
        }
        
        if (is_array($value)){
            $new_value = '';
            foreach($value as $val){
                if (is_array($val))
                    $new_value .= implode(', ', $val) . "\n";
            }
            if ($new_value != '')
                $value = $new_value;
        }
        
        return $value;
    }
	
   public static function get_file_name($media_ids, $short = true){
        $value = '';
        foreach((array)$media_ids as $media_id){
            if ( is_numeric($media_id) ) {
                $attachment = get_post($media_id);
                if(!$attachment)
                    continue;
                
                $url = wp_get_attachment_url($media_id);               
				$label = ($short) ? basename($attachment->guid) : $url;

				$value = array(
					'name' => $label,
					'url' => $url, 
					'path' => str_replace(site_url() .'/', ABSPATH, $url),
				);
        	}
	    }		
	    return $value;
    }		
	
	/*
	 * Function to convert form items such as dropdown boxes, radio buttons and checkboxes 
	 * into names instead of values
	 */
	public static function convert_values_to_name($val, $options)
	{
		/*
		 * Safeguard against $val not being an array
		 */
		 $array = array();
		if(!is_array($val))
		{
			$array[] = $val;	
		}
		else
		{
			$array = $val;	
		}
		
		if(sizeof($options) > 0)
		{

			foreach($options as $op)
			{				
				if(empty($op['value']) && empty($op['label']))
				{
					continue;
				}
			
				/*
				 * Check if the option value is in the $val array
				 */	
				 $key = array_search($op['value'], $array);

				 if($key !== false)
				 {
						 /*
						  * Found a match, so replace with the correct label
						  */						
						  $array[$key] = $op['label'];

				 }
			}
		}
		
		return $array;
	}
	
	/** 
	 * Function to display tick or cross for a radioline or an option-less
	 * checkbox or radio field in Formidable Plus
	 */
	public static function convert_checkboxes_to_image($data)
	{
		static $tick, $cross;
		if ( !isset( $tick ) ){
			$tick  = apply_filters( 'frmplus_pdf_tick', '<img alt="Yes" width="16" src="'.FP_PDF_PLUGIN_DIR.'images/tick.png" />' );
			$cross = apply_filters( 'frmplus_pdf_cross', '<img alt="No" width="16" src="'.FP_PDF_PLUGIN_DIR.'images/cross.png" />' );
		}
		extract( $data );
		if ( empty( $options['options'] ) ){
			// There are no options for this cell, which means it is either on or off
			// Display $tick or $cross accordingly
			echo $value == FrmPlusFieldsHelper::get_simple_on_value() ? $tick : $cross;
		}
		else{
			// Default to how we would display the value normally
			if (is_array($value)){
				echo implode(', ',$value);
			}
			elseif ($value == ''){
				echo '&nbsp;';
			}
			else{
				echo str_replace("\n","<br/>",$value);
			}
		}
	 }
}
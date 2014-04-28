<?php


class FPPDFRender
{
	/**
	 * Outputs a PDF entry from a Gravity Form
	 * var $form_id integer: The form id
	 * var $lead_id integer: The entry id
	 * var $output string: either view, save or download
	 * save will save a copy of the PDF to the server using the FP_PDF_SAVE_LOCATION constant
	 * var $return boolean: if set to true 
	 it will return the path of the saved PDF
	 * var $template string: if you want to use multiple PDF templates - name of the template file
	 * var $pdfname string: allows you to pass a custom PDF name to the generator e.g. 'Application Form.pdf' (ensure .pdf is appended to the filename)
	 * var $fpdf boolean: custom hook to allow the FPDF engine to generate PDFs instead of DOMPDF. Premium Paid Feature.
	 */
	public function PDF_Generator($form_id, $lead_id, $arguments = array())
	{
		/* 
		 * Because we merged the create and attach functions we need a measure to only run this function once per session per lead id. 
		 */
		static $pdf_creator = array();	

		/*
		 * Set user-variable to output HTML instead of PDF
		 */		
		 $html = (isset($_GET['html'])) ? (int) $_GET['html'] : 0;

		/*
		 * Join the form and lead IDs together to get the real ID
		 */
		$id = $form_id . $lead_id;
		
		/* 
		 * PDF_Generator was becoming too cluttered so store all the variables in an array 
		 */
		 $filename = $arguments['pdfname'];
		 $template = $arguments['template'];		 
		 $output = (isset($arguments['output']) && strlen($arguments['output']) > 0) ? $arguments['output'] : 'save';

		/* 
		 * Check if the PDF exists and if this function has already run this season 
		 */	

		if(in_array($lead_id, $pdf_creator) && file_exists(FP_PDF_SAVE_LOCATION.$id.'/'. $filename))
		{
			/* 
			 * Don't generate a new PDF, use the existing one 
			 */
			return FP_PDF_SAVE_LOCATION.$id.'/'. $filename;	
		}
		
		/*
		 * Add lead to PDF creation tracker
		 */
		$pdf_creator[] = $lead_id;

		/*
		 * Add filter before we load the template file so we can stop the main process
		 * Used in premium plugins
		 * return true to cancel, otherwise run.
		 */	 
		 $return = apply_filters('fppdfe_pre_load_template', $form_id, $lead_id, $template, $id, $output, $filename, $arguments);

		if($return !== true)
		{
			/*
			 * Get the tempalte HTML file
			 */
			$entry = $this->load_entry_data($form_id, $lead_id, $template);					

			/*
			 * Output HTML version and return if user requested a HTML version
			 */		 
			if($html === 1)
			{
				echo $entry;
				exit;	
			}
		
			/*
			 * If successfully got the entry then run the processor
			 */
			if(strlen($entry) > 0)
			{
				return $this->PDF_processing($entry, $filename, $id, $output, $arguments);
			}
	
			return false;
		}
		/*
		 * Used in extensions to return the name of the PDF file when attaching to notifications
		 */
		return apply_filters('fppdfe_return_pdf_path', $form_id, $lead_id);
	}
	
	/**
	 * Loads the Gravity Form output script (actually the print preview)
	 */
	private function load_entry_data($form_id, $lead_id, $template)
	{
		/* set up contstants for Formidable Pro to use so we can override the security on the printed version */		
		if(file_exists(FP_PDF_TEMPLATE_LOCATION.$template))
		{	
			return FPPDF_Common::get_html_template(FP_PDF_TEMPLATE_LOCATION.$template);
		}
		else
		{
			/*
			 * Check if template file exists in the plugin's core template folder
			 */
			if(file_exists(FP_PDF_PLUGIN_DIR."templates/" . $template))
			{
				return FPPDF_Common::get_html_template(FP_PDF_PLUGIN_DIR."templates/" . $template);
			}
			/*
			 * If template not found then we will resort to the default template.
			 */			
			else
			{
				return FPPDF_Common::get_html_template(FP_PDF_PLUGIN_DIR."templates/" . FPPDFGenerator::$default['template']);
			}
		}		
	}

	
	/**
	 * Creates the PDF and does a specific output (see PDF_Generator function above for $output variable types)
	 */
	public function PDF_processing($html, $filename, $id, $output = 'view', $arguments)
	{
		/* 
		 * DOMPDF replaced with mPDF in v3.0.0 
		 * Check which version of mpdf we are calling
		 * Full, Lite or Tiny
		 */
		 if(!class_exists('mPDF'))
		 {
			 if(FP_PDF_ENABLE_MPDF_TINY === true)
			 {
					include FP_PDF_PLUGIN_DIR .'/mPDF/mpdf-extra-lite.php';			 
			 }
			 elseif(FP_PDF_ENABLE_MPDF_LITE === true)
			 {
					include FP_PDF_PLUGIN_DIR .'/mPDF/mpdf-lite.php';			 
			 }
			 else
			 {	 		
					include FP_PDF_PLUGIN_DIR .'/mPDF/mpdf.php';
			 }
		 }
		
		/* 
		 * Initialise class and set the paper size and orientation
		 */
		 $paper_size = $arguments['pdf_size'];
		
		 
		 if(!is_array($paper_size))
		 {
			 $orientation = ($arguments['orientation'] == 'landscape') ? '-L' : '';
			 $paper_size = $paper_size.$orientation;
		 }
		 else
		 {
		 	$orientation = ($arguments['orientation'] == 'landscape') ? 'L' : 'P';			 			
		 }
		 
		 $mpdf = new mPDF('', $paper_size, 0, '', 15, 15, 16, 16, 9, 9, $orientation);
		
		/*
		 * Display PDF is full-page mode which allows the entire PDF page to be viewed
		 * Normally PDF is zoomed right in.
		 */
		$mpdf->SetDisplayMode('fullpage');			
		
		if(FP_PDF_ENABLE_SIMPLE_TABLES === true)
		{
				$mpdf->simpleTables = true;	
		}
		
		/*
		 * Automatically detect fonts and substitue as needed
		 */
		if(FP_PDF_DISABLE_FONT_SUBSTITUTION === true)
		{
			$mpdf->useSubstitutions = false;		
		}	
		else
		{	 
			$mpdf->SetAutoFont(AUTOFONT_ALL);
			$mpdf->useSubstitutions = true;
		}
		
		/*
		 * Set Creator Meta Data
		 */
		
		$mpdf->SetCreator('Formidable Pro PDF Extended v'. FP_PDF_EXTENDED_VERSION.'. http://formidablepropdfextended.com');	

		/*
		 * Set RTL languages at user request
		 */ 
		 if($arguments['rtl'] === true)
		 {
		 	$mpdf->SetDirectionality('rtl');
		 }

		/*
		 * Set up security if user requested
		 */ 
		 if($arguments['security'] === true && $arguments['pdfa1b'] !== true && $arguments['pdfx1a'] !== true)
		 {
				$password = (strlen($arguments['pdf_password']) > 0) ? $arguments['pdf_password'] : '';
				$master_password = (strlen($arguments['pdf_master_password']) > 0) ? $arguments['pdf_master_password'] : null;
				$pdf_privileges = (is_array($arguments['pdf_privileges'])) ? $arguments['pdf_privileges'] : array();	
				
				$mpdf->SetProtection($pdf_privileges, $password, $master_password, 128);											
		 }

		 /* PDF/A1-b support added in v3.4.0 */
		 if($arguments['pdfa1b'] === true)
		 {
		 		$mpdf->PDFA = true;
		 		$mpdf->PDFAauto = true;
		 }
		 else if($arguments['pdfx1a'] === true)  /* PDF/X-1a support added in v3.4.0 */
		 {
		 		$mpdf->PDFX = true;
			 	$mpdf->PDFXauto = true;
		 }		 
		 	 
		/* load HTML block */
		$mpdf->WriteHTML($html);			
		
		switch($output)
		{
			case 'download':
				 $mpdf->Output($filename, 'D');
				 exit;
			break;
			
			case 'view':
				 $mpdf->Output(time(), 'I');
				 exit;
			break;
			
			case 'save':
				/*
				 * PDF wasn't writing to file with the F method - http://mpdf1.com/manual/index.php?tid=125
				 * Return as a string and write to file manually
				 */					
				$pdf = $mpdf->Output('', 'S');
				return $this->savePDF($pdf, $filename, $id);				 
			break;
		}
	}
	 
	
	/**
	 * Creates the PDF and does a specific output (see PDF_Generator function above for $output variable types)
	 * var $dompdf Object
	 */
	 public function savePDF($pdf, $filename, $id) 
	 {			
		/* create unique folder for PDFs */
		if(!is_dir(FP_PDF_SAVE_LOCATION.$id))
		{
			if(!mkdir(FP_PDF_SAVE_LOCATION.$id))
			{
				trigger_error('Could not create PDF folder in '. FP_PDF_SAVE_LOCATION.$id, E_USER_WARNING);				
				return;
			}
		}	
		
		$pdf_save = FP_PDF_SAVE_LOCATION.$id.'/'. $filename;			
				
		if(!file_put_contents($pdf_save, $pdf))
		{
			trigger_error('Could not save PDF to '. $pdf_save, E_USER_WARNING);
			return;
		}
		return $pdf_save;
	}
}


(function($) {
	/*
	 * Function to get the URL parameters from the browser address
	 */	
	function getURLParameter(name) {
		return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
	}	
	
	function initialise_ajax_success(results)
	{
						$('.spinner').remove();		
									
						$('.fppdfe_message').slideUp();			
										
						/*
						 * Return Output
						 */ 
						 if(results.error)
						 {
							 $('#fppdfextended-setting').prepend(results.error).find('.fppdfe_message').delay(5000).slideUp(500, function() {
								$(this).remove(); 
							 });							 
						 }
						 else if(results.message)
						 {
							 $('#fppdfextended-setting').prepend(results.message).find('.fppdfe_message').delay(5000).slideUp(500, function() {
								$(this).remove(); 
							 });
						 }
						 else if(results.form)
						 {
							/* we need to get the FTP/SSH login details from the client before proceeding */ 
							 $('#fppdfextended-setting').prepend(results.form);
							 
							 /* Add AJAX POST handler to the submit button */
						 	 $('#fppdfextended-setting #upgrade').click(function() {
									 $('<span class="spinner" style="display: inline-block; margin-top:0; float: none;">').insertAfter($(this));
									 /*
									  * Load WP Spinner beside buttons
									  */
									jQuery.ajax({				
										type: "POST",				
										url: ajaxurl, 
										dataType: 'json',							
										data: {
											action: 'fppdfe_initialise',
											nonce: FPPDFE.nonce,
											hostname: $('#fppdfextended-setting #hostname').val(), 
											username: $('#fppdfextended-setting #username').val(),
											password: $('#fppdfextended-setting #password').val(),	
											ftp: $('#fppdfextended-setting #ftp').val(),
											ftps: $('#fppdfextended-setting #ftps').val()										
										},				
						
										success:function(results) {		
											$('#fppdfextended-setting form').remove();
											initialise_ajax_success(results);									
										}
									});
									
									return false;
							 });
						 }		
	}
	
	function font_ajax_success(results) {
			$('.spinner').remove();	
			$('.fppdfe_message').slideUp();							
			/*
			 * Return Output
			 */ 
			 if(results.message)
			 {		 	
				 $('#fppdfextended-setting').prepend(results.message).find('.fppdfe_message').delay(5000).slideUp(500, function() {
					$(this).remove(); 
				 });
			 }
			 else if(results.form)
			 {
				/* we need to get the FTP/SSH login details from the client before proceeding */ 
				 $('#fppdfextended-setting').prepend(results.form);
				 
				 /* Add AJAX POST handler to the submit button */
				 $('#fppdfextended-setting #upgrade').click(function() {
						 $('<span class="spinner" style="display: inline-block; margin-top:0; float: none;">').insertAfter($(this));
						 /*
						  * Load WP Spinner beside buttons
						  */
						jQuery.ajax({				
							type: "POST",				
							url: ajaxurl, 
							dataType: 'json',							
							data: {
								action: 'fppdfe_initialise_font',
								nonce: FPPDFE.nonce,
								hostname: $('#fppdfextended-setting #hostname').val(), 
								username: $('#fppdfextended-setting #username').val(),
								password: $('#fppdfextended-setting #password').val(),	
								ftp: $('#fppdfextended-setting #ftp').val(),
								ftps: $('#fppdfextended-setting #ftps').val()										
							},				
			
							success:function(results) {		
								$('#fppdfextended-setting form').remove();
								initialise_ajax_success(results);									
							}
						});
						
						return false;
				 });
			 }			 		
	}
	
	 $(document).ready(function() {
		/*
		 * Check if our PDF Hash is available and show the correct settings page
		 */
		 
		 if(getURLParameter('page') == 'formidable-settings')
		 {		 		 
			 if(window.location.hash == '#PDF_settings')
			 {
				/*
				 * Show the content panel
				 */ 
				$('.tabs-panel').hide();
				$('#PDF_settings, .PDF_settings').show(); 
				
				/*
				 * Set up the tabs
				 */
				 $('.frm-category-tabs .active').removeClass('active');
				 $(".frm-category-tabs [href='#PDF_settings']").parent().addClass('active');				 
			 }			 			 
				 
			/*
			 * Remove submit button FP auto adds
			 */ 
			 $('#PDF_settings .submit').remove();
			 $('#PDF_settings .frm_uninstall').remove();
			 
			/*
			 * Add AJAX for initialisation
			 */ 
			 $('#plugin-initialise').click(function() {
				 
				 /*
				  * If AJAX call already happening exit early
				  */
				 var $spinner = $('#PDF_settings .spinner');
				 if($spinner.length > 0)
				 {
					return false; 
				 }
				 $('#fppdfextended-setting form').remove();
				 				 
				 $('<span class="spinner" style="display: inline-block; margin-top:0; float: none;">').insertAfter($('#font-initialise'));
				 /*
				  * Load WP Spinner beside buttons
				  */
				jQuery.ajax({				
					type: "POST",				
					url: ajaxurl, 
					dataType: 'json',							
					data: {
						action: 'fppdfe_initialise',
						nonce: FPPDFE.nonce
					},				
	
					success:function(results) {
						initialise_ajax_success(results);
					}				
				});					 
				 
				return false; 
			 });
			 
			 
			 $('#font-initialise').click(function() {
				 /*
				  * If AJAX call already happening exit early
				  */
				 var $spinner = $('#PDF_settings .spinner');
				 if($spinner.length > 0)
				 {
					return false; 
				 }
				 
				 $('#fppdfextended-setting form').remove();
				 
				 $('<span class="spinner" style="display: inline-block; margin-top:0; float: none;">').insertAfter($('#font-initialise'));
				 /*
				  * Load WP Spinner beside buttons
				  */
				jQuery.ajax({				
					type: "POST",	
					dataType: 'json',
					url: ajaxurl,
					data: {
						action : 'fppdfe_initialise_font',
						nonce: FPPDFE.nonce
					},				
	
					success:function(results) {		
						font_ajax_success(results);
					}				
				});					 
				 
				return false; 
			 });

		 }
		 
		 if(getURLParameter('page') == 'formidable-entries')
		 {
		  		$('.fp_has_submenu > a').hover(function() {
					$(this).next().show();					
				},
				function() {
					var that = $(this);
					setTimeout(function() {
						var box = that.next();
						if(!box.hasClass('active'))
						{
							that.next().hide();
						}
					},250)
				});
				
				$('.fp_has_submenu .fp_submenu').hover(function() {
					$(this).addClass('active');	
				},function() {
					var that = $(this);
					setTimeout(function() {
						that.removeClass('active').hide();						
					},250)					
				});
		 }
		 
	 });
	
})(jQuery);
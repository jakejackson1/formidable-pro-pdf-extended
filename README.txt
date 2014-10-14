=== Plugin Name ===
Contributors: blueliquiddesigns
Donate link: http://www.formidablepropdfextended.com
Tags: formidable, pro, pdf, extended, automation, attachment
Requires at least: 3.9
Tested up to: 3.9.1
Stable tag: 1.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Formidable Pro PDF Extended allows you to save/view/download a PDF from the front- and back-end, and automate PDF creation on form submission. 

== Description ==

Automatically generate and email PDFs using Wordpress and Formidable Pro. Formidable Pro PDF Extended streamlines your business paperwork by taking it all online, letting you get on with more important things.

**Features**

* Save PDF File on user submission of a Formidable Pro form so it can be attached to a notification
* Customise the PDF template without affecting the core Formidable Pro Plugin
* Multiple PDF Templates
* Custom PDF Name
* Output individual form fields in the template
* View and download a PDF via the administrator interface
* Simple function to output PDF via template / plugin
* Works with Formidable Pro Signature Add-On
* Works with Formidable Plus Add-On

**PDF Features**

Along with the above features, the PDF features include:

* Language Support - almost all languages are supported including RTL (right to left) languages like Arabic and Hebrew and CJK languages - Chinese, Japanese and Korean.
* HTML Page Numbering
* Odd and even paging with mirrored margins (most commonly used in printing).
* Nested Tables
* Text-justification and hyphenation
* Table of Contents
* Index
* Bookmarks
* Watermarks
* Password protection
* UTF-8 encoded HTML
* Better system resource handling

**Server Requirements**

1. PHP 5+
2. MB String
3. GD Library (optional)
4. RAM:	Recommended: 128MB. Minimum: 64MB.

**Software Requirements**

1. [Purchase and install Formidable Pro](http://formidablepro.com/index.php?plugin=wafp&controller=links&action=redirect&l=formidable-pro&a=blue%20liquid%20designs)
2. Wordpress 3.6+
3. Formidable Pro 1.07.01+

**Documentation and Support**

To view the Development Documentation head to [http://www.formidablepropdfextended.com/documentation/](http://formidablepropdfextended.com/documentation/). If you need support with the plugin please post a topic in our [support forums](http://formidablepropdfextended.com/support/formidable-pro-pdf-extended/).

== Installation ==

1. Upload this plugin to your website and activate it
2. Create a form in Formidable Pro and configure your emails.
3. Get the Form ID and follow the steps in [the configuration section](http://formidablepropdfextended.com/documentation-v1/installation-and-configuration/)
4. Modify the PDF template file ([see the advanced templating section in the documentation](http://formidablepropdfextended.com/documentation-v1/templates/)) inside your active theme's FORMIDABLE_PDF_TEMPLATES/ folder.


== Frequently Asked Questions ==

All FAQs can be [viewed on the Formidable Pro PDF Extended website](http://formidablepropdfextended.com/faq/category/developers).  

== Screenshots ==

#1. View PDF from the Formidable Pro entries list.
#2. View or download the PDF from a Formidable Pro entry.

== Changelog ==

= 1.5.3 =
* Fixed up PHP notices when form fields are empty in PDF

= 1.5.2 =
* Fix up javascript error on initialisation page

= 1.5.1 =
* Included fallback for PHP5.3+ function array_replace_recursive()
* Fix up initialisation which wasn't running correctly for some users

= 1.5.0 =
* $form_data array had a new 'options' key added to radio, select and checkboxes which includes ALL avaliable options in the form. 
* Most fields had a new 'type' key added, which allows you to easily determine what field type it is. 
* Fixed bug where Formidable Plus key was still using the old field ID access key instead of new field key access key.

= 1.4.0 =
* Update mPDF package to latest version. Based on testing, it shouldn't have any major effect on custom templates but does correct padding/margin bugs (which could throw off some heavily-customised templates). 
* Added a 'return' type when calling FPPDF_Entry::show_entry() which allowed for greater manipulation of the output
* Added 'section headings' to the default template (previously ignored). These will be added with <h2> tags. 
* Added ability to exclude fields from the default template by using the 'exclude' class on a form field. 

= 1.3.0 =
* Changed $form_data array keys to use the unique field key instead of the field ID. This means if you are importing and exporting forms across multiple website your PDF template will function without any additional changes. 

Note: This release isn't backwards compatible with custom PDF templates.

= 1.2.2 = 
* Fix multi PDF dropdown view cutting off
* Fix mPDF UTF-8 error

= 1.2.1 =
* Added auto 'print' prompt feature when PDF opens. Create a link to the PDF and add &print=1 to the URL. Doesn't work with security features enabled.
* Fixed initilisation notice link so the settings page shows correctly. Previously blank.
* Fixed problem with the $fp_pdf_default_configuration settings correctly filter down on forms without configuration nodes 
* Fixed problem setting FPPDF_SET_DEFAULT_TEMPLATE to true.
* Fixed problem with entries creating and saving PDFs when no node was configured.
* Fixed problem with PDFs being created and saved when the node wasn't being attached to the notification
* Fixed issue displaying font initialisation message.
* Fixed issue using custom font files in PDF.
* Tidied up the configuration.php file
* Thanks to the author of Formidable Plus (Trevor Mills) for integrating his updated plugin's table format into the software. This will make his table field much more maintainable as it evolves.
* Special thanks to Thom Stark for his continual feedback and support. 


= 1.2.0 =
* Fixed date display issue
* Updated file upload $form_data value
* Added PDF/A1b and PDF/X1a support 
* Added filters for all the configuration options

= 1.1.0 =
* Fix problem with logged in users (any user with a role below editor) viewing PDFs using our front-end URL 
* Updated radio buttons, dropdowns and checkboxes so that they default to the name and not value
* Updated $form_data to include both name and value for radio buttons, dropdowns and checkboxes
* Fix compatibility problem with Gravity Forms PDF Extended activated at the same time as Formidable Pro PDF Extended
* Add PDF View/Download box to the view single entry page in the admin area
* Added a shortcode that can be used in Custom Displays. The format is [pdf] [pdf download="1" template="example-template.php"] and [pdf]This is the link text[/pdf] - or any of those combinations.
* Added support for Formidable Plus
* Fixed multi-view PDF feature in the admin area

= 1.0.0 =
* First release. 

== Upgrade Notice ==


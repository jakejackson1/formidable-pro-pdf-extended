=== Plugin Name ===
Contributors: blueliquiddesigns
Donate link: http://www.formidablepropdfextended.com
Tags: formidable, pro, pdf, extended, automation, attachment
Requires at least: 3.6
Tested up to: 3.6.1
Stable tag: 1.2.1
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


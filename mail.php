<?php
/*
 *	Mail Extension for Automad
 *
 *	Copyright (c) 2017-2020 by Marc Anton Dahmen
 *	http://marcdahmen.de
 *
 *	Licensed under the MIT license.
 *	http://automad.org/license
 */


namespace Automad;

defined('AUTOMAD') or die('Direct access not permitted!');

/**
 *	The Mail extension provides a basic wrapper for the PHP function mail(), including optional human verification using a honeypot. 
 *
 *	@author Marc Anton Dahmen
 *	@copyright Copyright (c) 2017-2020 Marc Anton Dahmen - <http://marcdahmen.de>
 *	@license MIT license - http://automad.org/license
 */

class Mail {
	
	
	/**
	 * 	The main mail function. 
	 * 
	 *  @param array $options
	 *  @param object $Automad
	 *  @return string Success or error message
	 */
	
	public function mail($options, $Automad) {
		
		$defaults = array(
			'to' => false,
			'error' => '<b>Please fill out all fields!</b>',
			'success' => '<b>Successfully sent email!</b>'
		);
		
		// Merge defaults with options.
		$options = array_merge($defaults, $options);
		
		// Define field names.
		$honeypot = 'human';
		$from = 'from';
		$subject = 'subject';
		$message = 'message';
		
		// Basic checks.
		if (empty($_POST) || empty($options['to'])) {
			return false;
		}
	
		// Check optional honeypot to verify human.
		if (isset($_POST[$honeypot]) && $_POST[$honeypot] != false) {
			return false;
		}
	
		// Check if form fields are not empty.
		if (empty($_POST[$from]) || empty($_POST[$subject]) || empty($_POST[$message])) {
			return $options['error'];
		}
	
		// Prepare mail.
		$subject = $Automad->Shared->get(AM_KEY_SITENAME) . ': ' . strip_tags($_POST[$subject]);
		$message = strip_tags($_POST[$message]);
		$header = 'From: ' . preg_replace('/[^\w\d\.@\-]/', '', $_POST[$from]);
	
		if (mail($options['to'], $subject, $message, $header)) {
			return $options['success'];
		}
			
	}
	
		
}

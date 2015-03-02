<?php

/**
 * Class for automatic framework updates
 * 
 * @package StagFramework
 * @return void
 */

class StagFrameworkUpdater{

	function __construct() {
		add_action( 'admin_init', array( &$this, 'init' ) );
	}

	function init() {

	}

	function check_for_updates() {

	}

	function has_update() {
		if( $this->check_for_updates == true ) {
			return true;
		}
		return false;
	}

	function when_checked_last() {
		
	}
	
}

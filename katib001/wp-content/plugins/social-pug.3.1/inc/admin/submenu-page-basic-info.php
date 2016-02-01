<?php 
	/*
	 * Function that creates the sub-menu item and page for the basic information page
	 *
	 *
	 * @return void
	 *
	 */
	function dpsp_register_basic_info_subpage() {
		add_submenu_page( 'dpsp-social-pug', __('Basic Information', 'social-pug'), __('Basic Information', 'social-pug'), 'manage_options', 'dpsp-basic-information', 'dpsp_basic_information_subpage' );
	}
	add_action( 'admin_menu', 'dpsp_register_basic_info_subpage' );


	/*
	 * Function that adds content to the basic infomartion subpage
	 *
	 * @return string
	 *
	 */
	function dpsp_basic_information_subpage() {

		include_once 'views/view-submenu-page-basic-info.php';

	}
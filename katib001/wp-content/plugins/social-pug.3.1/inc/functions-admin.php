<?php

	/*
	 * Function that displays the HTML for a settings field
	 *
	 */
	function dpsp_settings_field( $type, $name, $saved_value = '', $label = '', $options = array(), $tooltip = '' ) {

		$settings_field_slug = ( !empty($label) ? strtolower(str_replace(' ', '-', $label)) : '' );

		echo '<div class="dpsp-setting-field-wrapper ' . ( !empty($label) ? 'dpsp-setting-field-' . $settings_field_slug : '' ) . '">';

		switch( $type ) {

			// Display input type text
			case 'text':

				echo !empty( $label ) ? '<label for="' . $name . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';

				echo '<input type="text" ' . ( isset( $label ) ? 'id="' . $name . '"' : '' ) . ' name="' . $name . '" value="' . $saved_value . '" />';
				break;

			// Display input type radio
			case 'radio':

				echo !empty( $label ) ? '<label class="dpsp-setting-field-label">' . $label . '</label>' : '';
				
				if( !empty( $options ) ) {
					foreach( $options as $option_value => $option_name ) {
						echo '<label class="dpsp-settings-field-radio">';
						echo '<input type="radio" name="' . $name . '" value="' . $option_value . '" ' . checked( $option_value, $saved_value, false ) . ' />';
						echo ( isset( $option_name ) ? $option_name : $option_value );
						echo '</label>';
					}
				}
				break;

			// Display input type checkbox
			case 'checkbox':
			
				// If no options are passed make the main label as the label for the checkbox
				if( count( $options ) == 1 ) {

					if( is_array( $saved_value ) )
						$saved_value = $saved_value[0];

					echo !empty( $label ) ? '<label for="' . $name . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';
					echo '<input type="checkbox" ' . ( isset( $label ) ? 'id="' . $name . '"' : '' ) . ' name="' . $name . '" value="' . esc_attr( $options[0] ) . '" ' . checked( $options[0], $saved_value, false ) . ' />';

				// Else display checkboxes just like radios
				} else {

					echo !empty( $label ) ? '<label class="dpsp-setting-field-label">' . $label . '</label>' : '';

					if( !empty( $options ) ) {
						foreach( $options as $option_value => $option_name ) {
							echo '<label class="dpsp-settings-field-checkbox">';
							echo '<input type="checkbox" name="' . $name . '" value="' . $option_value . '" ' . ( in_array( $option_value, $saved_value ) ? 'checked' : '' ) . ' />';
							echo ( isset( $option_name ) ? $option_name : $option_value );
							echo '</label>';
						}
					}

				}
				break;

			case 'select':

				echo !empty( $label ) ? '<label for="' . $name . '" class="dpsp-setting-field-label">' . $label . '</label>' : '';
				echo '<select id="' . $name . '" name="' . $name . '">';

					foreach( $options as $option_value => $option_name ) {
						echo '<option value="' . $option_value . '" ' . selected( $saved_value, $option_value, false ) . '>' . $option_name . '</option>';
					}

				echo '</select>';

				break;

		} // end of switch

		if( !empty( $tooltip ) ) {

			dpsp_output_backend_tooltip( $tooltip );

		}

		do_action( 'dpsp_inner_after_settings_field', $settings_field_slug, $type, $name );

		echo '</div>';

	}


	/*
	 * Returns the HTML output with the selectable networks
	 *
	 */
	function dpsp_output_selectable_networks( $settings_networks ) {

		$networks = dpsp_get_networks();

		$output = '<div id="dpsp-networks-selector-wrapper">';

			$output .= '<ul id="dpsp-networks-selector">';

				if( !empty($networks) ) {
					foreach( $networks as $network_slug => $network_name ) {
						$output .= '<li>';
							$output .= '<div class="dpsp-network-item" data-network="' . $network_slug . '" data-network-name="' . $network_name . '" ' . ( isset( $settings_networks[$network_slug] ) ? 'data-checked="true"' : '' ) . '>';
							$output .= '<div class="dpsp-network-item-checkbox dpsp-icon-ok"></div>';
							$output .= '<div class="dpsp-network-item-name-wrapper dpsp-network-' . $network_slug . '">';
								$output .= '<span class="dpsp-list-icon dpsp-list-icon-social dpsp-icon-' . $network_slug . '"><!-- --></span>';
								$output .= '<h4>' . $network_name . '</h4>';
							$output .= '</div>';
						$output .= '</li>';
					}
				}

			$output .= '</ul>';

			$output .= '<div id="dpsp-networks-selector-footer">';
				$output .= '<a href="#" class="button button-primary">Apply Selection</a>';
			$output .= '</div>';

		$output .= '</div>';

		return $output;
	}


	/*
	 * Returns the HTML output with the sortable networks
	 *
	 */
	function dpsp_output_sortable_networks( $networks, $settings_name ) {

		$output = '<ul class="dpsp-social-platforms-sort-list sortable">';

			if( !empty($networks) ) {
				foreach( $networks as $network_slug => $network ) {
					$output .= '<li data-network="' . $network_slug . '">';
						$output .= '<div class="dpsp-sort-handle"><!-- --></div>';
						$output .= '<div class="dpsp-list-icon dpsp-list-icon-social dpsp-icon-' . $network_slug . '"><!-- --></div>';
						$output .= '<div class="dpsp-list-input-wrapper">';
							$output .= '<input class="dpsp-transition" name="' . $settings_name . '[networks][' . $network_slug . '][label]" value="' . ( isset( $network['label'] ) ? esc_attr( $network['label'] ) : dpsp_get_network_name( $network_slug ) ) . '" />';
							$output .= '<span class="dpsp-icon dpsp-icon-edit dpsp-transition"></span>';
						$output .= '</div>';
						$output .= '<a class="dpsp-list-remove dpsp-list-icon dpsp-icon-remove dpsp-transition" href="#"><!-- --></a>';
					$output .= '</li>';
				}
			}

		$output .= '</ul>';

		return $output;
	}


	/*
	 * Outputs the HTML of the tooltip
 	 *
	 * @param string tooltip - the text of the tooltip
	 * @param bool $return 	 - wether to return or to output the HTML
	 *
	 */
	function dpsp_output_backend_tooltip( $tooltip = '', $return = false ) {

		$output = '<div class="dpsp-setting-field-tooltip-wrapper ' . ( ( strpos( $tooltip,  '</a>' ) !== false ) ? 'dpsp-has-link' : '' ) . '">';
			$output .= '<span class="dpsp-setting-field-tooltip-icon"></span>';
			$output .= '<div class="dpsp-setting-field-tooltip dpsp-transition">' . $tooltip . '</div>';
		$output .= '</div>';

		if( $return )
			return $output;
		else
			echo $output;

	}


	/*
	 * Display admin notices for our pages
	 *
	 */
	function dpsp_admin_notices() {

		// Exit if settings updated is not present
		if( !isset( $_GET['settings-updated'] ) )
			return;

		$admin_page = ( isset( $_GET['page'] ) ? $_GET['page'] : '' );

		// Show these notices only on dpsp pages
		if( strpos( $admin_page, 'dpsp' ) === false || $admin_page == 'dpsp-register-version' )
			return;

		// Get messages
		$message_id = ( isset( $_GET['dpsp_message_id'] ) ? $_GET['dpsp_message_id'] : 0 );
		$message 	= dpsp_get_admin_notice_message( $message_id );

		$class = ( isset( $_GET['dpsp_message_class'] ) ? $_GET['dpsp_message_class'] : 'updated' );;

		if( isset( $message ) ) {

			echo '<div class="dpsp-admin-notice notice is-dismissible ' . $class . '">';
	        	echo '<p>' . $message . '</p>';
	        echo '</div>';
		}

	}
	add_action( 'admin_notices', 'dpsp_admin_notices' );


	/*
	 * Returns a human readable message given a message id
	 *
	 * @param int $message_id
	 *
	 */
	function dpsp_get_admin_notice_message( $message_id ) {

		$messages = apply_filters( 'dpsp_get_admin_notice_message', array(
			__( 'Settings saved.', 'social-pug' ),
			__( 'Settings imported.', 'social-pug' ),
			__( 'Please select an import file.', 'social-pug' ),
			__( 'Import file is not valid.', 'social-pug' )
		));

		return $messages[ $message_id ];
	}


	/*
	 * Remove dpsp query args from the URL
	 *
	 * @param array $removable_query_args 	- the args that WP will remove
	 *
	 */
	function dpsp_removable_query_args( $removable_query_args ) {

		$new_args = array( 'dpsp_message_id', 'dpsp_message_class' );

		return array_merge( $new_args, $removable_query_args );

	}
	add_filter( 'removable_query_args', 'dpsp_removable_query_args' );


	/*
	 * Add admin notice on plugin activation
	 *
	 */
	function dpsp_admin_notice_first_activation() {

		// Get first activation of the plugin
		$first_activation = get_option( 'dpsp_first_activation', '' );

		if( empty($first_activation) )
			return;

		// Do not display this notice if user cannot activate plugins
		if( !current_user_can( 'activate_plugins' ) )
			return;

		// Do not display this notice if plugin has been activated for more than 1 minute
		if( time() - 5 * MINUTE_IN_SECONDS >= $first_activation )
			return;

		// Do not display this notice for users that have dismissed it
		if( get_user_meta( get_current_user_id(), 'dpsp_admin_notice_first_activation', true ) != '' )
			return;

		// Echo the admin notice
		echo '<div class="dpsp-admin-notice dpsp-admin-notice-activation notice">';

        	echo '<h4>' . __( 'Thank you for installing Social Pug. Let\'s start pumping up those social shares.', 'social-pug' ) . '</h4>';

        	echo '<a class="dpsp-admin-notice-link" href="' . add_query_arg( array( 'dpsp_admin_notice_activation' => 1 ), admin_url('admin.php?page=dpsp-basic-information') ) . '"><span class="dashicons dashicons-admin-settings"></span>' . __( 'Go to the Plugin', 'social-pug' ) . '</a>';
        	echo '<a class="dpsp-admin-notice-link" href="http://docs.devpups.com/?utm_source=plugin&utm_medium=plugin-activation&utm_campaign=social-pug" target="_blank"><span class="dashicons dashicons-book"></span>' . __( 'View Documentation', 'social-pug' ) . '</a>';
        	echo '<a class="dpsp-admin-notice-link" href="http://www.devpups.com/social-pug/?utm_source=plugin&utm_medium=plugin-activation&utm_campaign=social-pug" target="_blank"><span class="dashicons dashicons-external"></span>' . __( 'Upgrade to Premium', 'social-pug' ) . '</a>';

        	echo '<a href="' . add_query_arg( array( 'dpsp_admin_notice_activation' => 1 ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>';

        echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_first_activation' );


	/*
	 * Add admin notice to send a 5 star review on wp.org
	 *
	 */
	function dpsp_admin_notice_wp_review() {

		// Get first activation of the plugin
		$first_activation = get_option( 'dpsp_first_activation', '' );

		if( empty($first_activation) )
			return;

		// Do not display this notice if user cannot activate plugins
		if( !current_user_can( 'activate_plugins' ) )
			return;

		// Display the plugin only if 7 days have past since activation
		if( time() <= $first_activation + 7 * DAY_IN_SECONDS || time() >= $first_activation + 12 * DAY_IN_SECONDS )
			return;

		// Do not display this notice for users that have dismissed it
		if( get_user_meta( get_current_user_id(), 'dpsp_admin_notice_wp_review', true ) != '' )
			return;

		// Echo the admin notice
		echo '<div class="dpsp-admin-notice dpsp-admin-notice-wp-rating notice">';

        	echo '<h4>' . __( 'Thank you for using Social Pug!', 'social-pug' ) . '</h4>';

        	echo '<p>' . __( 'If you enjoy using <strong>Social Pug</strong> please leave us a <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span> review. Reviews like yours help us improve the plugin.', 'social-pug' ) . '</p>';

        	echo '<a class="dpsp-admin-notice-link" href="https://wordpress.org/support/view/plugin-reviews/social-pug?rate=5#postform" target="_blank"><span class="dashicons dashicons-edit"></span>' . __( 'Leave a Review', 'social-pug' ) . '</a>';

        	echo '<a href="' . add_query_arg( array( 'dpsp_admin_notice_wp_review' => 1 ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>'; 

        echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_wp_review' );


	/*
	 * Add admin notice to promote premium features
	 *
	 */
	function dpsp_admin_notice_to_premium() {

		// Get first activation of the plugin
		$first_activation = get_option( 'dpsp_first_activation', '' );

		if( empty($first_activation) )
			return;

		// Do not display this notice if user cannot activate plugins
		if( !current_user_can( 'activate_plugins' ) )
			return;

		// Display the plugin only if 7 days have past since activation
		if( time() <= $first_activation + 12 * DAY_IN_SECONDS )
			return;

		// Do not display this notice for users that have dismissed it
		if( get_user_meta( get_current_user_id(), 'dpsp_admin_notice_to_premium', true ) != '' )
			return;

		// Echo the admin notice
		echo '<div class="dpsp-admin-notice dpsp-admin-notice-wp-rating notice">';

        	echo '<h4>' . __( 'Increase your social shares even more!', 'social-pug' ) . '</h4>';

        	echo '<p>' . __( 'Pump up your social shares with the awesome features of <strong>Social Pug Premium</strong>.', 'social-pug' ) . '</p>';

        	echo '<p>' . __( 'Add buttons for mobile screens, add more social networks, add buttons in your content with shortcodes and more.', 'social-pug' ) . '</p>';

        	echo '<a class="dpsp-admin-notice-link" href="http://www.devpups.com/social-pug/?utm_source=plugin&utm_medium=to-premium&utm_campaign=social-pug" target="_blank"><span class="dashicons dashicons-external"></span>' . __( 'Get Premium Features', 'social-pug' ) . '</a>';

        	echo '<a href="' . add_query_arg( array( 'dpsp_admin_notice_to_premium' => 1 ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>'; 

        echo '</div>';

	}
	add_action( 'admin_notices', 'dpsp_admin_notice_to_premium' );


	/*
	 * Handle admin notices dismissals
	 *
	 */
	function dpsp_admin_notice_dismiss() {

		if( isset( $_GET['dpsp_admin_notice_activation'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_first_activation', 1, true );

		if( isset( $_GET['dpsp_admin_notice_wp_review'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_wp_review', 1, true );

		if( isset( $_GET['dpsp_admin_notice_to_premium'] ) )
			add_user_meta( get_current_user_id(), 'dpsp_admin_notice_to_premium', 1, true );

	}
	add_action( 'admin_init', 'dpsp_admin_notice_dismiss' );


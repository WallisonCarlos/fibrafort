<?php

function booked_avatar($user_id,$size = 150){
	if (get_user_meta($user_id, 'avatar',true)):
		if (wp_get_attachment_image( get_user_meta($user_id,'avatar',true), array($size,$size) )):
			return wp_get_attachment_image( get_user_meta($user_id,'avatar',true), array($size,$size) );
		else:
			return get_avatar($user_id, $size);
		endif;
	else :
		return get_avatar($user_id, $size);
	endif;
}

function booked_compress_css($css){

	// Remove Comments
	$regex = array("`^([\t\s]+)`ism"=>'',"`^\/\*(.+?)\*\/`ism"=>"","`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"$1","`([\n\A;\s]+)//(.+?)[\n\r]`ism"=>"$1\n","`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"\n");
	$css = preg_replace(array_keys($regex),$regex,$css);

    // Remove tabs, spaces, newlines, etc.
    $css = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css );

    return $css;

}

function booked_add_to_calendar_button($dates,$cf_meta_value){
	if (!get_option('booked_hide_google_link',false)):
	
		// Convert BRs to line breaks
		$cf_meta_value = preg_replace('#<br\s*?/?>#i', "\n", $cf_meta_value);
	
		?><span class="addtocalendar atc-style-booked google-cal-button">
			<a class="atcb-link"><?php _e('Add to Calendar','booked'); ?></a>
	        <var class="atc_event">
	            <var class="atc_date_start"><?php echo $dates['atc_date_startend']; ?> <?php echo $dates['atc_time_start']; ?></var>
	            <var class="atc_date_end"><?php echo $dates['atc_date_startend_end']; ?> <?php echo $dates['atc_time_end']; ?></var>
	            <var class="atc_timezone"><?php echo booked_get_timezone_string(); ?></var>
	            <var class="atc_title"><?php echo sprintf(__('Appointment with %s','booked'),get_bloginfo('name')); ?></var>
	            <var class="atc_description"><?php echo strip_tags(str_replace('<br>','
',$cf_meta_value)); ?></var>
	            <var class="atc_location"><?php echo get_bloginfo('name'); ?></var>
	            <var class="atc_organizer"></var>
	            <var class="atc_organizer_email"></var>
	        </var>
	    </span><?php
	endif;
}

function booked_get_timezone_string() {
 
    if ( $timezone = get_option( 'timezone_string' ) )
        return $timezone;
 
    if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) )
        return 'UTC';
 
    $utc_offset *= 3600;
 
    if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
        return $timezone;
    }
    
    $is_dst = date_i18n( 'I' );
 
    foreach ( timezone_abbreviations_list() as $abbr ) {
        foreach ( $abbr as $city ) {
            if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset )
                return $city['timezone_id'];
        }
    }
    
    return 'UTC';
}

function booked_get_profile_page(){
		
	global $wpdb;

	$shortcode = '[booked-profile';
	$query = "SELECT * FROM `{$wpdb->posts}` WHERE `post_status` != 'trash' AND `post_type` = 'page' AND `post_content` LIKE '%{$shortcode}%'";
	$pages = $wpdb->get_results($query);
	
	if (!$pages){
		return;
	}

	return $pages[0];

}

function booked_home_url(){
	if (function_exists('pll_home_url')):
		$home_url = rtrim(pll_home_url(), "/");
	else:
		$home_url = rtrim(home_url(), "/");
	endif;
	
	return $home_url;
}

function booked_get_name($user_id){
	$username = get_user_meta( $user_id, 'first_name', true ) ? get_user_meta( $user_id, 'first_name', true ).(get_user_meta( $user_id, 'last_name', true ) ? ' '.get_user_meta( $user_id, 'last_name', true ) : '') : false;
	if (!$username):
		$user_info = get_userdata($user_id);
		if (!empty($user_info)): $username = $user_info->display_name; else: return false; endif;
	endif;
	if (!$username):
		$user_info = get_userdata($user_id);
		if (!empty($user_info)): $username = $user_info->user_login; else: return false; endif;
	endif;
	return $username;
}

function booked_user_role()
{
	$booked_current_user = wp_get_current_user();
	$roles = $booked_current_user->roles;
	$role = array_shift($roles);
	return $role;
}

function booked_filter_agent_calendars($this_user,$calendars)
{
	
	$booked_current_user_email = $this_user->data->user_email;
	
	foreach($calendars as $key => $calendar):
				
		$term_meta = get_option( "taxonomy_".$calendar->term_id );
		$calendar_owner = $term_meta['notifications_user_id'];
		
		if ($calendar_owner != $booked_current_user_email):
			unset($calendars[$key]);
		endif;
		
	endforeach;
	
	return $calendars;
	
}

function booked_convertTime($time)
{
	settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = lz(floor($time / 60));
    $minutes = lz(($time % 60));
    return $hours.':'.$minutes;
}

// lz = leading zero
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}

function booked_pending_appts_count(){
	
	$calendars = get_terms('booked_custom_calendars','orderby=slug&hide_empty=0');
	if (!empty($calendars) && !current_user_can('manage_booked_options')):
		
		$booked_current_user = wp_get_current_user();
		$calendars = booked_filter_agent_calendars($booked_current_user,$calendars);
		
		if (!empty($calendars)):
		
			foreach($calendars as $calendar):
				$calendar_ids[] = $calendar->term_id;
			endforeach;
		
			$args = array(
			   'posts_per_page' => -1,
			   'post_status' => apply_filters('booked_admin_pending_post_status',array('draft')),
			   'post_type' => 'booked_appointments',
			   'tax_query' => array(
					array(
						'taxonomy' => 'booked_custom_calendars',
						'field'    => 'term_id',
						'terms'    => $calendar_ids,
					),
				),
			);
			
		else:
			return 0;
		endif;
		
	elseif (empty($calendars) && !current_user_can('manage_booked_options')):
		return 0;
	else:
		$args = array(
		   'posts_per_page' => -1,
		   'post_status' => apply_filters('booked_admin_pending_post_status',array('draft')),
		   'post_type' => 'booked_appointments',
		);
	endif;

	$pending_count_query = new WP_Query($args);
	return $pending_count_query->found_posts;
	
}

function booked_mailer($to,$subject,$message){
	
	//if (!BOOKED_DEMO_MODE):

		add_filter('wp_mail_content_type', 'booked_set_html_content_type');
	
		$booked_email_logo = get_option('booked_email_logo');
		if ($booked_email_logo):
			$logo = '<img src="'.$booked_email_logo.'" style="max-width:100%; height:auto; display:block; margin:10px 0 20px;">';
		else :
			$logo = '';
		endif;
	
		$link_color = get_option('booked_button_color','#56C477');
	
		$template = file_get_contents('email-templates/default.html', true);
		$filter = array('%content%','%logo%','%link_color%');
		$replace = array(wpautop($message),$logo,$link_color);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$message = str_replace($filter, $replace, $template);
	
		wp_mail($to,$subject,$message,$headers);
	
		remove_filter('wp_mail_content_type','booked_set_html_content_type');

	//endif;

}

function booked_set_html_content_type() {
	return 'text/html';
}

function booked_registration_validation( $username, $email, $password, $captcha_value = false, $captcha_from_user = false )  {
	global $reg_errors;
	$reg_errors = new WP_Error;
	$errors = array();

	if ($captcha_value):
		if ($captcha_value != $captcha_from_user):
			$reg_errors->add('captcha', __('The text you\'ve entered does not match the image.','booked'));
		else :
			$captcha = new ReallySimpleCaptcha();
			$captcha->remove($captcha_value);
		endif;
	endif;

	if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
	    $reg_errors->add('field', __('All fields are required to register.','booked'));
	}

	if ( 4 > strlen( $username ) ) {
	    $reg_errors->add( 'username_length', __('That username is too short; at least 4 characters is required.','booked'));
	}

	if ( username_exists( $username ) ) {
    	$reg_errors->add('user_name', __('That username already exists.','booked'));
    }

    if ( ! validate_username( $username ) ) {
	    $reg_errors->add( 'username_invalid', __('That username is not valid.','booked'));
	}

    if ( !is_email( $email ) ) {
	    $reg_errors->add( 'email_invalid', __('That email address is not valid.','booked'));
	}

	if ( email_exists( $email ) ) {
	    $reg_errors->add( 'email', __('That email is already in use.','booked'));
	}

	if ( is_wp_error( $reg_errors ) ) {

		foreach ( $reg_errors->get_error_messages() as $error ) {
	    	$errors[] = $error;
	    }

	}

	return $errors;

}

function booked_complete_registration() {
    global $reg_errors, $name, $username, $password, $email;

    if ( 1 > count( $reg_errors->get_error_messages() ) ) {

        $userdata = array(
        	'user_login'    =>   $username,
			'user_email'    =>   $email,
			'user_pass'     =>   $password,
			'first_name'	=>	 $name
        );
        $user_id = wp_insert_user( $userdata );

        update_user_meta( $user_id, 'nickname', $name );
		wp_update_user( array ('ID' => $user_id, 'display_name' => $name ) );

        // Send a registration welcome email to the new user?
		$email_content = get_option('booked_registration_email_content');
		$email_subject = get_option('booked_registration_email_subject');
		if ($email_content && $email_subject):
			$tokens = array('%name%','%username%','%password%','%email%');
			$replacements = array($name,$username,$password,$email);
			$email_content = str_replace($tokens,$replacements,$email_content);
			$email_subject = str_replace($tokens,$replacements,$email_subject);
			booked_mailer( $email, $email_subject, $email_content );
		endif;

        return '<p class="booked-form-notice"><strong>'.__('Success!','booked').'</strong><br />'.__('Registration complete, please check your email for login information.','booked').'</p>';

    } else {
	    return false;
    }
}

function booked_which_admin_to_send_email($calendar_id = false){

	$admin_email = false;

	if ($calendar_id):
		$term_meta = get_option( "taxonomy_$calendar_id" );
		$selected_value = $term_meta['notifications_user_id'];

		if ($selected_value):
			$admin_email = $selected_value;
		endif;
	endif;

	if (!$admin_email && get_option('booked_default_email_user')):
		$admin_email = get_option('booked_default_email_user');
	endif;

	if (!$admin_email):
		$admin_email = get_option( 'admin_email' );
	endif;

	return $admin_email;

}

function booked_registration_form($name, $email, $password){

	?><form action="" method="post" class="wp-user-form">

		<p class="name">
			<label for="name"><?php _e('Your Name','booked'); ?></label>
			<input type="text" name="booked_reg_name" value="<?php echo ( isset( $_POST['name'] ) ? $name : null ); ?>" id="name" tabindex="101" />
		</p>
		<p class="email">
			<label for="email"><?php _e('Email Address','booked'); ?></label>
			<input type="email" name="booked_reg_email" value="<?php echo ( isset( $_POST['email'] ) ? $email : null ); ?>" id="email" tabindex="103" />
		</p>
		<p class="password">
			<label for="password"><?php _e('Choose a Password','booked'); ?></label>
			<input type="password" name="booked_reg_password" value="<?php echo ( isset( $_POST['password'] ) ? $password : null ); ?>" id="password" tabindex="104" />
		</p>

		<?php if (class_exists('ReallySimpleCaptcha')) :

			?><p class="captcha">
				<label for="captcha_code"><?php _e('Please enter the following text:','booked'); ?></label><?php

				$rsc_url = WP_PLUGIN_URL . '/really-simple-captcha/';

		        $captcha = new ReallySimpleCaptcha();
		        $captcha->fg = array(150,150,150);
	            $captcha_word = $captcha->generate_random_word(); //generate a random string with letters
	            $captcha_prefix = mt_rand(); //random number
	            $captcha_image = $captcha->generate_image($captcha_prefix, $captcha_word); //generate the image file. it returns the file name
	            $captcha_file = rtrim(get_bloginfo('wpurl'), '/') . '/wp-content/plugins/really-simple-captcha/tmp/' . $captcha_image; //construct the absolute URL of the captcha image

		        echo '<img class="captcha-image" src="'.$rsc_url.'tmp/'.$captcha_image.'">';

		        ?><input type="text" name="captcha_code" value="" tabindex="104" />
			    <input type="hidden" name="captcha_word" value="<?php echo $captcha_word; ?>" />
			</p><?php

		endif; ?>

		<input type="submit" name="booked_reg_submit" value="<?php _e('Register','booked'); ?>" class="user-submit button-primary" tabindex="105" />

	</form><?php

}

/* Custom Time Slot Functions */
function booked_apply_custom_timeslots_filter($booked_defaults = false,$calendar_id = false){
	$custom_timeslots_array = array();
	$booked_custom_timeslots_encoded = get_option('booked_custom_timeslots_encoded');
	$booked_custom_timeslots_decoded = json_decode($booked_custom_timeslots_encoded,true);

	if (!empty($booked_custom_timeslots_decoded)):

		$custom_timeslots_array = booked_custom_timeslots_reconfigured($booked_custom_timeslots_decoded);
		foreach($custom_timeslots_array as $key => $value):

			if ($value['booked_custom_start_date']):

				$formatted_date = date_i18n('Ymd',strtotime($value['booked_custom_start_date']));
				$formatted_end_date = date_i18n('Ymd',strtotime($value['booked_custom_end_date']));

				// To include or not to include?
				if (!isset($value['booked_custom_calendar_id']) || $calendar_id && isset($value['booked_custom_calendar_id']) && $value['booked_custom_calendar_id'] == $calendar_id || !$calendar_id && !$value['booked_custom_calendar_id']){

					if (!$value['booked_custom_end_date']){
						// Single Date
						if ($value['vacationDayCheckbox']){
							// Time slots disabled
							$booked_defaults[$formatted_date] = array();
						} else {
							// Add time slots to this date
							$booked_defaults[$formatted_date] = $value['booked_this_custom_timelots'];
						}
					} else {
						// Multiple Dates
						$tempDate = $formatted_date;
						do {
							if ($value['vacationDayCheckbox']){
								// Time slots disabled
								$booked_defaults[$tempDate] = array();
							} else {
								// Add time slots to this date
								$booked_defaults[$tempDate] = $value['booked_this_custom_timelots'];
							}
							$tempDate = date_i18n('Ymd',strtotime($tempDate . ' +1 day'));
						} while ($tempDate <= $formatted_end_date);
					}

				}

			endif;

		endforeach;

	endif;

	return $booked_defaults;
}

/* Custom Time Slot Functions */
function booked_apply_custom_timeslots_details_filter($booked_defaults = false,$calendar_id = false){
	$custom_timeslots_array = array();
	$booked_custom_timeslots_encoded = get_option('booked_custom_timeslots_encoded');
	$booked_custom_timeslots_decoded = json_decode($booked_custom_timeslots_encoded,true);

	if (!empty($booked_custom_timeslots_decoded)):

		$custom_timeslots_array = booked_custom_timeslots_reconfigured($booked_custom_timeslots_decoded);
		foreach($custom_timeslots_array as $key => $value):

			if ($value['booked_custom_start_date']):

				$formatted_date = date_i18n('Ymd',strtotime($value['booked_custom_start_date']));
				$formatted_end_date = date_i18n('Ymd',strtotime($value['booked_custom_end_date']));

				// To include or not to include?
				if (!isset($value['booked_custom_calendar_id']) || $calendar_id && isset($value['booked_custom_calendar_id']) && $value['booked_custom_calendar_id'] == $calendar_id || !$calendar_id && !$value['booked_custom_calendar_id']){

					if (!$value['booked_custom_end_date']){
						// Single Date
						if ($value['vacationDayCheckbox']){
							// Time slots disabled
							$booked_defaults[$formatted_date] = array();
							$booked_defaults[$formatted_date.'-details'] = array();
						} else {
							// Add time slots to this date
							$booked_defaults[$formatted_date] = $value['booked_this_custom_timelots'];
							$booked_defaults[$formatted_date.'-details'] = !empty($value['booked_this_custom_timelots_details']) ? $value['booked_this_custom_timelots_details'] : array();
						}
					} else {
						// Multiple Dates
						$tempDate = $formatted_date;
						do {
							if ($value['vacationDayCheckbox']){
								// Time slots disabled
								$booked_defaults[$tempDate] = array();
								$booked_defaults[$tempDate.'-details'] = array();
							} else {
								// Add time slots to this date
								$booked_defaults[$tempDate] = $value['booked_this_custom_timelots'];
								$booked_defaults[$tempDate.'-details'] = !empty($value['booked_this_custom_timelots_details']) ? $value['booked_this_custom_timelots_details'] : array();
							}
							$tempDate = date_i18n('Ymd',strtotime($tempDate . ' +1 day'));
						} while ($tempDate <= $formatted_end_date);
					}

				}

			endif;

		endforeach;

	endif;

	return $booked_defaults;
}

function booked_custom_timeslots_reconfigured($booked_custom_timeslots_decoded){

	$total_fields = count($booked_custom_timeslots_decoded['booked_custom_start_date']) - 1;
	$custom_timeslots_array = array();
	$counter = 0;

	if ($total_fields):

		do {
			foreach($booked_custom_timeslots_decoded as $key => $values):
				if ($key == 'booked_this_custom_timelots' || $key == 'booked_this_custom_timelots_details'):
					$values = json_decode($values[$counter],true);
					$custom_timeslots_array[$counter][$key] = $values;
				else:
					$custom_timeslots_array[$counter][$key] = (isset($values[$counter]) ? $values[$counter] : $values);
				endif;
			endforeach;
			$counter++;
		} while($total_fields >= $counter);

	else :

		$custom_timeslots_array[0] = $booked_custom_timeslots_decoded;

	endif;

	return $custom_timeslots_array;

}
/* End Custom Time Slot Functions */
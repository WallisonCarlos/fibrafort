<?php
	
$date = $_POST['date'];
$title = isset($_POST['title']) ? $_POST['title'] : '';	
$timeslot = $_POST['timeslot'];
$timeslot_parts = explode('-',$timeslot);
$day_name = date('D',strtotime($date));

$this_timeslot_timestamp = strtotime($date);

$new_appointment_default = get_option('booked_new_appointment_default','draft');

$date_format = get_option('date_format');
$time_format = get_option('time_format');

$args = array('orderby' => 'display_name');
$user_array = get_users($args);

$only_titles = get_option('booked_show_only_titles',false);

$calendar_id = (isset($_POST['calendar_id']) ? intval($_POST['calendar_id']) : false);
if ($calendar_id): $calendar_obj = get_term($calendar_id,'booked_custom_calendars'); $calendar_name = $calendar_obj->name; else: $calendar_name = ''; endif;

if ($only_titles && !$title || !$only_titles):
	if ($timeslot_parts[0] == '0000' && $timeslot_parts[1] == '2400'):
		$timeslotText = __('All day','booked');
	else :
		$timeslotText = date_i18n($time_format,strtotime($timeslot_parts[0])) . (!get_option('booked_hide_end_times') ? ' &ndash; '.date_i18n($time_format,strtotime($timeslot_parts[1])) : '');
	endif;
endif;

$appt_date_time_before = apply_filters('booked_fe_appt_form_date_time_before','',$this_timeslot_timestamp,$timeslot,$calendar_id);
$appt_date_time_after = apply_filters('booked_fe_appt_form_date_time_after','',$this_timeslot_timestamp,$timeslot,$calendar_id);

$appt_date_time = $appt_date_time_before.($title ? '<p class="title">'.$title.'</p>' : '').'<p class="name"><b><i class="fa fa-calendar-o"></i>&nbsp;&nbsp;&nbsp;' . date_i18n($date_format, strtotime($date)) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>'.($timeslotText ? '<b><i class="fa fa-clock-o"></i>&nbsp;&nbsp;' . $timeslotText . '</b>' : '').'</p>'.$appt_date_time_after;


?><div class="booked-form booked-scrollable">

	<?php $reached_limit = false; $appt_is_available = booked_appt_is_available($date,$timeslot,$calendar_id); ?>
		
	<?php if ($appt_is_available): ?>
		
		<?php if ($calendar_name): ?><p class="booked-calendar-name"><?php echo $calendar_name; ?></p><?php endif; ?>
			
		<?php if (is_user_logged_in()):
		
			?><form action="" method="post" id="newAppointmentForm" data-calendar-id="<?php echo $calendar_id ? $calendar_id : 0; ?>">
			
				<input type="hidden" name="title" value="<?php echo esc_attr($title); ?>" />
				<input type="hidden" name="date" value="<?php echo date_i18n('Y-m-j', strtotime($date)); ?>" />
				<input type="hidden" name="timestamp" value="<?php echo strtotime($date.' '.$timeslot_parts[0]); ?>" />
				<input type="hidden" name="timeslot" value="<?php echo $timeslot; ?>" />
				<input type="hidden" name="customer_type" value="current" />
				<input type="hidden" name="action" value="booked_add_appt" />
				<input type="hidden" name="calendar_id" value="<?php echo $calendar_id ? $calendar_id : 0; ?>" /><?php
				
				$booked_current_user = wp_get_current_user();
				
				$appointment_limit = get_option('booked_appointment_limit');
				if ($appointment_limit):
					$upcoming_user_appointments = booked_user_appointments($booked_current_user->ID,true);
					if ($upcoming_user_appointments >= $appointment_limit):
						$reached_limit = true;
					else :
						$reached_limit = false;
					endif;
				endif;
				
				if (!$reached_limit):
				
					?><p><?php echo sprintf( __( 'You are about to request an appointment for %s.','booked' ), booked_get_name( $booked_current_user->ID )); ?> <?php _e('Please confirm that you would like to request the following appointment:','booked'); ?></p>
					<?php echo $appt_date_time; ?>
				
					<input type="hidden" name="user_id" value="<?php echo $booked_current_user->ID; ?>" />
				
					<?php booked_custom_fields($calendar_id); ?>
					
					<div class="field">
						<p class="status"></p>
					</div>
				
					<div class="field">
						<?php if (!$reached_limit): ?>
							<input type="submit" id="submit-request-appointment" class="button button-primary" value="<?php _e('Request Appointment','booked'); ?>">
							<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
						<?php else: ?>
							<button class="cancel button"><?php _e('Okay','booked'); ?></button>
						<?php endif; ?>
					</div>
				
				<?php else : ?>
				
					<p><?php echo sprintf(_n("Sorry, but you've hit the appointment limit. Each user may only book %d appointment at a time.","Sorry, but you've hit the appointment limit. Each user may only book %d appointments at a time.", $appointment_limit, "booked" ), $appointment_limit); ?></p>
				
				<?php endif; ?>
			
			</form>
			
		<?php else : ?>
		
			<p><?php _e('Please review and confirm that you would like to request the following appointment:','booked'); ?></p>
		
			<?php echo $appt_date_time;
				
			$guest_booking = (get_option('booked_booking_type','registered') == 'guest' ? true : false);
				
			if (!$guest_booking): ?>
		
				<form name="customerChoices" action="" id="customerChoices" class="bookedClearFix">
					
					<div class="field">
						<span class="checkbox-radio-block">
							<input data-condition="customer_choice" type="radio" name="customer_choice[]" id="customer_new" value="new" checked="checked">
							<label for="customer_new"><?php _e('New customer','booked'); ?></label>
						</span>
					</div>
					
					<div class="field">
						<span class="checkbox-radio-block">
							<input data-condition="customer_choice" type="radio" name="customer_choice[]" id="customer_current" value="current">
							<label for="customer_current"><?php _e('Current customer','booked'); ?></label>
						</span>
					</div>
					
				</form>
			
				<div class="condition-block customer_choice" id="condition-current">
					
					<form id="ajaxlogin" action="" method="post">
					
						<div class="cf-block">
							
							<div class="field">
								<label class="field-label"><?php _e("Welcome back, please sign in:","booked"); ?></label>
							</div>
								
							<div class="field">
								<input value="" placeholder="<?php _e('Email Address','booked'); ?> ..." class="textfield" id="username" name="username" type="email" >
								<input value="" placeholder="<?php _e('Password','booked'); ?> ..." class="textfield" id="password" name="password" type="password" >
							</div>
					
							<input type="hidden" name="action" value="booked_ajax_login">
							<?php wp_nonce_field( 'ajax_login_nonce', 'security' ); ?>
							
							<div class="field">
								<p class="status"></p>
							</div>
							
						</div>
						
						<div class="field">
							<input name="submit" type="submit" class="button button-primary" value="<?php _e('Sign in', 'booked') ?>">
							<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
						</div>
	
					</form>
						
				</div>
	
			<?php endif; ?>
			
			<form action="" method="post" id="newAppointmentForm" data-calendar-id="<?php echo $calendar_id ? $calendar_id : 0; ?>">
			
				<input type="hidden" name="title" value="<?php echo esc_attr($title); ?>" />
				<input type="hidden" name="date" value="<?php echo date_i18n('Y-m-j', strtotime($date)); ?>" />
				<input type="hidden" name="timestamp" value="<?php echo strtotime($date.' '.$timeslot_parts[0]); ?>" />
				<input type="hidden" name="timeslot" value="<?php echo $timeslot; ?>" />
				<input type="hidden" name="customer_type" value="<?php if ($guest_booking): echo 'guest'; else : echo 'new'; endif; ?>" />
				<input type="hidden" name="action" value="booked_add_appt" />
				<input type="hidden" name="calendar_id" value="<?php echo $calendar_id ? $calendar_id : 0; ?>" />
				
				<?php if ($guest_booking):
					
					$email_required = get_option('booked_require_guest_email_address',false); ?>
				
					<div class="condition-block customer_choice default" id="condition-guest">
						
						<div class="field">
							<label class="field-label"><?php _e("Contact Information","booked"); ?><?php if ($email_required): ?><i class="required-asterisk fa fa-asterisk"></i><?php endif; ?></label>
							<?php if ($email_required): ?>
								<p class="field-small-p"><?php _e('Please enter your name and email address.','booked'); ?></p>
							<?php else: ?>
								<p class="field-small-p"><?php _e('Please enter your name (required) and an optional email address.','booked'); ?></p>
							<?php endif; ?>
						</div>
	
						<div class="field">
							<input type="text" placeholder="<?php _e("Name","booked"); ?> ..." class="textfield" name="guest_name" />
							<input type="email" placeholder="<?php _e("Email Address","booked"); ?> ..." class="textfield" name="guest_email" />
						</div>
				
						<?php booked_custom_fields($calendar_id); ?>
						
						<?php if (class_exists('ReallySimpleCaptcha')) :
			
							?><p class="captcha">
								<label for="captcha_code"><?php _e('Please enter the following text:','booked'); ?></label><?php
							
								$rsc_url = WP_PLUGIN_URL . '/really-simple-captcha/';
								
						        $captcha = new ReallySimpleCaptcha();
						        $captcha->bg = array(245,245,245);
						        $captcha->fg = array(150,150,150);
					            $captcha_word = $captcha->generate_random_word(); //generate a random string with letters
					            $captcha_prefix = mt_rand(); //random number
					            $captcha_image = $captcha->generate_image($captcha_prefix, $captcha_word); //generate the image file. it returns the file name
					            $captcha_file = rtrim(get_bloginfo('wpurl'), '/') . '/wp-content/plugins/really-simple-captcha/tmp/' . $captcha_image; //construct the absolute URL of the captcha image
						        
						        echo '<img class="captcha-image" src="'.$rsc_url.'tmp/'.$captcha_image.'">';
						        
						    ?></p>
						    							   
							<div class="field"> 
								<input type="text" name="captcha_code" class="textfield large" value="" tabindex="104" />
								<input type="hidden" name="captcha_word" value="<?php echo $captcha_word; ?>" />
							</div>
							
							<br><?php
								
						endif; ?>
						
						<div class="field">
							<p class="status"></p>
						</div>
					
						<div class="field">
							<input type="submit" id="submit-request-appointment" class="button button-primary" value="<?php _e('Request Appointment','booked'); ?>">
							<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
						</div>
						
					</div>
				
				<?php else : ?>
				
					<div class="condition-block customer_choice default" id="condition-new">
						
						<div class="field">
							<label class="field-label"><?php _e("Registration:","booked"); ?><i class="required-asterisk fa fa-asterisk"></i></label>
							<p class="field-small-p"><?php _e('Please enter your name, your email address and choose a password to get started.','booked'); ?></p>
						</div>
						
						<div class="field">
							<input value="" placeholder="<?php _e('Name','booked'); ?> ..." type="text" class="large textfield" name="booked_appt_name" />
						</div>
						<div class="field">
							<input value="" placeholder="<?php _e('Email Address','booked'); ?> ..." type="email" class="textfield" name="booked_appt_email" />
							<input value="" placeholder="<?php _e('Choose a password','booked'); ?> ..." type="password" class="textfield" name="booked_appt_password" />
						</div>
						
						<?php booked_custom_fields($calendar_id); ?>
						
						<?php if (class_exists('ReallySimpleCaptcha')) :
			
							?><p class="captcha">
								<label for="captcha_code"><?php _e('Please enter the following text:','booked'); ?></label><?php
							
								$rsc_url = WP_PLUGIN_URL . '/really-simple-captcha/';
								
						        $captcha = new ReallySimpleCaptcha();
						        $captcha->bg = array(245,245,245);
						        $captcha->fg = array(150,150,150);
					            $captcha_word = $captcha->generate_random_word(); //generate a random string with letters
					            $captcha_prefix = mt_rand(); //random number
					            $captcha_image = $captcha->generate_image($captcha_prefix, $captcha_word); //generate the image file. it returns the file name
					            $captcha_file = rtrim(get_bloginfo('wpurl'), '/') . '/wp-content/plugins/really-simple-captcha/tmp/' . $captcha_image; //construct the absolute URL of the captcha image
						        
						        echo '<img class="captcha-image" src="'.$rsc_url.'tmp/'.$captcha_image.'">';
						        
						    ?></p>
						    							   
							<div class="field"> 
								<input type="text" name="captcha_code" class="textfield large" value="" tabindex="104" />
								<input type="hidden" name="captcha_word" value="<?php echo $captcha_word; ?>" />
							</div>
							
							<br><?php
								
						endif; ?>
						
						<div class="field">
							<p class="status"></p>
						</div>
					
						<div class="field">
							<?php if (!$reached_limit): ?>
								<input type="submit" id="submit-request-appointment" class="button button-primary" value="<?php _e('Request Appointment','booked'); ?>">
								<button class="cancel button"><?php _e('Cancel','booked'); ?></button>
							<?php else: ?>
								<button class="cancel button"><?php _e('Okay','booked'); ?></button>
							<?php endif; ?>
						</div>
					
					</div>
				
				<?php endif; ?>
			
			</form>
		
		<?php endif; ?>

	<?php else: ?>
	
		<p><?php _e("Sorry, someone just booked this appointment before you could. Please choose a different booking time.", "booked" ); ?></p>
	
	<?php endif; ?>

</div>

<p class="booked-title-bar"><small><?php echo ($new_appointment_default == 'draft' ? __('Request an Appointment','booked') : __('Book an Appointment','booked')); ?></small></p>

<?php echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';
<?php

global $error,$post;

$booked_current_user = wp_get_current_user();
$profile_username = $booked_current_user->user_login;
$my_id = $booked_current_user->ID;
$my_profile = true;

$user_data = get_user_by( 'id', $booked_current_user->ID );

?><div id="booked-profile-page"<?php if ($my_profile): ?> class="me"<?php endif; ?>><?php

if (empty($user_data)) {

	echo '<h2>' . __('No profile here!','booked') . '</h2>';
	echo '<p>' . __('Sorry, this user profile does not exist.','booked') . '</p>';

} else { ?>

	<?php
			
	$user_meta = get_user_meta($user_data->ID);
	$user_url = $user_data->data->user_url;
	$user_desc = $user_meta['description'][0];
	$h3_class = '';
			
	?>

	<div class="booked-profile-header bookedClearFix">

		<div class="booked-info">
			<div class="booked-user">
				<div class="booked-user-avatar"><?php echo booked_avatar($user_data->ID,50); ?></div>
				<h3 class="<?php echo $h3_class; ?>">
					<?php echo sprintf(__('Welcome back, %s!','booked'),'<strong>'.booked_get_name( $user_data->ID ).'</strong>'); ?>
					<?php if ($my_profile): ?>
						&nbsp;&nbsp;<a class="booked-logout-button" href="<?php echo wp_logout_url(get_permalink($post->ID)); ?>" title="<?php _e('Sign Out','booked'); ?>"><?php _e('Sign Out','booked'); ?></a>
					<?php endif; ?>
				</h3>
			</div>
		</div>

	</div>

	<ul class="booked-tabs bookedClearFix">
		<?php
			
			$default_tabs = array(
				'appointments' => array(
					'title' => __('Upcoming Appointments','booked'),
					'fa-icon' => 'fa-calendar',
					'class' => false
				),
				'history' => array(
					'title' => __('Appointment History','booked'),
					'fa-icon' => 'fa-calendar-check-o',
					'class' => false
				),
				'edit' => array(
					'title' => __('Edit Profile','booked'),
					'fa-icon' => 'fa-edit',
					'class' => 'edit-button'
				)
			);
			
			echo apply_filters('booked_profile_tabs',$default_tabs);
		
		?>
	</ul>

	<?php $appointment_default_status = get_option('booked_new_appointment_default','draft');

	if ( is_user_logged_in() && $my_profile ) : ?>
	
		<?php echo apply_filters('booked_profile_tab_content',$default_tabs); ?>

	<?php endif; ?>

<?php } ?>

</div>
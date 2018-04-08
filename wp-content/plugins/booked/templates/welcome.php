<div id="booked-welcome-screen">
	<div class="wrap about-wrap">
		<h1><?php echo sprintf(__('Welcome to %s','booked'),'Booked '.BOOKED_VERSION); ?></h1>
		<div class="about-text">
			<?php echo sprintf(__('Thank you for choosing %s! If this is your first time using %s, you will find some helpful "Getting Started" links below. If you just updated the plugin, you can find out what\'s new in the "What\'s New" section below.','booked'),'Booked','Booked'); ?>
		</div>
		<div class="booked-badge">
			<img src="<?php echo BOOKED_PLUGIN_URL; ?>/templates/images/badge.png">
		</div>
		
		<div id="welcome-panel" class="welcome-panel">
			
			<img src="<?php echo BOOKED_PLUGIN_URL; ?>/templates/images/welcome-banner.jpg" class="booked-welcome-banner">
			
			<div class="welcome-panel-content">
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
					
						<iframe src="https://player.vimeo.com/video/155600760?color=56c477&title=0&byline=0&portrait=0" width="320" height="180" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style="margin-top:5px;"></iframe>
						
						<h3 style="margin-top:30px;"><?php _e('Getting Started','booked'); ?></h3>
						<ul>
							<li><a href="https://boxystudio.ticksy.com/article/3239/" target="_blank" class="welcome-icon welcome-learn-more"><?php _e('Installation &amp; Setup Guide','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/6268/" target="_blank" class="welcome-icon welcome-learn-more"><?php _e('Custom Calendars','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/3238/" target="_blank" class="welcome-icon welcome-learn-more"><?php _e('Default Time Slots','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/3233/" target="_blank" class="welcome-icon welcome-learn-more"><?php _e('Custom Time Slots','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/6267/" target="_blank" class="welcome-icon welcome-learn-more"><?php _e('Custom Fields','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
							<li><a href="https://boxystudio.ticksy.com/article/3240/" target="_blank" class="welcome-icon welcome-learn-more"><?php _e('Shortcodes','booked'); ?></a>&nbsp;&nbsp;<i class="fa fa-external-link"></i></li>
						</ul>
						<a class="button" style="margin-bottom:15px; margin-top:0;" href="https://boxystudio.ticksy.com/articles/7827/" target="_blank"><?php _e('View all Guides','booked'); ?>&nbsp;&nbsp;<i class="fa fa-external-link"></i></a>&nbsp;
						<a class="button button-primary" style="margin-bottom:15px; margin-top:0;" href="<?php echo get_admin_url().'admin.php?page=booked-settings'; ?>"><?php _e('Get Started','booked'); ?></a>
						
					</div>
					<div class="welcome-panel-column welcome-panel-last welcome-panel-updates-list">
						<h3><?php _e('What\'s new in this update?','booked'); ?> <a href="http://boxyupdates.com/changelog.php?p=booked" target="_blank"><?php _e('Full Changelog','booked'); ?>&nbsp;&nbsp;<i class="fa fa-external-link"></i></a></h3>
						
						<ul class="booked-whatsnew-list">
							<li><em class="fix">Fixed</em> Some quick bug fixes related to "Time Slot" and "Custom Time Slot" creation.</li>
						</ul>
						<br>
						
						<h3><?php echo sprintf(__('%s Update','booked'),'1.8.0'); ?></h3>
						<ul class="booked-whatsnew-list">
							<li><strong class="new">New</strong> <strong>Appointment Reminders:</strong> Booked will now send out appointment reminders on intervals that you set from the <strong><a href="<?php echo admin_url(); ?>admin.php?page=booked-settings#email-settings">Email Settings</a></strong> tab. You can send reminders to both the administrators/booking agents and customers.</li>
							<li><strong class="new">New</strong> <strong>Time Slot Titles:</strong> Now you can set a title for newly created <strong><a href="<?php echo admin_url(); ?>admin.php?page=booked-settings#defaults">time slots</a></strong> and <strong><a href="<?php echo admin_url(); ?>admin.php?page=booked-settings#custom-timeslots">custom time slots</a></strong>. This allows you to display a title and time (or just the title) for your available slots.</li>
							<li><em class="fix">Fixed</em> Language file update.</li>
							<li><em class="fix">Fixed</em> WPML config update (to support email reminder translations).</li>
							<li><em class="fix">Fixed</em> Various other bug fixes throughout.</li>
						</ul>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
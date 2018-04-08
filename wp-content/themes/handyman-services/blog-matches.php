<?php
/*
Template Name: Matches list
*/

/**
 * Make empty page with this template 
 * and put it into menu
 * to display all Matches as streampage
 */

handyman_services_storage_set('blog_filters', 'matches');

get_template_part('blog');
?>
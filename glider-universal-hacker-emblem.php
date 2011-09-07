<?php
/*
Plugin Name: Glider Universal Hacker Emblem
Plugin URI: http://www.mynakedgirlfriend.de/wordpress/glider/
Description: This plugin presents the official hacker emblem on your blog so you can show your sympathy with the goals and values of hackers and their lifestyle.
Author: Thomas Schulte
Version: 0.9.1
Author URI: http://www.mynakedgirlfriend.de

Copyright (C) 2011 Thomas Schulte

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


// Widget

class Glider_Widget extends WP_Widget {

	function Glider_Widget() {
		// widget actual processes
		parent::WP_Widget( 'gliderwidget', $name = 'Glider' );
	}

	function form($instance) {
		// outputs the options form on admin
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		}else {
			$title = __( 'The Glider', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php 
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function widget($args, $instance) {
		// outputs the content of the widget
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ($title) {
			echo $before_title . $title . $after_title;
		}

		echo '<p id="glider">';
		echo glider_logo();
		echo '</p>';
		echo $after_widget;
	}

}



// manual
function glider_logo() {
	$logohtml = "";

	if(get_bloginfo("language", "raw") == "de-DE") {
		$logohtml = '<a href="http://www.mynakedgirlfriend.de/glider-das-hackeremblem/" target="_blank">';
	}else {
		$logohtml = '<a href="http://www.mynakedgirlfriend.de/glider-the-hacker-emblem/" target="_blank">';
	}
	$logohtml.= '<img width="80" height="80" ';
	$logohtml.= 'src="' . plugins_url('glider-universal-hacker-emblem/glider-160px.png') . '" alt="glider hacker emblem" />';
	$logohtml.= '</a>';

	return $logohtml;
}

function display_glider_logo() {
	echo glider_logo();
}



// admin
function glider_settings_menu() {
	add_options_page('Glider Options', 'Glider', 'manage_options', 'glider_universal_hacker_emblem', 'glider_options');
}

function glider_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	$hidden_field_name = 'glider_submit_hidden';
	$alignment = get_option('glider_alignment');

	if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		$alignment = $_POST['glider_alignment'];
	}

        update_option('glider_alignment', $alignment);

	echo '<div class="wrap">';
	echo "<h2>" . __( 'Glider Settings', 'glider' ) . "</h2>";
?>

<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

	<p>
		<?php _e("Alignment:", 'glider' ); ?>&nbsp;
		<input type="text" name="glider_alignment" value="<?php echo $alignment; ?>" size="20">
	</p><hr />

	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>
	<p>add to functions.php: add_filter('widget_text', 'do_shortcode');</p>
</form>

<?php
	echo '</div>';
}



// go
/*
This is not needed at the moment:
add_action('admin_menu', 'glider_settings_menu');
*/
wp_enqueue_style('glider-style', plugins_url('glider-universal-hacker-emblem/glider-universal-hacker-emblem.css'));
add_shortcode('glider', 'glider_logo');
add_action( 'widgets_init', create_function( '', 'return register_widget("Glider_Widget");' ) );

?>

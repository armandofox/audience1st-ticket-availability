<?php
add_action('admin_menu', 'audience1st_ticket_availability_setup_menu');
 
function audience1st_ticket_availability_setup_menu() {
    add_options_page( __('Audience1st Ticket Availability Settings', 'audience1st-ticket-availability'),
                      __('Audience1st Ticket Availability', 'audience1st-ticket-availability'),
                      'manage_options', 'audience1st-ticket-availability-config', 'a1ta_display_options' );
}
 
function a1ta_update_config_values_if_form_submitted() {
    if (! isset($_POST['_submit']) || $_POST['_submit']!='_submit') {
        return;
    }
	$numshows_option = audience1st_ticket_availability::A1_NUM_SHOWS;
	$url_option = audience1st_ticket_availability::A1_URL;
    if (!wp_verify_nonce($_POST['a1_ticket_update_options'], -1)) {
        return(add_settings_error('a1-ticket-availability-config', 'error', 'Invalid form submission.  Please reload the original form page and try again.'));
    }
	$num_shows = 0 + $_POST[$numshows_option];
	if ($num_shows < 1) {
		return(add_settings_error('a1-ticket-availability-config', 'error', 'Number of shows to display must be at least 1.'));
	}
	$url = filter_var($_POST[$url_option], FILTER_SANITIZE_URL);
	if (preg_match('/^https?:\/\//i', $url) != 1) {
		return(add_settings_error('a1-ticket-availability-config', 'error', 'URL is invalid.'));
	}
	update_option($numshows_option, $num_shows);
	update_option($url_option, $url);
    echo '<div class="updated"><p>Settings saved.</p></div>';
}

function a1ta_display_options() {
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	a1ta_update_config_values_if_form_submitted();
    // settable options
    $a1_url = audience1st_ticket_availability::A1_URL;
    $a1_url_val = esc_url(get_option($a1_url));
    $a1_num_shows = audience1st_ticket_availability::A1_NUM_SHOWS;
    $a1_num_shows_val = sanitize_text_field(get_option($a1_num_shows));
    // for replay protection
    $a1_nonce = wp_nonce_field(-1, 'a1_ticket_update_options');

    echo '<div class="wrap">';
    echo '<h2>' . __('Audience1st Ticket Availability: Configuration', 'audience1st-ticket-availability')  . '</h2>';
    
    
    // validation errors from failed attempt to update values
    $a1_errors = settings_errors('a1-ticket-availability-config', true);

    echo <<<endofsettingspage
    
<form name="a1_ticket_availability_form" method="post" action="">
  ${a1_nonce}
  <input type="hidden" name="_submit" value="_submit">
  <p>Audience1st base URL (for example: <code>http://www.audience1st.com/your-theater-name</code>):</p>
  <input type="text" name="$a1_url" size="60" value="${a1_url_val}">
  <hr/>
  <p>Number of performances to display availability for:
    <input type="text" name="${a1_num_shows}" size="3" value="${a1_num_shows_val}">
  </p>
  <p class="submit">
    <input type="submit" name="Save Changes" class="button-primary" value="Save Changes">
  </p>
</form>
</div>

endofsettingspage;
}

?>

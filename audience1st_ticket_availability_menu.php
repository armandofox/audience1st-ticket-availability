<?php
add_action('admin_menu', 'audience1st_ticket_availability_setup_menu');
 
function audience1st_ticket_availability_setup_menu() {
    add_options_page( __('Audience1st Ticket Availability Settings', 'audience1st-ticket-availability'),
                      __('Audience1st Ticket Availability', 'audience1st-ticket-availability'),
                      'manage_options', 'audience1st-ticket-availability-config', 'display_options' );
}
 
function update_config_values_if_form_submitted() {
    if (isset($_POST['_submit']) && $_POST['_submit']=='_submit') {
        foreach (array(audience1st_ticket_availability::A1_URL, audience1st_ticket_availability::A1_NUM_SHOWS) as $opt) {
                update_option($opt, $_POST[$opt]);
        }
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }
}

function display_options() {
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))  {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    // settable options
    $a1_url = audience1st_ticket_availability::A1_URL;
    $a1_url_val = get_option($a1_url);
    $a1_num_shows = audience1st_ticket_availability::A1_NUM_SHOWS;
    $a1_num_shows_val = get_option($a1_num_shows);

    echo '<div class="wrap">';
    echo '<h2>' . __('Audience1st Ticket Availability: Configuration', 'audience1st-ticket-availability')  . '</h2>';
    
    update_config_values_if_form_submitted();
    echo <<<endofsettingspage
    
<form name="a1_ticket_availability_form" method="post" action="">
  <input type="hidden" name="_submit" value="_submit">
  <p>Audience1st base URL (for example: <code>http://www.audience1st.com/your-theater-name</code>):</p>
  <input type="text" name="$a1_url" size="30" value="${a1_url_val}">
  <hr/>
  <p>Number of performances to display availability for:</p>
  <input type="text" name="$a1_num_shows" size="3" value="${a1_num_shows_val}">
  <p class="submit">
    <input type="submit" name="Save Changes" class="button-primary" value="Save Changes">
  </p>
</form>
</div>

endofsettingspage;
}

?>


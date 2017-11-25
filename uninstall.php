// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// At uninstall, remove the options we use

delete_option(audience1st_ticket_availability::A1_URL);
delete_option(audience1st_ticket_availability::A1_NUM_SHOWS);

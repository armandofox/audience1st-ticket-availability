<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// At uninstall, remove the options we use

delete_option('audience1st_ticket_rss_url');
delete_option('audience1st_ticket_rss_num_shows');
delete_option('audience1st_ticket_rss_version');

?>

<?php 
/*
  Plugin Name: Audience1st Ticket Availability
  Plugin URI: https://github.com/armandofox/audience1st-ticket-availability
  Donate link: http://www.audience1st.com
  Description: Plugin for displaying ticket availability based on RSS feeds from Audience1st
  Author: Armando Fox, based on original version by Kanopi Studios
  Version: 1.0
  Author URI: https://github.com/armandofox
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  License: GPL2
  Text Domain: a1-rss
  Domain path: /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Appearance configuration
 */
require_once('audience1st_ticket_availability_menu.php');
/**
 * Add stylesheet to the page
 */
add_action( 'wp_enqueue_scripts', 'safely_add_stylesheet' );

function safely_add_stylesheet() {
    wp_enqueue_style( 'prefix-style', plugins_url('style.css', __FILE__) );
}

class audience1st_ticket_availability extends WP_Widget {

    const A1_URL = 'audience1st_ticket_rss_url';
    const A1_NUM_SHOWS = 'audience1st_ticket_rss_num_shows';
    
    //process the new widget
    public function __construct() {
        $option = array(
            'classname' => 'audience1st_ticket_availability',
            'description' => 'Audience1st ticket availability thermometers for next several performances.'
        );

        $this->WP_Widget('audience1st_ticket_availability', 'Audience1st Ticket Availability', $option);

    }
 
    //build the widget settings form
    function form($instance) {
        $num_shows = get_option('audience1st_ticket_rss_num_shows');
        echo '<p>Display Tickets for the next ' . $num_shows . ' shows</p>';
    }

    //save the widget settings
    function update($new_instance, $old_instance) {
        return $new_instance;
    }
         
    // helper function: retrieve & return RSS XML feed
    function load_rss_feed($url) {
        preg_match('/^https?:\/\/([^\/:]+)/', $url, $matches);
        $host = $matches[1];
        $opts = array('http' => array('method' => "GET",
                                      'header' => array('User-Agent' => 'PHP-LibXML-agent',
                                                        'Accept' => '*/*',
                                                        'Host' => $host)));
        libxml_set_streams_context(stream_context_create($opts));
        $rss = new DOMDocument();
        $rss->load($url);
        return $rss;
    }

    // helper function: given an XML node, extract the text value of a child element
    function nodeItem($node,$item) {
        return $node->getElementsByTagName($item)->item(0)->nodeValue;
    }

    //display the widget
    function widget($args, $instance) {

        echo '<div class=ticketRSS--widget">';
        echo '  <h3>Get Tickets</h3>';
        echo '  <div class="ticketsRSS">';
        echo '    <div class="ticketRSS--header-row">';
        echo '      <div class="ticketRSS--header">Description</div>';
        echo '      <div class="ticketRSS--header">Date</div>';
        echo '      <div class="ticketRSS--header">Price</div>';
        echo '      <div class="ticketRSS--header">Availability</div>';
        echo '    </div>';

        $rss = load_rss_feed(get_option(audience1st_ticket_availability::A1_URL) . '/rss/availability.rss');
        $num_shows = get_option(audience1st_ticket_availability::A1_NUM_SHOWS);
        $i = 1;

        foreach ($rss->getElementsByTagName('item') as $node) {
            echo '<div class="ticketRSS--row">';
            //echo '<p class="ticketRSS--title"><a href="'. $item['link'] . '">' . $item['title'] . '</a></p>';
            echo '  <p class="ticketRSS--title">' . nodeItem($node,'show')         . '</p>';
            echo '  <p class="ticketRSS--date">'  . nodeItem($node,'showDateTime') . '</p>';
            echo '  <p class="ticketRSS--price">' . nodeItem($node,'priceRange')   . '</p>';
            echo '  <p class="ticketRSS--availability">';
            switch ($avail = nodeItem($node,'availabilityGrade')) {
            case '3':
                echo '  <span class="availability availability--high"><span></span><span></span><span></span></span>';
                break;
            case '2':
                echo '  <span class="availability availability--medium"><span></span><span></span></span>';
                break;
            case '1':
                echo '  <span class="availability availability--low"><span></span></span>';
                break;
            case '0':
                echo '  <span class="availability availability--sold-out"></span>';
            }
            echo '  </p>';
            // show Buy link if available
            echo '  <p class="ticketRSS--link">';
            if ($avail == '0') {
                echo 'Sold out!';
            } else {
                echo '<a href="'. nodeItem($node,'link') . '" target="_blank">Buy</a>';
            }
            echo "</p></div>";

            if ($i++ == $num_shows) break;
        }
        echo '</div>';
        echo '</div>';

    }
    // helper function: display 'legend'
    function showLegend() {
        echo '<div class="ticketRSS--footer">';
        echo '  <h4>Availability</h4>';
        echo '  <div>';
        echo '    <span>Excellent</span><span class="availability availability--high"><span></span><span></span><span></span></span>';
        echo '    <span>Good</span><span class="availability availability--medium"><span></span><span></span></span>';
        echo '    <span>Limited</span><span class="availability availability--low"><span></span></span>';
        echo '    <span>Sold Out</span><span class="availability availability--sold-out"></span>';
        //echo '<span>Unavailable</span><span class="availability availability--none"><span></span><span></span><span></span></span>';
        echo '  </div>';
        echo '</div>';
    }
 
}

register_activation_hook(__FILE__, 'audience1st_ticket_availability_activation');
function audience1st_ticket_availability_activation() {
    update_option('audience1st_ticket_rss_version', '1.0.0');
}
 
add_action('widgets_init', 'audience1st_ticket_availability_register');
function audience1st_ticket_availability_register() {
    register_widget('audience1st_ticket_availability');
}
?>

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
add_action( 'wp_enqueue_scripts', 'a1ta_safely_add_stylesheet' );

function a1ta_safely_add_stylesheet() {
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

        parent::__construct('audience1st_ticket_availability', 'Audience1st Ticket Availability', $option);

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
                                      'header' => "User-Agent: PHP-LibXML-agent\r\n" . "Accept: */*\r\n" . "Host: " . $host . "\r\n"));
        libxml_set_streams_context(stream_context_create($opts));
        $rss = new DOMDocument();
        $rss->load($url);
        return $rss;
    }

    // helper function: given an XML node, extract the text value of a child element
    function nodeItem($node,$item) {
        return esc_html($node->getElementsByTagName($item)->item(0)->nodeValue);
    }

    //display the widget
    function widget($args, $instance) {
        $rss = $this->load_rss_feed(get_option(audience1st_ticket_availability::A1_URL) .
                                    '/rss/availability.rss');
        $num_shows = get_option(audience1st_ticket_availability::A1_NUM_SHOWS);

        echo <<<endOfHeaderRow
        <div class=ticketRSS--widget">
          <h3>Get Tickets</h3>
          <div class="ticketRSS">
            <div class="ticketRSS--header-row">
              <div class="ticketRSS--header">Show</div>
              <div class="ticketRSS--header">Date</div>
              <div class="ticketRSS--header">Price</div>
              <div class="ticketRSS--header">Availability</div>
            </div>
endOfHeaderRow;

        $i = 1;
        foreach ($rss->getElementsByTagName('item') as $node) {
            $avail = $this->nodeItem($node,'availabilityGrade');
            $show = $this->nodeItem($node, 'show');
            $link_url = $this->nodeItem($node, 'link');
            $showdate = $this->nodeItem($node,'showDateTime'); 
            $price = $this->nodeItem($node, 'priceRange');
            echo '<div class="ticketRSS--row">';
            echo '  <p class="ticketRSS--title">' . $show . '</p>';
            echo '  <p class="ticketRSS--date">';
            if ($avail == '0') {  // sold out
                echo $showdate;
            } else {
                echo('<a class="ticketRSS--link" href="' . $link_url . '">' . $showdate . '</a>');
            }
            echo '  </p>';
            echo '  <p class="ticketRSS--price">' . $price . '</p>';
            echo '  <p class="ticketRSS--availability">';
            switch ($avail) {
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
            echo '</div>  <!-- ticketRSS--row -->';
            if ($i++ == $num_shows) break;
        }
        echo ' </div>  <!-- ticketRSS -->';
		$this->showLegend();
        echo '</div>   <!-- widget -->';
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

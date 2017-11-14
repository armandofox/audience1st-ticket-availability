<?php 
    /*
    Plugin Name: Audience1 RSS
    Plugin URI: http://www.kanopistudios.com
    Description: Plugin for displaying events pulled from Audience1
    Author: Kanopi Studios
    Version: 1.0
    Author URI: http://www.kanopistudios.com
    */
?>

<?php

/**
 * Add stylesheet to the page
 */
add_action( 'wp_enqueue_scripts', 'safely_add_stylesheet' );

function safely_add_stylesheet() {
    wp_enqueue_style( 'prefix-style', plugins_url('style.css', __FILE__) );
}

class audienceOne_rss extends WP_Widget {
 
	//process the new widget
	public function __construct() {
		$option = array(
			'classname' => 'audienceOne_rss',
			'description' => 'RPG - X-Force Newsletter Box'
		);

		$this->WP_Widget('audienceOne_rss', 'Audience1 RSS Feed', $option);

	}
 
	//build the widget settings form
	function form($instance) {
		echo '<p>Display Tickets for the next 5 shows</p>';
	}

	//save the widget settings
	function update($new_instance, $old_instance) {
		return $old_instance;
	}
	 
	//display the widget
	function widget($args, $instance) {

		echo '<div class=ticketRSS--widget">';
			echo '<h3>Get Tickets</h3>';
			echo '<div class="ticketsRSS">';
				echo '<div class="ticketRSS--header-row">';
					echo '<div class="ticketRSS--header">Description</div>';
					echo '<div class="ticketRSS--header">Date</div>';
					echo '<div class="ticketRSS--header">Price</div>';
					echo '<div class="ticketRSS--header">Availability</div>';
				echo '</div>';

			$rss = new DOMDocument();
			$rss->load('https://www.audience1st.com/altarena/rss/availability'); // Set the blog RSS feed url here

			$i = 1;
			foreach ($rss->getElementsByTagName('item') as $node) {
				$item = array ( 
					'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
					'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
					'show' => $node->getElementsByTagName('show')->item(0)->nodeValue,
					'showDateTime' => $node->getElementsByTagName('showDateTime')->item(0)->nodeValue,
					'availabilityGrade' => $node->getElementsByTagName('availabilityGrade')->item(0)->nodeValue,
					'priceRange' => $node->getElementsByTagName('priceRange')->item(0)->nodeValue,

					);
				echo '<div class="ticketRSS--row">';
					//echo '<p class="ticketRSS--title"><a href="'. $item['link'] . '">' . $item['title'] . '</a></p>';
					echo '<p class="ticketRSS--title">' . $item['show'] . '</p>';
					echo '<p class="ticketRSS--date">' . $item['showDateTime'] . '</p>';
					echo '<p class="ticketRSS--price">' . $item['priceRange'] . '</p>';
					echo '<p class="ticketRSS--availability">';
					if ($item['availabilityGrade'] == '3') {
						echo '<span class="availability availability--high"><span></span><span></span><span></span></span>';
					} elseif ($item['availabilityGrade'] == '2') {
						echo '<span class="availability availability--medium"><span></span><span></span></span>';
					} elseif ($item['availabilityGrade'] == '1') {
						echo '<span class="availability availability--low"><span></span></span>';
					} elseif ($item['availabilityGrade'] == '0') {
						echo '<span class="availability availability--sold-out"></span>';
					}
					echo '</p>';
					if ($item['availabilityGrade'] == '0') {
						echo '<p class="ticketRSS--link">Sold out!</p>';
					} else {
						echo '<p class="ticketRSS--link"><a href="'. $item['link'] . '" target="_blank">Buy</a></p>';
					}
				echo '</div>';

				if ($i++ == 10) break;
			}
			echo '</div>';
			echo '<div class="ticketRSS--footer">';
				echo '<h4>Availability</h4>';
				echo '<div>';
					echo '<span>Excellent</span><span class="availability availability--high"><span></span><span></span><span></span></span>';
					echo '<span>Good</span><span class="availability availability--medium"><span></span><span></span></span>';
					echo '<span>Limited</span><span class="availability availability--low"><span></span></span>';
					echo '<span>Sold Out</span><span class="availability availability--sold-out"></span>';
					//echo '<span>Unavailable</span><span class="availability availability--none"><span></span><span></span><span></span></span>';
				echo '</div>';
			echo '</div>';
		echo '</div>';

	}
 
}
 
add_action('widgets_init', 'audienceOne_rss_register');
function audienceOne_rss_register() {
	register_widget('audienceOne_rss');
}
?>
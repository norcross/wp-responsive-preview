<?php 
/* 
Plugin Name: WP Responsive Preview
Plugin URI: http://andrewnorcross.com/plugins/
Description: Displays a post in a new window in various browser sizes.
Version: 1.0
Author: Andrew Norcross
Author URI: http://andrewnorcross.com

    Copyright 2012 Andrew Norcross

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 



// Start up the engine 
class ResponsivePreview
{
	/**
	 * Static property to hold our singleton instance
	 * @var ResponsivePreview
	 */
	static $instance = false;


	/**
	 * This is our constructor, which is private to force the use of
	 * getInstance() to make this a Singleton
	 *
	 * @return ResponsivePreview
	 */
	private function __construct() {
		add_action		( 'admin_head', 					array( $this, 'css_head'		) );
		add_action		( 'post_submitbox_misc_actions',	array( $this, 'rwd_button'		) );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return ResponsivePreview
	 */
	 
	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}


	/**
	 * CSS in the head for the settings page
	 *
	 * @return YOURLSCreator
	 */

	public function css_head() { ?>
		<style type="text/css">

		p.rwd-button {
			text-align: center;
			margin: 0.4em 0;
		}
		
		</style>

	<?php }

	
	/**
	 * Add responsive button to publish metabox
	 *
	 * @return ResponsivePreview
	 */

	public function rwd_button() {
	
		global $post;

		// bail if auto-draft, since there's nothing to preview
		if ('auto-draft' == $post->post_status)
			return;

		// build out box		        
		$rwd_id		= $post->ID;
		$rwd_nonce	= wp_create_nonce('post_preview_' . $rwd_id);
		
		$rwd_draft	= add_query_arg( 'preview', 'true', get_permalink($rwd_id) );
		$rwd_publ	= add_query_arg( array( 'preview' => 'true', 'preview_id' => $rwd_id, 'preview_nonce' => $rwd_nonce ), get_permalink($rwd_id) );

		$rwd_root	= 'http://www.responsinator.com/';
		$rwd_view	= 'publish' == $post->post_status ? $rwd_root.'?url='.esc_url($rwd_publ).'' : $rwd_root.'?url='.esc_url($rwd_draft);
		
		// display the button
		echo '<div class="misc-pub-section misc-pub-section-last" style="border-top: 1px solid #eee;">';
		echo '<p class="rwd-button"><a title="Click this button to load this content in a new window in various browser sizes." href="'.$rwd_view.'" class="button-secondary" target="_blank">Preview Responsive</a></p>';
		echo '</div>';

	}

/// end class
}


// Instantiate our class
$ResponsivePreview = ResponsivePreview::getInstance();

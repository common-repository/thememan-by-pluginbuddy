<?php
/**
 * @package Theme Manager
 * @author Dustin Bolton
 * @version 0.1.0
 *
 * Plugin Name: Theme Manager
 * Plugin URI: http://pluginbuddy.com/
 * Description: Adds a drop down box to your Manage Themes page to quickly Preview & Activate themes for development.
 * Version: 0.1.0
 * Author: Dustin Bolton
 * Author URI: http://dustinbolton.com
 *
 * Installation:
 * 
 * 1. Download and unzip the latest release zip file.
 * 2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
 * 3. Upload the entire plugin directory to your `/wp-content/plugins/` directory.
 * 4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
 * 
 * Usage:
 * 
 * 1. Navigate to the Appearance -> Themes in the WordPress Admin.
 * 2. Select a theme from the dropdown at the top middle of the page.
 * 3. Select Preview or Activate.
 *
 */


if (!class_exists("iThemesThemeMan")) {
    class iThemesThemeMan {
		var $_version = '0.1.0';
	
		var $_var = 'ithemes-thememan';
		var $_name = 'ThemeMan';
		var $_pluginPath = '';
		var $_pluginRelativePath = '';
		var $_pluginURL = '';
	
	
		/**
		 * iThemesThemeMan()
		 *
		 * Default Constructor
		 *
		 */
        function iThemesThemeMan() {
			$this->_pluginPath = dirname( __FILE__ );
			$this->_pluginRelativePath = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', $this->_pluginPath ) ), '\\\/' );
			$this->_pluginURL = get_option( 'siteurl' ) . '/' . $this->_pluginRelativePath;

			// Admin.
			if ( is_admin() ) {
				//if (array_shift(explode('?', (basename($_SERVER['REQUEST_URI'])))) == 'themes.php') {
				if ( 'themes.php' == basename($_SERVER['PHP_SELF']) ) {
					add_action('admin_notices', array(&$this, 'view_themes' ));
				}
			}
        }
	
	
		/**
		 * iThemesThemeMan::view_themes()
		 *
		 * Display drop down list of all installed themes.
		 *
		 */
		function view_themes() {
			wp_enqueue_script( 'ithemes-'.$this->_var.'-admin-js', $this->_pluginURL . '/js/admin.js' );
			wp_print_scripts( 'ithemes-'.$this->_var.'-admin-js' );
			
			$this->_themes = get_themes();
			uksort( $this->_themes, array( &$this, '_sortGroupsByName' ) );

			echo '<div style="position: absolute; top: 66px; left: 525px; background-color: #F9F9F9; -moz-border-radius: 15px; -webkit-border-radius: 15px;">';
			echo '<form><a href="http://pluginbuddy.com" title="'.$this->_name.' '.$this->_version.' by PluginBuddy.com"><img src="/wp-content/plugins/thememan/images/pluginbuddy.png" style="vertical-align: -3px;" /></a> <select name="ithemes-thememan_themes" id="ithemes-thememan_themes" title="'.$this->_name.' '.$this->_version.' by PluginBuddy.com - Select a theme to preview or activate. Asterisk indicates current activated theme.">';
			foreach ( $this->_themes as $k ) {
				$activate_link = wp_nonce_url("themes.php?action=activate&amp;template=".urlencode($k['Template'])."&amp;stylesheet=".urlencode($k['Stylesheet']), 'switch-theme_' . $k['Template']);
				$preview_link = get_option('siteurl').'/?preview=1&amp;template='.$k['Template'].'&amp;stylesheet='.$k['Stylesheet'].'&amp;TB_iframe=true';
				echo '<option value="'.$activate_link.'" title="'.$preview_link.'"';
				if ( get_option('template') == $k['Template'] ) {
					echo ' SELECTED>'.substr($k['Name'], 0, 20).'* &middot; v'.$k['Version'].' &middot; '.str_replace( ABSPATH.'wp-content', '', $k['Template Dir'] ) .'</a>';
				} else {
					echo '>'.substr($k['Name'], 0, 20).' &middot; v'.$k['Version'].' &middot; '.str_replace( ABSPATH.'wp-content', '', $k['Template Dir'] ) .'</a>';
				}
			}
			echo '</select>';
			echo '<a id="ithemes-thememan_preview" class="button add-new-h2" style="vertical-align: -3px;">Preview</a>';
			echo '<a id="ithemes-thememan_activate" class="button add-new-h2" style="vertical-align: -3px;">Activate</a>';
			echo '</form>';
			echo '</div>';
			
			unset($this->_themes);
		}
		
		function _sortGroupsByName( $a, $b ) {
			if ( $this->_themes[$a]['Name'] < $this->_themes[$b]['Name'] )
				return -1;
			
			return 1;
		}
		

    } // End class

	$iThemesThemeMan = new iThemesThemeMan(); // Create instance
}
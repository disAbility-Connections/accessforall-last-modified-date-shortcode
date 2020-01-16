<?php
/**
 * Plugin Name: Access For All - Last Modified Date Shortcode
 * Description: This Shortcode lives in a plugin to ensure that it isn't Theme Specific (Since that would prevent it from working in the App)
 * Version: 0.1.0
 * Text Domain: accessforall-last-modified-date-shortcode
 * Author: Real Big Marketing
 * Author URI: https://realbigmarketing.com/
 * Contributors: d4mation
 * GitHub Plugin URI: disAbility-Connections/accessforall-last-modified-date-shortcode
 * GitHub Branch: master
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AccessForAll_Last_Modified_Date_Shortcode' ) ) {

	/**
	 * Main AccessForAll_Last_Modified_Date_Shortcode class
	 *
	 * @since	  {{VERSION}}
	 */
	final class AccessForAll_Last_Modified_Date_Shortcode {
		
		/**
		 * @var			array $plugin_data Holds Plugin Header Info
		 * @since		{{VERSION}}
		 */
		public $plugin_data;
		
		/**
		 * @var			array $admin_errors Stores all our Admin Errors to fire at once
		 * @since		{{VERSION}}
		 */
		private $admin_errors;

		/**
		 * Get active instance
		 *
		 * @access	  public
		 * @since	  {{VERSION}}
		 * @return	  object self::$instance The one true AccessForAll_Last_Modified_Date_Shortcode
		 */
		public static function instance() {
			
			static $instance = null;
			
			if ( null === $instance ) {
				$instance = new static();
			}
			
			return $instance;

		}
		
		protected function __construct() {
			
			$this->setup_constants();
			$this->load_textdomain();
			
			if ( version_compare( get_bloginfo( 'version' ), '4.4' ) < 0 ) {
				
				$this->admin_errors[] = sprintf( _x( '%s requires v%s of %sWordPress%s or higher to be installed!', 'First string is the plugin name, followed by the required WordPress version and then the anchor tag for a link to the Update screen.', 'accessforall-last-modified-date-shortcode' ), '<strong>' . $this->plugin_data['Name'] . '</strong>', '4.4', '<a href="' . admin_url( 'update-core.php' ) . '"><strong>', '</strong></a>' );
				
				if ( ! has_action( 'admin_notices', array( $this, 'admin_errors' ) ) ) {
					add_action( 'admin_notices', array( $this, 'admin_errors' ) );
				}
				
				return false;
				
			}
			
			$this->require_necessities();
			
			// Register our CSS/JS for the whole plugin
			add_action( 'init', array( $this, 'register_scripts' ) );

			add_shortcode( 'accessforall_last_modified_date', array( $this, 'add_shortcode' ) );
			
		}

		/**
		 * Setup plugin constants
		 *
		 * @access	  private
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		private function setup_constants() {
			
			// WP Loads things so weird. I really want this function.
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}
			
			// Only call this once, accessible always
			$this->plugin_data = get_plugin_data( __FILE__ );

			if ( ! defined( 'AccessForAll_Last_Modified_Date_Shortcode_VER' ) ) {
				// Plugin version
				define( 'AccessForAll_Last_Modified_Date_Shortcode_VER', $this->plugin_data['Version'] );
			}

			if ( ! defined( 'AccessForAll_Last_Modified_Date_Shortcode_DIR' ) ) {
				// Plugin path
				define( 'AccessForAll_Last_Modified_Date_Shortcode_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'AccessForAll_Last_Modified_Date_Shortcode_URL' ) ) {
				// Plugin URL
				define( 'AccessForAll_Last_Modified_Date_Shortcode_URL', plugin_dir_url( __FILE__ ) );
			}
			
			if ( ! defined( 'AccessForAll_Last_Modified_Date_Shortcode_FILE' ) ) {
				// Plugin File
				define( 'AccessForAll_Last_Modified_Date_Shortcode_FILE', __FILE__ );
			}

		}

		/**
		 * Internationalization
		 *
		 * @access	  private 
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		private function load_textdomain() {

			// Set filter for language directory
			$lang_dir = AccessForAll_Last_Modified_Date_Shortcode_DIR . '/languages/';
			$lang_dir = apply_filters( 'accessforall_last_modified_date_shortcode_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'accessforall-last-modified-date-shortcode' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'accessforall-last-modified-date-shortcode', $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/accessforall-last-modified-date-shortcode/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/accessforall-last-modified-date-shortcode/ folder
				// This way translations can be overridden via the Theme/Child Theme
				load_textdomain( 'accessforall-last-modified-date-shortcode', $mofile_global );
			}
			else if ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/accessforall-last-modified-date-shortcode/languages/ folder
				load_textdomain( 'accessforall-last-modified-date-shortcode', $mofile_local );
			}
			else {
				// Load the default language files
				load_plugin_textdomain( 'accessforall-last-modified-date-shortcode', false, $lang_dir );
			}

		}
		
		/**
		 * Include different aspects of the Plugin
		 * 
		 * @access	  private
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		private function require_necessities() {
			
		}
		
		/**
		 * Show admin errors.
		 * 
		 * @access	  public
		 * @since	  {{VERSION}}
		 * @return	  HTML
		 */
		public function admin_errors() {
			?>
			<div class="error">
				<?php foreach ( $this->admin_errors as $notice ) : ?>
					<p>
						<?php echo $notice; ?>
					</p>
				<?php endforeach; ?>
			</div>
			<?php
		}
		
		/**
		 * Register our CSS/JS to use later
		 * 
		 * @access	  public
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		public function register_scripts() {
			
			wp_register_style(
				'accessforall-last-modified-date-shortcode',
				AccessForAll_Last_Modified_Date_Shortcode_URL . 'dist/assets/css/app.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : AccessForAll_Last_Modified_Date_Shortcode_VER
			);
			
			wp_register_script(
				'accessforall-last-modified-date-shortcode',
				AccessForAll_Last_Modified_Date_Shortcode_URL . 'dist/assets/js/app.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : AccessForAll_Last_Modified_Date_Shortcode_VER,
				true
			);
			
			wp_localize_script( 
				'accessforall-last-modified-date-shortcode',
				'accessForAllLastModifiedDateShortcode',
				apply_filters( 'accessforall_last_modified_date_shortcode_localize_script', array() )
			);
			
			wp_register_style(
				'accessforall-last-modified-date-shortcode-admin',
				AccessForAll_Last_Modified_Date_Shortcode_URL . 'dist/assets/css/admin.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : AccessForAll_Last_Modified_Date_Shortcode_VER
			);
			
			wp_register_script(
				'accessforall-last-modified-date-shortcode-admin',
				AccessForAll_Last_Modified_Date_Shortcode_URL . 'dist/assets/js/admin.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : AccessForAll_Last_Modified_Date_Shortcode_VER,
				true
			);
			
			wp_localize_script( 
				'accessforall-last-modified-date-shortcode-admin',
				'accessForAllLastModifiedDateShortcode',
				apply_filters( 'accessforall_last_modified_date_shortcode_localize_admin_script', array() )
			);
			
		}

		/**
		 * Adds our Shortcode to use within the Posts as shown on the Website and the App
		 *
		 * @param   array   $atts     Shortcode Atts
		 * @param   string  $content  Shortcode Content
		 *
		 * @return  string            Shortcode output
		 */
		public function add_shortcode( $atts, $content = '' ) {

			$atts = shortcode_atts( array(
				'post_id' => get_the_ID(),
			), $atts );

			return get_the_modified_date( '', $atts['post_id'] );

		}
		
	}
	
} // End Class Exists Check

/**
 * The main function responsible for returning the one true AccessForAll_Last_Modified_Date_Shortcode
 * instance to functions everywhere
 *
 * @since	  {{VERSION}}
 * @return	  \AccessForAll_Last_Modified_Date_Shortcode The one true AccessForAll_Last_Modified_Date_Shortcode
 */
add_action( 'plugins_loaded', 'accessforall_last_modified_date_shortcode_load' );
function accessforall_last_modified_date_shortcode_load() {

	require_once __DIR__ . '/core/accessforall-last-modified-date-shortcode-functions.php';
	ACCESSFORALLLASTMODIFIEDDATESHORTCODE();

}
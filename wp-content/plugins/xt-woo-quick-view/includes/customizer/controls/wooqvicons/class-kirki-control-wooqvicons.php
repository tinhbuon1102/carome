<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Kirki_Control_Wooqvicons' ) && class_exists('Kirki_Control_Base')) {


	/**
	 * Dashicons control (modified radio).
	 */
	class Kirki_Control_Wooqvicons extends Kirki_Control_Base {

		/**
		 * The control type.
		 *
		 * @access public
		 * @var string
		 */
		public $type = 'wooqvicons';


		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {
			
			$plugin = woo_quick_view();
			wp_enqueue_style( 'wooqvicons', $plugin->plugin_url('public/assets/css', 'wooqvicons.css'), array(), '1.0.0');
			wp_enqueue_style( 'xtkirki-wooqvicons', plugin_dir_url(__FILE__).'css/wooqvicons.css', array(), '1.0.0');
			wp_enqueue_script( 'xtkirki-wooqvicons', plugin_dir_url(__FILE__).'js/wooqvicons.js', array(), '1.0.0');
		}
		
		
		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @access public
		 */
		public function to_json() {
			
			$icons = false;

			if(!empty($this->choices) && !empty($this->choices['types']) && is_array($this->choices['types'])) {
				
				$types = $this->choices['types'];
				$icons = array();
				foreach($types as $type) {
					$icons = array_merge($icons, self::get_icons($type));
				}
				$this->choices = $icons;
			} 
			
			parent::to_json();
			
			if($icons === false) {
				$this->json['icons'] = self::get_icons();
			}
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see Kirki_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 *
		 * @access protected
		 */
		protected function content_template() {
			?>
			<# if ( data.tooltip ) { #>
				<a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='wooqvicons wooqvicons-info'></span></a>
			<# } #>
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			<div class="icons-wrapper">
				<# if ( 'undefined' !== typeof data.choices && 1 < _.size( data.choices ) ) { #>
					<# for ( key in data.choices ) { #>
						<input {{{ data.inputAttrs }}} class="wooqvicons-select" type="radio" value="{{ key }}" name="_customize-wooqvicons-radio-{{ data.id }}" id="{{ data.id }}_{{ key }}" {{{ data.link }}}<# if ( data.value === key ) { #> checked="checked"<# } #>>
							<label for="{{ data.id }}_{{ key }}">
								<span class="{{ data.choices[ key ] }}"></span>
							</label>
						</input>
					<# } #>
				<# } else { #>
				
					<h4>Cart Icons</h4>
					<# for ( key in data.icons['cart'] ) { #>
						<input {{{ data.inputAttrs }}} class="wooqvicons-select" type="radio" value="{{ data.icons['cart'][ key ] }}" name="_customize-wooqvicons-radio-{{ data.id }}" id="{{ data.id }}_{{ data.icons['cart'][ key ] }}" {{{ data.link }}}<# if ( data.value === data.icons['cart'][ key ] ) { #> checked="checked"<# } #>>
							<label for="{{ data.id }}_{{ data.icons['cart'][ key ] }}">
								<span class="{{ data.icons['cart'][ key ] }}"></span>
							</label>
						</input>
					<# } #>
					
					<h4>Close Icons</h4>
					<# for ( key in data.icons['close'] ) { #>
						<input {{{ data.inputAttrs }}} class="wooqvicons-select" type="radio" value="{{ data.icons['close'][ key ] }}" name="_customize-wooqvicons-radio-{{ data.id }}" id="{{ data.id }}_{{ data.icons['close'][ key ] }}" {{{ data.link }}}<# if ( data.value === data.icons['close'][ key ] ) { #> checked="checked"<# } #>>
							<label for="{{ data.id }}_{{ data.icons['close'][ key ] }}">
								<span class="{{ data.icons['close'][ key ] }}"></span>
							</label>
						</input>
					<# } #>
					
				<# } #>
			</div>
			<?php
		}
		
			
		protected function render_content() {
			
			self::print_template();
		}
		
		public static function get_icons($type = null) {
			
			$icons = array(
				
				'trigger' => array(
					
					'wooqvicon-eye' => 'wooqvicon-eye',
					'wooqvicon-eye-1' => 'wooqvicon-eye-1',
					'wooqvicon-eye-2' => 'wooqvicon-eye-2',
					'wooqvicon-eye-close-up' => 'wooqvicon-eye-close-up',
					'wooqvicon-medical' => 'wooqvicon-medical',
					'wooqvicon-medical-1' => 'wooqvicon-medical-1',
					'wooqvicon-photo' => 'wooqvicon-photo',
					'wooqvicon-symbols' => 'wooqvicon-symbols',
					'wooqvicon-view' => 'wooqvicon-view',
					'wooqvicon-view-1' => 'wooqvicon-view-1',
					'wooqvicon-view-2' => 'wooqvicon-view-2',
					'wooqvicon-view-3' => 'wooqvicon-view-3',
					'wooqvicon-view-4' => 'wooqvicon-view-4',
					'wooqvicon-visible' => 'wooqvicon-visible',
					
					'wooqvicon-loupe' => 'wooqvicon-loupe',
					'wooqvicon-magnifier' => 'wooqvicon-magnifier',
					'wooqvicon-magnifier-1' => 'wooqvicon-magnifier-1',
					'wooqvicon-magnifier-tool' => 'wooqvicon-magnifier-tool',
					'wooqvicon-magnifying-glass' => 'wooqvicon-magnifying-glass',
					'wooqvicon-magnifying-glass-1' => 'wooqvicon-magnifying-glass-1',
					'wooqvicon-magnifying-glass-browser' => 'wooqvicon-magnifying-glass-browser',
					'wooqvicon-musica-searcher' => 'wooqvicon-musica-searcher',
					'wooqvicon-search' => 'wooqvicon-search',
					'wooqvicon-search-1' => 'wooqvicon-search-1',
					'wooqvicon-search-2' => 'wooqvicon-search-2',
					'wooqvicon-search-3' => 'wooqvicon-search-3',
					'wooqvicon-search-4' => 'wooqvicon-search-4',
					'wooqvicon-search-5' => 'wooqvicon-search-5',
					'wooqvicon-search-6' => 'wooqvicon-search-6',
					'wooqvicon-tool' => 'wooqvicon-tool',
					'wooqvicon-zoom-in' => 'wooqvicon-zoom-in',
					'wooqvicon-zoom-in-1' => 'wooqvicon-zoom-in-1',
					'wooqvicon-square' => 'wooqvicon-square',
					'wooqvicon-square-1' => 'wooqvicon-square-1',
					'wooqvicon-arrows' => 'wooqvicon-arrows',
					'wooqvicon-arrows-1' => 'wooqvicon-arrows-1',
					'wooqvicon-arrows-10' => 'wooqvicon-arrows-10',
					'wooqvicon-arrows-2' => 'wooqvicon-arrows-2',
					'wooqvicon-arrows-11' => 'wooqvicon-arrows-11',
					'wooqvicon-arrow' => 'wooqvicon-arrow',
					'wooqvicon-arrow-1' => 'wooqvicon-arrow-1',
					'wooqvicon-arrows' => 'wooqvicon-arrows',
					'wooqvicon-arrows-1' => 'wooqvicon-arrows-1',
					'wooqvicon-arrows-10' => 'wooqvicon-arrows-10',
					'wooqvicon-arrows-11' => 'wooqvicon-arrows-11',
					'wooqvicon-arrows-2' => 'wooqvicon-arrows-2',
					'wooqvicon-arrows-3' => 'wooqvicon-arrows-3',
					'wooqvicon-arrows-4' => 'wooqvicon-arrows-4',
					'wooqvicon-arrows-5' => 'wooqvicon-arrows-5',
					'wooqvicon-arrows-6' => 'wooqvicon-arrows-6',
					'wooqvicon-arrows-7' => 'wooqvicon-arrows-7',
					'wooqvicon-interface' => 'wooqvicon-interface',
					'wooqvicon-arrows-8' => 'wooqvicon-arrows-8',
					'wooqvicon-arrows-9' => 'wooqvicon-arrows-9',
					'wooqvicon-circle' => 'wooqvicon-circle',
				),
				'close' => array(
					'wooqvicon-cancel' => 'wooqvicon-cancel',
					'wooqvicon-cancel-1' => 'wooqvicon-cancel-1',
					'wooqvicon-cancel-2' => 'wooqvicon-cancel-2',
					'wooqvicon-cancel-3' => 'wooqvicon-cancel-3',
					'wooqvicon-cancel-4' => 'wooqvicon-cancel-4',
					'wooqvicon-cancel-5' => 'wooqvicon-cancel-5',
					'wooqvicon-cancel-6' => 'wooqvicon-cancel-6',
					'wooqvicon-cancel-7' => 'wooqvicon-cancel-7',
					'wooqvicon-cancel-music' => 'wooqvicon-cancel-music',
					'wooqvicon-close' => 'wooqvicon-close',
					'wooqvicon-close-1' => 'wooqvicon-close-1',
					'wooqvicon-close-2' => 'wooqvicon-close-2',
					'wooqvicon-close-3' => 'wooqvicon-close-3',
					'wooqvicon-close-button' => 'wooqvicon-close-button',
					'wooqvicon-close-button-1' => 'wooqvicon-close-button-1',
					'wooqvicon-close-button-2' => 'wooqvicon-close-button-2',
					'wooqvicon-close-circular-button-of-a-cross' => 'wooqvicon-close-circular-button-of-a-cross',
					'wooqvicon-close-cross-circular-interface-button' => 'wooqvicon-close-cross-circular-interface-button',
					'wooqvicon-cross' => 'wooqvicon-cross',
					'wooqvicon-cross-mark-on-a-black-circle-background' => 'wooqvicon-cross-mark-on-a-black-circle-background',
					'wooqvicon-cross-out' => 'wooqvicon-cross-out',
					'wooqvicon-delete' => 'wooqvicon-delete',
					'wooqvicon-delete-button' => 'wooqvicon-delete-button',
					'wooqvicon-error' => 'wooqvicon-error',
					'wooqvicon-exit-to-app-button' => 'wooqvicon-exit-to-app-button',
					'wooqvicon-remove-button' => 'wooqvicon-remove-button',
				),
				'arrow' => array(
					'wooqvicon-angle-pointing-to-left' => 'wooqvicon-angle-pointing-to-left',
					'wooqvicon-arrows-15' => 'wooqvicon-arrows-15',
					'wooqvicon-arrows-25' => 'wooqvicon-arrows-25',
					'wooqvicon-arrows-21' => 'wooqvicon-arrows-21',
					'wooqvicon-arrows-26' => 'wooqvicon-arrows-26',
					'wooqvicon-arrows-27' => 'wooqvicon-arrows-27',
					'wooqvicon-arrows-22' => 'wooqvicon-arrows-22',	
					'wooqvicon-arrows-29' => 'wooqvicon-arrows-29',						
					'wooqvicon-arrows-12' => 'wooqvicon-arrows-12',
					'wooqvicon-arrows-20' => 'wooqvicon-arrows-20',
					'wooqvicon-arrows-13' => 'wooqvicon-arrows-13',
					'wooqvicon-arrows-14' => 'wooqvicon-arrows-14',
					'wooqvicon-arrows-16' => 'wooqvicon-arrows-16',
					'wooqvicon-arrows-18' => 'wooqvicon-arrows-18',
					'wooqvicon-arrows-17' => 'wooqvicon-arrows-17',
					'wooqvicon-arrows-19' => 'wooqvicon-arrows-19',
					'wooqvicon-arrows-23' => 'wooqvicon-arrows-23',
					'wooqvicon-arrows-24' => 'wooqvicon-arrows-24',
					'wooqvicon-arrows-28' => 'wooqvicon-arrows-28',
		
				)
			);	
			
			if(!empty($type) && !empty($icons[$type])) {
				return $icons[$type];	
			}	
			
			return $icons;
		}
		
	}
}

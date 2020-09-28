<?php
/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */

class digital_agency_lite_Control_Typography extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'typography';

	/**
	 * Array 
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $l10n = array();

	/**
	 * Set up our control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $id
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $id, $args = array() ) {

		// Let the parent class do its thing.
		parent::__construct( $manager, $id, $args );

		// Make sure we have labels.
		$this->l10n = wp_parse_args(
			$this->l10n,
			array(
				'color'       => esc_html__( 'Font Color', 'digital-agency-lite' ),
				'family'      => esc_html__( 'Font Family', 'digital-agency-lite' ),
				'size'        => esc_html__( 'Font Size',   'digital-agency-lite' ),
				'weight'      => esc_html__( 'Font Weight', 'digital-agency-lite' ),
				'style'       => esc_html__( 'Font Style',  'digital-agency-lite' ),
				'line_height' => esc_html__( 'Line Height', 'digital-agency-lite' ),
				'letter_spacing' => esc_html__( 'Letter Spacing', 'digital-agency-lite' ),
			)
		);
	}

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'digital-agency-lite-ctypo-customize-controls' );
		wp_enqueue_style(  'digital-agency-lite-ctypo-customize-controls' );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		// Loop through each of the settings and set up the data for it.
		foreach ( $this->settings as $setting_key => $setting_id ) {

			$this->json[ $setting_key ] = array(
				'link'  => $this->get_link( $setting_key ),
				'value' => $this->value( $setting_key ),
				'label' => isset( $this->l10n[ $setting_key ] ) ? $this->l10n[ $setting_key ] : ''
			);

			if ( 'family' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_families();

			elseif ( 'weight' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_weight_choices();

			elseif ( 'style' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_style_choices();
		}
	}

	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template() { ?>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<ul>

		<# if ( data.family && data.family.choices ) { #>

			<li class="typography-font-family">

				<# if ( data.family.label ) { #>
					<span class="customize-control-title">{{ data.family.label }}</span>
				<# } #>

				<select {{{ data.family.link }}}>

					<# _.each( data.family.choices, function( label, choice ) { #>
						<option value="{{ choice }}" <# if ( choice === data.family.value ) { #> selected="selected" <# } #>>{{ label }}</option>
					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.weight && data.weight.choices ) { #>

			<li class="typography-font-weight">

				<# if ( data.weight.label ) { #>
					<span class="customize-control-title">{{ data.weight.label }}</span>
				<# } #>

				<select {{{ data.weight.link }}}>

					<# _.each( data.weight.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.weight.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.style && data.style.choices ) { #>

			<li class="typography-font-style">

				<# if ( data.style.label ) { #>
					<span class="customize-control-title">{{ data.style.label }}</span>
				<# } #>

				<select {{{ data.style.link }}}>

					<# _.each( data.style.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.style.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.size ) { #>

			<li class="typography-font-size">

				<# if ( data.size.label ) { #>
					<span class="customize-control-title">{{ data.size.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.size.link }}} value="{{ data.size.value }}" />

			</li>
		<# } #>

		<# if ( data.line_height ) { #>

			<li class="typography-line-height">

				<# if ( data.line_height.label ) { #>
					<span class="customize-control-title">{{ data.line_height.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.line_height.link }}} value="{{ data.line_height.value }}" />

			</li>
		<# } #>

		<# if ( data.letter_spacing ) { #>

			<li class="typography-letter-spacing">

				<# if ( data.letter_spacing.label ) { #>
					<span class="customize-control-title">{{ data.letter_spacing.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.letter_spacing.link }}} value="{{ data.letter_spacing.value }}" />

			</li>
		<# } #>

		</ul>
	<?php }

	/**
	 * Returns the available fonts.  Fonts should have available weights, styles, and subsets.
	 *
	 * @todo Integrate with Google fonts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_fonts() { return array(); }

	/**
	 * Returns the available font families.
	 *
	 * @todo Pull families from `get_fonts()`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	function get_font_families() {

		return array(
			'' => __( 'No Fonts', 'digital-agency-lite' ),
        'Abril Fatface' => __( 'Abril Fatface', 'digital-agency-lite' ),
        'Acme' => __( 'Acme', 'digital-agency-lite' ),
        'Anton' => __( 'Anton', 'digital-agency-lite' ),
        'Architects Daughter' => __( 'Architects Daughter', 'digital-agency-lite' ),
        'Arimo' => __( 'Arimo', 'digital-agency-lite' ),
        'Arsenal' => __( 'Arsenal', 'digital-agency-lite' ),
        'Arvo' => __( 'Arvo', 'digital-agency-lite' ),
        'Alegreya' => __( 'Alegreya', 'digital-agency-lite' ),
        'Alfa Slab One' => __( 'Alfa Slab One', 'digital-agency-lite' ),
        'Averia Serif Libre' => __( 'Averia Serif Libre', 'digital-agency-lite' ),
        'Bangers' => __( 'Bangers', 'digital-agency-lite' ),
        'Boogaloo' => __( 'Boogaloo', 'digital-agency-lite' ),
        'Bad Script' => __( 'Bad Script', 'digital-agency-lite' ),
        'Bitter' => __( 'Bitter', 'digital-agency-lite' ),
        'Bree Serif' => __( 'Bree Serif', 'digital-agency-lite' ),
        'BenchNine' => __( 'BenchNine', 'digital-agency-lite' ),
        'Cabin' => __( 'Cabin', 'digital-agency-lite' ),
        'Cardo' => __( 'Cardo', 'digital-agency-lite' ),
        'Courgette' => __( 'Courgette', 'digital-agency-lite' ),
        'Cherry Swash' => __( 'Cherry Swash', 'digital-agency-lite' ),
        'Cormorant Garamond' => __( 'Cormorant Garamond', 'digital-agency-lite' ),
        'Crimson Text' => __( 'Crimson Text', 'digital-agency-lite' ),
        'Cuprum' => __( 'Cuprum', 'digital-agency-lite' ),
        'Cookie' => __( 'Cookie', 'digital-agency-lite' ),
        'Chewy' => __( 'Chewy', 'digital-agency-lite' ),
        'Days One' => __( 'Days One', 'digital-agency-lite' ),
        'Dosis' => __( 'Dosis', 'digital-agency-lite' ),
        'Droid Sans' => __( 'Droid Sans', 'digital-agency-lite' ),
        'Economica' => __( 'Economica', 'digital-agency-lite' ),
        'Fredoka One' => __( 'Fredoka One', 'digital-agency-lite' ),
        'Fjalla One' => __( 'Fjalla One', 'digital-agency-lite' ),
        'Francois One' => __( 'Francois One', 'digital-agency-lite' ),
        'Frank Ruhl Libre' => __( 'Frank Ruhl Libre', 'digital-agency-lite' ),
        'Gloria Hallelujah' => __( 'Gloria Hallelujah', 'digital-agency-lite' ),
        'Great Vibes' => __( 'Great Vibes', 'digital-agency-lite' ),
        'Handlee' => __( 'Handlee', 'digital-agency-lite' ),
        'Hammersmith One' => __( 'Hammersmith One', 'digital-agency-lite' ),
        'Inconsolata' => __( 'Inconsolata', 'digital-agency-lite' ),
        'Indie Flower' => __( 'Indie Flower', 'digital-agency-lite' ),
        'IM Fell English SC' => __( 'IM Fell English SC', 'digital-agency-lite' ),
        'Julius Sans One' => __( 'Julius Sans One', 'digital-agency-lite' ),
        'Josefin Slab' => __( 'Josefin Slab', 'digital-agency-lite' ),
        'Josefin Sans' => __( 'Josefin Sans', 'digital-agency-lite' ),
        'Kanit' => __( 'Kanit', 'digital-agency-lite' ),
        'Lobster' => __( 'Lobster', 'digital-agency-lite' ),
        'Lato' => __( 'Lato', 'digital-agency-lite' ),
        'Lora' => __( 'Lora', 'digital-agency-lite' ),
        'Libre Baskerville' => __( 'Libre Baskerville', 'digital-agency-lite' ),
        'Lobster Two' => __( 'Lobster Two', 'digital-agency-lite' ),
        'Merriweather' => __( 'Merriweather', 'digital-agency-lite' ),
        'Monda' => __( 'Monda', 'digital-agency-lite' ),
        'Montserrat' => __( 'Montserrat', 'digital-agency-lite' ),
        'Muli' => __( 'Muli', 'digital-agency-lite' ),
        'Marck Script' => __( 'Marck Script', 'digital-agency-lite' ),
        'Noto Serif' => __( 'Noto Serif', 'digital-agency-lite' ),
        'Open Sans' => __( 'Open Sans', 'digital-agency-lite' ),
        'Overpass' => __( 'Overpass', 'digital-agency-lite' ),
        'Overpass Mono' => __( 'Overpass Mono', 'digital-agency-lite' ),
        'Oxygen' => __( 'Oxygen', 'digital-agency-lite' ),
        'Orbitron' => __( 'Orbitron', 'digital-agency-lite' ),
        'Patua One' => __( 'Patua One', 'digital-agency-lite' ),
        'Pacifico' => __( 'Pacifico', 'digital-agency-lite' ),
        'Padauk' => __( 'Padauk', 'digital-agency-lite' ),
        'Playball' => __( 'Playball', 'digital-agency-lite' ),
        'Playfair Display' => __( 'Playfair Display', 'digital-agency-lite' ),
        'PT Sans' => __( 'PT Sans', 'digital-agency-lite' ),
        'Philosopher' => __( 'Philosopher', 'digital-agency-lite' ),
        'Permanent Marker' => __( 'Permanent Marker', 'digital-agency-lite' ),
        'Poiret One' => __( 'Poiret One', 'digital-agency-lite' ),
        'Quicksand' => __( 'Quicksand', 'digital-agency-lite' ),
        'Quattrocento Sans' => __( 'Quattrocento Sans', 'digital-agency-lite' ),
        'Raleway' => __( 'Raleway', 'digital-agency-lite' ),
        'Rubik' => __( 'Rubik', 'digital-agency-lite' ),
        'Rokkitt' => __( 'Rokkitt', 'digital-agency-lite' ),
        'Russo One' => __( 'Russo One', 'digital-agency-lite' ),
        'Righteous' => __( 'Righteous', 'digital-agency-lite' ),
        'Slabo' => __( 'Slabo', 'digital-agency-lite' ),
        'Source Sans Pro' => __( 'Source Sans Pro', 'digital-agency-lite' ),
        'Shadows Into Light Two' => __( 'Shadows Into Light Two', 'digital-agency-lite'),
        'Shadows Into Light' => __( 'Shadows Into Light', 'digital-agency-lite' ),
        'Sacramento' => __( 'Sacramento', 'digital-agency-lite' ),
        'Shrikhand' => __( 'Shrikhand', 'digital-agency-lite' ),
        'Tangerine' => __( 'Tangerine', 'digital-agency-lite' ),
        'Ubuntu' => __( 'Ubuntu', 'digital-agency-lite' ),
        'VT323' => __( 'VT323', 'digital-agency-lite' ),
        'Varela Round' => __( 'Varela Round', 'digital-agency-lite' ),
        'Vampiro One' => __( 'Vampiro One', 'digital-agency-lite' ),
        'Vollkorn' => __( 'Vollkorn', 'digital-agency-lite' ),
        'Volkhov' => __( 'Volkhov', 'digital-agency-lite' ),
        'Yanone Kaffeesatz' => __( 'Yanone Kaffeesatz', 'digital-agency-lite' )
		);
	}

	/**
	 * Returns the available font weights.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_weight_choices() {

		return array(
			'' => esc_html__( 'No Fonts weight', 'digital-agency-lite' ),
			'100' => esc_html__( 'Thin',       'digital-agency-lite' ),
			'300' => esc_html__( 'Light',      'digital-agency-lite' ),
			'400' => esc_html__( 'Normal',     'digital-agency-lite' ),
			'500' => esc_html__( 'Medium',     'digital-agency-lite' ),
			'700' => esc_html__( 'Bold',       'digital-agency-lite' ),
			'900' => esc_html__( 'Ultra Bold', 'digital-agency-lite' ),
		);
	}

	/**
	 * Returns the available font styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_style_choices() {

		return array(
			'' => esc_html__( 'No Fonts Style', 'digital-agency-lite' ),
			'normal'  => esc_html__( 'Normal', 'digital-agency-lite' ),
			'italic'  => esc_html__( 'Italic', 'digital-agency-lite' ),
			'oblique' => esc_html__( 'Oblique', 'digital-agency-lite' )
		);
	}
}

<?php

namespace Classifai\Admin;

use \Classifai\Features\FeaturePostTitleGeneration;
use \Classifai\Features\FeatureExcerptGeneration;
use \Classifai\Features\FeatureContentResizing;
use \Classifai\Services\TextGeneration\OpenAI\ChatGPTPostTitleGeneration;
use \Classifai\Features\Descriptor as FeatureDescriptor;
use \Classifai\Services\Descriptor as ProviderDescriptor;

/**
 * Class Settings
 *
 * The wp_options consists of two keys:
 * - classifai_feature_config Stores the feature settings.
 * - classifai_provider_config Stores the provider settings.
 */
class Settings {
	/**
	 * The feature settings options key.
	 *
	 * @var string
	 */
	const FEATURE_KEY = 'classifai_feature_config';

	/**
	 * The provider settings options key.
	 *
	 * @var string
	 */
	const PROVIDER_KEY = 'classifai_provider_config';

	/**
	 * The feature settings description.
	 *
	 * @var array
	 */
	protected static $feature_description = [];

	/**
	 * The provider settings description.
	 *
	 * @var array
	 */
	protected static $provider_description = [];

	/**
	 * The active settings description depending on the active setting page,
	 * or depending on how the constructor is called.
	 *
	 * @var array
	 */
	protected static $active_description = [];

	/**
	 * The context of the settings page.
	 * Either 'feature' or 'provider'.
	 *
	 * @var string
	 */
	protected $context;

	/**
	 * The context key of the settings page.
	 *
	 * For example, 'post_title_generation' if the context is feature,
	 * or 'openai_chatgpt' if the context is provider.
	 */
	protected $context_key;

	/**
	 * Settings constructor.
	 *
	 * @param string $context
	 * @param string $context_key
	 */
	public function __construct( $context = null, $context_key = null ) {
		$this->context = $context;
		$this->context_key = $context_key;

		/**
		 * @todo: Move this to a separate file to declutter.
		 * @todo: Add a key called provider_fields that are visible only when the provider is selected.
		 */
		self::$feature_description = FeatureDescriptor::get_descriptor();
		self::$provider_description = ProviderDescriptor::get_descriptor();

		$this->set_active_description();
	}

	/**
	 * Sets the context outside of the admin screen where $this->context will be null.
	 *
	 * @param string|null $context
	 */
	private function set_active_description( $context = null ) {
		$__context = $context ? $context : $this->context;

		if ( 'feature' === $__context ) {
			self::$active_description = self::$feature_description;
		} else if ( 'provider' === $__context ) {
			self::$active_description = self::$provider_description;
		}
	}

	/**
	 * Initializes the settings page.
	 * Should be called in the admin page.
	 */
	public function admin_init() {
		$this->context = isset( $_GET['context'] ) ? sanitize_text_field( wp_unslash( $_GET['context'] ) ) : 'feature';
		$this->context_key = isset( $_GET['context_key'] ) ? sanitize_text_field( wp_unslash( $_GET['context_key'] ) ) : FeaturePostTitleGeneration::ID;

		$this->set_active_description();

		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_menu_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Registers the settings.
	 */
	public function register_settings() {
		register_setting( self::FEATURE_KEY, self::FEATURE_KEY, [ 'sanitize_callback' => [ $this, 'sanitize_callback' ] ] );
		register_setting( self::PROVIDER_KEY, self::PROVIDER_KEY, [ 'sanitize_callback' => [ $this, 'sanitize_callback' ] ] );
	}

	/**
	 * Registers the menu page.
	 */
	public function register_menu_page() {
		add_submenu_page(
			'tools.php',
			'ClassifAI Settings',
			'ClassifAI Settings',
			'manage_options',
			'classifai',
			[ $this, 'render_settings_page' ]
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'classifai-admin', CLASSIFAI_PLUGIN_URL . 'build/configstyle.css', [], '1.0.0' );
	}

	/**
	 * Returns the current context of the settings page.
	 *
	 * @return string
	 */
	public function get_context() {
		return $this->context;
	}

	/**
	 * Returns the current context key of the settings page.
	 *
	 * @return string
	 */
	public function get_context_key() {
		return $this->context_key;
	}

	/**
	 * Runs when the settings are saved.
	 * Calls the sanitize_setting of each FieldControl on the current page.
	 *
	 * @param array $data
	 * @return array
	 */
	public function sanitize_callback( $data ) {
		$context     = isset( $_POST['context'] ) ? sanitize_text_field( wp_unslash( $_POST['context'] ) ) : 'feature';
		$context_key = isset( $_POST['context_key'] ) ? sanitize_text_field( wp_unslash( $_POST['context_key'] ) ) : FeaturePostTitleGeneration::ID;

		$this->set_active_description( $context );

		foreach ( self::$active_description[ $context_key ] as $setting_key => $setting ) {
			$class = $this->get_field_class( $setting['field'] );
			$setting_control = new $class( $this, $setting_key, $setting['args'] );
			$data[ $context_key ][ $setting_control->get_id() ] = $setting_control->sanitize_setting( $data[ $context_key ][ $setting_control->get_id() ] );
		}

		$options = get_option( $this->get_active_context_option_key( $context ), [] );

		return array_merge( $options, $data );
	}

	/**
	 * Returns the FieldControl class based on the field type.
	 * This array should be updated when a new FieldControl is added.
	 *
	 * @todo: Add a filter hook to add new FieldControls.
	 *
	 * @return array
	 */
	public function get_field_class( $field ) {
		$field_to_class = apply_filters(
			'classifai_field_to_class_mapping',
			[
				'checkbox' => '\Classifai\Admin\Fields\CheckboxControl',
				'select' => '\Classifai\Admin\Fields\SelectControl',
				'multi_select' => '\Classifai\Admin\Fields\MultiSelectControl',
				'number' => '\Classifai\Admin\Fields\NumberControl',
				'text' => '\Classifai\Admin\Fields\TextControl',
			]
		);

		return $field_to_class[ $field ];
	}

	/**
	 * Returns all the settings of a context_key, eg; the `post_title_generation` when the $key is null.
	 * When the $key is set, only the value of the key under `post_title_generation` is returned.
	 */
	public function get_setting( $key = null ) {
		$option_name = $this->get_active_context_option_key();
		$settings = get_option( $option_name, [] );
		$defaults = $this->get_settings_defaults();
		$merged   = $this->merge_arrays( $settings, $defaults, $this->context_key );

		if ( is_null( $key ) ) {
			return $merged[ $this->context_key ];
		}

		return isset( $merged[ $this->context_key ][ $key ] )
			? $merged[ $this->context_key ][ $key ]
			: self::$active_description[ $this->context_key ][ $key ]['args']['default'];
	}

	/**
	 * Returns the default settings of a context.
	 */
	public function get_settings_defaults() {
		$default_settings = [];

		foreach ( self::$active_description as $description_key => $settings ) {
			foreach ( $settings as $setting_key => $setting ) {
				$default_settings[ $description_key ][ $setting_key ] = $setting['args']['default'];
			}
		}

		return $default_settings;
	}

	/**
	 * Merges the settings with the defaults.
	 *
	 * @return array
	 */
	function merge_arrays( $source, $defaults, $context_key ) {
		if ( ! isset( $source[ $context_key ] ) ) {
			$source[ $context_key ] = $defaults[ $context_key ];
			return $source;
		}

		foreach ( $defaults as $key => $value ) {
			if ( ! isset( $source[ $context_key ] ) ) {
				$source[ $context_key ] = $value;
			}
		}

		return $source;
	}

	/**
	 * Convenience method to get the active option name depending on the current context.
	 * Either `classifai_feature_config` or `classifai_provider_config`.
	 *
	 * @return string
	 */
	private function get_active_context_option_key( $context = null ) {
		$__context = $context ? $context : $this->context;

		switch ( $__context ) {
			case 'feature':
				return self::FEATURE_KEY;

			case 'provider':
				return self::PROVIDER_KEY;

			default:
				return self::FEATURE_KEY;
		}
	}

	/**
	 * Returns the feature columns.
	 * Used to render the feature title column.
	 *
	 * @return array
	 */
	private static function get_feature_columns() {
		return [
			[
				'header' => __( 'Text generation', 'classifai' ),
				'features' => [
					FeaturePostTitleGeneration::ID => __( 'Post Title Generation', 'classifai' ),
					FeatureExcerptGeneration::ID => __( 'Post Excerpt Generation', 'classifai' ),
					FeatureContentResizing::ID => __( 'Content Resizing', 'classifai' ),
				]
			],
			[
				'header' => __( 'Speech', 'classifai' ),
				'features' => []
			]
		];
	}

	/**
	 * Returns the provider columns.
	 * Used to render the provider title column.
	 *
	 * @return array
	 */
	private static function get_provider_columns() {
		return [
			[
				'header' => __( 'OpenAI', 'classifai' ),
				'providers' => [
					ChatGPTPostTitleGeneration::CONNECTOR_ID => __( 'ChatGPT | DALL·E', 'classifai' ),
				]
			],
			[
				'header' => __( 'Microsoft', 'classifai' ),
				'providers' => [
					'microsoft_azure_vision' => __( 'Azure AI Vision', 'classifai' ),
					'microsoft_azure_speech' => __( 'Azure AI Speech', 'classifai' ),
				]
			]
		];
	}

	/**
	 * Renders the settings page.
	 */
	public function render_settings_page() {
		?>
		<h1>ClassifAI</h1>
		<form action="options.php" method="post">
			<div id="classifai-settings">
				<div class="classifai-settings__context">
					<a
						class="classifai-settings__context-features"
						href="<?php echo esc_url( admin_url( 'tools.php?page=classifai&context=feature&context_key=' . FeaturePostTitleGeneration::ID ) ); ?>">
						<?php esc_html_e( 'Features', 'classifai' ) ?>
					</a>
					<a
						class="classifai-settings__context-providers"
						href="<?php echo esc_url( admin_url( 'tools.php?page=classifai&context=provider&context_key=' . ChatGPTPostTitleGeneration::CONNECTOR_ID ) ); ?>">
						<?php esc_html_e( 'Providers', 'classifai' ) ?>
					</a>
				</div>
				<div class="classifai-settings classifai-settings--<?php echo esc_attr( $this->context ); ?>">
					<?php if ( 'feature' === $this->context ) : ?>
						<div class="classifai-settings__context-keys">
							<?php foreach ( self::get_feature_columns() as $column ) : ?>
								<div class="classifai-settings__service-group">
									<div class="classifai-settings__service-group-header">
										<?php echo esc_html( $column['header'] ); ?>
									</div>
									<div class="classifai-settings__service-group-features">
										<?php foreach ( $column['features'] as $feature_id => $feature ) : ?>
											<a
												href="<?php echo esc_url( admin_url( 'tools.php?page=classifai&context=feature&context_key=' . $feature_id ) ); ?>"
												class="classifai-settings__service-group-feature-name <?php echo $this->context_key === $feature_id ? 'classifai-settings__service-group-feature-name--active' : ''; ?>">
												<?php echo esc_html( $feature ); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( 'provider' === $this->context ) : ?>
						<div class="classifai-settings__context-keys">
							<?php foreach ( self::get_provider_columns() as $column ) : ?>
								<div class="classifai-settings__service-group">
									<div class="classifai-settings__service-group-header">
										<?php echo esc_html( $column['header'] ); ?>
									</div>
									<div class="classifai-settings__service-group-providers">
										<?php foreach ( $column['providers'] as $provider_id => $provider ) : ?>
											<a
												href="<?php echo esc_url( admin_url( 'tools.php?page=classifai&context=provider&context_key=' . $provider_id ) ); ?>"
												class="classifai-settings__service-group-provider-name <?php echo $this->context_key === $provider_id ? 'classifai-settings__service-group-provider-name--active' : ''; ?>">
												<?php echo esc_html( $provider ); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<div class="classifai-settings__context-settings">
					<?php
						foreach ( self::$active_description[ $this->context_key ] as $setting_key => $setting ) {
							$class = $this->get_field_class( $setting['field'] );
							$setting_control = new $class( $this, $setting_key, $setting['args'] );
							$setting_control->render();
						}
					?>
						<input type="hidden" name="context" value="<?php echo esc_attr( $this->context ); ?>">
						<input type="hidden" name="context_key" value="<?php echo esc_attr( $this->context_key ); ?>">
						<?php
							settings_fields( $this->get_active_context_option_key() );
							submit_button();
						?>
					</div>
				</div>
			</div>
		</form>
		<?php
	}
}

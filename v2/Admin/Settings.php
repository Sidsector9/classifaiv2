<?php

namespace Classifai\Admin;

use \Classifai\Features\FeaturePostTitleGeneration;
use \Classifai\Features\FeatureExcerptGeneration;
use \Classifai\Features\FeatureContentResizing;

class Settings {
	const FEATURE_KEY = 'classifai_feature_config';
	const PROVIDER_KEY = 'classifai_provider_config';

	protected static $feature_description;

	protected static $provider_description;

	protected static $active_description;

	protected $context;

	protected $context_key;

	public function __construct( $context = null, $context_key = null ) {
		$this->context = $context;
		$this->context_key = $context_key;

		self::$feature_description = [
			FeaturePostTitleGeneration::ID => [
				'status' => [
					'field' => 'checkbox',
					'args' => [
						'default' => 'off',
						'label' => __( 'Enable', 'classifai' ),
						'description' => __( 'Enabling this feature will generate post titles based on the content of the post.', 'classifai' ),
					],
				],
				'provider' => [
					'field' => 'select',
					'args' => [
						'default' => 'openaichatgpt',
						'label' => __( 'Select a provider', 'classifai' ),
						'description' => __( 'Choose an AI service provider to generate post titles.', 'classifai' ),
						'options' => [
							'openaichatgpt' => __( 'OpenAI ChatGPT', 'classifai' ),
						],
					],
				],
				'access_control_by_role' => [
					'field' => 'multi_select',
					'args' => [
						'default' => [ 'administrator' ],
						'label' => __( 'Control access by role', 'classifai' ),
						'description' => __( 'Limit the use of this feature by user roles.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_user_roles(),
					],
				],
				'access_control_by_post_type' => [
					'field' => 'multi_select',
					'args' => [
						'default' => [],
						'label' => __( 'Control access by post type', 'classifai' ),
						'description' => __( 'Limit the use of this feature by post types.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_public_post_types(),
					],
				],
			],
			FeatureExcerptGeneration::ID => [
				'status' => [
					'field' => 'checkbox',
					'args' => [
						'default' => 'off',
						'label' => __( 'Enable', 'classifai' ),
						'description' => __( 'Enabling this feature will generate post excerpt based on the content of the post.', 'classifai' ),
					],
				],
				'provider' => [
					'field' => 'select',
					'args' => [
						'default' => 'openaichatgpt',
						'label' => __( 'Select a provider', 'classifai' ),
						'description' => __( 'Choose an AI service provider to generate post excerpts.', 'classifai' ),
						'options' => [
							'openaichatgpt' => __( 'OpenAI ChatGPT', 'classifai' ),
						],
					],
				],
				'access_control_by_role' => [
					'field' => 'multi_select',
					'args' => [
						'default' => [ 'administrator' ],
						'label' => __( 'Control access by role', 'classifai' ),
						'description' => __( 'Limit the use of this feature by user roles.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_user_roles(),
					],
				],
				'access_control_by_post_type' => [
					'field' => 'multi_select',
					'args' => [
						'default' => [],
						'label' => __( 'Control access by post type', 'classifai' ),
						'description' => __( 'Limit the use of this feature by post types.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_public_post_types(),
					],
				],
			],
			FeatureContentResizing::ID => [
				'status' => [
					'field' => 'checkbox',
					'args' => [
						'default' => 'off',
						'label' => __( 'Enable', 'classifai' ),
						'description' => __( 'Enabling this feature will allow you to resize sections of post content.', 'classifai' ),
					],
				],
				'provider' => [
					'field' => 'select',
					'args' => [
						'default' => 'openaichatgpt',
						'label' => __( 'Select a provider', 'classifai' ),
						'description' => __( 'Choose an AI service provider to generate post excerpts.', 'classifai' ),
						'options' => [
							'openaichatgpt' => __( 'OpenAI ChatGPT', 'classifai' ),
						],
					],
				],
				'access_control_by_role' => [
					'field' => 'multi_select',
					'args' => [
						'default' => [ 'administrator' ],
						'label' => __( 'Control access by role', 'classifai' ),
						'description' => __( 'Limit the use of this feature by user roles.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_user_roles(),
					],
				],
				'access_control_by_post_type' => [
					'field' => 'multi_select',
					'args' => [
						'default' => [],
						'label' => __( 'Control access by post type', 'classifai' ),
						'description' => __( 'Limit the use of this feature by post types.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_public_post_types(),
					],
				],
			]
		];

		if ( 'feature' === $this->context ) {
			self::$active_description = self::$feature_description;
		} else if ( 'provider' === $this->context ) {
			self::$active_description = self::$provider_description;
		}
	}

	public function admin_init() {
		$this->context = isset( $_GET['context'] ) ? sanitize_text_field( wp_unslash( $_GET['context'] ) ) : 'feature';
		$this->context_key = isset( $_GET['context_key'] ) ? sanitize_text_field( wp_unslash( $_GET['context_key'] ) ) : FeaturePostTitleGeneration::ID;

		self::$provider_description = [];

		if ( 'feature' === $this->context ) {
			self::$active_description = self::$feature_description;
		} else if ( 'provider' === $this->context ) {
			self::$active_description = self::$provider_description;
		}

		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_menu_page' ] );
	}

	public function register_settings() {
		register_setting( self::FEATURE_KEY, self::FEATURE_KEY, [ 'sanitize_callback' => [ $this, 'sanitize_callback' ] ] );
		register_setting( self::PROVIDER_KEY, self::PROVIDER_KEY, [ 'sanitize_callback' => [ $this, 'sanitize_callback' ] ] );
	}

	public function get_context() {
		return $this->context;
	}

	public function get_context_key() {
		return $this->context_key;
	}

	public function sanitize_callback( $data ) {
		$context     = isset( $_POST['context'] ) ? sanitize_text_field( wp_unslash( $_POST['context'] ) ) : self::FEATURE_KEY;
		$context_key = isset( $_POST['context_key'] ) ? sanitize_text_field( wp_unslash( $_POST['context_key'] ) ) : FeaturePostTitleGeneration::ID;

		foreach ( self::$active_description[ $context_key ] as $setting_key => $setting ) {
			$class = $this->get_field_class( $setting['field'] );
			$setting_control = new $class( $this, $setting_key, $setting['args'] );
			$data[ $context_key ][ $setting_control->get_id() ] = $setting_control->sanitize_setting( $data[ $context_key ][ $setting_control->get_id() ] );
		}

		$option_name = '';

		if ( 'feature' === $context ) {
			$option_name = self::FEATURE_KEY;
		} else if ( 'provider' === $context ) {
			$option_name = self::PROVIDER_KEY;
		}

		$options = get_option( $option_name, [] );

		return array_merge( $options, $data );
	}

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

	public function get_field_class( $field ) {
		$field_to_class = [
			'checkbox' => '\Classifai\Admin\Fields\CheckboxControl',
			'select' => '\Classifai\Admin\Fields\SelectControl',
			'multi_select' => '\Classifai\Admin\Fields\MultiSelectControl',
		];

		return $field_to_class[ $field ];
	}

	public function get_setting( $context_key, $key, $context = null ) {
		if ( ! is_null( $context ) ) {
			$option_name = $context;
		} else {
			$option_name = $this->get_active_option_name();
		}

		$settings = get_option( $option_name, [] );
		$defaults = $this->get_settings_defaults();
		$merged   = $this->merge_arrays( $settings, $defaults, $context_key );

		return isset( $merged[ $context_key ][ $key ] )
			? $merged[ $context_key ][ $key ]
			: self::$active_description[ $context_key ][ $key ]['args']['default'];
	}

	public function get_settings_defaults() {
		$default_settings = [];

		foreach ( self::$active_description as $description_key => $settings ) {
			foreach ( $settings as $setting_key => $setting ) {
				$default_settings[ $description_key ][ $setting_key ] = $setting['args']['default'];
			}
		}

		return $default_settings;
	}

	function merge_arrays( $source, $defaults, $context_key ) {
		if ( ! isset( $source[ $context_key ] ) ) {
			$source[ $context_key ] = [];
		}

		foreach ( $defaults as $key => $value ) {
			if ( ! isset( $source[ $context_key ][$key] ) ) {
				$source[ $context_key ][ $key ] = $value;
			}
		}

		return $source;
	}

	public function get_active_option_name() {
		if ( 'feature' === $this->context ) {
			return self::FEATURE_KEY;
		} else if ( 'provider' === $this->context ) {
			return self::PROVIDER_KEY;
		}

		return self::FEATURE_KEY;
	}

	public static function get_feature_columns() {
		return [
			FeaturePostTitleGeneration::ID => [
				'domain' => __( 'Text', 'classifai' ),
				'label' => __( 'Post Title Generation', 'classifai' ),
			],
			FeatureExcerptGeneration::ID => [
				'domain' => __( 'Text', 'classifai' ),
				'label' => __( 'Post Excerpt Generation', 'classifai' )
			],
			FeatureContentResizing::ID => [
				'domain' => __( 'Text', 'classifai' ),
				'label' => __( 'Content Resizing', 'classifai' )
			],
		];
	}

	public function render_settings_page() {
		?>
		<h1>ClassifAI</h1>
		<form action="options.php" method="post">
			<div id="classifai-settings" class="classifai-settings classifai-settings--<?php echo esc_attr( $this->context ); ?>">
				<div class="classifai-settings__context-key">
					<?php foreach ( self::get_feature_columns() as $feature_id => $feature ) : ?>
						<a href="<?php echo esc_url( admin_url( 'tools.php?page=classifai&context=feature&context_key=' . $feature_id ) ); ?>" class="classifai-settings__context-key__item <?php echo $this->context_key === $feature_id ? 'classifai-settings__context-key__item--active' : ''; ?>">
							<span class="classifai-settings__context-key-domain"><?php echo esc_html( $feature['domain'] ); ?></span>
							<span class="classifai-settings__context-key-label"><?php echo esc_html( $feature['label'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>

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
						settings_fields( $this->get_active_option_name() );
						submit_button();
					?>
				</div>
			</div>
		</form>
		<?php
	}
}

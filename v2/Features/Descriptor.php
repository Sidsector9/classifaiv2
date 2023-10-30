<?php

namespace Classifai\Features;

use \Classifai\Features\FeaturePostTitleGeneration;
use \Classifai\Features\FeatureExcerptGeneration;
use \Classifai\Features\FeatureContentResizing;
use \Classifai\Features\AccessControl\Types\AccessControlByRole;
use \Classifai\Features\AccessControl\Types\AccessControlByPostType;
use \Classifai\Services\TextGeneration\OpenAI\ChatGPTPostTitleGeneration;

class Descriptor {
	public static function get_descriptor() {
		return [
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
						'default' => ChatGPTPostTitleGeneration::ID,
						'label' => __( 'Select a provider', 'classifai' ),
						'description' => __( 'Choose an AI service provider to generate post titles.', 'classifai' ),
						'options' => [
							ChatGPTPostTitleGeneration::ID => __( 'OpenAI ChatGPT', 'classifai' ),
						],
					],
				],
				AccessControlByRole::ID => [
					'field' => 'multi_select',
					'args' => [
						'default' => [ 'administrator' ],
						'label' => __( 'Control access by role', 'classifai' ),
						'description' => __( 'Limit the use of this feature by user roles.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_user_roles(),
					],
				],
				AccessControlByPostType::ID => [
					'field' => 'multi_select',
					'args' => [
						'default' => [],
						'label' => __( 'Control access by post type', 'classifai' ),
						'description' => __( 'Limit the use of this feature by post types.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_public_post_types(),
					],
				],
				'number_titles' => [
					'field' => 'number',
					'args' => [
						'default' => 1,
						'label' => __( 'Number of titles', 'classifai' ),
						'description' => __( 'Number of titles that will be generated in one request.', 'classifai' ),
						'options' => [
							'min' => 1,
							'step' => 1,
						],
						'provider_id' => ChatGPTPostTitleGeneration::ID,
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
				AccessControlByRole::ID => [
					'field' => 'multi_select',
					'args' => [
						'default' => [ 'administrator' ],
						'label' => __( 'Control access by role', 'classifai' ),
						'description' => __( 'Limit the use of this feature by user roles.', 'classifai' ),
						'options' => \Classifai\Admin\SettingsValues::get_user_roles(),
					],
				],
				AccessControlByPostType::ID => [
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
	}
}


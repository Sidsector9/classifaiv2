<?php

namespace Classifai\Services;

use \Classifai\Services\TextGeneration\OpenAI\ChatGPTPostTitleGeneration;

class Descriptor {
	public static function get_descriptor() {
		return [
			ChatGPTPostTitleGeneration::CONNECTOR_ID => [
				'api_key' => [
					'field' => 'text',
					'args' => [
						'default' => '',
						'label' => __( 'API Key', 'classifai' ),
						'description' => __( 'API Key for ChatGPT | DALL·E', 'classifai' ),
					],
				],
			]
		];
	}
}


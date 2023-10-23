<?php

namespace Classifai\Services\TextGeneration\OpenAI;

class ChatGPTPostTitleGeneration extends ChatGPTBase {
	public const CONNECTOR_ID = 'openaichatgpt';

	public function get_result( $post_id, $args ) {
		$api_key = $this->get_setting->get_provider_config( 'api_key' );
		$n       = $this->feature->get_feature_config( 'number_titles' );
		$request = new APIRequest( $api_key );

		$args     = wp_parse_args(
			array_filter( $args ),
			[
				'content' => '',
				'title'   => get_the_title( $post_id ),
			]
		);

		/**
		 * Filter the prompt we will send to ChatGPT.
		 *
		 * @since 2.2.0
		 * @hook classifai_chatgpt_title_prompt
		 *
		 * @param {string} $prompt Prompt we are sending to ChatGPT. Gets added before post content.
		 * @param {int} $post_id ID of post we are summarizing.
		 * @param {array} $args Arguments passed to endpoint.
		 *
		 * @return {string} Prompt.
		 */
		$prompt = apply_filters( 'classifai_chatgpt_title_prompt', 'Write an SEO-friendly title for the following content that will encourage readers to clickthrough, staying within a range of 40 to 60 characters.', $post_id, $args );

		/**
		 * Filter the request body before sending to ChatGPT.
		 *
		 * @since 2.2.0
		 * @hook classifai_chatgpt_title_request_body
		 *
		 * @param {array} $body Request body that will be sent to ChatGPT.
		 * @param {int} $post_id ID of post we are summarizing.
		 *
		 * @return {array} Request body.
		 */
		$body = apply_filters(
			'classifai_chatgpt_title_request_body',
			[
				'model'       => $this->chatgpt_model,
				'messages'    => [
					[
						'role'    => 'system',
						'content' => $prompt,
					],
					[
						'role'    => 'user',
						'content' => $this->get_content( $post_id, absint( $n ) * 15, false, $args['content'] ) . '',
					],
				],
				'temperature' => 0.9,
				'n'           => absint( $n ),
			],
			$post_id
		);

		// Make our API request.
		$response = $request->post(
			$this->chatgpt_url,
			[
				'body' => wp_json_encode( $body ),
			]
		);

		set_transient( 'classifai_openai_chatgpt_latest_response', $response, DAY_IN_SECONDS * 30 );

		// Extract out the text response, if it exists.
		if ( ! is_wp_error( $response ) && ! empty( $response['choices'] ) ) {
			foreach ( $response['choices'] as $choice ) {
				if ( isset( $choice['message'], $choice['message']['content'] ) ) {
					// ChatGPT often adds quotes to strings, so remove those as well as extra spaces.
					$response = sanitize_text_field( trim( $choice['message']['content'], ' "\'' ) );
				}
			}
		}

		return $response;
	}
}

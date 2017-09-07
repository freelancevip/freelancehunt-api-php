<?php

namespace Freelancevip\FreelancehuntApi;

class Api {
	const API_BASE = 'https://api.freelancehunt.com/';
	private $api_token;
	private $api_secret;

	/**
	 * FreelancehuntApi constructor.
	 *
	 * @param $id
	 * @param $secret
	 */
	function __construct( $id, $secret ) {
		$this->api_token  = $id;
		$this->api_secret = $secret;
	}

	/**
	 * Список переписок
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function threads( $options = array( 'filter' => 'new', 'page' => 1, 'per_page' => 10 ) ) {
		return $this->get( 'threads' );
	}

	/**
	 * Список сообщений в переписке
	 *
	 * @param $thread_id
	 *
	 * @return array
	 */
	function get_messages( $thread_id ) {
		$api = 'threads/' . $thread_id;

		return $this->get( $api );
	}

	/**
	 * Публикация нового сообщения в переписке
	 *
	 * @param $thread_id
	 * @param string $message
	 *
	 * @return array
	 */
	function post_message( $thread_id, $message = '' ) {
		$api = 'threads/' . $thread_id;

		return $this->post( $api, array(
			'message' => $message
		) );
	}

	/**
	 * Сообщения в ленте новостей
	 *
	 * @return array
	 */
	function feed() {
		return $this->get( 'my/feed' );
	}


	/**
	 * Информация о пользователе по логину
	 *
	 * @param string $login
	 * @param array $include
	 *
	 * @return array
	 */
	function profile( $login = 'me', $include = array() ) {
		$api = 'profiles/' . $login;
		if ( $include != '' ) {
			$api .= '?include=' . implode( ',', $include );
		}

		return $this->get( $api );
	}

	/**
	 * Отзывы о пользователе
	 *
	 * @param string $login
	 *
	 * @return array
	 */
	function reviews( $login = 'me' ) {
		$api = 'profiles/' . $login . '?include=reviews';

		return $this->get( $api );
	}

	/**
	 * Портфолио фрилансера
	 *
	 * @param string $login
	 *
	 * @return array
	 */
	function portfolio( $login = 'me' ) {
		$api = 'profiles/' . $login . '?include=portfolio';

		return $this->get( $api );
	}

	/**
	 * Список категорий
	 *
	 * @return array
	 */
	function skills() {
		$api = 'skills';

		return $this->get( $api );
	}

	/**
	 * Список открытых проектов
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	function projects( $options = array( 'page' => 1, 'per_page' => 10, 'skills' => '', 'tags' => '' ) ) {
		$api = 'projects?' . http_build_query( $options );
		$api = rtrim( $api, '?' );

		return $this->get( $api );
	}

	/**
	 * Детали проекта
	 *
	 * @param $project_id
	 *
	 * @return array
	 */
	function project( $project_id ) {
		$api = 'projects/' . $project_id;

		return $this->get( $api );
	}

	/**
	 * Список ставок на проект
	 *
	 * @param $project_id
	 *
	 * @return array
	 */
	function bids( $project_id ) {
		$api = "projects/{$project_id}/bids";

		return $this->get( $api );
	}

	/**
	 * Добавление новой ставки на проект
	 *
	 * @param $project_id
	 * @param array $options
	 *
	 * @return array
	 */
	function post_bid(
		$project_id, $options = array(
		'days_to_deliver' => '',
		'amount'          => '',
		'currency_code'   => '',
		'safe_type'       => '',
		'comment'         => ''
	)
	) {
		$api = 'projects/' . $project_id;

		return $this->post( $api, $options );
	}

	/**
	 * Вычисление подписи
	 *
	 * @param $api_secret
	 * @param $url
	 * @param $method
	 * @param string $post_params
	 *
	 * @return string
	 */
	private function sign( $api_secret, $url, $method, $post_params = '' ) {
		return base64_encode( hash_hmac( "sha256", $url . $method . $post_params, $api_secret, true ) );
	}

	/**
	 * GET request
	 *
	 * @param $api
	 * @param array $options
	 *
	 * @return array
	 */
	private function get( $api, $options = array() ) {
		$url = self::API_BASE . $api;
		if ( ! empty( $options ) ) {
			$url .= '?' . http_build_query( $options );
		}
		$signature = $this->sign( $this->api_secret, $url, 'GET', http_build_query( $options ) );
		$curl      = curl_init();
		$curl_opts = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_USERPWD        => $this->api_token . ":" . $signature,
			CURLOPT_URL            => $url
		);
		curl_setopt_array( $curl, $curl_opts );
		$return = curl_exec( $curl );

		return (array) json_decode( $return );
	}

	/**
	 * POST request
	 *
	 * @param $api
	 * @param $options
	 *
	 * @return array
	 */
	private function post( $api, $options ) {
		$url    = self::API_BASE . $api;
		$params = json_encode( $options );

		$signature = $this->sign( $this->api_secret, $url, 'POST', $params );
		$curl      = curl_init();
		$curl_opts = array(
			CURLOPT_HTTPHEADER => [ 'Content-Type: application/json', 'Content-Length: ' . strlen( $params ) ],
			CURLOPT_USERPWD    => $this->api_token . ":" . $signature,
			CURLOPT_URL        => $url,
			CURLOPT_POST       => true,
			CURLOPT_POSTFIELDS => $params,
		);
		curl_setopt_array( $curl, $curl_opts );
		$return = curl_exec( $curl );

		return (array) json_decode( $return );
	}
}

<?php
require_once '../vendor/autoload.php';
$settings = include_once '../private_options.php';
$api      = new freelancevip\FreelancehuntApi( $settings['app_token'], $settings['app_secret'] );

var_dump( $api->threads() );
var_dump( $api->get_messages( $settings['thread_id'] ) );
var_dump( $api->post_message( $settings['thread_id'], "Я бы хотел еще отведать этих мягких булок\nда выпить чаю." ) );
var_dump( $api->feed() );
var_dump( $api->profile( 'me' ) );
var_dump( $api->profile( 'some_user' ) );
var_dump( $api->reviews( 'me' ) );
var_dump( $api->portfolio( 'me' ) );
var_dump( $api->skills() );
var_dump( $api->projects() );
var_dump( $api->projects( array(
	'per_page' => 10,
	'page'     => 3,
	'skills'   => '17,75',
	'tags'     => 'php,joomla,cms'
) ) );
var_dump( $api->project( $settings['some_project_id'] ) );
var_dump( $api->bids( $settings['some_project_id'] ) );

//https://freelancehunt.com/my/api/#api-projects-addbid
//var_dump( $api->post_bid( $settings['some_project_id'], array(
//	'days_to_deliver' => '5',
//	'amount'          => '250',
//	'currency_code'   => 'UAH',
//	'safe_type'       => 'employer',
//	// работа с резервированием через Сейф. Допустимые значения: 'employer' -- комиссию платит заказчик, 'freelancer' -- комиссию платит исполнитель, 'split' -- комиссия делится пополам. Если вы хотите получить оплату напрямую, не передавайте параметр вообще иди передайте пустую строку. Обратите внимание, что для Сейфа сумма оплаты должны быть не менее минимально допустимой https://feedback.freelancehunt.com/topic/531579-rezervirovanie-summyi-zakazchikom/)
//	'comment'         => 'Есть опыт разработки, так что смогу легко выполнить вашу задачу. Детали изучил, с тз ознакомился. Сумму указал за час.',
//	// <100 chars
//) ) );

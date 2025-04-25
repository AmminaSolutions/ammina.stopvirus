<?php

$messages = [
	'MAIN' => [
		'TEXT' => 'Ammina StopVirus: Защита от внедрения вирусов на сайт',
		'TITLE' => 'Ammina StopVirus: Защита от внедрения вирусов на сайт',
	],
	'ATTEMPTS' => [
		'TEXT' => 'Журнал запросов',
		'TITLE' => 'Журнал попыток внедрения вирусов и журнал запросов',
	],
	'RULES' => [
		'TEXT' => 'Правила защиты',
		'TITLE' => 'Правила защиты от вирусов',
	],
	'SIGNATURES' => [
		'TEXT' => 'Сигнатуры вирусов',
		'TITLE' => 'Сигнатуры поиска вирусов',
	],
	'SETTINGS' => [
		'TEXT' => 'Настройки',
		'TITLE' => 'Настройки',
	],
	'INSTRUCTION' => [
		'TEXT' => 'Инструкция к модулю',
		'TITLE' => 'Инструкция к модулю',
	],
	'RECOMMENDATION' => [
		'TEXT' => 'Рекомендации по защите сайта',
		'TITLE' => 'Рекомендации по защите сайта',
	],
	'SUPPORT' => [
		'TEXT' => 'Техническая поддержка',
		'TITLE' => 'Техническая поддержка',
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_MENU');

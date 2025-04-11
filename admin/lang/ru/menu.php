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
	'SETTINGS' => [
		'TEXT' => 'Настройки',
		'TITLE' => 'Настройки',
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_MENU');

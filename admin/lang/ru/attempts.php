<?php

$messages = [
	'PAGE_TITLE' => 'Журнал попыток внедрения вирусов и журнал запросов',
	'FILTER' => [
		'ID' => 'ID (код)',
		'IS_DETECT_VIRUS' => 'Попытка внедрения',
		'IS_DETECT_VIRUS_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
		'DATE_CREATE' => 'Дата запроса',
		'REQUEST_TYPE' => 'Тип запроса',
		'HTTP_HOST' => 'Доменное имя',
		'REQUEST_URI' => 'URI запроса',
		'SCRIPT_FILENAME' => 'Путь к файлу',
		'FROM_IP' => 'IP адрес посетителя',
		'FROM_HOST' => 'Хост посетителя',
		'FROM_USER_AGENT' => 'User agent посетителя',
	],
	'FIELD' => [
		'ID' => 'ID',
		'IS_DETECT_VIRUS' => 'Попытка внедрения',
		'IS_DETECT_VIRUS_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
		'DATE_CREATE' => 'Дата запроса',
		'REQUEST_TYPE' => 'Тип запроса',
		'HTTP_HOST' => 'Доменное имя',
		'REQUEST_URI' => 'URI запроса',
		'SCRIPT_FILENAME' => 'Путь к файлу',
		'FROM_IP' => 'IP адрес посетителя',
		'FROM_HOST' => 'Хост посетителя',
		'FROM_USER_AGENT' => 'User agent посетителя',
	],
	'ACTION' => [
		'VIEW' => 'Просмотр',
		'DELETE' => 'Удалить',
		'DELETE_CONFIRM' => 'Будет удалена запись и вся связанная информация без возможности восстановления. Продолжить?',
		'ACTIVATE' => 'Активировать',
		'DEACTIVATE' => 'Деактивировать',
	],
	'RECORD' => [
		'VIEW' => 'Просмотреть запись',
		'NEW' => [
			'TEXT' => 'Добавить',
			'TITLE' => 'Добавить новую запись',
		],
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_ATTEMPTS');

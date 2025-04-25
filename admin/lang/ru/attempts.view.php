<?php

$messages = [
	'PAGE_TITLE' => "Детальная информация о запросе ##ID#",
	'TAB' => [
		'EDIT1' => [
			'TAB' => 'Основная информация',
			'TITLE' => 'Основная информация',
		],
		'EDIT2' => [
			'TAB' => 'Заголовки запроса',
			'TITLE' => 'Заголовки запроса',
		],
		'EDIT3' => [
			'TAB' => 'POST body',
			'TITLE' => 'Контент POST-запроса',
		],
		'EDIT4' => [
			'TAB' => 'Query string',
			'TITLE' => 'Контент строки запроса',
		],
	],
	'MENU' => [
		'RETURN_LIST' => [
			'TEXT' => 'Вернуться',
			'TITLE' => 'Вернуться в журнал попыток внедрения вирусов',
		],
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
		'DATA_HEADER' => 'Заголовки запроса',
		'DATA_QUERY' => 'Строка запроса',
		'DATA_BODY' => 'Тело POST-запроса',
		'MATCH_SIGNATURES' => 'Сработавшие сигнатуры',
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_ATTEMPTS_VIEW');

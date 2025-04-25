<?php

$messages = [
	'PAGE_TITLE' => [
		'EDIT' => 'Редактирование сигнатуры вирусов ##ID#',
		'NEW' => 'Новая сигнатура вирусов',
	],
	'TAB' => [
		'EDIT1' => [
			'TAB' => 'Настройки сигнатуры',
			'TITLE' => 'Настройки сигнатуры',
		],
	],
	'MENU' => [
		'RETURN_LIST' => [
			'TEXT' => 'Вернуться',
			'TITLE' => 'Вернуться в список сигнатур вирусов',
		],
	],
	'FIELD' => [
		'ID' => 'ID',
		'NAME' => 'Название',
		'IS_DEFAULT' => 'По умолчанию',
		'SIGNATURES' => 'Сигнатуры (каждая с новой строки)',
		'SIGNATURES_SYSTEM' => 'Системные сигнатуры вирусов (для информации)',
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_SIGNATURES_EDIT');

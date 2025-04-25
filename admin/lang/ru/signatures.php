<?php

$messages = [
	'PAGE_TITLE' => 'Сигнатуры вирусов',
	'FILTER' => [
		'ID' => 'ID (код)',
		'NAME' => 'Название',
		'IS_DEFAULT' => 'По умолчанию',
		'IS_DEFAULT_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
	],
	'FIELD' => [
		'ID' => 'ID',
		'NAME' => 'Название',
		'IS_DEFAULT' => 'По умолчанию',
		'IS_DEFAULT_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
		'SIGNATURES' => 'Сигнатуры',
	],
	'ACTION' => [
		'EDIT' => 'Редактировать',
		'DELETE' => 'Удалить',
		'DELETE_CONFIRM' => 'Будет удалена запись и вся связанная информация без возможности восстановления. Продолжить?',
	],
	'RECORD' => [
		'EDIT' => 'Просмотреть запись',
		'NEW' => [
			'TEXT' => 'Добавить',
			'TITLE' => 'Добавить новую запись',
		],
		'ERROR' => [
			'UPDATE' => 'Ошибка изменения записи #ID#: #ERROR_TEXT#',
		],
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_SIGNATURES');

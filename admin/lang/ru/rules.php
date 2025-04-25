<?php

$messages = [
	'PAGE_TITLE' => 'Правила защиты',
	'FILTER' => [
		'ID' => 'ID (код)',
		'NAME' => 'Название',
		'ACTIVE' => 'Активность',
		'ACTIVE_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
	],
	'FIELD' => [
		'ID' => 'ID',
		'NAME' => 'Название',
		'ACTIVE' => 'Активность',
		'ACTIVE_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
		'SORT' => 'Порядок',
		'ACTION' => 'Действие',
		'ACTION_VALUE' => [
			'D' => '[D] Блокировать опасные',
			'L' => '[L] Только журналировать опасные',
			'E' => '[E] Блокировать опасные, журналировать все',
			'A' => '[A] Только журналировать все',
			'N' => '[N] Ничего не делать',
		],
		'USE_DEFAULT_SIGNATURE' => 'Сигнатура по умолчанию',
		'USE_DEFAULT_SIGNATURE_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
		'SIGNATURE_ID' => 'Проверяемые сигнатуры',
		'STORE_POST_BODY' => 'Сохранять POST в журнал',
		'STORE_POST_BODY_VALUE' => [
			'Y' => 'Да',
			'N' => 'Нет',
		],
		'MEMORY_LIMIT' => 'Установить лимит памяти, Мб',
		'PAGE_RULES' => 'Для каких страниц',
		'IP_RULES' => 'Для каких IP',
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

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_RULES');

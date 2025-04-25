<?php

$messages = [
	'PAGE_TITLE' => "Техническая поддержка модуля \"Ammina StopVirus: Защита от внедрения вирусов на сайт\"",
	'TAB' => [
		'EDIT1' => [
			'TAB' => 'Техническая поддержка',
			'TITLE' => 'Техническая поддержка',
		],
	],
	'MENU' => [
		'RETURN_LIST' => [
			'TEXT' => 'Вернуться',
			'TITLE' => 'Вернуться в журнал попыток внедрения вирусов',
		],
	],
	'FIELD' => [
		'SUPPORT' => 'Инструкция',
		'SUPPORT_TEXT' => '<p>По вопросам технической поддержки модуля, очистке зараженных сайтов от вирусов, технической поддержке/доработке сайтов обращайтесь на электронную почту <a href="mailto:support@ammina.ru">support@ammina.ru</a>.</p>
',
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_SUPPORT');

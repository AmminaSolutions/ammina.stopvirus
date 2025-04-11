<?php

$messages = [
	'MODULE' => [
		'NAME' => 'Ammina StopVirus: Защита от внедрения вирусов на сайт',
		'DESC' => 'Ammina StopVirus: Защита от внедрения вирусов на сайт',
	],
	'PARTNER' => [
		'NAME' => 'Ammina - решения для 1С-Битрикс',
		'URI' => 'https://www.ammina.ru',
	],
	'INSTALL' => [
		'TITLE' => 'Установка модуля Ammina StopVirus: Защита от внедрения вирусов на сайт',
		'OK' => 'Установка модуля успешно завершена',
		'ERROR' => 'Ошибки при установке:',
		'BACK' => 'Вернуться в список',
		'GOTO_SETTINGS' => 'Перейти к настройками модуля',
	],
	'UNINSTALL' => [
		'TITLE' => 'Удаление модуля Ammina StopVirus: Защита от внедрения вирусов на сайт',
		'WARNING' => 'Внимание!<br>Модуль будет удален из системы',
		'SAVE' => 'Вы можете сохранить данные в таблицах базы данных:',
		'SAVE_TABLES' => 'Сохранить таблицы',
		'DELETE' => 'Удалить модуль',
		'OK' => 'Удаление модуля успешно завершено',
		'ERROR' => 'Ошибки при удалении:',
		'BACK' => 'Вернуться в список',
	],
];

\Ammina\StopVirus\LangFile::setMessages($MESS, $messages, 'AMMINA_STOPVIRUS_INSTALL');

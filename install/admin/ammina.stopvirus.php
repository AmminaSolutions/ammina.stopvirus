<?php
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/modules/ammina.stopvirus')) {
	include_once($_SERVER['DOCUMENT_ROOT'] . '/local/modules/ammina.stopvirus/constants.php');
} else {
	include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/ammina.stopvirus/constants.php');
}

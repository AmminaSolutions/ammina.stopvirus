<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die('Prologue is not connected');

if (!defined("AMMINA_STOPVIRUS_RUN")) {
	define("AMMINA_STOPVIRUS_RUN", true);
}
if (\Bitrix\Main\Loader::includeModule('ammina.stopvirus')) {
	(\Ammina\StopVirus\Detector::getInstance())->run();
}

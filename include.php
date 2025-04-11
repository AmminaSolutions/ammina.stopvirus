<?php
include_once(__DIR__ . '/constants.php');

use Bitrix\Main\Loader;

Loader::registerNamespace("Ammina\\StopVirus", AMMINA_STOPVIRUS_ROOT . "/lib");

(\Ammina\StopVirus\Settings::getInstance())->checkDeleteOldData();
(\Ammina\StopVirus\Settings::getInstance())->checkUpdates();
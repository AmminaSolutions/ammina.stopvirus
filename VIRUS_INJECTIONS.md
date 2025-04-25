# Дополнительные обнаруженные пути внедрения вирусов на сайт

| Решение        | Версия | Файл                                    | Пример кода и исправление        | Примечание                                             |
|----------------|--------|-----------------------------------------|----------------------------------|--------------------------------------------------------|
| Aspro Allcorp3 | 1.1.9  | /include/mainpage/comp_catalog_ajax.php | [к примеру](#aspro-allcorp3---1) | Возможность исполнения без подключения пролога Битрикс |


## Aspro Allcorp3 - 1

```php
<?$bAjaxMode = (isset($_POST["AJAX_POST"]) && $_POST["AJAX_POST"] == "Y");

if ($bAjaxMode) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $APPLICATION;
	if (\Bitrix\Main\Loader::includeModule("aspro.allcorp3")) 	{
		$arRegion = CAllcorp3Regionality::getCurrentRegion();
	}
	$template = $arParams['TYPE_TEMPLATE'];
	if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
		throw new SystemException('Error include solution constants');
	}
}?>
<?
//ВСТАВКА КОДА ДЛЯ ИСПРАВЛЕНИЯ - BEGIN
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
//ВСТАВКА КОДА ДЛЯ ИСПРАВЛЕНИЯ - END
?>
<?if ((isset($arParams["IBLOCK_ID"]) && $arParams["IBLOCK_ID"]) || $bAjaxMode):?>
```

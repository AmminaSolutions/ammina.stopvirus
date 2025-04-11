<?php

/**
 * @var CMain $APPLICATION
 * @var CDatabase $DB
 * @var CUser $USER
 * @var string $by
 * @var string $order
 */

use Ammina\StopVirus\LangFile;
use Ammina\StopVirus\Settings;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.stopvirus');
require_once(AMMINA_STOPVIRUS_ROOT . "prolog.php");

LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_ATTEMPTS');

$modulePermissions = CMain::GetGroupRight('ammina.stopvirus');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(LangFile::message("ACCESS_DENIED", ''));
}

//////////////////
$sTableID = "ammina_stopvirus_attempts_list";
$pageLinkList = '/bitrix/admin/ammina.stopvirus.attempts.php';
$pageLinkEdit = '/bitrix/admin/ammina.stopvirus.attempts.view.php';

$oSort = new CAdminSorting($sTableID, "DATE_CREATE", "desc");
$arOrder = (strtoupper($by) === "ID" ? [$by => $order] : [$by => $order, "ID" => "DESC"]);
$lAdmin = new CAdminUiList($sTableID, $oSort);

$filterFields = [
	[
		"id" => "ID",
		"name" => LangFile::message('FILTER_ID'),
		"type" => "number",
		"filterable" => "",
		'default' => true,
	],
	[
		"id" => "IS_DETECT_VIRUS",
		"name" => LangFile::message('FILTER_IS_DETECT_VIRUS'),
		"filterable" => "",
		"type" => "list",
		"items" => [
			"Y" => LangFile::message('FILTER_IS_DETECT_VIRUS_VALUE_Y'),
			"N" => LangFile::message('FILTER_IS_DETECT_VIRUS_VALUE_N'),
		],
		"default" => true,
	],
	[
		"id" => "DATE_CREATE",
		"name" => LangFile::message("FILTER_DATE_CREATE"),
		"filterable" => "",
		"type" => "date",
		"default" => true,
	],
	[
		"id" => "REQUEST_TYPE",
		"name" => LangFile::message("FILTER_REQUEST_TYPE"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => false,
	],
	[
		"id" => "HTTP_HOST",
		"name" => LangFile::message("FILTER_HTTP_HOST"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => false,
	],
	[
		"id" => "REQUEST_URI",
		"name" => LangFile::message("FILTER_REQUEST_URI"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => false,
	],
	[
		"id" => "SCRIPT_FILENAME",
		"name" => LangFile::message("FILTER_SCRIPT_FILENAME"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => false,
	],
	[
		"id" => "FROM_IP",
		"name" => LangFile::message("FILTER_FROM_IP"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => false,
	],
	[
		"id" => "FROM_HOST",
		"name" => LangFile::message("FILTER_FROM_HOST"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => false,
	],
];
$arFilter = [];
$lAdmin->AddFilter($filterFields, $arFilter);
global $FIELDS;

if (($arID = $lAdmin->GroupAction()) && $modulePermissions >= "W") {
	if ($lAdmin->IsGroupActionToAll() && $_REQUEST['action'] === 'delete') {
		$DB->Query('TRUNCATE TABLE ' . \Ammina\StopVirus\Db\AttemptsTable::getTableName());
	} else {
		if ($lAdmin->IsGroupActionToAll()) {
			$arID = [];
			$dbResultList = \Ammina\StopVirus\Db\AttemptsTable::getList(
				[
					"order" => $arOrder,
					"filter" => $arFilter,
					"select" => ["ID"],
				]
			);
			while ($arResult = $dbResultList->Fetch()) {
				$arID[] = $arResult['ID'];
			}
		}

		foreach ($arID as $ID) {
			if (strlen($ID) <= 0) {
				continue;
			}

			switch ($_REQUEST['action']) {
				case "delete":
					@set_time_limit(0);
					$bComplete = true;
					$DB->StartTransaction();
					\Ammina\StopVirus\Db\AttemptsTable::delete($ID);
					$DB->Commit();
					break;
			}
		}
	}
}

$arHeader = [
	[
		"id" => "ID",
		"content" => LangFile::message("FIELD_ID"),
		"sort" => "ID",
		"default" => true,
	],
	[
		"id" => "IS_DETECT_VIRUS",
		"content" => LangFile::message("FIELD_IS_DETECT_VIRUS"),
		"sort" => "IS_DETECT_VIRUS",
		"default" => true,
	],
	[
		"id" => "DATE_CREATE",
		"content" => LangFile::message("FIELD_DATE_CREATE"),
		"sort" => "DATE_CREATE",
		"default" => true,
	],
	[
		"id" => "REQUEST_TYPE",
		"content" => LangFile::message("FIELD_REQUEST_TYPE"),
		"sort" => "REQUEST_TYPE",
		"default" => true,
	],
	[
		"id" => "HTTP_HOST",
		"content" => LangFile::message("FIELD_HTTP_HOST"),
		"default" => true,
	],
	[
		"id" => "REQUEST_URI",
		"content" => LangFile::message("FIELD_REQUEST_URI"),
		"default" => true,
	],
	[
		"id" => "SCRIPT_FILENAME",
		"content" => LangFile::message("FIELD_SCRIPT_FILENAME"),
		"default" => true,
	],
	[
		"id" => "FROM_IP",
		"content" => LangFile::message("FIELD_FROM_IP"),
		"default" => true,
	],
	[
		"id" => "FROM_HOST",
		"content" => LangFile::message("FIELD_FROM_HOST"),
		"default" => true,
	],
	[
		"id" => "FROM_USER_AGENT",
		"content" => LangFile::message("FIELD_FROM_USER_AGENT"),
		"default" => true,
	],
];

$lAdmin->AddHeaders($arHeader);

$arSelected = $lAdmin->GetVisibleHeaderColumns();
if (empty($arSelected)) {
	$arSelected = ['ID'];
}

$rsItems = \Ammina\StopVirus\Db\AttemptsTable::getList(
	[
		"order" => $arOrder,
		"filter" => $arFilter,
		"select" => $arSelected,
	]
);
$rsItems = new CAdminUiResult($rsItems, $sTableID);
$rsItems->NavStart();

$listIdDetected = [];
$lAdmin->SetNavigationParams($rsItems);
while ($arData = $rsItems->NavNext()) {
	$row =& $lAdmin->AddRow($arData['ID'], $arData, $pageLinkEdit . '?ID=' . $arData['ID'] . '&lang=' . LANGUAGE_ID, LangFile::message("RECORD_VIEW"));

	if ($arData['IS_DETECT_VIRUS'] === 'Y') {
		$row->AddViewField('ID', $arData['ID'] . '<style>.main-grid-row-body[data-id="' . $arData['ID'] . '"] > td {background-color: #ffe4e4;}</style>');
	}
	$row->AddViewField('IS_DETECT_VIRUS', $arData['IS_DETECT_VIRUS'] === 'Y' ? LangFile::message('FIELD_IS_DETECT_VIRUS_VALUE_Y') : LangFile::message('FIELD_IS_DETECT_VIRUS_VALUE_N'));

	$arActions = [];
	if ($modulePermissions >= "R") {
		$arActions[] = [
			"ICON" => "view",
			"TEXT" => LangFile::message("ACTION_VIEW"),
			"DEFAULT" => true,
			"ACTION" => $lAdmin->ActionRedirect($pageLinkEdit . "?ID=" . $arData['ID'] . "&lang=" . LANGUAGE_ID),
		];
		$arActions[] = [
			"SEPARATOR" => true,
		];
		$arActions[] = [
			"ICON" => "delete",
			"TEXT" => LangFile::message("ACTION_DELETE"),
			"ACTION" => "if(confirm('" . LangFile::message('ACTION_DELETE_CONFIRM') . "')) " . $lAdmin->ActionDoGroup($arData['ID'], "delete"),
		];
	}
	if (count($arActions) > 0) {
		$row->AddActions($arActions);
	}

}

$lAdmin->AddFooter(
	[
		["title" => LangFile::message("MAIN_ADMIN_LIST_SELECTED", ''), "value" => $rsItems->SelectedRowsCount()],
		["counter" => true, "title" => LangFile::message("MAIN_ADMIN_LIST_CHECKED", ''), "value" => "0"],
	]
);

//if ($modulePermissions >= "R") {
$aContext = [];

$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->AddGroupActionTable(
	[
		//"edit" => true,
		"delete" => true,
		'for_all' => true,
	]
);

//}
$lAdmin->CheckListMode();
//////////////////

$APPLICATION->SetTitle(LangFile::message("PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$lAdmin->DisplayFilter($filterFields);

$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");

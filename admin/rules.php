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

LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_RULES');

$modulePermissions = CMain::GetGroupRight('ammina.stopvirus');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(LangFile::message("ACCESS_DENIED", ''));
}

//////////////////
$sTableID = "ammina_stopvirus_rules_list";
$pageLinkList = '/bitrix/admin/ammina.stopvirus.rules.php';
$pageLinkEdit = '/bitrix/admin/ammina.stopvirus.rules.edit.php';

$oSort = new CAdminSorting($sTableID, "SORT", "asc");
$arOrder = (strtoupper($by) === "ID" ? [$by => $order] : [$by => $order, "ID" => "ASC"]);
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
		"id" => "NAME",
		"name" => LangFile::message("FILTER_NAME"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => true,
	],
	[
		"id" => "ACTIVE",
		"name" => LangFile::message('FILTER_ACTIVE'),
		"filterable" => "",
		"type" => "list",
		"items" => [
			"Y" => LangFile::message('FILTER_ACTIVE_VALUE_Y'),
			"N" => LangFile::message('FILTER_ACTIVE_VALUE_N'),
		],
		"default" => true,
	],
];
$arFilter = [];
$lAdmin->AddFilter($filterFields, $arFilter);
global $FIELDS;
if ($lAdmin->EditAction()) {
	foreach ($FIELDS as $ID => $postFields) {
		$ID = IntVal($ID);
		$DB->StartTransaction();
		if (!$lAdmin->IsUpdated($ID)) {
			continue;
		}

		$allowedFields = [
			"NAME",
			"SORT",
			"ACTIVE",
			"ACTION",
			"STORE_POST_BODY",
		];

		$arFields = [];
		foreach ($allowedFields as $fieldId) {
			if (array_key_exists($fieldId, $postFields)) {
				$arFields[$fieldId] = $postFields[$fieldId];
			}
		}

		$oUpdate = \Ammina\StopVirus\Db\RulesTable::update($ID, $arFields);
		if (!$oUpdate->isSuccess()) {
			$lAdmin->AddUpdateError(LangFile::message("RECORD_ERROR_UPDATE", null, ["ID" => $ID, "ERROR_TEXT" => implode(", ", $oUpdate->getErrorMessages())]), $ID);
			$DB->Rollback();
		} else {
			$DB->Commit();
		}
	}
}
if (($arID = $lAdmin->GroupAction()) && $modulePermissions >= "W") {
	if ($lAdmin->IsGroupActionToAll()) {
		$arID = [];
		$dbResultList = \Ammina\StopVirus\Db\RulesTable::getList(
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
				\Ammina\StopVirus\Db\RulesTable::delete($ID);
				$DB->Commit();
				break;
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
		"id" => "NAME",
		"content" => LangFile::message("FIELD_NAME"),
		"sort" => "NAME",
		"default" => true,
	],
	[
		"id" => "ACTIVE",
		"content" => LangFile::message("FIELD_ACTIVE"),
		"sort" => "ACTIVE",
		"default" => true,
	],
	[
		"id" => "SORT",
		"content" => LangFile::message("FIELD_SORT"),
		"sort" => "SORT",
		"default" => true,
	],
	[
		"id" => "ACTION",
		"content" => LangFile::message("FIELD_ACTION"),
		"sort" => "ACTION",
		"default" => true,
	],
	[
		"id" => "USE_DEFAULT_SIGNATURE",
		"content" => LangFile::message("FIELD_USE_DEFAULT_SIGNATURE"),
		"sort" => "USE_DEFAULT_SIGNATURE",
		"default" => true,
	],
	[
		"id" => "SIGNATURE_ID",
		"content" => LangFile::message("FIELD_SIGNATURE_ID"),
		"sort" => "SIGNATURE_ID",
		"default" => true,
	],
	[
		"id" => "STORE_POST_BODY",
		"content" => LangFile::message("FIELD_STORE_POST_BODY"),
		"sort" => "STORE_POST_BODY",
		"default" => true,
	],
	[
		"id" => "MEMORY_LIMIT",
		"content" => LangFile::message("FIELD_MEMORY_LIMIT"),
		"sort" => "MEMORY_LIMIT",
		"default" => false,
	],
	[
		"id" => "PAGE_RULES",
		"content" => LangFile::message("FIELD_PAGE_RULES"),
		"default" => true,
	],
	[
		"id" => "IP_RULES",
		"content" => LangFile::message("FIELD_IP_RULES"),
		"default" => true,
	],
];

$lAdmin->AddHeaders($arHeader);

$arSelected = $lAdmin->GetVisibleHeaderColumns();
if (empty($arSelected)) {
	$arSelected = ['ID'];
}

$rsItems = \Ammina\StopVirus\Db\RulesTable::getList(
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

	$row->AddInputField('NAME', ['size' => 30]);
	$row->AddInputField('SORT', ['size' => 10]);
	$row->AddCheckField('ACTIVE');
	$row->AddSelectField('ACTION', [
		'D' => LangFile::message('FIELD_ACTION_VALUE_D'),
		'L' => LangFile::message('FIELD_ACTION_VALUE_L'),
		'E' => LangFile::message('FIELD_ACTION_VALUE_E'),
		'A' => LangFile::message('FIELD_ACTION_VALUE_A'),
		'N' => LangFile::message('FIELD_ACTION_VALUE_N'),
	]);
	$row->AddViewField('USE_DEFAULT_SIGNATURE', LangFile::message('FIELD_USE_DEFAULT_SIGNATURE_VALUE_' . ($arData['USE_DEFAULT_SIGNATURE'])));
	if ($arData['SIGNATURE_ID'] > 0) {
		$signatire = \Ammina\StopVirus\Db\SignaturesTable::getById($arData['SIGNATURE_ID'])->fetchObject();
		if ($signatire) {
			$row->AddViewField('SIGNATURE_ID', '[<a href="/bitrix/admin/ammina.stopvirus.signatures.edit.php?ID=' . $signatire->getId() . '&lang=' . LANGUAGE_ID . '">' . $signatire->getId() . '</a>] ' . $signatire->getName());
		}
	}
	$row->AddCheckField('STORE_POST_BODY');
	if (in_array('PAGE_RULES', $arSelected)) {
		$row->AddViewField('PAGE_RULES', nl2br(htmlspecialchars(implode("\n", $arData['PAGE_RULES'] ?? []))));
	}
	if (in_array('IP_RULES', $arSelected)) {
		$row->AddViewField('IP_RULES', nl2br(htmlspecialchars(implode("\n", $arData['IP_RULES'] ?? []))));
	}
	$arActions = [];
	if ($modulePermissions >= "R") {
		$arActions[] = [
			"ICON" => "edit",
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
$aContext = [
	[
		"ICON" => "btn_new",
		"TEXT" => LangFile::message("RECORD_NEW_TEXT"),
		"LINK" => $pageLinkEdit . '?lang=' . LANGUAGE_ID,
		"TITLE" => LangFile::message("RECORD_NEW_TITLE"),
	],
];

$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->AddGroupActionTable(
	[
		"edit" => true,
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

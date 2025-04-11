<?php

/**
 * @var CMain $APPLICATION
 * @var CUser $USER
 */

use Ammina\StopVirus\LangFile;
use Ammina\StopVirus\Settings;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.stopvirus');
//require_once(AMMINA_STOPVIRUS_ROOT . "prolog.php");

LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_SETTINGS');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(LangFile::message("ACCESS_DENIED", ''));
}

////////////////////////////
$sTabID = 'ammina_stopvirus_settings';
$pageLinkList = '/bitrix/admin/ammina.stopvirus.attempts.php';
$pageLinkEdit = '/bitrix/admin/ammina.stopvirus.settings.php';

$context = (\Bitrix\Main\Application::getInstance())->getContext();
$request = $context->getRequest();

//$ID = $request->get('ID') ? (int)$request->get('ID') : 0;
$save = trim($request->get('save'));
$apply = trim($request->get('apply'));
$requestMethod = $context->getServer()->getRequestMethod();
$message = null;


$isSavingOperation = ($requestMethod === "POST" && (!empty($save) || !empty($apply)) && check_bitrix_sessid());
$needFieldsRestore = $requestMethod === "POST" && !$isSavingOperation;

$aTabs = [
	[
		'DIV' => 'edit1',
		'TAB' => LangFile::message('TAB_EDIT1_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT1_TITLE'),
	],
	[
		'DIV' => 'edit2',
		'TAB' => LangFile::message('TAB_EDIT2_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT2_TITLE'),
	],
	[
		'DIV' => 'edit3',
		'TAB' => LangFile::message('TAB_EDIT3_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT3_TITLE'),
	],
];
$tabControl = new CAdminForm($sTabID, $aTabs);

if ($isSavingOperation) {
	$strError = '';

	if ($request->get('INCLUDE_RUN_FILE') === 'Y') {
		(Settings::getInstance())->includeRunFile();
	}

	(Settings::getInstance())
		->setOptionActive($request->get('ACTIVE') === 'Y')
		->setOptionStoreHost($request->get('STORE_HOST') === 'Y')
		->setOptionStoreAll($request->get('STORE_ALL') === 'Y')
		->setOptionTtlDetected((int)$request->get('TTL_DETECTED'))
		->setOptionTtlNoDetected((int)$request->get('TTL_NO_DETECTED'))
		->setOptionMessage($request->get('MESSAGE'))
		->setOptionAllowSystemRequests($request->get('ALLOW_SYSTEM_REQUESTS') === 'Y')
		->setOptionDisableBlockRequests($request->get('DISABLE_BLOCK_REQUESTS') === 'Y');

	if (!empty($save)) {
		LocalRedirect($pageLinkList . "?lang=" . LANGUAGE_ID);
	} else {
		LocalRedirect($pageLinkEdit . "?lang=" . LANGUAGE_ID . '&' . $tabControl->ActiveTabParam());
	}
}

$APPLICATION->SetTitle(LangFile::message("PAGE_TITLE"));

$aMenu = [
	[
		'TEXT' => LangFile::message('MENU_RETURN_LIST_TEXT'),
		'TITLE' => LangFile::message('MENU_RETURN_LIST_TITLE'),
		'LINK' => $pageLinkList . '?lang=' . LANGUAGE_ID,
		'ICON' => 'btn_list',
	],
];
$contextMenu = new CAdminContextMenu($aMenu);
////////////////////////////

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

////////////////////////////
$contextMenu->Show();

if (!empty($strError)) {
	CAdminMessage::ShowMessage($strError);
}

$tabControl->BeginEpilogContent();
echo bitrix_sessid_post();

$tabControl->EndEpilogContent();

$tabControl->Begin([
	"FORM_ACTION" => $APPLICATION->GetCurPage() . "?lang=" . LANGUAGE_ID,
]);

$tabControl->BeginNextFormTab();

$tabControl->BeginCustomField('DETECT_FILE_INCLUDE', LangFile::message('FIELD_DETECT_FILE_INCLUDE'), false);
if ((Settings::getInstance())->detectRunFileInclude()) {
	?>
	<tr>
		<td colspan="2">
			<div style="display:flex; justify-content: center;">
				<?
				\CAdminMessage::ShowMessage([
					'MESSAGE' => LangFile::message('FIELD_DETECT_FILE_OK'),
					'TYPE' => 'OK',
					'HTML' => true,
				]);
				?>
			</div>
		</td>
	</tr>
	<?
} else {
	?>
	<tr>
		<td colspan="2">
			<div style="display:flex; justify-content: center;">
				<?
				\CAdminMessage::ShowMessage([
					'MESSAGE' => LangFile::message('FIELD_DETECT_FILE_ERROR'),
					'TYPE' => 'ERROR',
				]);
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td width="40%"><?= LangFile::message('FIELD_INCLUDE_RUN_FILE') ?>:</td>
		<td>
			<input type="hidden" name="INCLUDE_RUN_FILE" value="N"/>
			<input type="checkbox" name="INCLUDE_RUN_FILE" value="Y"/>
		</td>
	</tr>
	<?
}
$tabControl->EndCustomField('DETECT_FILE_INCLUDE');

$tabControl->AddCheckBoxField('ACTIVE', LangFile::message('FIELD_ACTIVE') . ':', false, 'Y', (Settings::getInstance())->optionActive());
$tabControl->AddCheckBoxField('STORE_HOST', LangFile::message('FIELD_STORE_HOST') . ':', false, 'Y', (Settings::getInstance())->optionStoreHost());
$tabControl->AddCheckBoxField('STORE_ALL', LangFile::message('FIELD_STORE_ALL') . ':', false, 'Y', (Settings::getInstance())->optionStoreAll());
$tabControl->AddEditField('TTL_DETECTED', LangFile::message('FIELD_TTL_DETECTED') . ':', false, ["size" => 10, "maxlength" => 10], (Settings::getInstance())->optionTtlDetected());
$tabControl->AddEditField('TTL_NO_DETECTED', LangFile::message('FIELD_TTL_NO_DETECTED') . ':', false, ["size" => 10, "maxlength" => 10], (Settings::getInstance())->optionTtlNoDetected());
$tabControl->AddEditField('MESSAGE', LangFile::message('FIELD_MESSAGE') . ':', false, ["size" => 50, "maxlength" => 255], (Settings::getInstance())->optionMessage());
$tabControl->AddCheckBoxField('ALLOW_SYSTEM_REQUESTS', LangFile::message('FIELD_ALLOW_SYSTEM_REQUESTS') . ':', false, 'Y', (Settings::getInstance())->optionAllowSystemRequests());
$tabControl->AddCheckBoxField('DISABLE_BLOCK_REQUESTS', LangFile::message('FIELD_DISABLE_BLOCK_REQUESTS') . ':', false, 'Y', (Settings::getInstance())->optionDisableBlockRequests());

$tabControl->BeginNextFormTab();
$tabControl->BeginCustomField('INSTRUCTION', LangFile::message('FIELD_INSTRUCTION'), false);
?>
	<tr>
		<td colspan="2"><?= LangFile::message('FIELD_INSTRUCTION_TEXT') ?></td>
	</tr>
<?
$tabControl->EndCustomField('INSTRUCTION');

$tabControl->BeginNextFormTab();
$tabControl->BeginCustomField('RECOMMENDED', LangFile::message('FIELD_RECOMMENDED'), false);
?>
	<tr>
		<td colspan="2"><?= LangFile::message('FIELD_RECOMMENDED_TEXT') ?></td>
	</tr>
<?

$tabControl->EndCustomField('RECOMMENDED');



$tabControl->Buttons([
	"btnSave" => true,
	"btnApply" => true,
	"btnCancel" => false,
]);
$tabControl->Show();

//$tabControl->ShowWarnings($tabControl->GetName(), $message);

///////////////////////////

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");

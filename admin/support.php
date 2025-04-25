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

LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_SUPPORT');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(LangFile::message("ACCESS_DENIED", ''));
}

////////////////////////////
$sTabID = 'ammina_stopvirus_support';
$pageLinkList = '/bitrix/admin/ammina.stopvirus.attempts.php';
$pageLinkEdit = '/bitrix/admin/ammina.stopvirus.support.php';

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
];
$tabControl = new CAdminForm($sTabID, $aTabs);

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
$tabControl->BeginCustomField('SUPPORT', LangFile::message('FIELD_SUPPORT'), false);
?>
	<tr>
		<td colspan="2"><?= LangFile::message('FIELD_SUPPORT_TEXT') ?></td>
	</tr>
<?
$tabControl->EndCustomField('SUPPORT');

$tabControl->Buttons([
	"btnSave" => false,
	"btnApply" => false,
	"btnCancel" => false,
]);
$tabControl->Show();

//$tabControl->ShowWarnings($tabControl->GetName(), $message);

///////////////////////////

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");

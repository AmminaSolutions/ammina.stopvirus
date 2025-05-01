<?php

/**
 * @var CMain $APPLICATION
 * @var CUser $USER
 */

use Ammina\StopVirus\Db\SignaturesTable;
use Ammina\StopVirus\Detector;
use Ammina\StopVirus\LangFile;
use Ammina\StopVirus\Orm\Signatures;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.stopvirus');
require_once(AMMINA_STOPVIRUS_ROOT . "prolog.php");

LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_SIGNATURES_EDIT');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(LangFile::message("ACCESS_DENIED", ''));
}

////////////////////////////
$sTabID = 'ammina_stopvirus_signatures_edit';
$pageLinkList = '/bitrix/admin/ammina.stopvirus.signatures.php';
$pageLinkEdit = '/bitrix/admin/ammina.stopvirus.signatures.edit.php';

$context = (\Bitrix\Main\Application::getInstance())->getContext();
$request = $context->getRequest();

$ID = $request->get('ID') ? (int)$request->get('ID') : 0;
$save = trim($request->get('save'));
$apply = trim($request->get('apply'));
$requestMethod = $context->getServer()->getRequestMethod();
$isSavingOperation = ($requestMethod === "POST" && (!empty($save) || !empty($apply)) && check_bitrix_sessid());
$needFieldsRestore = $requestMethod === "POST" && !$isSavingOperation;
$message = null;
if ($ID > 0) {
	$currentItem = SignaturesTable::getById($ID)->fetchObject();
} else {
	$currentItem = new Signatures();
}

if (!$currentItem) {
	LocalRedirect($pageLinkList . '?lang=' . LANGUAGE_ID);
}
$aTabs = [
	[
		'DIV' => 'edit1',
		'TAB' => LangFile::message('TAB_EDIT1_TAB'),
		'TITLE' => LangFile::message('TAB_EDIT1_TITLE'),
	],
];
$tabControl = new CAdminForm($sTabID, $aTabs);

if ($isSavingOperation) {
	$strError = '';
	$signatures = [];
	$ar = explode("\n", $request->get('SIGNATURES'));
	foreach ($ar as $val) {
		$val = ltrim($val);
		if (!empty($val)) {
			$signatures[] = $val;
		}
	}
	$currentItem
		->setName(trim($request->get('NAME')))
		->setIsDefault($request->get('IS_DEFAULT') === 'Y' ? 'Y' : 'N')
		->setSignatures(array_unique($signatures));

	$result = $currentItem->save();

	if (!$result->isSuccess()) {
		$message = $result->getErrors();
	} else {
		$ID = $currentItem->getId();
	}
	if ($result->isSuccess()) {
		if (!empty($save)) {
			LocalRedirect($pageLinkList . "?lang=" . LANGUAGE_ID);
		} else {
			LocalRedirect($pageLinkEdit . "?lang=" . LANGUAGE_ID . "&ID=" . $ID . '&' . $tabControl->ActiveTabParam());
		}
	}
}

$APPLICATION->SetTitle($currentItem->getId() > 0 ? LangFile::message("PAGE_TITLE_EDIT", null, ['ID' => $currentItem->getId()]) : LangFile::message("PAGE_TITLE_NEW"));

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
?>
	<input type="hidden" name="ID" value="<?= $currentItem->getId() ?>"/>
<?
$tabControl->EndEpilogContent();

$tabControl->Begin([
	"FORM_ACTION" => $APPLICATION->GetCurPage() . "?lang=" . LANGUAGE_ID,
]);

$tabControl->BeginNextFormTab();
$tabControl->AddViewField('ID', LangFile::message('FIELD_ID') . ':', $currentItem->getId());
$tabControl->AddEditField('NAME', LangFile::message('FIELD_NAME'), false, ["size" => 50, "maxlength" => 255], $currentItem->getName());
$tabControl->AddCheckBoxField('IS_DEFAULT', LangFile::message('FIELD_IS_DEFAULT') . ':', false, 'Y', $currentItem->getIsDefault());
$tabControl->AddTextField('SIGNATURES', LangFile::message('FIELD_SIGNATURES') . ':', implode("\n", $currentItem->getSignatures() ?? []), ['cols' => 50, 'rows' => 15]);
$tabControl->AddViewField('SIGNATURES_SYSTEM', LangFile::message('FIELD_SIGNATURES_SYSTEM') . ':', nl2br(htmlspecialchars(implode("\n", (Detector::getInstance())->getSystemSignatures()))));

$tabControl->Buttons([
	"btnSave" => true,
	"btnApply" => true,
	"btnCancel" => true,
]);
$tabControl->Show();

//$tabControl->ShowWarnings($tabControl->GetName(), $message);

///////////////////////////

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");

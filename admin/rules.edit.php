<?php

/**
 * @var CMain $APPLICATION
 * @var CUser $USER
 */

use Ammina\StopVirus\Db\RulesTable;
use Ammina\StopVirus\Detector;
use Ammina\StopVirus\LangFile;
use Ammina\StopVirus\Orm\Rules;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Loader::includeModule('ammina.stopvirus');
require_once(AMMINA_STOPVIRUS_ROOT . "prolog.php");

LangFile::loadMessages(__FILE__, 'AMMINA_STOPVIRUS_RULES_EDIT');

if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm(LangFile::message("ACCESS_DENIED", ''));
}

////////////////////////////
$sTabID = 'ammina_stopvirus_rules_edit';
$pageLinkList = '/bitrix/admin/ammina.stopvirus.rules.php';
$pageLinkEdit = '/bitrix/admin/ammina.stopvirus.rules.edit.php';

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
	$currentItem = RulesTable::getById($ID)->fetchObject();
} else {
	$currentItem = new Rules();
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
	$pageRules = [];
	$ar = explode("\n", $request->get('PAGE_RULES'));
	foreach ($ar as $val) {
		$val = trim($val);
		if (!empty($val)) {
			$url = parse_url($val);
			$pageRules[] = $url['path'];
		}
	}
	$ipRules = [];
	$ar = explode("\n", $request->get('IP_RULES'));
	foreach ($ar as $val) {
		$val = trim($val);
		if (!empty($val)) {
			$ipRules[] = $val;
		}
	}
	$currentItem
		->setName(trim($request->get('NAME')))
		->setActive($request->get('ACTIVE') === 'Y' ? 'Y' : 'N')
		->setSort(intval($request->get('SORT')))
		->setAction(trim($request->get('ACTION')))
		->setUseDefaultSignature($request->get('USE_DEFAULT_SIGNATURE') === 'Y' ? 'Y' : 'N')
		->setSignatureId($request->get('USE_DEFAULT_SIGNATURE') === 'Y' ? null : intval($request->get('SIGNATURE_ID')))
		->setStorePostBody($request->get('STORE_POST_BODY') === 'Y' ? 'Y' : 'N')
		->setMemoryLimit(intval($request->get('MEMORY_LIMIT')) > 0 ? intval($request->get('MEMORY_LIMIT')) : null)
		->setPageRules(array_unique($pageRules))
		->setIpRules(array_unique($ipRules));

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
	<script>
        function checkSignatureDefault() {
            const signature = document.querySelector('select[name="SIGNATURE_ID"]');
            const signatureDefault = document.querySelector('input[name="USE_DEFAULT_SIGNATURE"]');
            if (signatureDefault.checked) {
                signature.setAttribute('disabled', 'disabled');
            } else {
                signature.removeAttribute('disabled');
            }
        }
	</script>
<?
$tabControl->EndEpilogContent();

$tabControl->Begin([
	"FORM_ACTION" => $APPLICATION->GetCurPage() . "?lang=" . LANGUAGE_ID,
]);

$tabControl->BeginNextFormTab();
$tabControl->AddViewField('ID', LangFile::message('FIELD_ID') . ':', $currentItem->getId());
$tabControl->AddEditField('NAME', LangFile::message('FIELD_NAME') . ':', false, ["size" => 50, "maxlength" => 255], $currentItem->getName());
$tabControl->AddCheckBoxField('ACTIVE', LangFile::message('FIELD_ACTIVE') . ':', false, 'Y', $currentItem->getActive());
$tabControl->AddEditField('SORT', LangFile::message('FIELD_SORT') . ':', false, ["size" => 10, "maxlength" => 20], $currentItem->getSort());
$tabControl->AddDropDownField('ACTION', LangFile::message('FIELD_ACTION'), true, [
	'D' => LangFile::message('FIELD_ACTION_VALUE_D'),
	'L' => LangFile::message('FIELD_ACTION_VALUE_L'),
	'E' => LangFile::message('FIELD_ACTION_VALUE_E'),
	'A' => LangFile::message('FIELD_ACTION_VALUE_A'),
	'N' => LangFile::message('FIELD_ACTION_VALUE_N'),
], $currentItem->getAction());
$tabControl->AddCheckBoxField('USE_DEFAULT_SIGNATURE', LangFile::message('FIELD_USE_DEFAULT_SIGNATURE') . ':', false, 'Y', $currentItem->getUseDefaultSignature(), ['onchange="checkSignatureDefault()"']);
$signatures = [
	0 => LangFile::message('FIELD_SIGNATURE_ID_NOT_REF'),
];
$items = \Ammina\StopVirus\Db\SignaturesTable::getList([
	'order' => [
		'ID' => 'ASC',
	],
]);
while ($item = $items->fetchObject()) {
	$signatures[$item->getId()] = '[' . $item->getId() . '] ' . $item->getName();
}
$extParams = [];
if ($currentItem->getUseDefaultSignature()) {
	$extParams[] = 'disabled="disabled"';
}
$tabControl->AddDropDownField('SIGNATURE_ID', LangFile::message('FIELD_SIGNATURE_ID'), false, $signatures, $currentItem->getSignatureId(), $extParams);
$tabControl->AddCheckBoxField('STORE_POST_BODY', LangFile::message('FIELD_STORE_POST_BODY') . ':', false, 'Y', $currentItem->getStorePostBody());
$tabControl->AddEditField('MEMORY_LIMIT', LangFile::message('FIELD_MEMORY_LIMIT') . ':', false, ["size" => 10, "maxlength" => 20], $currentItem->getMemoryLimit());
$tabControl->BeginCustomField('PAGE_RULES', LangFile::message('FIELD_PAGE_RULES'));
?>
	<tr valign="top" id="tr_PAGE_RULES">
		<td width="40%" class="adm-detail-content-cell-l"><?= LangFile::message('FIELD_PAGE_RULES') ?>:<br/><?= LangFile::message('FIELD_PAGE_RULES_HELP') ?></td>
		<td class="adm-detail-content-cell-r">
			<textarea name="PAGE_RULES" cols="50" rows="15"><?= htmlspecialchars(implode("\n", $currentItem->getPageRules() ?? [])) ?></textarea>
		</td>
	</tr>
<?
$tabControl->EndCustomField('PAGE_RULES');
$tabControl->BeginCustomField('IP_RULES', LangFile::message('FIELD_IP_RULES'));
?>
	<tr valign="top" id="tr_IP_RULES">
		<td width="40%" class="adm-detail-content-cell-l"><?= LangFile::message('FIELD_IP_RULES') ?>:<br/><?= LangFile::message('FIELD_IP_RULES_HELP') ?></td>
		<td class="adm-detail-content-cell-r">
			<textarea name="IP_RULES" cols="50" rows="15"><?= htmlspecialchars(implode("\n", $currentItem->getIpRules() ?? [])) ?></textarea>
		</td>
	</tr>
<?
$tabControl->EndCustomField('IP_RULES');

$tabControl->Buttons([
	"btnSave" => true,
	"btnApply" => true,
	"btnCancel" => true,
]);
$tabControl->Show();

$tabControl->ShowWarnings($tabControl->GetName(), $message);

///////////////////////////

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");


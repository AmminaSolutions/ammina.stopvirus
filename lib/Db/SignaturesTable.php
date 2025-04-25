<?php

namespace Ammina\StopVirus\Db;

use Ammina\StopVirus;
use Bitrix\Main;

class SignaturesTable extends Main\ORM\Data\DataManager
{
	protected static $stopCheckDefault = false;

	public static function getTableName(): string
	{
		return 'am_stopvirus_signatures';
	}

	public static function getObjectClass(): string
	{
		return StopVirus\Orm\Signatures::class;
	}

	public static function getCollectionClass(): string
	{
		return StopVirus\Orm\SignaturesCollection::class;
	}

	/**
	 * @return array
	 * @throws Main\SystemException
	 */
	public static function getMap(): array
	{
		return [
			new Main\Orm\Fields\IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
			]),

			new Main\Orm\Fields\StringField('NAME', [
				'nullable' => true,
			]),

			(new Main\Orm\Fields\ArrayField('SIGNATURES', [
				'nullable' => true,
			]))
				->configureSerializationJson(),
			new Main\Orm\Fields\BooleanField('IS_DEFAULT', [
				'nullable' => false,
				'values' => ['N', 'Y'],
				'default_value' => 'N',
			]),
		];
	}

	public static function onAfterAdd(Main\ORM\Event $event)
	{
		$id = $event->getParameter("id");
		if (is_array($id)) {
			$id = $id['ID'];
		}
		static::cleanCache();
		self::checkDefault($id);
	}

	public static function onAfterUpdate(Main\ORM\Event $event)
	{
		$id = $event->getParameter("id");
		if (is_array($id)) {
			$id = $id['ID'];
		}
		static::cleanCache();
		self::checkDefault($id);
	}

	protected static function checkDefault($id): void
	{
		if (static::$stopCheckDefault) {
			return;
		}
		static::$stopCheckDefault = true;
		$item = static::getByID($id)->fetchObject();
		if ($item->getIsDefault()) {
			$otherItems = static::getList([
				'filter' => [
					'!ID' => $id,
					'IS_DEFAULT' => 'Y',
				],
			]);
			while ($other = $otherItems->fetchObject()) {
				$other
					->setIsDefault(false)
					->save();
			}
		}

		static::$stopCheckDefault = false;
	}
}
<?php

namespace Ammina\StopVirus\Db;

use Ammina\StopVirus;
use Bitrix\Main;


class AttemptsTable extends Main\ORM\Data\DataManager
{
	public static function getTableName(): string
	{
		return 'am_stopvirus_attempts';
	}

	public static function getObjectClass(): string
	{
		return StopVirus\Orm\Attempts::class;
	}

	public static function getCollectionClass(): string
	{
		return StopVirus\Orm\AttemptsCollection::class;
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

			new Main\Orm\Fields\DatetimeField('DATE_CREATE', [
				'nullable' => false,
				'default_value' => function () {
					return new Main\Type\DateTime();
				},
			]),
			new Main\Orm\Fields\StringField('REQUEST_TYPE', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\StringField('HTTP_HOST', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\StringField('REQUEST_URI', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\StringField('SCRIPT_FILENAME', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\StringField('FROM_IP', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\StringField('FROM_HOST', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\StringField('FROM_USER_AGENT', [
				'nullable' => true,
			]),
			new Main\Orm\Fields\BooleanField('IS_DETECT_VIRUS', [
				'nullable' => false,
				'values' => ['N', 'Y'],
				'default_value' => 'N',
			]),
			(new Main\Orm\Fields\ArrayField('DATA_HEADER', [
				'nullable' => true,
			]))
				->configureSerializationJson(),
			(new Main\Orm\Fields\ArrayField('DATA_QUERY', [
				'nullable' => true,
			]))
				->configureSerializationJson(),
			(new Main\Orm\Fields\ArrayField('DATA_BODY', [
				'nullable' => true,
			]))
				->configureSerializationJson(),
			(new Main\Orm\Fields\ArrayField('MATCH_SIGNATURES', [
				'nullable' => true,
			]))
				->configureSerializationJson(),
		];
	}


}
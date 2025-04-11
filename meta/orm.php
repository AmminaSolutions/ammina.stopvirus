<?php

/* ORMENTITYANNOTATION:Ammina\StopVirus\Db\AttemptsTable */
namespace Ammina\StopVirus\Db {
	/**
	 * Attempts
	 * @see \Ammina\StopVirus\Db\AttemptsTable
	 *
	 * Custom methods:
	 * ---------------
	 *
	 * @method \int getId()
	 * @method \Ammina\StopVirus\Orm\Attempts setId(\int|\Bitrix\Main\DB\SqlExpression $id)
	 * @method bool hasId()
	 * @method bool isIdFilled()
	 * @method bool isIdChanged()
	 * @method \Bitrix\Main\Type\DateTime getDateCreate()
	 * @method \Ammina\StopVirus\Orm\Attempts setDateCreate(\Bitrix\Main\Type\DateTime|\Bitrix\Main\DB\SqlExpression $dateCreate)
	 * @method bool hasDateCreate()
	 * @method bool isDateCreateFilled()
	 * @method bool isDateCreateChanged()
	 * @method \Bitrix\Main\Type\DateTime remindActualDateCreate()
	 * @method \Bitrix\Main\Type\DateTime requireDateCreate()
	 * @method \Ammina\StopVirus\Orm\Attempts resetDateCreate()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetDateCreate()
	 * @method \Bitrix\Main\Type\DateTime fillDateCreate()
	 * @method null|\string getRequestType()
	 * @method \Ammina\StopVirus\Orm\Attempts setRequestType(null|\string|\Bitrix\Main\DB\SqlExpression $requestType)
	 * @method bool hasRequestType()
	 * @method bool isRequestTypeFilled()
	 * @method bool isRequestTypeChanged()
	 * @method null|\string remindActualRequestType()
	 * @method null|\string requireRequestType()
	 * @method \Ammina\StopVirus\Orm\Attempts resetRequestType()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetRequestType()
	 * @method null|\string fillRequestType()
	 * @method null|\string getHttpHost()
	 * @method \Ammina\StopVirus\Orm\Attempts setHttpHost(null|\string|\Bitrix\Main\DB\SqlExpression $httpHost)
	 * @method bool hasHttpHost()
	 * @method bool isHttpHostFilled()
	 * @method bool isHttpHostChanged()
	 * @method null|\string remindActualHttpHost()
	 * @method null|\string requireHttpHost()
	 * @method \Ammina\StopVirus\Orm\Attempts resetHttpHost()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetHttpHost()
	 * @method null|\string fillHttpHost()
	 * @method null|\string getRequestUri()
	 * @method \Ammina\StopVirus\Orm\Attempts setRequestUri(null|\string|\Bitrix\Main\DB\SqlExpression $requestUri)
	 * @method bool hasRequestUri()
	 * @method bool isRequestUriFilled()
	 * @method bool isRequestUriChanged()
	 * @method null|\string remindActualRequestUri()
	 * @method null|\string requireRequestUri()
	 * @method \Ammina\StopVirus\Orm\Attempts resetRequestUri()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetRequestUri()
	 * @method null|\string fillRequestUri()
	 * @method null|\string getScriptFilename()
	 * @method \Ammina\StopVirus\Orm\Attempts setScriptFilename(null|\string|\Bitrix\Main\DB\SqlExpression $scriptFilename)
	 * @method bool hasScriptFilename()
	 * @method bool isScriptFilenameFilled()
	 * @method bool isScriptFilenameChanged()
	 * @method null|\string remindActualScriptFilename()
	 * @method null|\string requireScriptFilename()
	 * @method \Ammina\StopVirus\Orm\Attempts resetScriptFilename()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetScriptFilename()
	 * @method null|\string fillScriptFilename()
	 * @method null|\string getFromIp()
	 * @method \Ammina\StopVirus\Orm\Attempts setFromIp(null|\string|\Bitrix\Main\DB\SqlExpression $fromIp)
	 * @method bool hasFromIp()
	 * @method bool isFromIpFilled()
	 * @method bool isFromIpChanged()
	 * @method null|\string remindActualFromIp()
	 * @method null|\string requireFromIp()
	 * @method \Ammina\StopVirus\Orm\Attempts resetFromIp()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetFromIp()
	 * @method null|\string fillFromIp()
	 * @method null|\string getFromHost()
	 * @method \Ammina\StopVirus\Orm\Attempts setFromHost(null|\string|\Bitrix\Main\DB\SqlExpression $fromHost)
	 * @method bool hasFromHost()
	 * @method bool isFromHostFilled()
	 * @method bool isFromHostChanged()
	 * @method null|\string remindActualFromHost()
	 * @method null|\string requireFromHost()
	 * @method \Ammina\StopVirus\Orm\Attempts resetFromHost()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetFromHost()
	 * @method null|\string fillFromHost()
	 * @method null|\string getFromUserAgent()
	 * @method \Ammina\StopVirus\Orm\Attempts setFromUserAgent(null|\string|\Bitrix\Main\DB\SqlExpression $fromUserAgent)
	 * @method bool hasFromUserAgent()
	 * @method bool isFromUserAgentFilled()
	 * @method bool isFromUserAgentChanged()
	 * @method null|\string remindActualFromUserAgent()
	 * @method null|\string requireFromUserAgent()
	 * @method \Ammina\StopVirus\Orm\Attempts resetFromUserAgent()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetFromUserAgent()
	 * @method null|\string fillFromUserAgent()
	 * @method \boolean getIsDetectVirus()
	 * @method \Ammina\StopVirus\Orm\Attempts setIsDetectVirus(\boolean|\Bitrix\Main\DB\SqlExpression $isDetectVirus)
	 * @method bool hasIsDetectVirus()
	 * @method bool isIsDetectVirusFilled()
	 * @method bool isIsDetectVirusChanged()
	 * @method \boolean remindActualIsDetectVirus()
	 * @method \boolean requireIsDetectVirus()
	 * @method \Ammina\StopVirus\Orm\Attempts resetIsDetectVirus()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetIsDetectVirus()
	 * @method \boolean fillIsDetectVirus()
	 * @method null|array getDataHeader()
	 * @method \Ammina\StopVirus\Orm\Attempts setDataHeader(null|array|\Bitrix\Main\DB\SqlExpression $dataHeader)
	 * @method bool hasDataHeader()
	 * @method bool isDataHeaderFilled()
	 * @method bool isDataHeaderChanged()
	 * @method null|array remindActualDataHeader()
	 * @method null|array requireDataHeader()
	 * @method \Ammina\StopVirus\Orm\Attempts resetDataHeader()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetDataHeader()
	 * @method null|array fillDataHeader()
	 * @method null|array getDataQuery()
	 * @method \Ammina\StopVirus\Orm\Attempts setDataQuery(null|array|\Bitrix\Main\DB\SqlExpression $dataQuery)
	 * @method bool hasDataQuery()
	 * @method bool isDataQueryFilled()
	 * @method bool isDataQueryChanged()
	 * @method null|array remindActualDataQuery()
	 * @method null|array requireDataQuery()
	 * @method \Ammina\StopVirus\Orm\Attempts resetDataQuery()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetDataQuery()
	 * @method null|array fillDataQuery()
	 * @method null|array getDataBody()
	 * @method \Ammina\StopVirus\Orm\Attempts setDataBody(null|array|\Bitrix\Main\DB\SqlExpression $dataBody)
	 * @method bool hasDataBody()
	 * @method bool isDataBodyFilled()
	 * @method bool isDataBodyChanged()
	 * @method null|array remindActualDataBody()
	 * @method null|array requireDataBody()
	 * @method \Ammina\StopVirus\Orm\Attempts resetDataBody()
	 * @method \Ammina\StopVirus\Orm\Attempts unsetDataBody()
	 * @method null|array fillDataBody()
	 *
	 * Common methods:
	 * ---------------
	 *
	 * @property-read \Bitrix\Main\ORM\Entity $entity
	 * @property-read array $primary
	 * @property-read int $state @see \Bitrix\Main\ORM\Objectify\State
	 * @property-read \Bitrix\Main\Type\Dictionary $customData
	 * @property \Bitrix\Main\Authentication\Context $authContext
	 * @method mixed get($fieldName)
	 * @method mixed remindActual($fieldName)
	 * @method mixed require($fieldName)
	 * @method bool has($fieldName)
	 * @method bool isFilled($fieldName)
	 * @method bool isChanged($fieldName)
	 * @method \Ammina\StopVirus\Orm\Attempts set($fieldName, $value)
	 * @method \Ammina\StopVirus\Orm\Attempts reset($fieldName)
	 * @method \Ammina\StopVirus\Orm\Attempts unset($fieldName)
	 * @method void addTo($fieldName, $value)
	 * @method void removeFrom($fieldName, $value)
	 * @method void removeAll($fieldName)
	 * @method \Bitrix\Main\ORM\Data\Result delete()
	 * @method mixed fill($fields = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL) flag or array of field names
	 * @method mixed[] collectValues($valuesType = \Bitrix\Main\ORM\Objectify\Values::ALL, $fieldsMask = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL)
	 * @method \Bitrix\Main\ORM\Data\AddResult|\Bitrix\Main\ORM\Data\UpdateResult|\Bitrix\Main\ORM\Data\Result save()
	 * @method static \Ammina\StopVirus\Orm\Attempts wakeUp($data)
	 */
	class EO_Attempts {
		/* @var \Ammina\StopVirus\Db\AttemptsTable */
		static public $dataClass = '\Ammina\StopVirus\Db\AttemptsTable';
		/**
		 * @param bool|array $setDefaultValues
		 */
		public function __construct($setDefaultValues = true) {}
	}
}
namespace Ammina\StopVirus\Db {
	/**
	 * AttemptsCollection
	 *
	 * Custom methods:
	 * ---------------
	 *
	 * @method \int[] getIdList()
	 * @method \Bitrix\Main\Type\DateTime[] getDateCreateList()
	 * @method \Bitrix\Main\Type\DateTime[] fillDateCreate()
	 * @method null|\string[] getRequestTypeList()
	 * @method null|\string[] fillRequestType()
	 * @method null|\string[] getHttpHostList()
	 * @method null|\string[] fillHttpHost()
	 * @method null|\string[] getRequestUriList()
	 * @method null|\string[] fillRequestUri()
	 * @method null|\string[] getScriptFilenameList()
	 * @method null|\string[] fillScriptFilename()
	 * @method null|\string[] getFromIpList()
	 * @method null|\string[] fillFromIp()
	 * @method null|\string[] getFromHostList()
	 * @method null|\string[] fillFromHost()
	 * @method null|\string[] getFromUserAgentList()
	 * @method null|\string[] fillFromUserAgent()
	 * @method \boolean[] getIsDetectVirusList()
	 * @method \boolean[] fillIsDetectVirus()
	 * @method null|array[] getDataHeaderList()
	 * @method null|array[] fillDataHeader()
	 * @method null|array[] getDataQueryList()
	 * @method null|array[] fillDataQuery()
	 * @method null|array[] getDataBodyList()
	 * @method null|array[] fillDataBody()
	 *
	 * Common methods:
	 * ---------------
	 *
	 * @property-read \Bitrix\Main\ORM\Entity $entity
	 * @method void add(\Ammina\StopVirus\Orm\Attempts $object)
	 * @method bool has(\Ammina\StopVirus\Orm\Attempts $object)
	 * @method bool hasByPrimary($primary)
	 * @method \Ammina\StopVirus\Orm\Attempts getByPrimary($primary)
	 * @method \Ammina\StopVirus\Orm\Attempts[] getAll()
	 * @method bool remove(\Ammina\StopVirus\Orm\Attempts $object)
	 * @method void removeByPrimary($primary)
	 * @method array|\Bitrix\Main\ORM\Objectify\Collection|null fill($fields = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL) flag or array of field names
	 * @method static \Ammina\StopVirus\Orm\AttemptsCollection wakeUp($data)
	 * @method \Bitrix\Main\ORM\Data\Result save($ignoreEvents = false)
	 * @method void offsetSet() ArrayAccess
	 * @method void offsetExists() ArrayAccess
	 * @method void offsetUnset() ArrayAccess
	 * @method void offsetGet() ArrayAccess
	 * @method void rewind() Iterator
	 * @method \Ammina\StopVirus\Orm\Attempts current() Iterator
	 * @method mixed key() Iterator
	 * @method void next() Iterator
	 * @method bool valid() Iterator
	 * @method int count() Countable
	 * @method \Ammina\StopVirus\Orm\AttemptsCollection merge(?\Ammina\StopVirus\Orm\AttemptsCollection $collection)
	 * @method bool isEmpty()
	 * @method array collectValues(int $valuesType = \Bitrix\Main\ORM\Objectify\Values::ALL, int $fieldsMask = \Bitrix\Main\ORM\Fields\FieldTypeMask::ALL, bool $recursive = false)
	 */
	class EO_Attempts_Collection implements \ArrayAccess, \Iterator, \Countable {
		/* @var \Ammina\StopVirus\Db\AttemptsTable */
		static public $dataClass = '\Ammina\StopVirus\Db\AttemptsTable';
	}
}
namespace Ammina\StopVirus\Db {
	/**
	 * @method static EO_Attempts_Query query()
	 * @method static EO_Attempts_Result getByPrimary($primary, array $parameters = [])
	 * @method static EO_Attempts_Result getById($id)
	 * @method static EO_Attempts_Result getList(array $parameters = [])
	 * @method static EO_Attempts_Entity getEntity()
	 * @method static \Ammina\StopVirus\Orm\Attempts createObject($setDefaultValues = true)
	 * @method static \Ammina\StopVirus\Orm\AttemptsCollection createCollection()
	 * @method static \Ammina\StopVirus\Orm\Attempts wakeUpObject($row)
	 * @method static \Ammina\StopVirus\Orm\AttemptsCollection wakeUpCollection($rows)
	 */
	class AttemptsTable extends \Bitrix\Main\ORM\Data\DataManager {}
	/**
	 * Common methods:
	 * ---------------
	 *
	 * @method EO_Attempts_Result exec()
	 * @method \Ammina\StopVirus\Orm\Attempts fetchObject()
	 * @method \Ammina\StopVirus\Orm\AttemptsCollection fetchCollection()
	 */
	class EO_Attempts_Query extends \Bitrix\Main\ORM\Query\Query {}
	/**
	 * @method \Ammina\StopVirus\Orm\Attempts fetchObject()
	 * @method \Ammina\StopVirus\Orm\AttemptsCollection fetchCollection()
	 */
	class EO_Attempts_Result extends \Bitrix\Main\ORM\Query\Result {}
	/**
	 * @method \Ammina\StopVirus\Orm\Attempts createObject($setDefaultValues = true)
	 * @method \Ammina\StopVirus\Orm\AttemptsCollection createCollection()
	 * @method \Ammina\StopVirus\Orm\Attempts wakeUpObject($row)
	 * @method \Ammina\StopVirus\Orm\AttemptsCollection wakeUpCollection($rows)
	 */
	class EO_Attempts_Entity extends \Bitrix\Main\ORM\Entity {}
}
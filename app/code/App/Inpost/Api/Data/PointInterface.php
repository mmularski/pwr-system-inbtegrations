<?php

namespace App\Inpost\Api\Data;

/**
 * Interface PointInterface
 */
interface PointInterface
{
    /**
     * Point entity_id
     */
    const ENTITY_ID = 'entity_id';

    /**
     * Point name
     */
    const NAME = 'name';

    /**
     * Point type
     */
    const TYPE = 'type';

    /**
     * Point status
     */
    const STATUS = 'status';

    /**
     * Point latitude
     */
    const LATITUDE = 'latitude';

    /**
     * Point longitude
     */
    const LONGITUDE = 'longitude';

    /**
     * Point opening hours
     */
    const OPENING_HOURS = 'opening_hours';

    /**
     * Point city location
     */
    const CITY = 'city';

    /**
     * Point province location
     */
    const PROVINCE = 'province';

    /**
     * Point post code location
     */
    const POST_CODE = 'post_code';

    /**
     * Point street location
     */
    const STREET = 'street';

    /**
     * Point building number
     */
    const BUILDING_NUMBER = 'building_number';

    /**
     * Point flat number
     */
    const FLAT_NUMBER = 'flat_number';

    /**
     * Point description
     */
    const POINT_DESCRIPTION = 'point_description';

    /**
     * Location description
     */
    const LOCATION_DESCRIPTION = 'location_description';

    /**
     * Payment available
     */
    const PAYMENT_AVAILABLE = 'payment_available';

    /**
     * Available payment type
     */
    const PAYMENT_TYPE = 'payment_type';

    /**
     * Flag to delete
     */
    const TO_DELETE_FLAG = 'to_delete';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getLatitude();

    /**
     * @return string
     */
    public function getLongitude();

    /**
     * @return string
     */
    public function getOpeningHours();

    /**
     * @return string
     */
    public function getCity();

    /**
     * @return string
     */
    public function getProvince();

    /**
     * @return string
     */
    public function getPostCode();

    /**
     * @return string
     */
    public function getStreet();

    /**
     * @return string
     */
    public function getBuildingNumber();

    /**
     * @return string
     */
    public function getFlatNumber();

    /**
     * @return string
     */
    public function getPointDescription();

    /**
     * @return string
     */
    public function getLocationDescription();

    /**
     * @return bool
     */
    public function isPaymentAvailable();

    /**
     * @return string
     */
    public function getPaymentType();

    /**
     * @return bool
     */
    public function isToDelete();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setName($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setType($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStatus($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLatitude($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLongitude($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setOpeningHours($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCity($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setProvince($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPostCode($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStreet($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setBuildingNumber($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setFlatNumber($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPointDescription($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLocationDescription($value);

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setIsPaymentAvailable($value);

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPaymentType($value);

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setIsToDelete($value);
}

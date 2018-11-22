<?php

namespace App\Inpost\Model;

use Magento\Framework\Model\AbstractModel;
use App\Inpost\Api\Data\PointInterface;
use App\Inpost\Model\ResourceModel\Point as PointResource;

/**
 * Class Point
 */
class Point extends AbstractModel implements PointInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(PointResource::class);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->getData(self::LATITUDE);
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->getData(self::LONGITUDE);
    }

    /**
     * @return string
     */
    public function getOpeningHours()
    {
        return $this->getData(self::OPENING_HOURS);
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->getData(self::CITY);
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->getData(self::PROVINCE);
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->getData(self::POST_CODE);
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->getData(self::STREET);
    }

    /**
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->getData(self::BUILDING_NUMBER);
    }

    /**
     * @return string
     */
    public function getFlatNumber()
    {
        return $this->getData(self::FLAT_NUMBER);
    }

    /**
     * @return string
     */
    public function getPointDescription()
    {
        return $this->getData(self::POINT_DESCRIPTION);
    }

    /**
     * @return string
     */
    public function getLocationDescription()
    {
        return $this->getData(self::LOCATION_DESCRIPTION);
    }

    /**
     * @return bool
     */
    public function isPaymentAvailable()
    {
        return (bool) $this->getData(self::PAYMENT_AVAILABLE);
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->getData(self::PAYMENT_TYPE);
    }

    /**
     * @return bool
     */
    public function isToDelete()
    {
        return (bool) $this->getData(self::TO_DELETE_FLAG);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setName($value)
    {
        return $this->setData(self::NAME, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setType($value)
    {
        return $this->setData(self::TYPE, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStatus($value)
    {
        return $this->setData(self::STATUS, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLatitude($value)
    {
        return $this->setData(self::LATITUDE, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLongitude($value)
    {
        return $this->setData(self::LONGITUDE, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setOpeningHours($value)
    {
        return $this->setData(self::OPENING_HOURS, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setCity($value)
    {
        return $this->setData(self::CITY, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setProvince($value)
    {
        return $this->setData(self::PROVINCE, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPostCode($value)
    {
        return $this->setData(self::POST_CODE, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStreet($value)
    {
        return $this->setData(self::STREET, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setBuildingNumber($value)
    {
        return $this->setData(self::BUILDING_NUMBER, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setFlatNumber($value)
    {
        return $this->setData(self::FLAT_NUMBER, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPointDescription($value)
    {
        return $this->setData(self::POINT_DESCRIPTION, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setLocationDescription($value)
    {
        return $this->setData(self::LOCATION_DESCRIPTION, $value);
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setIsPaymentAvailable($value)
    {
        return $this->setData(self::PAYMENT_AVAILABLE, $value);
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setPaymentType($value)
    {
        return $this->setData(self::PAYMENT_TYPE, $value);
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setIsToDelete($value)
    {
        return $this->setData(self::TO_DELETE_FLAG, $value);
    }
}

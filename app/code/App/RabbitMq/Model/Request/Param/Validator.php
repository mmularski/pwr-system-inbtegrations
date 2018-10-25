<?php
/**
 * @package   App\RabbitMq
 * @author    Wiktor Kaczorowski <wkaczorowski@App.pl>
 * @copyright 2016-2018 App Sp. z o.o.
 * @license   See LICENSE.txt for license details.
 */

namespace App\RabbitMq\Model\Request\Param;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Validator\IntUtils;
use Magento\Framework\Validator\NotEmpty;
use Magento\Framework\Validator\StringLength;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Class Validator
 *
 * @package App\RabbitMq\Model\Request\Param
 */
class Validator extends AbstractModel
{

    const RULE_IS_INT = 'is_int';

    const RULE_IS_REQUIRED = 'is_required';

    const RULE_MAX_LENGTH = 'max_length';

    /**
     * @var IntUtils $intValidator
     */
    protected $intValidator;

    /**
     * @var NotEmpty $notEmptyValidator
     */
    protected $notEmptyValidator;

    /**
     * @var StringLength $lenghtValidator
     */
    protected $lenghtValidator;

    /**
     * Validator constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param IntUtils $intValidator
     * @param NotEmpty $notEmptyValidator
     * @param StringLength $lenghtValidator
     */
    public function __construct(
        Context $context,
        Registry $registry,
        IntUtils $intValidator,
        NotEmpty $notEmptyValidator,
        StringLength $lenghtValidator
    ) {
        parent::__construct(
            $context,
            $registry
        );

        $this->intValidator = $intValidator;
        $this->notEmptyValidator = $notEmptyValidator;
        $this->lenghtValidator = $lenghtValidator;
    }

    /**
     * Checks if value is an integer
     *
     * @param string|int $value
     *
     * @return bool
     */
    public function isInt($value)
    {
        return $this->intValidator->isValid($value);
    }

    /**
     * Checks if string is not empty
     *
     * @param string $value
     *
     * @return bool
     */
    public function isNotEmpty($value)
    {
        return $this->notEmptyValidator->isValid($value);
    }

    /**
     * Validates string length
     *
     * @param string $value
     * @param int $max
     * @param null|int $min
     *
     * @return bool
     */
    public function length($value, $max, $min = null)
    {
        $this->lenghtValidator->setMax((int) $max);
        if (null !== $min) {
            $this->lenghtValidator->setMin((int) $min);
        }

        return $this->lenghtValidator->isValid($value);
    }
}

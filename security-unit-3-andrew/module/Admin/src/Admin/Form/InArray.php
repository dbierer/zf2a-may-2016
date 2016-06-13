<?php
namespace Admin\Form;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;
/**
 * Rewrite InArray to accept an array of values as input
 *
 * @author db
 *
 */
class InArray extends AbstractValidator
{
    const NOT_IN_ARRAY = 'notInArray';

    // Type of Strict check
    /**
     * standard in_array strict checking value and type
     */
    const COMPARE_STRICT = 1;

    /**
     * Non strict check but prevents "asdf" == 0 returning TRUE causing false/positive.
     * This is the most secure option for non-strict checks and replaces strict = false
     * This will only be effective when the input is a string
     */
    const COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY = 0;

    /**
     * Standard non-strict check where "asdf" == 0 returns TRUE
     * This will be wanted when comparing "0" against int 0
     */
    const COMPARE_NOT_STRICT = -1;


    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_IN_ARRAY => 'The input was not found in the haystack',
    );

    /**
     * Haystack of possible values
     *
     * @var array
     */
    protected $haystack;

    /**
     * Type of strict check to be used. Due to "foo" == 0 === TRUE with in_array when strict = false,
     * an option has been added to prevent this. When $strict = 0/false, the most
     * secure non-strict check is implemented. if $strict = -1, the default in_array non-strict
     * behaviour is used
     *
     * @var int
     */
    protected $strict = self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY;

    /**
     * Whether a recursive search should be done
     *
     * @var bool
     */
    protected $recursive = false;

    /**
     * Returns the haystack option
     *
     * @return mixed
     * @throws Exception\RuntimeException if haystack option is not set
     */
    public function getHaystack()
    {
        if ($this->haystack === null) {
            throw new Exception\RuntimeException('haystack option is mandatory');
        }
        return $this->haystack;
    }

    /**
     * Sets the haystack option
     *
     * @param  mixed $haystack
     * @return InArray Provides a fluent interface
     */
    public function setHaystack(array $haystack)
    {
        $this->haystack = $haystack;
        return $this;
    }

    /**
     * Returns the strict option
     *
     * @return bool|int
     */
    public function getStrict()
    {
        // To keep BC with new strict modes
        if ($this->strict == self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY
            || $this->strict == self::COMPARE_STRICT
        ) {
            return (bool) $this->strict;
        }
        return $this->strict;
    }

    /**
     * Sets the strict option mode
     * InArray::CHECK_STRICT | InArray::CHECK_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY | InArray::CHECK_NOT_STRICT
     *
     * @param  int $strict
     * @return InArray Provides a fluent interface
     * @throws Exception\InvalidArgumentException
     */
    public function setStrict($strict)
    {
        $checkTypes = array(
            self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY,    // 0
            self::COMPARE_STRICT,                                             // 1
            self::COMPARE_NOT_STRICT                                          // -1
        );

        // validate strict value
        if (!in_array($strict, $checkTypes)) {
            throw new Exception\InvalidArgumentException('Strict option must be one of the COMPARE_ constants');
        }

        $this->strict = $strict;
        return $this;
    }

    /**
     * Returns the recursive option
     *
     * @return bool
     */
    public function getRecursive()
    {
        return $this->recursive;
    }

    /**
     * Sets the recursive option
     *
     * @param  bool $recursive
     * @return InArray Provides a fluent interface
     */
    public function setRecursive($recursive)
    {
        $this->recursive = (bool) $recursive;
        return $this;
    }

    /**
     * Returns true if and only if $value is contained in the haystack option. If the strict
     * option is true, then the type of $value is also checked.
     *
     * @param mixed $value
     * See {@link http://php.net/manual/function.in-array.php#104501}
     * @return bool
     */
    public function isValid($value)
    {
        // we create a copy of the haystack in case we need to modify it
        $haystack = $this->getHaystack();
        $result = FALSE;
        if (is_array($value)) {
            foreach ($value as $item) {
                if (in_array($item, $haystack, TRUE)) {
                    $result = TRUE;
                } else {
                    $this->error(self::NOT_IN_ARRAY);
                }
            }
        } else {
            if (in_array($value, $haystack, TRUE)) {
                $result = TRUE;
            } else {
                $this->error(self::NOT_IN_ARRAY);
            }
        }
        return $result;
    }
}

<?php

namespace App\Constants;

use Exception;

class Constants
{
    protected $defaultscope = null;

    protected $constants = [];

    private $scopefiltered = false;

    public function __construct($constants = null)
    {
        if (is_array($constants)) {
            $this->constants = $constants;
        }
    }

    public function __call($method, $parameters)
    {
        $fmethod = 'facade' . ucfirst($method) . 'Method';
        if (method_exists($this, $fmethod)) {
            return $this->$fmethod(...$parameters);
        } else {
            throw new Exception('The function ' . $method . ' does not exist in this class');
        }
    }

    public static function __callStatic($method, $parameters)
    {
        $fmethod = 'facade' . ucfirst($method) . 'Method';
        $class = new static();
        if (method_exists($class, $fmethod)) {
            return $class->$fmethod(...$parameters);
        } else {
            throw new Exception('The function "' . $method . '" does not exist in this class');
        }
    }

    /**
     * Get the scope of the constant
     * If the constant has no scope this function will not have any effect
     * @param string|null $scope
     * @return Constants
     */
    protected function facadeScopeMethod($scope = null)
    {
        if ($this->scopefiltered) {
            return;
        }
        if (is_null($scope)) {
            $scope = $this->defaultscope;
        }

        $values = array_filter($this->constants, fn ($v) => is_array($v) ? isset($v[$scope]) : true);
        $values = array_map(fn ($v) => is_array($v) ? $v[$scope] : $v, $values);
        $this->constants = $values;
        $this->scopefiltered = true;

        return $this;
    }

    /**
     * Get an array of all constants in the current scope
     * @return array
     */
    protected function facadeArrayMethod($split = false, $key = 'key', $value = 'value')
    {
        $this->scope();
        $array = $this->constants;
        if ($split) {
            $array = array_map(function ($k, $v) use ($key, $value) {
                return [$key => $k, $value => $v];
            }, array_keys($array), array_values($array));
        }
        return $array;
    }

    /**
     * Get a sorted array of all values in the current scope
     * @return array
     */
    protected function facadeValuesMethod()
    {
        $this->scope();

        $values = array_keys($this->constants);
        sort($values);
        return $values;
    }

    /**
     * Get a naturally sorted array of all descriptions in the current scope
     * @return array
     */
    protected function facadeDescriptionsMethod()
    {
        $this->scope();

        $values = array_values($this->constants);
        natsort($values);
        return $values;
    }

    /**
     * Get the description for a given value
     * @param string $value
     * @return string
     */
    protected function facadeDescriptionMethod($value)
    {
        $this->scope();

        return $this->constants[$value] ?? '';
    }

    /**
     * Filter for specific values
     * @param string|array $filter values which should be left
     * @return Constants
     */
    protected function facadeFilterMethod($filter)
    {
        $this->scope();

        if (!is_array($filter)) {
            $filter = [$filter];
        }

        $values = array_filter($this->constants, fn ($k) => in_array($k, $filter), ARRAY_FILTER_USE_KEY);

        $this->constants = $values;

        return $this;
    }

    /**
     * Check if specific value exists
     * @param string $value
     * @return bool
     */
    protected function facadeHasMethod($value)
    {
        $this->scope();
        return isset($this->constants[$value]);
    }

    /**
     * Get a single value as own pair
     * @param string $value
     * @return null|stdClass
     */
    protected function facadeGetMethod($value)
    {
        $this->scope();
        if (!isset($this->constants[$value])) {
            return null;
        }

        $pair = new \stdClass();
        $pair->value = $value;
        $pair->description = $this->constants[$value];

        return $pair;
    }

    /**
     * Search for specific description
     * @param string $description
     * @return Constants
     */
    protected function facadeSearchMethod($description)
    {
        $this->scope();

        $description = strtolower($description);

        $values = array_filter($this->constants, fn ($v) => strpos(strtolower($v), $description) !== false);

        $this->constants = $values;

        return $this;
    }
}

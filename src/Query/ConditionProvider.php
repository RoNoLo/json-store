<?php

namespace RoNoLo\JsonStorage\Query;

class ConditionProvider
{
    /** All supported rules and their $-command */
    const RULES = [
        '$eq' => 'isEqual',
        '$neq' => 'isNotEqual',
        '$gt' => 'isGreaterThan',
        '$gte' => 'isGreaterThanOrEqual',
        '$lt' => 'isLessThan',
        '$lte' => 'isLessThanOrEqual',
        '$in'    => 'isIn',
        '$nin' => 'isNotIn',
        '$nn' => 'isNotNull',
        '$null' => 'isNull',
        '$contains' => 'contains',
        '$ne' => 'isEmpty',
        '$empty' => 'isEmpty',
        '$regex' => 'isRegExMatch',
    ];

    public function get($op, $value, $comparable)
    {
        switch ($op) {
            case '$eq': return $this->isEqual($value, $comparable);
            case '$neq': return $this->isNotEqual($value, $comparable);
            case '$gt': return $this->isGreaterThan($value, $comparable);
            case '$gte': return $this->isGreaterThanOrEqual($value, $comparable);
            case '$lt': return $this->isLessThan($value, $comparable);
            case '$lte': return $this->isLessThanOrEqual($value, $comparable);
            case '$in': return $this->isIn($value, $comparable);
            case '$nin': return $this->isNotIn($value, $comparable);
            case '$nn': return $this->isNull($value, !$comparable);
            case '$null': return $this->isNull($value, $comparable);
            case '$nc': return $this->notContains($value, $comparable);
            case '$contains': return $this->contains($value, $comparable);
            case '$ne': return $this->isEmpty($value, !$comparable);
            case '$empty': return $this->isEmpty($value, $comparable);
            case '$regex': return $this->isRegExMatch($value, $comparable);
            default:
                throw new \Exception(sprintf("%s is not implemented yet", $op));
        }
    }


    /**
     * Is Equal.
     *
     * Type strict support for: int, float, string, datetime
     * Everything else including stdClass objects, arrays is checked not type strict.
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function isEqual($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                // We are strict with scalars
                case is_int($comparable):
                    return intval($value) === $comparable;

                case is_float($comparable):
                    return floatval($value) === $comparable;

                case is_string($comparable) && is_string($value):
                    return strcmp($value, $comparable) === 0;

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime == $comparable;

                default:
                    return $value == $comparable;
            }
        };
    }

    /**
     * Is Not Equal.
     *
     * Type strict support for: int, float, string, datetime
     * Everything else including stdClass objects, arrays is checked not type strict.
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function isNotEqual($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                // We are strict with scalars
                case is_int($comparable):
                    return intval($value) !== $comparable;

                case is_float($comparable):
                    return floatval($value) !== $comparable;

                case is_string($comparable) && is_string($value):
                    return strcmp($value, $comparable) !== 0;

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime != $comparable;

                default:
                    return $value != $comparable;
            }
        };
    }

    /**
     * Is Greater Than
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function isGreaterThan($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_int($comparable):
                    return intval($value) > $comparable;

                case is_float($comparable):
                    return floatval($value) > $comparable;

                case is_string($comparable) && is_string($value):
                    return strcmp($value, $comparable) > 0;

                default:
                    return $value > $comparable;

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime > $comparable;
            }
        };
    }

    /**
     * Strict less than
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function isLessThan($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_int($comparable):
                    return intval($value) < $comparable;

                case is_float($comparable):
                    return floatval($value) < $comparable;

                case is_string($comparable) && is_string($value):
                    return strcmp($value, $comparable) < 0;

                default:
                    return $value < $comparable;

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime < $comparable;
            }
        };
    }

    /**
     * Greater or equal
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function isGreaterThanOrEqual($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_int($comparable):
                    return intval($value) >= $comparable;

                case is_float($comparable):
                    return floatval($value) >= $comparable;

                case is_string($comparable) && is_string($value):
                    return strcmp($value, $comparable) >= 0;

                default:
                    return $value >= $comparable;

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime >= $comparable;
            }
        };
    }

    /**
     * Less or equal
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function isLessThanOrEqual($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_int($comparable):
                    return intval($value) <= $comparable;

                case is_float($comparable):
                    return floatval($value) <= $comparable;

                case is_string($comparable) && is_string($value):
                    return strcmp($value, $comparable) <= 0;

                default:
                    return $value <= $comparable;

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime <= $comparable;
            }
        };
    }

    /**
     * In array
     * or
     * In String
     *
     * @param mixed $value
     * @param array|string $comparable
     *
     * @return \Closure
     */
    public function isIn($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_array($comparable):
                    return in_array($value, $comparable);

                case is_string($comparable) && is_string($value):
                    return strpos($comparable, $value) !== false;
            }

            return false;
        };
    }

    /**
     * Not In Array
     * or
     * Not In String
     *
     * @param mixed $value
     * @param array|string $comparable
     *
     * @return \Closure
     */
    public function isNotIn($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_array($comparable):
                    return !in_array($value, $comparable);

                case is_string($comparable) && is_string($value):
                    return strpos($comparable, $value) === false;
            }

            return true;
        };
    }

    /**
     * Is Null
     *
     * @param mixed $value
     * @param bool $comparable
     *
     * @return \Closure
     */
    public function isNull($value, bool $comparable)
    {
        return function () use ($value, $comparable)
        {
            return $comparable ? is_null($value) : !is_null($value);
        };
    }

    /**
     * Is empty string or empty array.
     *
     * @param mixed $value
     * @param bool $comparable
     *
     * @return \Closure
     */
    public function isEmpty($value, bool $comparable)
    {
        return function () use ($value, $comparable)
        {
            if (is_array($value)) {
                return $comparable ? count($value) === 0 : count($value) > 0;
            } elseif (is_string($value)) {
                return $comparable ? trim($value) === '' : trim($value) !== '';
            }

            return $comparable ? false : true;
        };
    }

    /**
     * Match with pattern
     *
     * @param mixed $value
     * @param string $comparable
     *
     * @return \Closure
     */
    public function isRegExMatch($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            if (!is_string($comparable)) {
                return false;
            }

            $comparable = trim($comparable);
            if (preg_match($comparable, $value)) {
                return true;
            }

            return false;
        };
    }

    /**
     * Contains substring in string
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function contains($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_string($value) && is_string($comparable):
                    return strpos($value, $comparable) !== false;

                case is_string($value) && is_array($comparable):
                    foreach ($comparable as $item) {
                        if (strpos($value, $item) !== false) {
                            return true;
                        }
                    }
                    return false;

                case is_array($value) && !is_array($comparable):
                    return in_array($comparable, $value, true);

                case is_array($value) && is_array($comparable):
                    return count(array_diff($comparable, $value)) != count($comparable);
            }

            return false;
        };
    }

    /**
     * Contains substring in string
     *
     * @param mixed $value
     * @param mixed $comparable
     *
     * @return \Closure
     */
    public function notContains($value, $comparable)
    {
        return function () use ($value, $comparable)
        {
            switch (true) {
                case is_string($value) && is_string($comparable):
                    return strpos($value, $comparable) === false;

                case is_string($value) && is_array($comparable):
                    foreach ($comparable as $item) {
                        if (strpos($value, $item) === false) {
                            return true;
                        }
                    }
                    return false;

                case is_array($value) && !is_array($comparable):
                    return !in_array($comparable, $value, true);

                case is_array($value) && is_array($comparable):
                    return count(array_diff($comparable, $value)) == count($comparable);
            }

            return true;
        };
    }

    /**
     * @param mixed $value
     * @return \DateTime|null
     * @throws \Exception
     */
    private function toDateTime($value)
    {
        if (is_object($value) && $value instanceof \DateTime) {
            return $value;
        } elseif (is_string($value)) {
            $dateTime = date_create($value);

            return $dateTime ? $dateTime : null;
        } else {
            $unixtime = strtotime($value);
            if ($unixtime === false) {
                return null;
            } else {
                return (new \DateTime())->setTimestamp($unixtime);
            }
        }
    }
}
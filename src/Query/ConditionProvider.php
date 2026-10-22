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

    public function get(string $op, mixed $value, mixed $comparable): \Closure
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
     */
    public function isEqual(mixed $value, mixed $comparable): \Closure
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
     */
    public function isNotEqual(mixed $value, mixed $comparable): \Closure
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
     */
    public function isGreaterThan(mixed $value, mixed $comparable): \Closure
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

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime > $comparable;

                default:
                    return $value > $comparable;
            }
        };
    }

    /**
     * Strict less than
     */
    public function isLessThan(mixed $value, mixed $comparable): \Closure
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

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime < $comparable;

                default:
                    return $value < $comparable;
            }
        };
    }

    /**
     * Greater or equal
     */
    public function isGreaterThanOrEqual(mixed $value, mixed $comparable): \Closure
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

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime >= $comparable;

                default:
                    return $value >= $comparable;
            }
        };
    }

    /**
     * Less or equal
     */
    public function isLessThanOrEqual(mixed $value, mixed $comparable): \Closure
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

                case is_object($comparable) && $comparable instanceof \DateTime:
                    $valueDateTime = $this->toDateTime($value);

                    if (!$valueDateTime) {
                        return false;
                    }

                    return $valueDateTime <= $comparable;

                default:
                    return $value <= $comparable;
            }
        };
    }

    /**
     * In array or In String
     */
    public function isIn(mixed $value, mixed $comparable): \Closure
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
     * Not In Array or Not In String
     */
    public function isNotIn(mixed $value, mixed $comparable): \Closure
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
     */
    public function isNull(mixed $value, bool $comparable): \Closure
    {
        return function () use ($value, $comparable)
        {
            return $comparable ? is_null($value) : !is_null($value);
        };
    }

    /**
     * Is empty string or empty array.
     */
    public function isEmpty(mixed $value, bool $comparable): \Closure
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
     */
    public function isRegExMatch(mixed $value, string $comparable): \Closure
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
     */
    public function contains(mixed $value, mixed $comparable): \Closure
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
     */
    public function notContains(mixed $value, mixed $comparable): \Closure
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
     * @throws \Exception
     */
    private function toDateTime(mixed $value): ?\DateTime
    {
        if (is_object($value) && $value instanceof \DateTime) {
            return $value;
        } elseif (is_object($value) && $value instanceof \stdClass && isset($value->date) && isset($value->timezone_type) && isset($value->timezone)) {
            $dateTime = (new \DateTime($value->date, new \DateTimeZone($value->timezone)));

            return $dateTime ? $dateTime : null;
        } elseif (is_int($value)) {
            return (new \DateTime())->setTimestamp($value);
        } elseif (is_string($value)) {
            $dateTime = date_create($value);

            return $dateTime ? $dateTime : null;
        } else {
            return null;
        }
    }
}
<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonStorage\Exception\{DocumentNotFoundException, ResultSetException};

/**
 * Result
 *
 * A collection of Documents returned from a Query.
 */
abstract class Result implements \IteratorAggregate, \ArrayAccess, \JsonSerializable, \Countable
{
    protected array $ids;

    protected int $total;

    protected bool $assoc = false;

    protected array $fields = [];

    public function __construct(array $ids = [], array $fields = [], int $total = 0, bool $assoc = false)
    {
        $this->ids = $ids;
        $this->total = $total;
        $this->assoc = $assoc;
        $this->fields = $fields;
    }

    /**
     * How many documents are in the ResultSet.
     */
    public function count(): int
    {
        return count($this->ids);
    }

    /**
     * How many documents were there (before limit or skip was apply'ed)
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * Returns an document with an ID from the result set.
     *
     * @throws DocumentNotFoundException
     */
    abstract public function document(string $id): array|\stdClass;

    /** @return DocumentIterator */
    #[\ReturnTypeWillChange]
    abstract public function getIterator();

    #[\ReturnTypeWillChange]
    abstract public function offsetGet($offset);

    public function getIds(): array
    {
        return $this->ids;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->ids[$offset]);
    }

    public function offsetSet($offset, $value): void
    {
        throw new ResultSetException("It is not possible to write to the result set.");
    }

    public function offsetUnset($offset): void
    {
        unset($this->ids[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'ids' => $this->ids,
            'fields' => $this->fields,
            'total' => $this->total,
            'assoc' => $this->assoc
        ];
    }
}
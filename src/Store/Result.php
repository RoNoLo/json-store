<?php

namespace RoNoLo\JsonStorage\Store;

use RoNoLo\JsonStorage\Exception\DocumentNotFoundException;
use RoNoLo\JsonStorage\Exception\ResultSetException;
use RoNoLo\JsonStorage\Result as AbstractResult;
use RoNoLo\JsonStorage\Store;

/**
 * Result
 *
 * A collection of Documents returned from a Query.
 *
 * To save memory the Result only contains the document IDs. On request the store
 * requests the full JSON document and returns it.
 */
class Result extends AbstractResult
{
    protected Store $store;

    public static function fromJson(Store $store, array $data = []): static
    {
        // Setup defaults
        $data += [
            "ids" => [],
            "fields" => [],
            "total" => 0,
            "assoc" => false
        ];

        $ids = $data['ids'];
        $fields = $data['fields'];
        $total = $data['total'];
        $assoc = $data['assoc'];

        return new self($store, $ids, $fields, $total, $assoc);
    }

    protected function __construct(Store $store, array $ids = [], array $fields = [], int $total = 0, bool $assoc = false)
    {
        $this->store = $store;

        parent::__construct($ids, $fields, $total, $assoc);
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
    public function document(string $id): array|\stdClass
    {
        if (!in_array($id, $this->ids)) {
            throw new DocumentNotFoundException("No documentwith ID " . $id . " was found in result set.");
        }

        $id = [$id];
        return (new DocumentIterator($this->store, $id, $this->fields, $this->assoc))->current();
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    /** @return DocumentIterator */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new DocumentIterator($this->store, $this->ids, $this->fields, $this->assoc);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->ids[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        $id = [$this->ids[$offset]];
        return (new DocumentIterator($this->store, $id, $this->fields, $this->assoc))->current();
    }

    public function offsetSet($offset, $value): void
    {
        throw new ResultSetException("It is not possible to write to the result set.");
    }

    public function offsetUnset($offset): void
    {
        unset($this->ids[$offset]);
    }
}
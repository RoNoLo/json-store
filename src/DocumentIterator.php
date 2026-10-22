<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonQuery\JsonQuery;

abstract class DocumentIterator implements \Iterator
{
    protected array $ids;

    protected int $idx = 0;

    protected array $fields;

    protected bool $assoc;

    public function __construct(array &$ids, array $fields = [], bool $assoc = false)
    {
        $this->ids = $ids;
        $this->fields = $fields;
        $this->assoc = $assoc;
    }

    /**
     * Return the current element
     */
    #[\ReturnTypeWillChange]
    abstract public function current();

    /**
     * Move forward to next element.
     */
    public function next(): void
    {
        $this->idx++;
    }

    /**
     * Return the key of the current element.
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->ids[$this->idx];
    }

    /**
     * Checks if current position is valid.
     */
    public function valid(): bool
    {
        return isset($this->ids[$this->idx]);
    }

    /**
     * Rewind the Iterator to the first element.
     */
    public function rewind(): void
    {
        $this->idx = 0;
    }

    /**
     * Reduces the document fields, if requested.
     */
    protected function reduceFields(\stdClass|array $document): \stdClass|array
    {
        if (!count($this->fields)) {
            return $this->assoc ? json_decode(json_encode($document), true) : $document;
        }

        $jsonQuery = JsonQuery::fromData($document);

        $doc = [];
        foreach ($this->fields as $to => $from) {
            if (is_numeric($to)) {
                $to = $from;
            }

            $doc[$to] = $jsonQuery->query($from);
        }

        return $this->assoc ? $doc : json_decode(json_encode($doc));
    }
}

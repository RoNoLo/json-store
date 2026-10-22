<?php

namespace RoNoLo\JsonStorage;

use RoNoLo\JsonQuery\JsonQuery;
use RoNoLo\JsonStorage\Query\QueryExecuter;

/**
 * Query
 *
 * Builds an executes a query which searches and sorts documents from a
 * repository.
 */
abstract class Query
{
    protected ?\Closure $conditions = null;

    protected array $fields = [];

    protected int $skip = 0;

    protected int $limit = PHP_INT_MAX;

    protected ?array $sort = null;

    public function find(array $input): static
    {
        $this->parseInput($input);

        return $this;
    }

    /**
     * Modifies the document on the fly.
     *
     * There are a few syntax options. You can say, which data to keep,
     * which to delete and which to rewrite. The order of calling this method
     * matters. You can call this method more then once. It will processed
     * in that order.
     *
     * To COPY key/values:
     * ->fields(["to" => "from.here", "bernd" => "foo.moo.boo"]);
     * To KEEP only specific keys:
     * ->fields(["name", "age", "color"]);
     */
    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Sets the field to sort by and direction.
     */
    public function sort(?string $field = null, string $direction = "asc"): static
    {
        $this->sort = [$field => $direction];

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function skip(int $skip): static
    {
        $this->skip = $skip;

        return $this;
    }

    public function match(JsonQuery $jsonQuery): bool
    {
        return ($this->conditions)($jsonQuery);
    }

    abstract public function execute(bool $assoc = false);

    /**
     * Parsing the given JSON like query into an execution tree
     * of Closures.
     */
    protected function parseInput(array $conditions): void
    {
        $this->conditions = (new QueryExecuter())->parse($conditions);
    }
}

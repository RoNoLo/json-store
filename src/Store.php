<?php

namespace RoNoLo\JsonStorage;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\StorageAttributes;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use RoNoLo\JsonStorage\Exception\{DocumentNotFoundException, DocumentNotStoredException};
use RoNoLo\JsonStorage\Store\DocumentIterator;
use RoNoLo\JsonStorage\Store\StoreConfig;

/**
 * Store
 *
 * Analogous to a table in a traditional RDBMS, a store is a collection where documents live.
 */
class Store
{
    const STORE_INDEX_FILE = '__index.json';

    protected Filesystem $flysystem;

    protected StoreConfig $config;

    protected array $index = [];

    public static function create(StoreConfig $config): static
    {
        return new static($config);
    }

    protected function __construct(StoreConfig $config)
    {
        $this->config = $config;
        $this->flysystem = new Filesystem($config->getAdapter());

        // Init or rebuild the Index.
        if (!$this->flysystem->has(self::STORE_INDEX_FILE)) {
            $this->rebuildIndex();
        }

        $indexJson = $this->flysystem->read(self::STORE_INDEX_FILE);
        $this->index = json_decode($indexJson, true) ?: [];
    }

    /**
     * This saves the index to the flysystem.
     */
    public function __destruct()
    {
        try {
            if (count($this->index)) {
                $this->flysystem->write(self::STORE_INDEX_FILE, json_encode(array_values($this->index)));
            } else {
                if ($this->flysystem->has(self::STORE_INDEX_FILE)) {
                    $this->flysystem->delete(self::STORE_INDEX_FILE);
                }
            }
        } catch (FilesystemException $e) {
            // Silently ignore during shutdown
        }
    }

    /**
     * Tells if a document exists.
     */
    public function has(string $id): bool
    {
        $path = $this->getPathForDocument($id);

        return $this->flysystem->has($path);
    }

    /**
     * Stores many documents to the store.
     *
     * @return array Of IDs
     *
     * @throws DocumentNotStoredException
     */
    public function putMany(array $documents): array
    {
        // This will force an array as root
        $documents = json_decode(json_encode($documents));

        if (!is_array($documents)) {
            throw new DocumentNotStoredException("Your data was not an array of objects. (To store objects use ->put() instead.)");
        }

        $ids = [];
        foreach ($documents as $document) {
            $ids[] = $this->put($document);
        }

        return $ids;
    }

    /**
     * Stores a document or data structure to the store.
     *
     * It has to be a single document i.e. a \stdClass after converting it via json_encode().
     *
     * @throws DocumentNotStoredException
     */
    public function put(\stdClass|array $document): string
    {
        // This will force an stdClass object as root
        $document = json_decode(json_encode($document));

        if (!is_object($document)) {
            throw new DocumentNotStoredException("Your data was not an single object. (Maybe an array, you may use ->putMany() instead.)");
        }

        if (!isset($document->__id)) {
            $id = $this->generateId();
            $document->__id = $id;
        } else {
            $id = $document->__id;
        }

        $path = $this->getPathForDocument($id);
        $json = json_encode($document, defined('STORE_JSON_OPTIONS') ? intval(constant('STORE_JSON_OPTIONS')) : 0);

        try {
            $this->flysystem->write($path, $json);
        } catch (FilesystemException $e) {
            throw new DocumentNotStoredException(
                sprintf(
                    "The document could not be stored. Writing to flysystem-adapter `%s` failed.",
                    get_class($this->config->getAdapter())
                ),
                0,
                $e
            );
        }

        $this->index[] = $id;

        return $id;
    }

    /**
     * Reads a document from the store.
     *
     * @param string $id
     * @param bool|null $assoc Will be used for json_decode's 2nd argument.
     *
     * @return mixed
     *
     * @throws DocumentNotFoundException
     */
    public function read(string $id, ?bool $assoc = false): mixed
    {
        $path = $this->getPathForDocument($id);

        try {
            $json = $this->flysystem->read($path);

            if (is_null($assoc)) {
                return $json;
            }

            return json_decode($json, !!$assoc);
        }
        catch (UnableToReadFile | FilesystemException $e) {
            throw new DocumentNotFoundException(sprintf("Document with id `%s` not found.", $id), 0, $e);
        }
    }

    /**
     * Reads documents from the store.
     *
     * @param array $ids
     * @param bool $assoc Will be used for json_decode's 2nd argument.
     * @param bool $check If false, no documents exists check will be executed in advance, just the Iterator will be created.
     *
     * @return DocumentIterator
     */
    public function readMany(array $ids, bool $assoc = false, bool $check = true): DocumentIterator
    {
        if (!$check) {
            return new DocumentIterator($this, $ids, [], $assoc);
        }

        $existIds = [];
        foreach ($ids as $id) {
            if ($this->has($id)) {
                $existIds[] = $id;
            }
        }

        return new DocumentIterator($this, $existIds, [], $assoc);
    }

    /**
     * Removes a document from the store.
     *
     * @param string $id
     */
    public function remove(string $id): void
    {
        try {
            foreach (array_keys($this->index, $id) as $key) {
                unset($this->index[$key]);
            }

            $this->flysystem->delete($this->getPathForDocument($id));

            // Directory cleanup
            $documentDirectory = $this->getPathForDocument($id, 2);

            $contents = $this->flysystem->listContents($documentDirectory)->toArray();

            if (count($contents)) {
                return;
            }

            $this->flysystem->deleteDirectory($documentDirectory);

            $documentGroupDirectory = $this->getPathForDocument($id, 1);

            $contents = $this->flysystem->listContents($documentGroupDirectory)->toArray();

            if (!count($contents)) {
                $this->flysystem->deleteDirectory($documentGroupDirectory);
            }
        } catch (FilesystemException $e) {
            ;
        }
    }

    /**
     * Removes many documents from the store.
     *
     * @param array $ids
     *
     * @return void
     */
    public function removeMany(array $ids): void
    {
        foreach ($ids as $id) {
            $this->remove($id);
        }
    }

    /**
     * Removes all documents from the store.
     *
     * @return void
     */
    public function truncate(): void
    {
        $contents = $this->flysystem->listContents('', false);

        foreach ($contents as $content) {
            if ($content->isDir()) {
                $this->flysystem->deleteDirectory($content->path());
            } elseif ($content->isFile() && $content->path() !== self::STORE_INDEX_FILE) {
                $this->flysystem->delete($content->path());
            }
        }

        $this->index = [];
    }

    public function count(): int
    {
        return count($this->index);
    }

    /**
     * Returns all documents for further processing.
     *
     * @return \Generator
     */
    public function documentsGenerator(): \Generator
    {
        foreach ($this->index as $id) {
            yield $this->flysystem->read($this->getPathForDocument($id));
        }
    }

    public function rebuildIndex(): void
    {
        $index = [];
        foreach ($this->flysystem->listContents('', true) as $content) {
            if (!$content->isFile()) {
                continue;
            }
            if (pathinfo($content->path(), PATHINFO_EXTENSION) !== 'json') {
                continue;
            }
            if (pathinfo($content->path(), PATHINFO_BASENAME) === self::STORE_INDEX_FILE) {
                continue;
            }

            $documentJson = $this->flysystem->read($content->path());
            $document = json_decode($documentJson);
            if (isset($document->__id)) {
                $index[] = $document->__id;
            }
        }

        $this->flysystem->write(self::STORE_INDEX_FILE, json_encode($index));
    }

    /**
     * Get the filesystem path for a document based on it's ID.
     *
     * @param string $id The ID of the document.
     * @param int $parts For internal reasons, this will limit the return value.
     *
     * @return string The full filesystem path of the document.
     */
    protected function getPathForDocument(string $id, int $parts = 3): string
    {
        $first = substr($id, 0, 1);
        $second = substr($id, 0, 2);
        $filename = $id . '.json';

        switch ($parts) {
            default:
            case 3:
                return $first . '/' . $second . '/' . $filename;
            case 2:
                return $first . '/' . $second;
            case 1:
                return $first;
        }
    }

    /**
     * Generates a random, unique ID for a document.
     *
     * @return string The generated ID.
     * @throws DocumentNotStoredException
     */
    protected function generateId(): string
    {
        $breaker = 10;
        while ($breaker) {
            $id = strrev(str_replace('.', '', uniqid('', true)));
            $path = $this->getPathForDocument($id);

            if (!$this->flysystem->has($path)) {
                return $id;
            }

            $breaker--;
        }

        throw new DocumentNotStoredException("It was not possible to generate a unique ID for the document (tried 10 times).");
    }
}

# Json-Store

A document store which uses any type of filesystem to store documents as JSON.
It uses https://flysystem.thephpleague.com/ to abstract the storage space.

As by definition a *store* acts like ONE table in a database. You can put 
everything into one store or have many stores for different JSON objects.

It uses a NoSQL like query system for the documents and aims to use very low 
memory footprint (aka not loading all documents into memory to process them).

Note: There is a ronolo/json-database package, which uses the json-store and
extends it with document relations (foreign keys) and query result caching. 

## Usage

First create a Config object.

Then specify the adapter which shall be used to actually store the JSON files to
a disc/cloud/memory/zip. See https://github.com/thephpleague/flysystem 
to find the one which fits your needs. You have to init the adapter with the 
correct parameters. 

```php
// First create the config object
$config = new Store\Config();
// Set the the adapter
$config->setAdapter(new Local('some/path/persons'));
// Secondly create the JsonDB
$store = new Store($config);
```

The store is now ready. We can now store, read, delete and update documents. As a very
basic usage, we can read every document back by ID.

Note: Update is always an update of the whole object. It is not possible to update single
fields via store command.

As a speed bonus the store keeps all document IDs in an index file, which will be loaded
on store construct. Another speed bonus would be to use the caching and speedup adapters
found on the https://flysystem.thephpleague.com/v1/docs/advanced/caching/ page.

```php
$document = file_get_contents('file/with/json/object.json');
// store a document
$id = $store->put($document);
// read a document
$document = $store->read($id);
// update document
$document->foobar = "Heinz";
$store->put($document);
// remove document
$store->remove($id); 
```

It is also possible to query documents in a CouchDB like fashion from the store.

```php
$query = new Store\Query($store);
$result = $query->find([
    "name" => "Bernd"
]);

// An iterator can be used to fetch one by one all documents

foreach ($result as $id => $document) {
    ; // do something with the document
}
```

There are the following conditions implemented:

```php
[
    '$eq' => 'isEqual',
    '$neq' => 'isNotEqual',
    '$gt' => 'isGreaterThan',
    '$gte' => 'isGreaterThanOrEqual',
    '$lt' => 'isLessThan',
    '$lte' => 'isLessThanOrEqual',
    '$in'    => 'isIn',
    '$nin' => 'isNotIn',
    '$null' => 'isNull',
    '$n' => 'isNull',
    '$notnull' => 'isNotNull',
    '$nn' => 'isNotNull',
    '$contains' => 'contains',
    '$c' => 'contains',
    '$ne' => 'isNotEmpty',
    '$e' => 'isEmpty',
    '$regex' => 'isRegExMatch',
]
```
Examples can be found in the subdirectories of tests/src/query.

A few examples from there:

```php
// SELECT * FROM store WHERE age = 20 OR age = 30 OR age = 40;
$query = new Store\Query($store);
$result = $query
    ->find([
        ["age" => 20],
        ["age" => 30],
        ["age" => 40]
    ])
    ->execute()
;

// SELECT index, guid FROM store ORDER BY index ASC LIMIT 60;
$query = new Store\Query($store);
$result = $query
    ->find([])
    ->fields(["index", "guid"])
    ->sort("index", "asc")
    ->limit(60)
    ->execute()
;

// SELECT * FROM store WHERE age = 20 AND phone = '12345' OR age = 40;
$query = new Store\Query($store);
$result = $query
    ->find([
        '$or' => [
            [
                "age" => [
                    '$eq' => 20,
                ],
                "phone" => [
                    '$eq' => "12345",
                ]
            ],
            [
                "age" => [
                    '$eq' => 40
                ]
            ]
        ]
    ])
    ->execute()
;
```

## Goals

- No real database needed like SqlLite, Mysql, MongoDB, CouchDB ...)
- PHP 7.2+
- Document Store aka NoSQL
- JSON as format of storage
- (very) low memory usage even for huge results
- NoSQL like query syntax (CouchDB style)
- Abstract data location via https://flysystem.thephpleague.com/

## Limitations

- Any limitation the underlying flysystem adapter has
- If the creation of an unique ID fails, there will be an exception 
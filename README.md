# JsonStorage

*Warning: Heavy development! Do not use below 1.0.0!*

A document store which uses any type of filesystem to store documents as JSON.
It uses https://flysystem.thephpleague.com/ to abstract the storage space.

It uses a NoSQL like query system for the documents and aims to use very less 
memory (aka not loading all documents into memory to process them).

## Usage

First create a Config, which tells the store what adapter shall be used.

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

As a speed bonus the store keeps all document IDs in an index file, which will be loaded
on store construct. 

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

## Goals

- No real database needed like SqlLite, Mysql, MongoDB, CouchDB ...)
- PHP 7.2+
- Document Store aka NoSQL
- JSON as format of storage
- (very) low memory usage even for huge results
- NoSQL like query syntax (CouchDB style)
- Abstract data location via https://flysystem.thephpleague.com/
- Someday: Symfony/Laravel bundle

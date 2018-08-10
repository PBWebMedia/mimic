# Mimic

[![pipeline status](https://gitlab.pbwdev.com/symfony-bundles/mimic/badges/master/pipeline.svg)](https://gitlab.pbwdev.com/symfony-bundles/mimic/commits/master)
[![coverage report](https://gitlab.pbwdev.com/symfony-bundles/mimic/badges/master/coverage.svg)](https://pages.pbwdev.com/symfony-bundles/mimic/)

This library can be used to create mimics of your classes, which can be used for (functional) testing.

A mimicked class is similar to mock objects with stub methods in phpunit, but on a functional level.

## Installation

### Install with composer

Install mimic using composer:

```
composer require pbweb/mimic
```

## Example

Lets say you have a client class which talks to an external REST service. The interface might look like this:

```php
interface RestClient
{
    public function get($something);
    public function put($something);
}
```

Now, using a class which really connects to the REST server in a (functional) test is a bad idea, since that server may not be in the scope of your test and influence the results.

To create a mimic client you need to extend `MimicActionHandler` like this:

```php
class MimicRestClient extends MimicActionHandler implements RestClient
{
    public function get($something)
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }
    
    public function put($something)
    {
        return $this->handleAction(__FUNCTION__, func_get_args());
    }
}
```

Now you can mimic the behaviour of the `RestClient` in your tests:

```php
// In your dependency injection container:
$mimicClient = new MimicRestClient();

// In your test setup:
$mimicClient->enqueue('get', ['cheese'], 'cheese result');

// In your test or in a class which you are testing:
$result = $mimicClient->get('cheese'); // returns 'cheese result'
```

#### Argument Matchers
In case you want more control over the arguments that are expected for a call, you can use argument matchers.

The `ArgumentMatchers` class instantiates several matchers which can be passed to the expected argument list of enqueue.
```php
// Matches any call to update that has 1 as its first argument and any value as its second argument.
$mimicClient->enqueue('update', [1, ArgumentMatchers::any(), 'result');
```

## Usage

Extending `MimicActionHandler` will allow your class to have the mimic enqueue system.
Every method you want to mimic should have this as the body:

```php
return $this->handleAction(__FUNCTION__, func_get_args());
```

See `tests/Service/SampleMimic` for an example.

### enableQueue

```php
$mimic->enableQueue();
```

`enableQueue` will enable the use of the queue.

### disableQueue

```php
$mimic->disableQueue();
```

`disableQueue` will stop the use of the queue.

### isQueueEnabled

```php
$isQueueEnabled = $mimic->isQueueEnabled();
```

`isQueueEnabled` will return a boolean value with the state of the queue.

### enqueue

```php
$mimic->enqueue($method, array $argumentList = [], $response = null, $throw = false);
```
    
`enqueue` will add a method call to the expected queue.
You can add as many method prediction as you like.
If the next call to the mimic is the expected call then the given response will be returned and it will be removed from the queue.
If the next call to the mimic is not the same as the expected call added to the queue then an exception will be thrown.

If you set `throw` to `true` then the response will be thrown instead of returned.

### getQueueContent

```php
$actionList = $mimic->getQueueContent();
```
    
`getQueueContent` will return all the remaining actions added to the queue as Action models.
See the `Action` class for more information about the model.

### isFinished

```php
$isFinished = $mimic->isFinished();
```
    
`isFinished` will return true if there are no more action left in the queue. false otherwise.

### clearQueue
    
```php
$mimic->clearQueue();
```
    
`clearQueue` will remove all the remaining actions from the queue.


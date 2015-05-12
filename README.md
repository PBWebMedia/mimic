# PBWeb Mimic

Allows creation of Mimic versions of your classes.

# Installation

## Install with composer

Add the following line in the `"require"` section in your `composer.json`:

    "pbweb/mimic": "dev-master"

then, ask composer to install it:

    composer update pbweb/mimic


# Usage

Extending `MimicActionHandler` will allow your class to have the mimic enqueue system.
Every method you want to mimic should have this as the body:

```php
    return $this->handleAction(__FUNCTION__, func_get_args());
```

See `SampleMimic` for an example.

## enableQueue

    Mimic->enableQueue();

`enableQueue` will enable the use of the queue.

## disableQueue

    Mimic->disableQueue();

`disableQueue` will stop the use of the queue.

## isQueueEnabled

    $isQueueEnabled = Mimic->isQueueEnabled();

`isQueueEnabled` will return a boolean value with the state of the queue.

## enqueue

    Mimic->enqueue($method, array $argumentList = [], $response = null, $throw = false);
    
`enqueue` will add a method call to the expected queue.
You can add as many method prediction as you like.
If the next call to the mimic is the expected call then the given response will be returned and it will be removed from the queue.
If the next call to the mimic is not the same as the expected call added to the queue then an exception will be thrown.

If you set `throw` to `true` then the response will be thrown instead of returned.

## getQueueContent

    $actionList = Mimic->getQueueContent()
    
`getQueueContent` will return all the remaining actions added to the queue as Action models.
See the `Action` class for more information about the model.

## isFinished

    $isFinished = Mimic->isFinished()
    
`isFinished` will return true if there are no more action left in the queue. false otherwise.

## clearQueue
    
    Mimic->clearQueue()
    
`clearQueue` will remove all the remaining actions from the queue.

Arbiter-Node
============

AbiterJS was created by [Matt Kruse](http://www.mattkruse.com). I take no
credit for creating this work.

[Arbiter.js](http://www.arbiterjs.com) is a light-weight, library-agnostic JavaScript implementation of the [pub/sub pattern](http://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern), written by Matt Kruse. It allows objects on your page to be de-coupled, and communicate with each other through messages. This leads to a cleaner, more easily understood design, and easier maintenance.

For example, if the user changes a value on one part of the page, it can publish a message saying which action was taken. Other parts of the page can subscribe to that action, and do something when it happens. But the first interaction doesn't have to know anything about the second. It just announces what happened, and anyone who cares can act on it.

##Example

    // I want to do something when new mail arrives
    Arbiter.subscribe("email/new", mail_arrival);

    // Listens for any email related event
    Arbiter.subscribe("email/*", mail_process);

    // Listens for any published message for logging purposes
    Arbiter.subscribe("*", console.log.bind(console));

    // This code should be called ed by the system that detects incoming email
    Arbiter.publish("email/new", {from:"Bob"});

## Documentation
This is a complete description of the Arbiter API.

### Message Format
At the heart of Abriter is the message format. A message can be any format but cannot contain whitespave, commas, or asterisk. Each of these have special meaning for subscribers. A structure like `a/b/c` is recommended by convention to allow messages to be categorized.

A subscriber can end their message with an asterisk (\*). This message format will match any published message that has the same subscribers message format until the asterisk. For example `email/*` will match `email/new` and `email/send`. This can be useful for handling all messages of a certain component or category. If you take care when naming your messages, using wildcards can help avoid subscribing to multiple individual messages and needing to update as new messages are added.

Subscribers can also subscribe to multiple evens by seperating them by a comma and a space.

    Arbiter.subscribe( 'component/msg, component/msg2', function() { } );

### Method Summary

#### Arbiter.publish( msg [, data [, options] ] )

Publishes `data` to all subscribers of the `msg`. This method returns true on success, false if any subscriber has thrown a JavaScript exception.

    Arbiter.publish( 'component/msg', {"data": "value"} );

##### Options
Options is a JavaScript object that accepts these options.

`cancelable`: By default, subscribers can return "false" to prevent subsequent subscribers from receiving the message. By passing cancelable:false in the options, the publisher can prevent canceling.
`persist`: By default, subscribers only receive notifications about messages sent after they subscribe. But for some events, like "system initalized" that may fire only once, it can be useful to allow subscribers to that message to get fired if the message has already been sent. If the publishers wants subscribers to be notified of this message even if they subscribe later, setting the persist flag will do that.
`async`: By default, subscribers are notified and their functions are run synchronously, so the publish() function doesn't return until all subscribers have finished. If you wish to notify the subscribers but return from the publish() call before the subscriber functions execute, use asynchronous mode. Note: Subscribers cannot cancel asynchonous messages, because the subscribers are executed independently using setTimeout()

    //The default options
    Arbiter.publish( 'component/msg', "ready", { canceleable: true, persist: false, async: false } );

#### Arbiter.subscribe( msg, [, options, [context] ], func )

Executes `func` when a message matches the message format `msg`. `msg` can also be an array of messages specified to the Message Format.

##### Options
Options is a JavaScript object that accepts these options.

`async`: A subscriber can be set to execute asynchronously, even if the message wasn't published as async. If a subscriber knows that it will do some heavy calculations, for example, it can force itself to be async so it won't interfere with the execution of other subscribers.
`persist`: If a message was persisted, a subscriber will be notified of it even if was sent in the past. If your subscriber is not interested in any past messages that may have been persisted, you can force them to be ignored.
`priority`: By default, all subscribers have a priority of 0. Higher values get higher priority and are executed first. Negative values are allowed.

    //The default options
    Arbiter.publish( 'component/msg', "ready", { canceleable: true, persist: false, async: false, priority: 0 } );

##### context
The value of `this` inside `func` can be changed by using the context.

##### func( published_data, message, subscriber_data)
 * published_data: Data that the publisher provided
 * message: The message that triggered the notification (useful if a handler is used for multiple messages)
 * subscriber_data: An initally empty object that will be passed between all subscribers.

#### Arbiter.unsubscribe( subscription_id )
Unsubscribing simply sets a flag which prevents the subscriber from executing, in case you want to re-subscribe later.

    var subscription_id = Arbiter.subscribe( 'msg', function(){} );
    Arbiter.unsubscribe( subscription_id );

#### Arbiter.resubscribe( subscription_id )
After unsubscribing, you can later re-subscribe to begin receiving messages again.

    var subscription_id = Arbiter.subscribe( 'msg', function(){} );
    Arbiter.unsubscribe( subscription_id );
    Arbiter.resubscribe( subscription_id );

#### Arbiter.create()
This creates a separate Arbiter instance. If you want to have different message handlers entirely, for example, this will allow for that. Messages sent to the new object will not be shared with the default Arbiter object. You may create as many arbiters as you wish, and they will all operate independently.

    var MyController = Arbiter.create()

## License
This work is in the public domain and may be used in any way, for any purpose, without restriction.


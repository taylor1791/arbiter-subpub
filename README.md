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
At the heart of Abriter is the message format. A message can be any format but cannot contain whitespave, commas, or asterisk. Each of these have special meaning for subscribers. A structure like "a/b/c" is recommended by convention to allow messages to be categorized.

A subscriber can end their message with an asterisk \(*\). This message format will match any published message that has the same subscribers message format until the asterisk. For example `email/*` will match `email/new` and `email/send`.

Subscribers can also subscribe to multiple evens by seperating them by a comma and a space

    Arbiter.subscribe( 'component/msg, component/msg2', function() { } );

## Method Summary

### Arbiter.publish( msg [, data [, options] ] )

Publishes `data` to all subscribers of the `msg`. This method returns true on success, false if any subscriber has thrown a JavaScript exception.

    Arbiter.publish( 'conponent/msg' );


### Arbiter.subscribe( msg, [, options, [context] ], func )

Executes `func` when a message matches the message format `msg`.

* options: asdfasdf
* context: asdfasdf
* func( published_data, message, subscriber_data )



async: A subscriber can be set to execute asynchronously, even if the message wasn't published as async. If a subscriber knows that it will do some heavy calculations, for example, it can force itself to be async so it won't interfere with the execution of other subscribers.
persist: If a message was persisted, a subscriber will be notified of it even if was sent in the past. If your subscriber is not interested in any past messages that may have been persisted, you can force them to be ignored.
context: When executing the subscriber function, the value of "this" in the function can be specified at subscription time.
priority: By default, all subscribers have a priority of 0. Higher values get higher priority and are executed first. Negative values are allowed.



Arbiter.subscribe( 'msg', {persist:false}, func(){} );
Arbiter.subscribe( 'msg', null, document.getElementById('x'),
                   function() {
                      this.innerHTML = "Message handled!";
                   }
                 );
Arbiter.subscribe( 'msg', {async:true}, func(){} );
Arbiter.subscribe( 'msg', {priority:10}, func(){} );
Arbiter.subscribe( 'msg', {priority:20}, func(){} ); // Called first!

### Arbiter.unsubscribe( subscription_id )
Unsubscribing simply sets a flag which prevents the subscriber from executing, in case you want to re-subscribe later.

    var subscription_id = Arbiter.subscribe( 'msg', function(){} );
    Arbiter.unsubscribe( subscription_id );

### Arbiter.resubscribe( subscription_id )
After unsubscribing, you can later re-subscribe to begin receiving messages again.

    var subscription_id = Arbiter.subscribe( 'msg', function(){} );
    Arbiter.unsubscribe( subscription_id );
    Arbiter.resubscribe( subscription_id );

### Arbiter.create()
This creates a separate Arbiter instance. If you want to have different message handlers entirely, for example, this will allow for that. Messages sent to the new object will not be shared with the default Arbiter object. You may create as many arbiters as you wish, and they will all operate independently.

    var MyController = Arbiter.create()


##License
This work is in the public domain and may be used in any way, for any purpose, without restriction.


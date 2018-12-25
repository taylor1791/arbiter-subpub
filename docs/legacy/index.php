<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<link rel="shortcut icon" href="favicon.ico" >
	<meta property="fb:page_id" content="316663535020759" />
	<meta name="title" content="Arbiter.js - A full-featured javascript pub/sub (Observer) implementation" />
	<meta name="description" content="Arbiter.js is a light-weight, library-agnostic javascript implementation of the pub/sub pattern, written by Matt Kruse. It allows objects on your page to be de-coupled, and communicate with each other through messages. This leads to a cleaner, more easily understood design, and easier maintenance. " />
	<link rel="image_src" type="image/gif" href="fb_share.gif" />
	<meta property="og:image" content="http://arbiterjs.com/fb_share.gif"/>
	<title>Arbiter.js - A full-featured javascript pub/sub (Observer) implementation by Matt Kruse</title>
<script src="jquery.js"></script>
<script src="Arbiter.js"></script>
<script>
$(function() {
	$('#version').html( Arbiter.version );
	$('#updated_on').html( Arbiter.updated_on );
});
</script>
<style>
* {
	font-family:verdana,arial,sans-serif;
}
#wrapper {
	width:850px;
	text-align:left;
}
#license {
	font-size:smaller;
	font-style:italic;
}
.section {
	margin-top:20px;
}
.section .title {
	background-color:#669;
	color:white;
	padding:5px;
	font-size:20px;
	font-weight:bold;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
}
.download_wrapper {
	float:right;
	width:250px;
	padding:20px 0 20px 20px;
	background-color:white;
}
.download {
	border:2px solid #343499;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	background-color:#CACAFC;
	padding:5px;
}

.source {
	margin-top:20px;
	border:1px solid #ccc;
	background-color:#eee;
	padding:3px;
}
.code, .code * {
	white-space:pre;
	font-family:courier new;
	font-size:12px;
	overflow:auto;
}
.comment {
	color:#999;
}
.doc-title {
	font-weight:bold;
	font-size:18px;
	margin:20px 0;
}
.doc-definition {
	margin-left:20px;
	font-style:italic;
}
.doc-returns {
	margin-left:20px;
	font-size:smaller;
	font-style:italic;
}
.doc-example {
	border:1px solid #ccc;
	background-color:#eee;
	padding:3px;
	margin:5px 0;
}
</style>
</head>
<body>

<center>
<div id="wrapper">

	<div class="header">
		<img src="logo.gif">
	</div>
	
	<div class="download_wrapper">
		<div class="download">
			Download source:
			<a href="Arbiter.js">Arbiter.js</a><br>
			Version: <span id="version"></span><br>
			Updated: <span id="updated_on"></span><br>
			<span id="license">This work is in the public domain and may be used in any way, for any purpose, without restriction.</span>
		</div>
		<br>
		Support:<br>
<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FArbiterjs%2F316663535020759&amp;width=210&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:210px; height:62px;" allowTransparency="true"></iframe>	</div>
	
	<div class="section">
		<div class="body">
			<p>
			<a href="https://www.google.com/search?q=define%3Aarbiter" target="_blank">Arbiter</a>.js is a light-weight, library-agnostic javascript implementation of the <a href="http://en.wikipedia.org/wiki/Publish%E2%80%93subscribe_pattern" target="_blank">pub/sub pattern</a>, written by <a href="http://MattKruse.com" target="_blank">Matt Kruse</a>. It allows objects on your page to be de-coupled, and communicate with each other through messages. This leads to a cleaner, more easily understood design, and easier maintenance.
			</p>
			<center><img src="example.gif"></center>
			<p>
			For example, if the user changes a value on one part of the page, it can publish a message saying which action was taken. Other parts of the page can subscribe to that action, and do something when it happens. But the first interaction doesn't have to know anything about the second. It just announces what happened, and anyone who cares can act on it.
			</p>
		</div>
	</div>

	<div class="section">
		<div class="title">Example</div>
		<div class="body">
			<p>A simple code example might look like this:</p>
			<div class="code">
// In the "notifications" widget, I want to do something when new mail arrives
Arbiter.subscribe("email/new", function(data) {
	document.getElementById('notification').innerHTML = "New email from "+data.from;
});

// This code is called by the system that detects incoming email
Arbiter.publish("email/new", {from:"Bob"});
			</div>
			<div id="console" style="font-size:10px;max-height:125px;overflow-y:auto;background-color:#ffffcc;border:2px solid #666; padding:3px;margin:5px;min-height:100px;float:right;width:600px;">
			<b>Console:</b><br>
			Arbiter.subscribe('click/*', null, document.getElementById('console'), function(data,msg) {
				this.innerHTML += msg+"&lt;br&gt;";
			});
			<hr>
			</div>
			<p>Here is a simple working example:</p>
<script type="text/javascript">
			Arbiter.subscribe('click/*', null, document.getElementById('console'), function(data,msg) {
				this.innerHTML += msg+"<br>";
			});
</script>
			<button onclick="Arbiter.publish('click/1')">Arbiter.publish('click/1')</button><br>
			<button onclick="Arbiter.publish('click/2')">Arbiter.publish('click/2')</button><br>
			<button onclick="Arbiter.publish('click/3')">Arbiter.publish('click/3')</button><br>
			<br style="clear:both;">
		</div>
	</div>

	<div class="section">
		<div class="title">Method Summary</div>
		<div class="body">
			<div class="doc-title">Arbiter.publish</div>
			<div class="code">Arbiter.publish( msg [, data [, options] ] )
Returns: true on success, false if any subscriber has thrown a js exception
			</div>

			<div class="doc-title">Arbiter.subscribe</div>
			<div class="code">Arbiter.subscribe( msg, func )
Arbiter.subscribe( msg, options, func )
Arbiter.subscribe( msg, options, context, func )
Returns: subscription id
         or [id1,id2] if subscribing to multiple messages
			</div>

			<div class="doc-title">Arbiter.unsubscribe</div>
			<div class="code">Arbiter.unsubscribe( subscription_id )</div>

			<div class="doc-title">Arbiter.resubscribe</div>
			<div class="code">Arbiter.resubscribe( subscription_id )</div>

			<div class="doc-title">Arbiter.create</div>
			<div class="code">Arbiter.create()</div>

		</div>
	</div>

	<div class="section">
		<div class="title">How To</div>
		<div class="body">
		
			<div class="doc-section">
				<div class="doc-title">Publish a simple message</div>
				<div class="doc-example code">Arbiter.publish( 'component/msg' );</div>
				<div class="doc-desc">A message may be in any format, but may not contain [ ,*]. A structure like a/b/c is recommended by convention, to allow messages to be categorized.
			</div>

			<div class="doc-section">
				<div class="doc-title">Subscribe to a message</div>
				<div class="doc-example code">Arbiter.subscribe( 'component/msg', function() { } );</div>
				<div class="doc-desc">Subscriber functions will be passed the following arguments:
					<ul>
						<li>published_data: Any data that the publisher has passed along
						<li>message: The message text that triggered the notification (useful if a subscriber function can handle multiple messages)
						<li>subscriber_data: An object (initially empty) that will be passed between subscribers. This may be useful if you would like subscribers to send context or additional data to subsequent subscribers
					</ul>
					The value of "this" to be used within the function may be set in the subscribe() method itself.
				</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Pass data to subscribers</div>
				<div class="doc-example code">Arbiter.publish( 'component/msg', {"data":"value"} );</div>
				<div class="doc-desc">Publishers can pass data to subscribers that contains details about the message.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Force message bubbling</div>
				<div class="doc-example code">Arbiter.publish( 'component/msg' , null, {cancelable:false} );</div>
				<div class="doc-desc">By default, subscribers can return "false" to prevent subsequent subscribers from receiving the message. By passing cancelable:false in the options, the publisher can prevent canceling.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Allow late susbcribers to be notified of past messages</div>
				<div class="doc-example code">Arbiter.publish( 'component/msg' , null, {persist:true} );</div>
				<div class="doc-desc">By default, subscribers only receive notifications about messages sent after they subscribe. But for some events, like "system initalized" that may fire only once, it can be useful to allow subscribers to that message to get fired if the message has already been sent. If the publishers wants subscribers to be notified of this message even if they subscribe later, setting the persist flag will do that.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Fire subscribers asynchronously</div>
				<div class="doc-example code">Arbiter.publish( 'component/msg', null, {async:true} );</div>
				<div class="doc-desc">By default, subscribers are notified and their functions are run synchronously, so the publish() function doesn't return until all subscribers have finished. If you wish to notify the subscribers but return from the publish() call before the subscriber functions execute, use asynchronous mode. <b>Note:</b> Subscribers cannot cancel asynchonous messages, because the subscribers are executed independently using setTimeout()</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Subscribe to multiple messages at once</div>
				<div class="doc-example code">Arbiter.subscribe( 'component/msg, component/msg2', function() { } );
		or
Arbiter.subscribe( ['component/msg','component/msg2'], function() { } );
</div>
				<div class="doc-desc">The second argument to the subscriber function is the message, so you can distinguish which messages you are handling.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Subscribe to multiple messages using a wildcard</div>
				<div class="doc-example code">Arbiter.subscribe( 'component/*', function() { } );</div>
				<div class="doc-desc">This can be useful for handling all messages of a certain component or category. If you take care when naming your messages, using wildcards can help avoid subscribing to multiple individual messages and needing to update as new messages are added.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Subscribe to ALL messages</div>
				<div class="doc-example code">Arbiter.subscribe( '*', function() { } );</div>
				<div class="doc-desc">This can be useful for logging, for example. You can create a separate message logger that receives all messages and displays them in a debug window.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Set subscriber priority</div>
				<div class="doc-example code">Arbiter.subscribe( 'msg', {priority:10}, func(){} );
Arbiter.subscribe( 'msg', {priority:20}, func(){} ); // Called first!
</div>
				<div class="doc-desc">By default, all subscribers have a priority of 0. Higher values get higher priority and are executed first. Negative values are allowed.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Execute a subscriber asynchronously</div>
				<div class="doc-example code">Arbiter.subscribe( 'msg', {async:true}, func(){} );</div>
				<div class="doc-desc">A subscriber can be set to execute asynchronously, even if the message wasn't published as async. If a subscriber knows that it will do some heavy calculations, for example, it can force itself to be async so it won't interfere with the execution of other subscribers.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Ignore persisted messages</div>
				<div class="doc-example code">Arbiter.subscribe( 'msg', {persist:false}, func(){} );</div>
				<div class="doc-desc">If a message was persisted, a subscriber will be notified of it even if was sent in the past. If your subscriber is not interested in any past messages that may have been persisted, you can force them to be ignored.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Set the value of "this"</div>
				<div class="doc-example code">Arbiter.subscribe( 'msg', null, document.getElementById('x'),
                   function() {
                      this.innerHTML = "Message handled!";
                   }
                 );
</div>
				<div class="doc-desc">When executing the subscriber function, the value of "this" in the function can be specified at subscription time.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Unsubscribe from messages</div>
				<div class="doc-example code">var subscription_id = Arbiter.subscribe( 'msg', function(){} );
Arbiter.unsubscribe( subscription_id );
</div>
				<div class="doc-desc">Unsubscribing simply sets a flag which prevents the subscriber from executing, in case you want to re-subscribe later.</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Re-subscribe to messages</div>
				<div class="doc-example code">var subscription_id = Arbiter.subscribe( 'msg', function(){} );
Arbiter.unsubscribe( subscription_id );
Arbiter.resubscribe( subscription_id );
</div>
				<div class="doc-desc">After unsubscribing, you can later re-subscribe to begin receiving messages again</div>
			</div>

			<div class="doc-section">
				<div class="doc-title">Create a new message handler</div>
				<div class="doc-example code">var MyController = Arbiter.create()</div>
				<div class="doc-desc">This creates a separate Arbiter instance. If you want to have different message handlers entirely, for example, this will allow for that. Messages sent to the new object will not be shared with the default Arbiter object. You may create as many arbiters as you wish, and they will all operate independently.</div>
			</div>

		</div>
	</div>

	<div class="section">
		<div class="title">Source</div>
		<div class="body code source">
<?
$file = file_get_contents('Arbiter.js');
$file = preg_replace('/\t/','   ',$file);
$file = preg_replace('/</','&lt;',$file);
$file = preg_replace('/>/','&gt;',$file);
$file = preg_replace('/([\s\t]\/\/.*)/','<span class="comment">$1</span>',$file);
echo $file;
?>
		</div>
	</div>

	

</div>
</center>

</body>
</html>

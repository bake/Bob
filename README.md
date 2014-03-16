Bob
===

Very basic routing class (about 105 lines) ...

Add a route:

	<?php
	Bob::get('/', function() {
		echo 'Hello World';
	});

Add a bunch of routes:

	<?php
	Bob::get(['/', '/home'], function() {
		echo 'Hello World';
	});

A little bit more:

	<?php
	Bob::get('/user/bob', function() {
		echo 'Hey, bob!';
	});

Use a function:

	<?php
	Bob::get('/user/:is_numeric', function($id) {
		echo 'Hello, '.$user[$id];
	});
	
Use multiple functions:

	<?php
	Bob::get('/user/:is_numeric', [function($id) {
		echo 'Hello, '.$user[$id];
	}, count_login($id)]);

Use an own function:

	<?php
	Bob::get('/user/:is_user', function($user) {
		echo 'Hey, '.$user.'!';
	});

	function is_user($user) {
		return in_array($user, [1, 2, 3]);
	}

Negate:

	<?php
	Bob::get('/user/:is_user', function($user) {
		echo 'Hey, '.$user.'!';
	});

	Bob::get('/user/!is_user', function($user) {
		echo 'Can\'t find this user :(';
	});

Multiple request methods:

	<?php
	Bob::add(['post', 'put'], '/user', function() {
		// Add a user! or something else! I don't care!
	});

Execute:

	<?php
	Bob::go();

Or - if you'd like to work in a subdirectory - trim the request url:

	<?php
	Bob::go('/foo/bar.php');

`http://localhost/foo/bar.php/user/1` `=>` `/user/1`

404:

	<?php
	Bob::summary(function($passed, $refused) {
		echo $passed.' routes passed, '.$refused.' were refused.';
	});

This will only execute the callback, if no rule matched the request:

	Bob::notfound(function($passed, $refused) {
		echo '404 :(';
	});

You're done.

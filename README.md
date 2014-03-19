Bob
===

Very basic routing class (about 105 lines) ...

tl;dr
-----

```php
<?php
Bob::get($pattern, $callback);
```

is short for

```php
<?php
Bob::add('get', $pattern, $callback);
```

`$method` and `$pattern` can either be strings or arrays of strings. `$callback`s require one or more functions.

```php
<?php
Bob::go($file);
```

Usage
-----

Add a route:

```php
<?php
Bob::get('/', function() {
	echo 'Hello World';
});
```

A little bit more:

```php
<?php
Bob::get('/user/bob', function() {
	echo 'Hey, bob!';
});
```

Add a bunch of patterns:

```php
<?php
Bob::get(['/', '/home'], function() {
	echo 'Hello World';
});
```

Use a function:

```php
<?php
Bob::get('/user/:is_numeric', function($id) {
	echo 'Hello, '.$user[$id];
});
```

Use an own function:

```php
<?php
Bob::get('/user/:is_user', function($user) {
	echo 'Hey, '.$user.'!';
});

function is_user($user) {
	return in_array($user, ['Justus', 'Peter', 'Bob']);
}
```

Negate:

```php
<?php
Bob::get('/user/:is_user', function($user) {
	echo 'Hey, '.$user.'!';
});

Bob::get('/user/!is_user', function($user) {
	echo 'Can\'t find this user :(';
});
```

Use multiple callbacks:

```php
<?php
Bob::get('/user/:is_numeric', [function($id) {
	echo 'Hello, '.$user[$id];
}, count_login($id)]);
```

Multiple request methods:

```php
<?php
Bob::add(['post', 'put'], '/user', function() {
	// Add a user! Or something else! I don't care!
});
```

Execute:

```php
<?php
Bob::go();
```

Or - if you'd like to work in a subdirectory - trim the request url:

```php
<?php
Bob::go('/foo/bar.php');
```

`http://localhost/foo/bar.php/user/1` `=>` `/user/1`

404:

```php
<?php
Bob::summary(function($passed, $refused) {
	echo $passed.' routes passed, '.$refused.' were refused.';
});
```

This will only execute the callback, if no rule matched the request:

```php
Bob::notfound(function($passed, $refused) {
	echo '404 :(';
});
```

You're done. Have a nice day.

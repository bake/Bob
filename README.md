# Bob

Very basic routing class (about 101 lines) ...

## tl;dr

```php
Bob::get($pattern, $callback);
```

is short for

```php
Bob::add('get', $pattern, $callback);
```

`$method` and `$pattern` can either be strings or arrays of strings. `$callback`s require one or more functions.

```php
Bob::go($file);
```

## Usage

Add a route:

```php
Bob::get('/', function() {
	echo 'Hello World';
});
```

A little bit more:

```php
Bob::get('/user/bob', function() {
	echo 'Hey, bob!';
});
```

Add a bunch of patterns:

```php
Bob::get(['/', '/home'], function() {
	echo 'Hello World';
});
```

Use a function:

```php
Bob::get('/user/:is_numeric', function($id) {
	echo 'Hello, '.$user[$id];
});
```

Use an own function:

```php
Bob::get('/user/:is_user', function($user) {
	echo 'Hey, '.$user.'!';
});

function is_user($user) {
	return in_array($user, ['Justus', 'Peter', 'Bob']);
}
```

Negate:

```php
Bob::get('/user/:is_user', function($user) {
	echo 'Hey, '.$user.'!';
});

Bob::get('/user/!is_user', function($user) {
	echo 'Can\'t find this user :(';
});
```

You can also use regex (in the same way you'd use a function):

```php
Bob::$patterns = [
	'num' => '[0-9]+',
	'all' => '.*'
];

Bob::get('/:num', function($id) {
	echo $id;
});
```

Use multiple callbacks:

```php
Bob::get('/user/:is_numeric', [function($id) {
	echo 'Hello, '.$user[$id];
}, count_login($id)]);
```

Multiple request methods:

```php
Bob::add(['post', 'put'], '/user', function() {
	// Add a user! Or something else! I don't care!
});
```

Execute:

```php
Bob::go();
```

Or - if you'd like to work in a subdirectory - trim the request url:

```php
Bob::go('/foo/bar.php');
```

`http://localhost/foo/bar.php/user/1` `=>` `/user/1`

404:

```php
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

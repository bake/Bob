# Bob

Very basic routing class ([about 103 lines](https://github.com/BakeRolls/Bob/blob/master/Bob.php#L103)) ...

## tl;dr

```php
Bob::get($pattern, $callback);
```

is short for

```php
Bob::add('get', $pattern, $callback);
```

`$method` and `$pattern` can either be strings or arrays of strings. `$callback`s are [one](#routes) or [more functions](#callbacks) or a [class](#a-class-as-callback).

```php
Bob::go($file);
```

## Usage

### Routes

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

### Callbacks

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

### A Class as Callback

Your can also use a class as callback. Just pass its name. Bob will try to execute `$method` on `$callback`, so something like this will work:

```php
class SayHello {
	static function get($num) {
		for($i = 0; $i < $num; $i++)
			echo 'GET Hello!';
	}

	static function post($num) {
		for($i = 0; $i < $num; $i++)
			echo 'POST Hello!';
	}
}
```

```php
Bob::add([], '/:is_numeric', 'SayHello');
```

Notice that you have to use `Bob::add()` with an empty array. In this case, you're not required to tell Bob the accepted HTTP methods, it'll just look inside the provided class.

### Execute

With the *use-an-own-function-example* from above, the ([second](#if-nothing-was-found)) final step could look like this:

```php
Bob::go();
```

Or - if you'd like to work in a subdirectory - trim the request url:

```php
Bob::go('/foo/bar.php');
```

`http://localhost/foo/bar.php/user/1` `=>` `/user/1`

### If nothing was found

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

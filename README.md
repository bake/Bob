Bob
===

Very basic routing class ...

	<?php
	$bob = new Bob();
	?>

Add a route:

	<?php
	$bob->get('/user/bob', function() {
		echo 'Hey, Bob!';
	});
	?>

Better:

	<?php
	$bob->get('/user/:is_user', function($user) {
		echo 'Hey, '.$user.'!';
	});

	function is_user($user) {
		return ($user == 'bob') ? true : false;
	}
	?>

Negate:

	<?php
	$bob->get('/user/!is_numeric', function($user) {
		echo 'ID PLZ!';
	});
	?>

Multiple request methods:

	<?php
	$bob->add(['post', 'put'], '/user/:is_user', function($user) {
		// delete user
	});
	?>

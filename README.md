# Bistro: Session

A Session library for PHP 5.3+. Allows different storage engines to be used.

## Engines

2 Storage engines are included in the library.

### Native

This driver uses the php native `$_SESSION` array to handle the session data.

``` php
$session = new \Bistro\Session\Native;

$timeout = $session->get('timeout');

if ($timeout === null)
{
	$session->set('timeout', time() + 43200);
}
```
### MockArray

There is also a MockArray storage engine to help with unit testing session data.
The MockArray session doesn't save state across "requests".

``` php
$data = array(
	'test' => "Session data",
	'goes' => "Here"
)

$session = new \Bistro\Session\MockArray($data);

$session->has('test'); // true
$missing = $session->get('missing'); // null
```

## Storage Interface

Check `\Bistro\Session\Session` for all of the public methods that are available
to a session storage engine.

<?php
/**
 * We have to do the session driver tests in the browser so we know it actually works...
 */

use \Bistro\Session\Native;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

include '../vendor/autoload.php';

$session = new Native;
$output = array();

switch($page)
{
	case 1:
		$session->clear();

		$data = $session->toArray();
		$output[] = array(
			'name' => "Empty Session Data",
			'result' => empty($data) ? 'Passed' : 'Failed'
		);

		$session->set('user', "Dave");
	break;
	case 2:
		$output[] = array(
			'name' => 'Session has user key',
			'result' => $session->has('user') === true ? "Passed" : 'Failed'
		);

		$output[] = array(
			'name' => "Session data was saved",
			'result' => $session->get('user') === 'Dave' ? "Passed" : 'Failed'
		);
	break;
	case 3:
		$old = $session->getId();
		$session->regenerate();
		$new = $session->getId();

		$output[] = array(
			'name' => "Regenerating Id's",
			'result' => $old !== $new ? "Passed" : "Failed"
		);

		$session->set('flash_data', "This test is cool!");
	break;
	case 4:
		$msg = $session->getOnce('flash_data');
		$output[] = array(
			'name' => "Testing flash data",
			'result' => $msg === 'This test is cool!' ? "Passed" : "Failed"
		);

		$output[] = array(
			'name' => "Get Once clears out key",
			'result' => $session->has('flash_data') === false ? "Passed" : "Failed"
		);
	break;
	case 5:
		$session->destroy();
		$output[] = array(
			'name' => 'Destroying Session',
			'result' => $session->isStarted() === false ? "Passed" : "Failed"
		);
	break;
	case 6:
		$data = $session->toArray();
		$output[] = array(
			'name' => 'Destorying session clears data',
			'result' => empty($data) === true ? "Passed" : "Failed"
		);
	break;
}

/**
 * Please don't mix HTML and PHP IRL, use real view classes!!
 */
?>
<!doctype html5>
<html lang="en">
<head>
	<title>Session Test</title>

	<style type="text/css">
		.unstyled { list-style: none; padding-left: 0; }
		.unstyled li { padding: 0.5em; margin-bottom: 0.5em; }
		.Passed { background-color: #EFE; }
		.Passed b { color: #090; }
		.Failed { background-color: #FEE; }
		.Failed b { color: #900; }
		.navigation { overflow: auto; }
		.navigation li { float: left; margin-right: 0.5em; }
	</style>
</head>
<body>
	<h1>Session Testing</h1>

	<h3>Output</h3>
	<ul class="unstyled">
<?php foreach ($output as $test) : ?>
		<li class="<?php echo $test['result']; ?>">
			<b><?php echo $test['result']; ?></b>
			<?php echo $test['name']; ?>
		</li>
<?php endforeach; ?>
	</ul>

	<ul class="navigation unstyled">
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>">&laquo; Start Over</a></li>
<?php if ($page < 6): ?>
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $page + 1; ?>">Next Test &raquo;</a></li>
<?php endif; ?>
	</ul>
</body>
</html>

<?php
declare(strict_types=1);
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "global.php";

use App\Controller\UserController;
use App\Db;
use App\ErrorJsonResponse;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

$actualLink = $_SERVER['REQUEST_URI'];
$parsedActualLink = explode('/', $actualLink);
$firstElement = $parsedActualLink[1];
$secondElement = $parsedActualLink[2] ?? "";
$logger = new Logger('monolog');
$logger->pushHandler(new StreamHandler('../logs/monolog.log', Level::Warning));

try {
    $dbConnectionDsnString = "mysql:host=" . MYSQL_SERVER_NAME . ";dbname=" . MYSQL_DATABASE;
    $pdo = new PDO($dbConnectionDsnString, MYSQL_USER, MYSQL_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Throwable $e) {
    throw new PDOException("Connection failed. Throwable name: '" . $e::class . "'. Message : " . $e->getMessage());
}

$db = new Db($pdo, $logger);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($firstElement == 'register') {
            (new UserController($logger, $db))->register();
        } elseif ($firstElement == 'login') {
            (new UserController($logger, $db))->login();
        }
    }

} catch (Exception $e) {
    echo new ErrorJsonResponse($e->getMessage());
    exit();
}

echo new ErrorJsonResponse("got invalid request URI:  $actualLink");
exit();

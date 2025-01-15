<?php 
use Bramus\Router\Router;
use Dotenv\Dotenv;
/*===============================
INCORPORANDO LA LIBRERIA VARIABLES 
DE ENTORNO
=================================*/

require_once 'storage/logs/php_errors.php';
require_once 'vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->load();

require_once 'app/config/setting.php';

require_once 'app/lib/Controls.php';

require_once 'public/fpdf/fpdf.php';/// reportes pdf => libreria fpdf
# requerimos el archivo router 
require_once 'autoload.php';
$route = new Router;
require 'routes/web.php';
require_once 'routes/Auth_route.php';
require_once 'routes/citas.php';
require_once 'routes/Config.php';
$route->run();
 
 


 
 
<?
// Hello world
require_once "vendor/autoload.php";
include_once 'core/core.php'; # Основные настройки
session_start();

// Обработка входящих параметров
$oRequest = json_decode(file_get_contents("php://input"));
if ( empty($oRequest) ) $oRequest = (object)$_REQUEST;
$sAction = strtolower($_SERVER['REQUEST_METHOD']);
if ( $oRequest->action ) $sAction = $oRequest->action;

// Навигация и доступы к страницам
$arrNavs = [
    '/' => array('title'=>'Home','access'=>-1),
    '/users/' => array('title'=>'Users','access'=>0),
    '/login/' => array('title'=>'Login','access'=>-1),
    '/registration/' => array('title'=>'Registration','access'=>-1),
    '/api/' => array('access'=>1),
];
if ( $_SERVER['REDIRECT_URL'] ) $arrPage = $arrNavs[$_SERVER['REDIRECT_URL']];
else $arrPage = $arrNavs['/'];

// Сессия
if ( 
    empty($_SESSION['session']) 
    && empty($_SESSION['session']['user']) 
) {
    $oSession = new session();
    if ( isset($oRequest->jwt) ) $oSession->jwt = $oRequest->jwt;
    else if ( isset($_REQUEST['jwt']) ) $oSession->jwt = $_REQUEST['jwt'];
    $_SESSION['session'] = $oSession->install();
    if ( 
        $oRequest->action === 'continue' 
        && $oRequest->table === 'session'
    )
    die(json_encode(["reload" => true]));
}

// Доступы к api
$arrApiAccesses = [];
$arrApiAccesses['session'] = [];
$arrApiAccesses['session']['continue'] = -1;
$arrApiAccesses['authorization'] = [];
$arrApiAccesses['authorization']['login'] = -1;
$arrApiAccesses['authorization']['registration'] = -1;
$arrApiAccesses['default'] = [];
$arrApiAccesses['default']['show'] = -1;
$arrApiAccesses['default']['get'] = -1;
$arrApiAccesses['default']['post'] = 1;
$arrApiAccesses['default']['put'] = 1;
$arrApiAccesses['default']['patch'] = 1;
$arrApiAccesses['default']['delete'] = 1;
$sTable = $oRequest->table ? $oRequest->table : 'default';

// Определяем что открыть
switch ($_SERVER['REDIRECT_URL']) {
    case '/api/';
        // Проверка доступа
        $bAccessLevel = false;
        // Если только для авторизированных
        if ( (int)$arrApiAccesses[$sTable][$sAction] > 0 ) {
            // Пользователь авторизирован
            if ( isset($_SESSION['session']['user']) )
                // Проверяем его уровень доступа
                if ( (int)$arrApiAccesses[$sTable][$sAction] <= (int)$_SESSION['session']['user']['role'] )
                    $bAccessLevel = true;
        }
        // Для всех
        else $bAccessLevel = true;

        // Нет доступа
        if ( ! $bAccessLevel ) {
            http_response_code(403);
            die(json_encode(["message" => 'Access denied!']));
        }
        include_once 'core/api/api.php';
        break;

    default: # Запрашиваемая страница
        // Определяем урл
        $sIncludePathContent = $_SERVER['REDIRECT_URL'] != '' ? 'pages'.$_SERVER['REDIRECT_URL'].'index' : 'pages/index';
        
        // Содержаине
        include_once 'core/templates/head.php';
        include_once 'core/templates/header.php';

        // Существование файла
        $bFile = file_exists($sIncludePathContent.'.php') ? 1 : 0;
        if ( $bFile ) {
            // Проверка доступа
            $bAccessLevel = false;
            $oSession = $_SESSION['session'];
            if ( 
                isset($oSession->user) 
                && (int)$arrPage['access'] <= (int)$oSession->user->role
            ) $bAccessLevel = true;
            if ( (int)$arrPage['access'] < 0 ) $bAccessLevel = true;
            
            // Нет доступа есть
            if ( ! $bAccessLevel ) {
                http_response_code(403);
                $sIncludePathContent = 'pages/errors/403/index';
            }
        }
        // Нет файла
        else {
            http_response_code(404);
            $sIncludePathContent = 'pages/errors/404/index';
        }

        include_once $sIncludePathContent . '.php';

        include_once 'core/templates/footer.php';
        break;
}
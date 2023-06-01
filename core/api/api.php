<?
// Обработка запросов к приложению
header('Access-Control-Allow-Origin: *'); # Для подключения из вне
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Max-Age: 0");
header("Content-Security-Policy: default-src *; connect-src *; script-src *; object-src *;");
header("X-Content-Security-Policy: default-src *; connect-src *; script-src *; object-src *;");
header("X-Webkit-CSP: default-src *; connect-src *; script-src 'unsafe-inline' 'unsafe-eval' *; object-src *;");
header("Content-Type: application/json; charset=UTF-8");

switch ($oRequest->table) {
    case 'session':
        switch ($sAction) {
            case 'continue': # Вход
                // print_r($_SESSION);
                // die();
            break;
        }
        break;
    case 'authorization':
        switch ($sAction) {
            case 'login': # Вход
                $oUser = new user();
                // Проверка по мылу
                if ( empty($oRequest->email) ) {
                    http_response_code(503);
                    die(json_encode(["message" => 'Email not send!']));
                }
                if ( ! $oUser->check_email( $oRequest->email ) ) {
                    http_response_code(503);
                    die(json_encode(["message" => 'Not valid datas!']));
                }
                // Проверка по паролю
                $password = hash( 'ripemd128', $oRequest->password );
                $oUser->query .= ' AND `email` = "' . $oRequest->email . '"';
                $oUser->query .= ' AND `password` = "' . $password . '"';
                $arrUser = $oUser->get();
                if ( count($arrUser) ) $arrUser = $arrUser[0];
                
                if ( empty($arrUser['id']) ) {
                    http_response_code(503);
                    die(json_encode(["message" => 'Not valid password']));   
                }

                // Создаём ключ сессии
                $oSession = new session();
                $oSession->user = $arrUser;
                $oSession->create_jwt();
                $_SESSION['session'] = $oSession->install();
                
                // ОК
                http_response_code(200);
                die(json_encode(["message" => 'Success login!',"jwt" => $oSession->jwt]));
                break;

            case 'registration': # Регистрация
                $oUser = new user();
                // Пароли
                $password = hash( 'ripemd128', $oRequest->password );
                $oRequest->password = $password;
                $password2 = hash( 'ripemd128', $oRequest->password2 );
                // Проверка совпадения паролей
                if ( $password !== $password ) {
                    http_response_code(503);
                    die(json_encode(["message" => 'Password mismatch!']));
                }
                // Почта
                if ( $oUser->check_email( $oRequest->email ) ) {
                    http_response_code(503);
                    die(json_encode(["message" => 'Email already in use!']));
                }
                // Создание пользователя
                $oUser->post( (array)$oRequest );
                http_response_code(200);
                die(json_encode(["message" => 'Success user create!']));
                break;

            case 'logout': # Выход
                session_destroy();
                http_response_code(200);
                die(json_encode(["message" => 'Success user exit!']));
                break;
        }
        break;
    
    // case 'users':
    //     // include_once 'users/index.php';
    //     echo 'ok';
    //     break;
    
    default:
        $oModel = new model( $oRequest->id );
        $oModel->table_name = $oRequest->table;

        switch ($sAction) {
            case 'show': # Вывод       
            case 'get': # Вывод       
                $arrResult = $oModel->get();
                if ( empty($arrResult) ) {
                    http_response_code(404);
                    echo json_encode(["message" => 'Elems not found']);
                }
                else {
                    http_response_code(200);
                    echo json_encode(["data" => $arrResult]);
                }        
                break;

            case 'post': # Добавление
                $oModel->post();
                http_response_code(200);
                echo json_encode(["message" => 'Success post!']);
                break;

            case 'put': # Обновление данных
                $oModel->put();
                http_response_code(200);
                echo json_encode(["message" => 'Success put!']);
                break;

            case 'patch': # Изменение поля
                $oModel->patch();
                http_response_code(200);
                echo json_encode(["message" => 'Success patch!']);
                break;

            case 'delete': # Удаление
                $oModel->delete();
                http_response_code(200);
                echo json_encode(["message" => 'Success delete!']);
                break;
        }
        break;
}
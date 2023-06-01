<?php
class model
{
    public $id = '';
    public $table_name = '';
    public $query = '';

    // Получение новых полей
    function get_data (){
        // получаем id товара для редактирования
        $arrResult = (array)json_decode(file_get_contents("php://input"));
        if ( ! count($arrResult) ) $arrResult = $_REQUEST;
        return $arrResult;
    }
    
    // Получаем поля текущего класса
    function get_model_fields (){
        // Собираем поля которые можно редактировать
        $arrFields = db::query_all("SHOW COLUMNS FROM " . $this->table_name);
        if ( ! $arrFields ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error while executing request']));
        }
        return $arrFields;
    }

    // Получение объекта
    public function get(){
        $mySqlSalt = $this->query ? $this->query : '' ;
        $arrResult = '';
        
        // Собираем поля которые можно редактировать
        $arrFieldsTypes = [];
        $arrFields = $this->get_model_fields();
        foreach ($arrFields as $arrField) $arrFieldsTypes[$arrField['Field']] = $arrField['Type'];
        
        if ( $this->id ) {
            $arrResult = db::query("SELECT * FROM `" . $this->table_name . "` WHERE `id` = " . $this->id . $mySqlSalt);
            // Обработка полей
            foreach ( $arrResult as $key => &$value ) {
                switch ( $arrFieldsTypes[$key] ) {
                case 'longtext':
                    $value = base64_decode($value);
                    break;
                }
            }
        }
        else {
            $arrResult = db::query_all("SELECT * FROM `" . $this->table_name . "` WHERE `id` != 0 " . $mySqlSalt);
            // Обработка полей
            foreach ( $arrResult as &$arrRestultitem ) {
                foreach ( $arrRestultitem as $key => &$value ) {
                    switch ( $arrFieldsTypes[$key] ) {
                        case 'longtext':
                            $value = base64_decode($value);
                            break;
                    }
                }
            }
        }
        
        return $arrResult;
    }

    // Создание
    public function post( $arrData = [] ){
        $sSql = ''; # SQL запрос на добавление
        $sSql .= "INSERT INTO `" . $this->table_name . "` ";

        // Собираем поля которые можно добавить
        $arrFieldsTypes = [];
        $arrFields = $this->get_model_fields();
        foreach ($arrFields as $arrField) $arrFieldsTypes[$arrField['Field']] = $arrField['Type'];

        // Получаем поля для добавления
        // die(count($arrData));
        $arrResult = (count($arrData)) ? $arrData : $this->get_data();
         
        // Обработка полей
        foreach ( $arrResult as $key => &$value ) {
            switch ( $arrFieldsTypes[$key] ) {
                case 'longtext':
                    $value = base64_encode($value);
                    break;
                default:
                    $value = htmlspecialchars(strip_tags($value));
                    break;
            }
        }

        // Добавление полей и данных в SQL запрос
        $sSqlNames = '('; # Названия полей
        $sSqlValues = '('; # Значения полей
        $bSqlNotValues = true;
        $sSqlSep = ''; # Разделитель

        foreach ( $arrFields as $arrField ) {
            $sSqlNames .= $sSqlSep . "`" . $arrField['Field'] . "`";
            $sSqlValues .= $sSqlSep . "'" . $arrResult[$arrField['Field']] . "'";
            if ( $arrResult[$arrField['Field']] ) $bSqlNotValues = false;
            $sSqlSep = ',';
        }

        // Нет данных для заполнения
        if ( $bSqlNotValues ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error post, not datas']));
        }

        $sSqlNames .= ')';
        $sSqlValues .= ')';
        $sSql .= $sSqlNames . ' VALUES ' . $sSqlValues;

        if ( db::query($sSql) ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error post query']));
        } 
    }

    // Сохранение всех изменений
    public function put( $arrData = [] ){
        if ( ! $this->id ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error putch, not select elem']));
        }

        $sSql = ''; # SQL запрос на добавление
        $sSql .= "UPDATE `" . $this->table_name . "` SET ";

        // Собираем поля которые можно добавить
        $arrFieldsTypes = [];
        $arrFields = $this->get_model_fields();
        foreach ($arrFields as $arrField) $arrFieldsTypes[$arrField['Field']] = $arrField['Type'];

        // Получаем поля для добавления
        $arrResult = (count($arrData)) ? $arrData : $this->get_data();
         
        // Обработка полей
        foreach ( $arrResult as $key => &$value ) {
            switch ( $arrFieldsTypes[$key] ) {
                case 'longtext':
                    $value = base64_encode($value);
                    break;
                default:
                    $value = htmlspecialchars(strip_tags($value));
                    break;
            }
        }

        // Добавление полей и данных в SQL запрос
        $sSqlSep = '';
        $bSqlNotValues = true;

        foreach ( $arrFields as $arrField ) {
            $sSql .= $sSqlSep . "`" . $arrField['Field'] . "` = '" . $arrResult[$arrField['Field']] . "'";
            $sSqlSep = ', ';
            if ( $arrResult[$arrField['Field']] ) $bSqlNotValues = false;
        }

        // Нет данных для заполнения
        if ( $bSqlNotValues ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error patch, not datas']));
        }

        $sSql .= ' WHERE `id` = ' . $this->id;

        if ( db::query($sSql) ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error post query']));
        } 
    }

    // Сохранение некоторых данных
    public function patch( $arrData = [] ){
        if ( ! $this->id ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error putch, not select elem']));
        }

        $sSql = ''; # SQL запрос на добавление
        $sSql .= "UPDATE `" . $this->table_name . "` SET ";

        // Собираем поля которые можно добавить
        $arrFieldsTypes = [];
        $arrFields = $this->get_model_fields();
        foreach ($arrFields as $arrField) $arrFieldsTypes[$arrField['Field']] = $arrField['Type'];

        // Получаем поля для добавления
        $arrResult = (count($arrData)) ? $arrData : $this->get_data();
         
        // Обработка полей
        foreach ( $arrResult as $key => &$value ) {
            switch ( $arrFieldsTypes[$key] ) {
                case 'longtext':
                    $value = base64_encode($value);
                    break;
                default:
                    $value = htmlspecialchars(strip_tags($value));
                    break;
            }
        }

        // Добавление полей и данных в SQL запрос
        $bSqlNotValues = true;

        foreach ( $arrFields as $arrField ) {
            if ( $arrField['Field'] == 'id' ) continue;
            if ( $arrResult[$arrField['Field']] ) {
                $sSql .= "`" . $arrField['Field'] . "` = '" . $arrResult[$arrField['Field']] . "'";
                $bSqlNotValues = false;
            }
        }

        // Нет данных для заполнения
        if ( $bSqlNotValues ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error patch, not datas']));
        }

        $sSql .= ' WHERE `id` = ' . $this->id;

        if ( db::query($sSql) ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error post query']));
        } 
    }

    // Удаление
    public function delete(){
        if ( ! $this->id ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error delete, not select elem']));
        }
        $sSql = "DELETE FROM `" . $this->table_name . "`";
        $sSql .= " WHERE `id` = '" . $this->id . "'";
        if ( db::query($sSql) ) {
            http_response_code(503);
            die(json_encode(["message" => 'Error delete']));
        }
    }
	/**
	 */
	function __construct( $iId = 0 ) {
        if ( $iId ) $this->id = $iId;
	}
}

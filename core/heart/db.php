<?
/**
 * db
 */
class db extends config
{
  // Простой запрос к базе
  static public function query($sQuery){
    if ( $sQuery ) {
      // - Подключаемся
      $PDO = new PDO('mysql:host='.config::$host.';dbname='.config::$db, config::$user, config::$password);

      // - Выполняем запрос
      $arrResult = $PDO->query($sQuery)->fetch(PDO::FETCH_ASSOC);

      // - Отключаемся
      $PDO = null;

      // - Возвращяем результат
      return $arrResult;
    }
  }

  // Вывод массива данных
  static public function query_all($sQuery){
    if ( $sQuery ) {
      // - Подключаемся
      $PDO = new PDO('mysql:host='.config::$host.';dbname='.config::$db, config::$user, config::$password);

      // - Выполняем запрос
      $arrResult = $PDO->query($sQuery)->fetchAll(PDO::FETCH_ASSOC);

      // - Отключаемся
      $PDO = null;

      // - Возвращяем результат
      return $arrResult;
    }
  }
}
$db = new db();

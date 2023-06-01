<?
/**
 * config
 */
class config
{
  static $user = 'trywar4y_aut0';
  static $password = 'Et%45qFY';
  static $host = 'localhost';
  static $db = 'trywar4y_aut0';
  static $site_url = 'aut0.trywar.ru';
  static $site_port = 'https://';
  static $arrConfig = [];

  function __construct(){
  }
}
$config = new config();
config::$arrConfig = [
  'name' => 'aut0',
];

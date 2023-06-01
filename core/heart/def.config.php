<?
/**
 * config
 */
class config
{
  static $user = '';
  static $password = '';
  static $host = 'localhost';
  static $db = '';
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

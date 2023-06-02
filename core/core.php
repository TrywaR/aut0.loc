<?php
/**
 * @OA\Server(
 *     url="aut0.trywar.ru/core/api/",
 *     description="Easy API interface on PHP"
 * ),
 * @OA\Info(
 *     title="aut0",
 *     version="1",
 *     description="Easy API interface on PHP",
 * )
 */

// Основное
include_once 'heart/config.php'; # Настройки проекта
include_once 'heart/db.php'; # Подключение к БД

// Классы для работы с базой
include_once 'models/model.php'; # Общий для всех
include_once 'models/users.php'; # Пользователи
include_once 'models/sessions.php'; # Сессии
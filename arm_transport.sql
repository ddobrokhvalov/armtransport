-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 13 2020 г., 01:10
-- Версия сервера: 5.7.28-0ubuntu0.18.04.4
-- Версия PHP: 7.2.24-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `arm_transport`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

DROP TABLE IF EXISTS `cars`;
CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `reg_mark` varchar(100) NOT NULL,
  `fuel_cons` float NOT NULL,
  `way_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`id`, `name`, `reg_mark`, `fuel_cons`, `way_id`) VALUES
(1, 'Газель', 'А111АА69', 10.5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `dispatchers`
--

DROP TABLE IF EXISTS `dispatchers`;
CREATE TABLE `dispatchers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dispatchers`
--

INSERT INTO `dispatchers` (`id`, `firstname`, `lastname`, `login`, `password`) VALUES
(1, 'Иван', 'Иванов', 'dispatcher1', '00386d875419af87dfecc71fc81583fc');

-- --------------------------------------------------------

--
-- Структура таблицы `drivers`
--

DROP TABLE IF EXISTS `drivers`;
CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `car_id` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `drivers`
--

INSERT INTO `drivers` (`id`, `firstname`, `lastname`, `car_id`, `login`, `password`) VALUES
(1, 'Иван', 'Иванов', 1, 'ivanov1', 'd5bf581f5d2b80104b95181c1db35020');

-- --------------------------------------------------------

--
-- Структура таблицы `traffic`
--

DROP TABLE IF EXISTS `traffic`;
CREATE TABLE `traffic` (
  `id` int(11) NOT NULL,
  `way_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `time_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_end` timestamp NULL DEFAULT NULL,
  `weight` int(11) NOT NULL,
  `traffic_type` set('direct','opposite') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `traffic_points`
--

DROP TABLE IF EXISTS `traffic_points`;
CREATE TABLE `traffic_points` (
  `id` int(11) NOT NULL,
  `traffic_id` int(11) NOT NULL,
  `mileage` int(11) NOT NULL,
  `point_name` varchar(100) NOT NULL,
  `point_type` set('start','pause','continue','end') NOT NULL,
  `point_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ways`
--

DROP TABLE IF EXISTS `ways`;
CREATE TABLE `ways` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mileage` int(11) NOT NULL,
  `norm_cargo_day` int(11) NOT NULL,
  `norm_cargo_week` int(11) NOT NULL,
  `norm_cargo_month` int(11) NOT NULL,
  `norm_fuel_day` int(11) NOT NULL,
  `norm_fuel_week` int(11) NOT NULL,
  `norm_fuel_month` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ways`
--

INSERT INTO `ways` (`id`, `name`, `mileage`, `norm_cargo_day`, `norm_cargo_week`, `norm_cargo_month`, `norm_fuel_day`, `norm_fuel_week`, `norm_fuel_month`) VALUES
(1, 'Маршрут номер 1', 2345, 100, 700, 3000, 100, 700, 3000);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dispatchers`
--
ALTER TABLE `dispatchers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `traffic`
--
ALTER TABLE `traffic`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `traffic_points`
--
ALTER TABLE `traffic_points`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ways`
--
ALTER TABLE `ways`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `dispatchers`
--
ALTER TABLE `dispatchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `traffic`
--
ALTER TABLE `traffic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `traffic_points`
--
ALTER TABLE `traffic_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `ways`
--
ALTER TABLE `ways`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

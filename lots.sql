-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 15 2024 г., 16:33
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lots`
--

-- --------------------------------------------------------

--
-- Структура таблицы `lot`
--

CREATE TABLE `lot` (
  `id` int NOT NULL,
  `numberlot` varchar(25) NOT NULL,
  `url` varchar(200) NOT NULL,
  `Description` varchar(400) NOT NULL,
  `Price` varchar(100) NOT NULL,
  `number` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `inn` varchar(50) NOT NULL,
  `casenumber` varchar(50) NOT NULL,
  `date1` varchar(100) DEFAULT NULL,
  `date2` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `lot`
--

INSERT INTO `lot` (`id`, `numberlot`, `url`, `Description`, `Price`, `number`, `email`, `inn`, `casenumber`, `date1`, `date2`) VALUES
(1, 'Лот № 1', 'https://nistp.ru/bankrot/trade_view.php?trade_nid=362386', 'Земельный участок, кадастровый номер 63:32:1305001:5679, площадь 540 кв.м, Адрес (местоположение): Самарская область, муниципальный р-н Ставропольский, с/пос. Нижнее Санчелеево, СТ Новая Васильевка, 3 Тупиковый проезд, участок № 60. Категория земель: Земли населенных пунктов. Вид разрешенного использования: для коллективного садоводства,', '52 200.00', '+7-499-938-74-30 доб. 455', 'arbitr.torgi@list.ru', '632312062812', 'А55-1488/2023', '16.04.2024 00:00:00', ''),
(2, 'Лот № 1', 'https://nistp.ru/bankrot/trade_view.php?trade_nid=362360', 'Сооружение наружный газопровод низкого давления, протяженность 461м., кад. номер 47:09:0110001:319, к жилому дому по адресу Всеволожский р-н, дер. Старая, ул. Верхняя, дом №3, корпус 2', '1 501 033.00', '+7(905)211-85-29', 'sputnik.spb@inbox.ru', '7814124671', 'А56-85983/2019', '15.04.2024 10:00:00', '29.05.2024 12:00:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `lot`
--
ALTER TABLE `lot`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `lot`
--
ALTER TABLE `lot`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

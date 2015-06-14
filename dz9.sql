-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 14 2015 г., 17:08
-- Версия сервера: 5.5.43-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `dz9`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `price` varchar(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `email` varchar(32) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `descr` text NOT NULL,
  `id_city` varchar(11) NOT NULL,
  `id_tube_station` varchar(11) NOT NULL,
  `id_subcategory` varchar(11) NOT NULL,
  `private` varchar(1) NOT NULL,
  `send_to_email` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `category` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'Транспорт'),
(2, 'Недвижимость'),
(3, 'Работа'),
(4, 'Услуги'),
(5, 'Личные вещи'),
(6, 'Для дома и дачи'),
(7, 'Бытовая электроника'),
(8, 'Хобби и отдых'),
(9, 'Животные'),
(10, 'Для бизнеса');

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL,
  `city` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`id`, `city`) VALUES
(70000, 'Другой город...'),
(641780, 'Новосибирск'),
(641490, 'Барабинск'),
(641510, 'Бердск'),
(641600, 'Искитим'),
(641630, 'Колывань'),
(641680, 'Краснообск'),
(641710, 'Куйбышев'),
(641760, 'Мошково'),
(641790, 'Обь'),
(641800, 'Ордынское'),
(641970, 'Черепаново');

-- --------------------------------------------------------

--
-- Структура таблицы `subcategories`
--

CREATE TABLE IF NOT EXISTS `subcategories` (
  `id` int(11) NOT NULL,
  `subcategory` text NOT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subcategories`
--

INSERT INTO `subcategories` (`id`, `subcategory`, `category`) VALUES
(9, 'Автомобили с пробегом', 1),
(109, 'Новые автомобили', 1),
(14, 'Мотоциклы и мототехника', 1),
(81, 'Грузовики и спецтехника', 1),
(11, 'Водный транспорт', 1),
(10, 'Запчасти и аксессуары', 1),
(24, 'Квартиры', 2),
(23, 'Комнаты', 2),
(25, 'Дома, дачи, коттеджи', 2),
(26, 'Земельные участки', 2),
(85, 'Гаражи и машиноместа', 2),
(42, 'Коммерческая недвижимость', 2),
(86, 'Недвижимость за рубежом', 2),
(111, 'Вакансии (поиск сотрудников)', 3),
(112, 'Резюме (поиск работы)', 3),
(114, 'Предложения услуг', 4),
(115, 'Запросы на услуги', 4),
(27, 'Одежда, обувь, аксессуары', 5),
(29, 'Детская одежда и обувь', 5),
(30, 'Товары для детей и игрушки', 5),
(28, 'Часы и украшения', 5),
(88, 'Красота и здоровье', 5),
(21, 'Бытовая техника', 6),
(20, 'Мебель и интерьер', 6),
(87, 'Посуда и товары для кухни', 6),
(82, 'Продукты питания', 6),
(19, 'Ремонт и строительство', 6),
(106, 'Растения', 6),
(32, 'Аудио и видео', 7),
(97, 'Игры, приставки и программы', 7),
(31, 'Настольные компьютеры', 7),
(98, 'Ноутбуки', 7),
(99, 'Оргтехника и расходники', 7),
(96, 'Планшеты и электронные книги', 7),
(84, 'Телефоны', 7),
(101, 'Товары для компьютера', 7),
(33, 'Билеты и путешествия', 8),
(34, 'Велосипеды', 8),
(83, 'Книги и журналы', 8),
(36, 'Коллекционирование', 8),
(38, 'Музыкальные инструменты', 8),
(102, 'Охота и рыбалка', 8),
(39, 'Спорт и отдых', 8),
(103, 'Знакомства', 8),
(89, 'Собаки', 9),
(90, 'Кошки', 9),
(91, 'Птицы', 9),
(92, 'Аквариум', 9),
(93, 'Другие животные', 9),
(94, 'Товары для животных', 9),
(116, 'Готовый бизнес', 10),
(40, 'Оборудование для бизнеса', 10);

-- --------------------------------------------------------

--
-- Структура таблицы `tube_stations`
--

CREATE TABLE IF NOT EXISTS `tube_stations` (
  `id` int(11) NOT NULL,
  `tube_station` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tube_stations`
--

INSERT INTO `tube_stations` (`id`, `tube_station`) VALUES
(2028, 'Берёзовая роща'),
(2018, 'Гагаринская'),
(2017, 'Заельцовская'),
(2029, 'Золотая Нива'),
(641630, 'Маршала Покрышкина'),
(2021, 'Октябрьская'),
(2025, 'Площадь Гарина-Михайловского'),
(2020, 'Площадь Ленина'),
(2024, 'Площадь Маркса'),
(2022, 'Речной вокзал'),
(2026, 'Сибирская'),
(2023, 'Студенческая');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

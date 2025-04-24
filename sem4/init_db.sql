-- Удаляем существующие таблицы, если они есть
DROP TABLE IF EXISTS app_link_lang;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS prog_lang;
DROP TABLE IF EXISTS application;

-- Создаем таблицу application
CREATE TABLE application (
    id_app INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    fio VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    bday DATE NOT NULL,
    sex VARCHAR(5) NOT NULL,
    biography VARCHAR(512),
    PRIMARY KEY (id_app)
) ENGINE=InnoDB;

-- Создаем таблицу prog_lang
CREATE TABLE prog_lang (
    id_prog_lang INT(4) UNSIGNED NOT NULL,
    name_prog_lang VARCHAR(64) NOT NULL,
    PRIMARY KEY (id_prog_lang)
) ENGINE=InnoDB;

-- Создаем таблицу app_link_lang с внешним ключом на application с каскадным удалением
CREATE TABLE app_link_lang (
    id_link INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    id_app INT(10) UNSIGNED NOT NULL,
    id_prog_lang INT(4) UNSIGNED NOT NULL,
    PRIMARY KEY (id_link),
    FOREIGN KEY (id_app) REFERENCES application(id_app) ON DELETE CASCADE,
    FOREIGN KEY (id_prog_lang) REFERENCES prog_lang(id_prog_lang)
) ENGINE=InnoDB;

-- Создаем таблицу users с внешним ключом на application с каскадным удалением
CREATE TABLE users (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    login VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    application_id INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (application_id) REFERENCES application(id_app) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Создаем таблицу admins
CREATE TABLE admins (
    id_admin INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    login VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_admin)
) ENGINE=InnoDB;

-- Заполняем таблицу prog_lang
INSERT INTO prog_lang (id_prog_lang, name_prog_lang) VALUES
(0, 'Pascal'),
(1, 'C'),
(2, 'C++'),
(3, 'JavaScript'),
(4, 'PHP'),
(5, 'Python'),
(6, 'Java'),
(7, 'Haskel'),
(8, 'Clojure'),
(9, 'Prolog'),
(10, 'Scala');


INSERT INTO admins (login, password_hash) VALUES("admin", "$2y$10$xJdultbDHcUyPyWvvvNQNuCMrpWaoeYa4QuCFwka5Np2Dwoyp4N52");

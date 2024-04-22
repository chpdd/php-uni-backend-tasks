<?php
//application
//+-----------+------------------+------+-----+---------+----------------+
//| Field     | Type             | Null | Key | Default | Extra          |
//+-----------+------------------+------+-----+---------+----------------+
//| id_app    | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
//| fio       | varchar(255)     | NO   |     | NULL    |                |
//| telephone | varchar(20)      | NO   |     | NULL    |                |
//| email     | varchar(255)     | NO   |     | NULL    |                |
//| bday      | date             | NO   |     | NULL    |                |
//| sex       | varchar(5)       | NO   |     | NULL    |                |
//| biography | varchar(512)     | YES  |     | NULL    |                |
//+-----------+------------------+------+-----+---------+----------------+

//prog_lang
//+----------------+-----------------+------+-----+---------+-------+
//| Field          | Type            | Null | Key | Default | Extra |
//+----------------+-----------------+------+-----+---------+-------+
//| id_prog_lang   | int(4) unsigned | NO   | PRI | NULL    |       |
//| name_prog_lang | varchar(64)     | NO   |     | NULL    |       |
//+----------------+-----------------+------+-----+---------+-------+

//app_link_lang
//+--------------+------------------+------+-----+---------+----------------+
//| Field        | Type             | Null | Key | Default | Extra          |
//+--------------+------------------+------+-----+---------+----------------+
//| id_link      | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
//| id_app       | int(10) unsigned | NO   |     | NULL    |                |
//| id_prog_lang | int(4) unsigned  | NO   |     | NULL    |                |
//+--------------+------------------+------+-----+---------+----------------+




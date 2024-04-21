from http.server import BaseHTTPRequestHandler, HTTPServer
import mysql.connector
import urllib.parse as urlparse
import cgi
import json
import re
import hid_vars


# Функция для сохранения данных в базу данных MySQL
def save_to_database(data):
    try:
        db = mysql.connector.connect(host=hid_vars.host,
                                     user=hid_vars.user,
                                     password=hid_vars.password,
                                     database=hid_vars.database)
        cursor = db.cursor()

        # Делаем запрос для таблицы application
        app_attrs = "fio telephone email bday sex biography".split()
        data_for_app = []
        for attr in app_attrs:
            data_for_app.append(data.get(attr))
        app_req = "INSERT INTO application ({', '.join(app_attrs}) VALUES ("
        app_req += f"{', '.join(data_for_app)});"
        cursor.execute(app_req)

        # Делаем запрос для таблицы app_link_lang
        cursor.execute("SELECT MAX(id_app) FROM application")
        app_id = int(cursor.fetchone()) + 1
        data_for_link = [f"({str(app_id)}, {lang})" for lang in data.get('prog-lang')]
        link_req = "INSERT INTO app_link_lang (id_app, id_prog_lang) VALUES "
        link_req += f"{', '.join(data_for_link)};"
        cursor.execute(link_req)

        db.commit()
        cursor.close()
        db.close()
        return "Данные успешно сохранены в базе данных!"
    except mysql.connector.Error as err:
        return f"Ошибка при сохранении данных в базе данных: {err}"


# Валидация данных
def validate_data(data):
    errors = []

    if not data.get("name"):
        errors.append("Name is required.")
    elif len(data.get("name")) > 150:
        errors.append("Name must be no more than 150 characters long.")

    telephone_pattern = r"/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/"
    if not data.get("telephone"):
        errors.append("Telephone is required.")
    elif not re.match(telephone_pattern, data.get("telephone")):
        errors.append("Invalid telephone.")

    if not data.get("bday"):
        errors.append("Bday is required.")

    if not data.get("email"):
        errors.append("Email address is required.")
    elif "@" not in data.get("email"):
        errors.append("Invalid email address.")

    if not data.get("sex-radios"):
        errors.append("Sex is required.")

    if not data.get("prog-lang"):
        errors.append("Prog lang is required.")

    if not data.get("biography"):
        errors.append("Biographu is required.")

    return errors


# Обработчик HTTP-запросов
class MyHandler(BaseHTTPRequestHandler):
    def do_POST(self):
        print("New POST request")
        content_length = int(self.headers['Content-Length'])
        post_data = self.rfile.read(content_length)
        data = urlparse.parse_qs(post_data.decode('utf-8'))
        print(data)

        # Преобразование данных в словарь
        data_dict = {key: value[0] for key, value in data.items()}
        print(data_dict)
        # Валидация данных
        errors = validate_data(data_dict)

        if errors:
            print("Поймали ошибки:")
            # Если есть ошибки, отправляем ошибку обратно клиенту
            self.send_response(400)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps({'errors': errors}).encode('utf-8'))
        else:
            # Если все данные корректны, отправляем сообщение об успешном сохранении
            self.send_response(200)
            self.send_header('Content-type', 'text/plain')
            self.end_headers()
            self.wfile.write(b"Data saved successfully!")
            save_to_database(data)


# Запуск сервера
def run(server_class=HTTPServer, handler_class=MyHandler, port=8080):
    server_address = ("", port)
    httpd = server_class(server_address, handler_class)
    print(f"Starting server on port {port}...")
    httpd.serve_forever()


if __name__ == '__main__':
    run()

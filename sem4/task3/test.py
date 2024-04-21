import mysql.connector as dbcon
import hid_vars
def test():
    db = dbcon.connect(
        host=hid_vars.host,
        user=hid_vars.user,
        password=hid_vars.password,
        database=hid_vars.database
    )
    cursor = db.cursor()
    cursor.execute("SELECT * FROM comp_lang")
    print(cursor.fetchall())

if __name__ == '__main__':
    test()
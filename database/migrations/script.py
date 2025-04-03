import os
import subprocess

# Задаем путь к директории, который хотим использовать
# Если путь не задан, используется текущая директория скрипта
dir_path =  os.path.dirname(os.path.abspath(__file__))

# Проверяем, существует ли указанная директория
if os.path.exists(dir_path) and os.path.isdir(dir_path):
    # Получаем список всех файлов и папок в указанной директории
    files = os.listdir(dir_path)

    # Фильтруем только файлы с расширением .php
    php_files = [file for file in files if file.endswith('.php')]

    if php_files:
        print(f"Путь к директории: {dir_path}")
        print("Список PHP файлов в директории:")

        # Выводим все файлы с расширением .php
        for file in php_files:
            print(file)
            # Формируем команду для выполнения
            migrate_command = f"php artisan migrate --path=/database/migrations/{file}"
            try:
                # Выполняем команду
                subprocess.run(migrate_command, shell=True, check=True)
                print(f"Миграция для {file} выполнена успешно.")
            except subprocess.CalledProcessError as e:
                print(f"Ошибка при выполнении миграции для {file}: {e}")
    else:
        print("Нет PHP файлов в директории.")
else:
    print("Указанный путь не существует или не является директорией.")


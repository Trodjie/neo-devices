# neo-devices
### Для запуска необходимо: ###
1. Загрузить и установить Docker (Docker Desktop for Windows) - https://docs.docker.com/docker-for-windows/install/, перезагрузить компьютер
2. Далее необходимо установить пакет обновления ядра Linux (Шаг 4 на странице, над примечанием wsl_update_x64.msi) - https://docs.microsoft.com/ru-ru/windows/wsl/install-win10#step-4---download-the-linux-kernel-update-package и перезагрузить компьютер
3.После перезагрузки, в командной строке необходимо перейти в корневую папку проекта и выполнить команды:
docker-compose up -d
docker-compose exec php bin/console d:da:dr --force
docker-compose exec php bin/console d:da:cr
docker-compose exec php bin/console d:m:m
docker-compose exec php bin/console d:f:l
Для некоторых пунктов требуется подтверждение, для этого необходимо написать Yes и нажать Enter

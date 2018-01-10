<?php

	//Определяем, с ккаким контроллером будем работать
    $controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) : 'Index';

    echo 'Подключаемый php файл (Контроллер) = '. $controllerName . '<br>';
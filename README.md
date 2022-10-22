## WebComponent плагин для CakePHP.

### Что может этот плагин?

При помощи этого плагина можно быстро создавать каркас для веб-компонента, используя командную строку.

### Установка

Вы можете установить этот плагин в свое приложение CakePHP с помощью [composer](https://getcomposer.org).

Рекомендуемый способ установки пакетов composer - это:

```
composer require valeriy-brunov/web-component
composer dumpautoload
bin/cake plugin load WebComponent
```

### Настройка

После установки плагина необходимо подключить помошник плагина. Для этого необходимо добавить строку:

```php
// ./src/Controller/AppController.php
public function initialize(): void
{
    // Добавить строку:
    $this->viewBuilder()->addHelper('WebComponent.Webcomp');
}
```

### Основные команды

Создать файлы для нового веб-компонента:

```
$ sudo bin/cake webcomp <name>
```

где *name* - имя веб-компонента, может состоять из нескольких слов через символ "-" (тире).

Можно создавать файлы веб-компонента в созданном плагине (только для плагинов в директории plugin).
Для начала создадим новый плагин. Делается это командой:

```
$ sudo bin/cake bake plugin <name>
```
Далее, внутри этого плагина создим файлы веб-компонента:

```
$ sudo bin/cake webcomp <name> --plugin
```
Или применим краткую запись команды:

```
$ sudo bin/cake webcomp <name> -p
```
Аргумент *name* (имя веб-компонента) должно обязательно совпадать с именем плагина.

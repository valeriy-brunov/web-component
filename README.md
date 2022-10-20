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


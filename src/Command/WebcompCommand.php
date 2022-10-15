<?php
declare(strict_types=1);

namespace WebComponent\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Filesystem\File;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

/**
 * Webcomp command.
 */
class WebcompCommand extends Command
{
    /**
     * Получает экземпляр анализатора параметров и настраивает его.
     * 
     * @param \Cake\Console\ConsoleOptionParser $parser Анализатор параметров консоли.
     * @return \Cake\Console\ConsoleOptionParser
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Создаёт файлы веб-компонента.');

        $parser->addOptions([
            'help' => [
                'help' => 'Справка о команде.',
                'short' => 'h',
                'boolean' => true,
            ],
            'verbose' => [
                'help' => 'Подробный вывод.',
                'short' => 'v',
            ],
            'quiet' => [
                'help' => 'Скрытый вывод.',
                'short' => 'q',
            ],
        ]);

        $parser->addArgument('name', [
            'help' => 'Имя веб-компонента. Может содержать знак "-" (тире).',
            'required' => true,
        ]);

        return $parser;
    }

    /**
     * Реализуйте этот метод с помощью логики вашей команды.
     *
     * @param \Cake\Console\Arguments $args Аргументы команды.
     * @param \Cake\Console\ConsoleIo $io Консольный ввод-вывод.
     * @return null|void|int Код выхода или null для успеха.
     */
    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $name = $args->getArgument('name');
        $name = mb_strtolower($name);
        if ( preg_match( '/\_/', $name ) ) {
            $io->abort('В название имени веб-компонента указан недопустимый символ "_" (подчёркивание).');
        }

        $pathTempl = ROOT . DS . 'vendor' . DS . 'valeriy-brunov' . DS . 'web-component' . DS . 'templates' . DS . 'webcomponent' . DS . 'js_template.twig';
        $io->createFile(
            "./webroot/js/components/{$name}/{$name}.js",
            $this->contentTemplateFile( $pathTempl, $name ),
        );

        $pathTempl = ROOT . DS . 'vendor' . DS . 'valeriy-brunov' . DS . 'web-component' . DS . 'templates' . DS . 'webcomponent' . DS . 'comp_template.twig';
        $io->createFile(
            "./templates/element/components/{$name}.php",
            $this->contentTemplateFile( $pathTempl, $name ),
        );

        $pathTempl = ROOT . DS . 'vendor' . DS . 'valeriy-brunov' . DS . 'web-component' . DS . 'templates' . DS . 'webcomponent' . DS . 'template_template.twig';
        $io->createFile(
            "./webroot/js/components/{$name}/template.js",
            $this->contentTemplateFile( $pathTempl, $name ),
        );

        $io->out('');
        $io->out("Создан веб-компонент {$name}.");
        $io->out('');

        return static::CODE_SUCCESS;
    }

    /**
     * Загружает шаблоны twig, подставляет необходимые значения и возвращает шаблон
     * в виде строки.
     * 
     * @param {string} $pathTempl Путь до загружаемого файла.
     * @param {string} $name Имя веб-компонента.
     * @return string Шаблон загруженного файла в виде строки.
     */
    private function contentTemplateFile( $pathTempl, $name ): string
    {
        $file = new File( $pathTempl );
        $content = $file->read();
        $file->close();

        $arr = explode('-', $name );
        if ( count($arr) > 0 ) {
            for ($i = 0; $i <= count($arr) - 1; $i++) {
                $arr[$i] = ucwords($arr[$i]);
            }
        }
        $nameClass = implode('', $arr);

        $nameWebComp = lcfirst($nameClass);

        $content = str_replace([
            '{{ name }}',
            '{{ nameClass }}',
            '{{ nameWebComp }}'], [
            $name,
            $nameClass,
            $nameWebComp,
        ], $content);

        return $content;
    }
}

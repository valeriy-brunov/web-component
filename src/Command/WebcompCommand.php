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
use Cake\Core\Plugin;

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

        $parser->addOption('plugin', [
            'help' => 'Создает из имеющегося веб-компонента плагин.',
            'short' => 'p',
            'boolean' => true,
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

        $confApp = Configure::read('App');
        $pathPluginTemplate = Plugin::templatePath('webcomponent');

        if ($args->getOption('plugin')) {
            $dirPlugin = ucfirst($name);
            $pathElement = $confApp['paths']['plugins'][0] . $dirPlugin . DS . 'templates' . DS . 'element' . DS . 'components' . DS;
            $pathJs = $confApp['paths']['plugins'][0] . $dirPlugin . DS . $confApp['webroot'] . DS . $confApp['jsBaseUrl'] . 'components' . DS . $name . DS;
            $this->_execNewComp($name, $pathElement, $pathJs, $pathPluginTemplate, $io);
        }
        else {
            $pathElement = $confApp['paths']['templates'][0] . 'element' . DS . 'components' . DS;
            $pathJs = ROOT . DS . $confApp['webroot'] . DS . $confApp['jsBaseUrl'] . 'components' . DS . $name . DS;
            $this->_execNewComp($name, $pathElement, $pathJs, $pathPluginTemplate, $io);
        }

        return static::CODE_SUCCESS;
    }

    /**
     * Создаёт новый веб-компонент.
     *
     * @param {string} $name Имя веб-компонента.
     * @param {string} $pathElement Путь до директории, где должен располагаться файл элемента веб-компонента.
     * @param {string} $pathJs Путь до директории, где должны располагаться js-файлы веб-компонента.
     * @param {string} $pathPluginTemplate Месторасположение файлов плагина.
     * @param \Cake\Console\ConsoleIo $io Консольный ввод-вывод.
     */
    protected function _execNewComp($name, $pathElement, $pathJs, $pathPluginTemplate, $io): void
    {
        $templ = [
            'js_template.twig',
            'template_template.twig',
            'comp_template.twig',
        ];
        $file = [
            "{$name}.js",
            "template.js",
            "{$name}.php",
        ];
        for ($i = 0; $i < count($templ); $i++) {
            $io->createFile(
                $i == 2 ? $pathElement . $file[$i] : $pathJs . $file[$i],
                $this->_contentTemplateFile( $pathPluginTemplate . 'webcomponent' . DS . $templ[$i], $name ),
            );
        }

        $io->setStyle('greentext', ['text' => 'green']);
        $io->setStyle('boldik', ['text' => 'green', 'bold' => true]);

        $io->hr();
        $io->out("<greentext>Создан веб-компонент </greentext><boldik>{$name}</boldik>");
        $io->hr();
    }

    /**
     * Загружает шаблоны twig, подставляет необходимые значения и возвращает шаблон
     * в виде строки.
     * 
     * @param {string} $pathTempl Путь до загружаемого файла.
     * @param {string} $name Имя веб-компонента.
     * @return string Шаблон загруженного файла в виде строки с подставленными значениями.
     */
    protected function _contentTemplateFile( $pathTempl, $name ): string
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
            '{{ nameWebComp }}'
        ],[
            $name,
            $nameClass,
            $nameWebComp,
        ], $content);

        return $content;
    }
}

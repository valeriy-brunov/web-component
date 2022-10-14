<?php
declare(strict_types=1);

namespace WebComponent\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use WebComponent\View\Helper\WebcompHelper;

/**
 * WebComponent\View\Helper\WebcompHelper Test Case
 */
class WebcompHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \WebComponent\View\Helper\WebcompHelper
     */
    protected $Webcomp;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Webcomp = new WebcompHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Webcomp);

        parent::tearDown();
    }
}

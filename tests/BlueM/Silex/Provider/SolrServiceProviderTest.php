<?php

namespace BlueM\Silex\Provider;

if (file_exists(__DIR__.'/../../../../../../../vendor/autoload.php')) {
    require_once __DIR__.'/../../../../../../../vendor/autoload.php';
} else {
    require_once __DIR__.'/../../../../vendor/autoload.php';
}

require_once __DIR__.'/../../../../src/BlueM/Silex/Provider/SolrServiceProvider.php';

/**
 * Unit tests for BlueM\Silex\Provider\SolrServiceProvider
 *
 * @covers BlueM\Silex\Provider\SolrServiceProvider
 */
class SolrServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage non-empty string
     */
    public function thePrefixMustNotBeEmpty()
    {
        new SolrServiceProvider('');
    }

    /**
     * @test
     */
    public function thePrefixPassedAsArgumentIsSaved()
    {
        $subject = new SolrServiceProvider('myprefix');

        $prefixProperty = new \ReflectionProperty($subject, 'prefix');
        $prefixProperty->setAccessible(true);

        $this->assertSame('myprefix', $prefixProperty->getValue($subject));
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage No Solr options are defined
     */
    public function anExceptionIsThrownIfNoOptionsAreDefined()
    {
        $subject = new SolrServiceProvider();
        $method = new \ReflectionMethod($subject, 'getOptions');
        $method->setAccessible(true);

        $appStub = $this->getMockBuilder('Silex\Application')
            ->disableOriginalConstructor()
            ->getMock();

        $appStub->expects($this->exactly(17))
            ->method('offsetExists')
            ->will($this->returnValue(false));

        $method->invoke($subject, $appStub);
    }

    /**
     * @test
     */
    public function getTheOptions()
    {
        $subject = new SolrServiceProvider();
        $method = new \ReflectionMethod($subject, 'getOptions');
        $method->setAccessible(true);

        $appStub = $this->getMockBuilder('Silex\Application')
            ->disableOriginalConstructor()
            ->getMock();

        $valueMap = array(
            array('solr.port', '1.2.3.4'),
            array('solr.timeout', 4711),
        );

        $appStub->expects($this->exactly(17))
            ->method('offsetExists')
            ->will($this->returnValueMap($valueMap));

        $appStub->expects($this->exactly(count($valueMap)))
            ->method('offsetGet')
            ->will($this->returnValueMap($valueMap));

        $options = $method->invoke($subject, $appStub);

        $this->assertSame(array('port' => '1.2.3.4', 'timeout' => 4711), $options);
    }
}

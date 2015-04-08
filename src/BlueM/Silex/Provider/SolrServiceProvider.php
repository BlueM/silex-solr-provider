<?php

namespace BlueM\Silex\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

/**
 * Provides access to Solr via ext/solr 2
 *
 * @author  Carsten Bluem <carsten@bluem.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD 2-Clause License
 */
class SolrServiceProvider implements ServiceProviderInterface
{
    const MINIMUM_EXT_VERSION = 2;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @param string $prefix The Solr configuration keys' prefix string
     *
     * @throws \RuntimeException If the Solr extension is not available or is too old
     */
    public function __construct($prefix = 'solr')
    {
        if (!is_string($prefix) || !trim($prefix)) {
            throw new \InvalidArgumentException(
                'The prefix must be a non-empty string'
            );
        }

        if (!function_exists('solr_get_version')) {
            throw new \RuntimeException(
                'Looks like the Solr extension is not available'
            );
        }

        if (version_compare(self::MINIMUM_EXT_VERSION, solr_get_version()) > 0) {
            throw new \RuntimeException(
                'This version ('.solr_get_version().') of the Solr extension is too old'
            );
        }

        $this->prefix = $prefix;
    }

    /**
     * {@inheritDoc}
     */
    public function register(Container $pimple)
    {
        $that = $this;

        $app[$this->prefix] = function ($pimple) use ($that) {
            return new \SolrClient($that->getOptions($pimple));
        };
    }

    /**
     * @param Container $pimple
     *
     * @return array
     */
    private function getOptions(Container $pimple)
    {
        $prefix = $this->prefix;

        $optionKeys = array(
            'hostname',
            'login',
            'password',
            'path',
            'port',
            'proxy_host',
            'proxy_login',
            'proxy_password',
            'proxy_port',
            'secure',
            'ssl_cainfo',
            'ssl_capath',
            'ssl_cert',
            'ssl_key',
            'ssl_keypassword',
            'timeout',
            'wt',
        );

        $options = array();

        foreach ($optionKeys as $key) {
            if (isset($pimple["$prefix.$key"])) {
                $options[$key] = $pimple["$prefix.$key"];
            }
        }

        if (!count($options)) {
            throw new \RuntimeException('No Solr options are defined');
        }

        return $options;
    }
}

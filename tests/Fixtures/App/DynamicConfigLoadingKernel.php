<?php

/*
 * This file is part of the EasyAdminBundle.
 *
 * (c) Javier Eguiluz <javier.eguiluz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * The kernel used in the unit tests related to the configuration processing.
 */
class DynamicConfigLoadingKernel extends AppKernel
{
    private $backendConfig;

    public function __construct($backendConfig)
    {
        parent::__construct('dynamic_config_loading_kernel', true);
        $this->backendConfig = $backendConfig;
    }

    /**
     * This method is overridden to generate a different kernel name for each
     * configuration. Otherwise the config loaded for one unit test can end up
     * used by a different test. After lots of trials and different approaches,
     * this is the only one which always worked as expected.
     */
    public function getContainerClass()
    {
        return 'TestDynamicConfigContainer'.\md5(\serialize($this->backendConfig));
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        parent::registerContainerConfiguration($loader);

        $backendConfig = $this->backendConfig; // needed for PHP 5.3
        $loader->load(function (ContainerBuilder $container) use ($backendConfig) {
            $container->loadFromExtension('easy_admin', $backendConfig);
        });
    }
}

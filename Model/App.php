<?php

namespace LoganStellway\FishPigBedrock\Model;

/**
 * Dependencies
 */
use \FishPig\WordPress\Model\App\Integration\Exception as IntegrationException;

/**
 * App
 */
class App extends \FishPig\WordPress\Model\App
{
    /**
     * @var string
     */
    protected $_isBedrock;

    /**
     * Dependency Injection
     * 
     * @param \FishPig\WordPress\Model\Config                 $config
     * @param \FishPig\WordPress\Model\App\ResourceConnection $resourceConnection
     * @param \FishPig\WordPress\Model\App\Url                $urlBuilder
     * @param \FishPig\WordPress\Model\App\Factory            $factory
     * @param \FishPig\WordPress\Helper\Theme                 $themeHelper
     */
    public function __construct(
        \FishPig\WordPress\Model\Config $config,
        \FishPig\WordPress\Model\App\ResourceConnection $resourceConnection,
        \FishPig\WordPress\Model\App\Url $urlBuilder,
        \FishPig\WordPress\Model\App\Factory $factory,
        \FishPig\WordPress\Helper\Theme $themeHelper
    ) {
        parent::__construct($config, $resourceConnection, $urlBuilder, $factory, $themeHelper);
    }

    /**
     * WordPress install uses Bedrock?
     *
     * @return  bool
     */
    public function getBedrockEnabled()
    {
        if (is_null($this->_isBedrock)) {
            $this->_isBedrock = (bool) $this->getConfig()->getStoreConfigValue('wordpress/setup/bedrock_enabled');
        }
        return $this->_isBedrock;
    }

   /**
     * Get the absolute path to the WordPress installation
     *
     * @return false|string
     */
    public function getPath()
    {
        if (!is_null($this->path)) {
            return $this->path;
        }

        if ($this->getBedrockEnabled()) {
            return $this->getConfig()->getStoreConfigValue('wordpress/setup/bedrock_path');
        }
        return parent::getPath();
    }

    /**
     * Get the WordPress configuration using the Bedrock ".env" file
     *
     * @param string|null $key = null
     * @return mixed
     */
    public function getWpConfigValue($key = null)
    {
        if (is_null($this->wpconfig) && $this->getBedrockEnabled()) {
            $env = new \Dotenv\Dotenv(
                trim($this->getPath())
            );
            $env = $env->load();

            if (! empty($_ENV)) {
                $_ENV['DB_HOST'] = isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'localhost';
                $_ENV['DB_TABLE_PREFIX'] = isset($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : 'wp_';
                $this->wpconfig = $_ENV;
            }
        }

        // throw new \Exception('getWpConfigValue() => ' . print_r($this->wpconfig, 1));

        return parent::getWpConfigValue($key);
    }

    /**
     * Check that the WP settings allow for integration
     *
     * @return bool
     */
    protected function _validateIntegration()
    {
        if (! $this->getBedrockEnabled()) {
            return parent::_validateIntegration();
        }

        $magentoUrl = $this->wpUrlBuilder->getMagentoUrl();

        if ($this->isRoot()) {
            if ($this->wpUrlBuilder->getHomeUrl() !== $magentoUrl) {
                IntegrationException::throwException(
                    sprintf('Your home URL is incorrect and should match your Magento URL. Change to. %s', $magentoUrl)
                );
            }
        } else {
            if (strpos($this->wpUrlBuilder->getHomeUrl(), $magentoUrl) !== 0) {
                IntegrationException::throwException(
                    sprintf('Your home URL (%s) is invalid as it does not start with the Magento base URL (%s).', $this->wpUrlBuilder->getHomeUrl(), $magentoUrl)
                );
            }
            
            if ($this->wpUrlBuilder->getHomeUrl() === $magentoUrl) {
                IntegrationException::throwException('Your WordPress Home URL matches your Magento URL. Try changing your Home URL to something like ' . $magentoUrl . '/blog');
            }
        }
        
        return $this;
    }
}

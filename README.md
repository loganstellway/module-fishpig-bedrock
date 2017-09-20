# FishPig Bedrock Integration

Magento 2 module that extends [FishPig WordPress integration](https://github.com/bentideswell/magento2-wordpress-integration/) module to support [Bedrock WordPress boilerplate](https://roots.io/bedrock/)

## Installation

Install module with [Composer](https://getcomposer.org/):
```
composer require loganstellway/module-fishpig-bedrock
```

Enable Modules:
```
php bin/magento module:enable FishPig_WordPress LoganStellway_FishPigBedrock
```

Upgrade / compile Magento:
```
php bin/magento setup:upgrade && php bin/magento setup:di:compile
```

## Next Steps

  - Install Bedrock in a subdirectory as you would with the standard module. 
  - Follow the [Installation Guide](https://github.com/bentideswell/magento2-wordpress-integration/) for the FishPig Wordpress integration module (you do not need to install another WordPress instance).
  - When configuring the module, choose **Yes** for the **Bedrock Installation?** option. 
  - Configure the **Bedrock Path** option to point to the root of your Bedrock installation.

## Issues

This module has only been created to suit my needs (basic WordPress installation with no frills) and has not been tested with the other great [add-ons offered by FishPig](https://fishpig.co.uk/magento-2/wordpress-integration/add-ons/). Please feel free to add issues and contribute to the project. 

OXID eShop Enterprise Edition Core Component
============================================

The repository contains OXID eShop Enterprise Edition Core Component source code.

Installation
------------

Compilation installation
^^^^^^^^^^^^^^^^^^^^^^^^

For full installation instructions, please check the `OXID eShop compilation installation manual <https://docs.oxid-esales.com/developer/en/6.0/getting_started/installation/eshop_installation.html>`__.

Installation for Contributors
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Enterprise edition goes as a composer dependency for the OXID eShop.
Professional Edition package has to be added as a dependency required as well.

Run the following commands to install Enterprise Edition:

.. code ::

    git clone https://github.com/OXID-eSales/oxideshop_ce.git oxideshop
    cd oxideshop
    composer config repositories.oxid-esales/oxideshop-ee vcs https://github.com/OXID-eSales/oxideshop_ee
    composer config repositories.oxid-esales/oxideshop-pe vcs https://github.com/OXID-eSales/oxideshop_pe
    composer require oxid-esales/oxideshop-pe:dev-master
    composer require oxid-esales/oxideshop-ee:dev-master

If you want to install OXID eShop including example data like products, categories etc., you first need to install the demo data package:

.. code ::

  composer config repositories.oxid-esales/oxideshop-demodata-ee vcs https://github.com/OXID-eSales/oxideshop_demodata_ee
  composer require oxid-esales/oxideshop-demodata-ee:dev-b-6.0

In case Community or Professional edition was configured earlier, the database should 
be upgraded to Enterprise edition too. Please run the ``vendor/bin/reset-shop``, which will 
be available after ``composer install``. The script will install edition database structure 
by your configured edition. Note that your previously installed database will be deleted.

IDE code completion
-------------------

You can easily enable code completion in your IDE by installing `this script <https://github.com/OXID-eSales/eshop-ide-helper>`__ and generating it as described.

Testing
-------

This component comes with PHPCodeSniffer and follows PSR12 coding guidelines. To check locally, head to the folder where this component lies and run:

.. code ::

  composer install
  composer test


Bugs and Issues
---------------

If you experience any bugs or issues, please report them in the section **OXID eShop (all versions)** of https://bugs.oxid-esales.com.

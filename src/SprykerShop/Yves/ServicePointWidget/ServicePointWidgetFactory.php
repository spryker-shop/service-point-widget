<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ServicePointWidget\Checker\AddressFormChecker;
use SprykerShop\Yves\ServicePointWidget\Checker\AddressFormCheckerInterface;
use SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointSearchClientInterface;
use SprykerShop\Yves\ServicePointWidget\Dependency\Client\ServicePointWidgetToServicePointStorageClientInterface;
use SprykerShop\Yves\ServicePointWidget\Expander\ServicePointAddressExpander;
use SprykerShop\Yves\ServicePointWidget\Expander\ServicePointAddressExpanderInterface;
use SprykerShop\Yves\ServicePointWidget\Form\ServicePointAddressStepForm;
use SprykerShop\Yves\ServicePointWidget\Hydrator\ServicePointFormPreSetDataHydrator;
use SprykerShop\Yves\ServicePointWidget\Hydrator\ServicePointFormPreSetDataHydratorInterface;
use SprykerShop\Yves\ServicePointWidget\Hydrator\ServicePointFormSubmitDataHydrator;
use SprykerShop\Yves\ServicePointWidget\Hydrator\ServicePointFormSubmitDataHydratorInterface;
use SprykerShop\Yves\ServicePointWidget\Reader\AvailableServicePointReader;
use SprykerShop\Yves\ServicePointWidget\Reader\AvailableServicePointReaderInterface;
use SprykerShop\Yves\ServicePointWidget\Reader\ServicePointReader;
use SprykerShop\Yves\ServicePointWidget\Reader\ServicePointReaderInterface;
use SprykerShop\Yves\ServicePointWidget\Validator\ServicePointFormValidator;
use SprykerShop\Yves\ServicePointWidget\Validator\ServicePointFormValidatorInterface;
use Symfony\Component\Form\FormTypeInterface;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetConfig getConfig()
 */
class ServicePointWidgetFactory extends AbstractFactory
{
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointSearchClient(),
            $this->getTwigEnvironment(),
        );
    }

    public function createServicePointAddressStepForm(): FormTypeInterface
    {
        return new ServicePointAddressStepForm();
    }

    public function createServicePointFormPreSetDataHydrator(): ServicePointFormPreSetDataHydratorInterface
    {
        return new ServicePointFormPreSetDataHydrator(
            $this->createAddressFormChecker(),
        );
    }

    public function createServicePointFormSubmitDataHydrator(): ServicePointFormSubmitDataHydratorInterface
    {
        return new ServicePointFormSubmitDataHydrator(
            $this->createAvailableServicePointReader(),
            $this->createServicePointFormValidator(),
            $this->createAddressFormChecker(),
        );
    }

    public function createServicePointAddressExpander(): ServicePointAddressExpanderInterface
    {
        return new ServicePointAddressExpander();
    }

    public function createServicePointFormValidator(): ServicePointFormValidatorInterface
    {
        return new ServicePointFormValidator(
            $this->createAddressFormChecker(),
        );
    }

    public function createAddressFormChecker(): AddressFormCheckerInterface
    {
        return new AddressFormChecker(
            $this->getConfig(),
        );
    }

    public function createAvailableServicePointReader(): AvailableServicePointReaderInterface
    {
        return new AvailableServicePointReader(
            $this->getServicePointStorageClient(),
        );
    }

    public function getServicePointStorageClient(): ServicePointWidgetToServicePointStorageClientInterface
    {
        return $this->getProvidedDependency(ServicePointWidgetDependencyProvider::CLIENT_SERVICE_POINT_STORAGE);
    }

    public function getServicePointSearchClient(): ServicePointWidgetToServicePointSearchClientInterface
    {
        return $this->getProvidedDependency(ServicePointWidgetDependencyProvider::CLIENT_SERVICE_POINT_SEARCH);
    }

    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(ServicePointWidgetDependencyProvider::TWIG_ENVIRONMENT);
    }
}

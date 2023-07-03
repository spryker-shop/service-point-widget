<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Controller;

use Generated\Shared\Transfer\ServicePointSearchRequestTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetFactory getFactory()
 */
class ServicePointSearchController extends AbstractController
{
    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\ServiceTypesServicePointSearchQueryExpanderPlugin::PARAMETER_SERVICE_TYPES
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_SERVICE_TYPES = 'serviceTypes';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_OFFSET
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\Query\PaginatedServicePointSearchQueryExpanderPlugin::PARAMETER_LIMIT
     *
     * @var string
     */
    protected const SEARCH_REQUEST_PARAMETER_LIMIT = 'limit';

    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_TYPE_KEY = 'serviceTypeKey';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_STRING = 'searchString';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $servicePointSearchRequestTransfer = $this->createServicePointSearchRequestTransfer($request);

        $searchResults = $this->getFactory()
            ->createServicePointReader()
            ->searchServicePoints($servicePointSearchRequestTransfer);

        return new Response($searchResults);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchRequestTransfer
     */
    protected function createServicePointSearchRequestTransfer(Request $request): ServicePointSearchRequestTransfer
    {
        $requestParameters = [
            static::SEARCH_REQUEST_PARAMETER_OFFSET => $request->get(static::SEARCH_REQUEST_PARAMETER_OFFSET),
            static::SEARCH_REQUEST_PARAMETER_LIMIT => $request->get(static::SEARCH_REQUEST_PARAMETER_LIMIT),
        ];

        $serviceTypeKey = $request->get(static::PARAMETER_SERVICE_TYPE_KEY);

        if ($serviceTypeKey) {
            $requestParameters[static::SEARCH_REQUEST_PARAMETER_SERVICE_TYPES] = [$serviceTypeKey];
        }

        return (new ServicePointSearchRequestTransfer())
            ->setSearchString($request->get(static::PARAMETER_SEARCH_STRING))
            ->setRequestParameters($requestParameters);
    }
}
<?php declare(strict_types=1);

namespace Hoerbiger\HoerbigerCom\Catalog\Calculator\Application;

/*
 * This file is part of the Hoerbiger.HoerbigerCom package.
 */

use GuzzleHttp\Psr7\Response;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Constants\APIConstants;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Services\CalculatorService;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Services\UtilityService;
use Hoerbiger\HoerbigerCom\Catalog\Calculator\Domain\Services\ValidationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class CalculatorEndpoint implements MiddlewareInterface
{
    const URI_PATH = '/catalog/calculate';


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUri()->getPath() === self::URI_PATH && $request->getMethod() === 'POST') {
            $data = json_decode($_POST["data"]);
            $ValidationResponse = ValidationService::validateRequest($data);
            if ($ValidationResponse->get_isValid()) {
                try {
                    $data = UtilityService::createMap($data);
                    $ResponseData = CalculatorService::calculate($data);
                    if (!empty($ResponseData)) {
                        $code = 200;
                        $body = UtilityService::deliveryResponse(200, APIConstants::SUCCESSTEXT, $ResponseData);
                    } else {
                        $code = 200;
                        $body = UtilityService::deliveryResponse(200, APIConstants::EXCEPTIONMESSAGERESPONSE, $ResponseData);
                    }
                } catch (\Exception $e) {
                    $code = 500;
                    $body = UtilityService::deliveryResponse(500, APIConstants::EXCEPTIONMESSAGERESPONSE, $e);
                }
            } else {
                $code = 400;
                $body = UtilityService::deliveryResponse(400, APIConstants::BADREQUESTTEXT, $ValidationResponse->get_response());
            }
            return new Response($code, ['content-type' => 'application/json'], $body);
        }
        return $handler->handle($request);
    }
}

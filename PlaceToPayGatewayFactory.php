<?php
namespace Payum\PlaceToPay;

use Payum\PlaceToPay\Action\AuthorizeAction;
use Payum\PlaceToPay\Action\CancelAction;
use Payum\PlaceToPay\Action\ConvertPaymentAction;
use Payum\PlaceToPay\Action\CaptureAction;
use Payum\PlaceToPay\Action\NotifyAction;
use Payum\PlaceToPay\Action\RefundAction;
use Payum\PlaceToPay\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class PlaceToPayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'place_to_place',
            'payum.factory_title' => 'place_to_place',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.authorize' => new AuthorizeAction(),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);
                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Request;
use App\Services\PicPayService;

class WebhookController extends BaseController
{
    public function picpay(Request $request): \Core\Http\Response
    {
        $payload = $request->getJsonBody() ?? [];
        $service = new PicPayService();
        return $service->processarWebhook($payload);
    }
}

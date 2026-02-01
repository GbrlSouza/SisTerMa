<?php

return [
    'pix_key' => getenv('PICPAY_PIX_KEY') ?: '63ed7caf-3e50-41fc-9320-7e2050de5455',
    'client_id' => getenv('PICPAY_CLIENT_ID') ?: '',
    'client_secret' => getenv('PICPAY_CLIENT_SECRET') ?: '',
    'webhook_token' => getenv('PICPAY_WEBHOOK_TOKEN') ?: '',
    'sandbox' => filter_var(getenv('PICPAY_SANDBOX') ?: true, FILTER_VALIDATE_BOOLEAN),
    'base_url' => getenv('PICPAY_SANDBOX') === 'false'
        ? 'https://api.picpay.com'
        : 'https://api-sandbox.picpay.com',
];

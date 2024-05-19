# MidtransCI3 PHP Library

MidtransCI3 is a PHP library for integrating Midtrans payment gateway into CodeIgniter 3 applications. This library simplifies the process of setting up and making transactions through the Midtrans payment gateway.

## Installation

You can install this library via Composer:

```bash
composer require wildanmzaki/midtrans-ci3
```

## Usage

### Basic Setup

First, import the necessary classes:

```php
use WildanMZaki\MidtransCI3\CoreAPI;
```

Next: You can copy midtrans.php file to your application/config directory and change all the value from this file

But, i would highly recommend you to use .env file. You can use .env.references to set up key that required in .env file
And use contents of midtrans.env.example file if you use vlucas/php-dot-env package and paste all the contain to the midtrans.php in your application/config file

### Example Usage

To start using the library, initialize the `CoreAPI` class and set up the basic parameters:

```php
// This snippet would getting parameter based on method and option that inputted to the 'method' function
$params = CoreAPI::method($method, $option)
    ->invoice('JL-1234566')
    ->total(100000)
    ->va('89619925681')
    ->message('Test Payment')
    ->options([
        'alfamart_free_text_1' => 'jasdfkas'
    ])
    ->params();
```


### Sending Payment Request

To send the payment request to the Midtrans API, use the `send()` method:

```php
$response = CoreAPI::method($method, $option)
    // set up parameters
    ->send();
```


### Methods Description

```php
/**
* 'method': this function is initialized the CoreAPI param setup object
*/
::method($method, $option) // initialization method to create instance of CoreAPI object statically


/**
* config : set up which config file would be used by the library
*/
->config('mymidtransconfigfile')


/**
* invoice method: set up the order id
*/
->invoice($inv)

// Variasi penggunaan: bisa diisikan callback function yang mereturn data dengan type string
->invoice(function() {
    return 'string of order id'
})


/**
* inv method : similiar with invoice, but it will use library utility to generate the invoice in format Prefix-Ymd000x
*/
->inv('INV-', 2)  // this would set up order id to 'INV-20240515002'

->inv('INV-', 2, 5) // this variant would set up the order_id to: 'INV-2024-051500001'  (The third parameter is digits it's mean how long the order id number would be set up)

// other variant: use callback that return int in second parameter
->inv('INV-', function(string $prefix) {
    // $prefix is prefix that already set up for the invoice including the date when the invoice generated.
    // This can help you to count how many invoice that have same prefix (get by like query)

    return 5;
})


/**
* total : this set up the gross_amount of the transaction
*/
->total(100000)

/**
* va : this set up custom va_number and also bill_key for echannel method
* But you need to noted that not all bank use same limitation like min length or max length for the length of this custom va number
* https://docs.midtrans.com/docs/coreapi-core-api-bank-transfer-integration
*/
->va('12345567')


/**
* message: Give payment description to users like in display console alfamart or indomaret
*/
->message('Write your message here')


/**
* options: this set up the sometime required for some payment method, like alfamart_free_text_1 in cstore : alfamart method
*/
->options([
    'alfamart_free_text_1' => 'Text 1 here...',
    ....
])


/**
* items: set up the items detail that ordered by user
* please note this items parameter will also count the gross_amount of the transaction by multiplying quantity and price property
* in each items detail. You can see detailed description in example usage below
*/

->items([
    [
        id' => 'a01',
        'price' => 7000,
        'quantity' => 1,
        'name' => 'Apple'
    ],
])

// Note you can customize you price and qty column by passing config parameter like below:
->items([
    [
        id' => 'a01',
        'price' => 7000,
        'qty' => 1,
        'name' => 'Apple'
    ],
], [
    'price_column' => 'price',
    'qty_column' => 'qty'
])


/**
* customer: this set up customer detail for the transaction
*/
->customer([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john@example.com',
    'phone' => '+1234567890',
    'billing_address' => [
        // billing address details
    ],
    'shipping_address' => [
        // shipping address details
    ]
])


# result method
/**
* params: it would return the parameter that already setted up by the librar
*/
->params();

// Note: You can use this params method as setter also. But please remember that this method would replace all params created by library
->params($yourparamhere)


/**
* send: send the request, it is like calling \Midtrans\CoreAPI::charge($params) in the library automatically
*/
->send();

// Note: if you send same order id to the server the send method would catch error from midtrans and returning the status of the order id that is used previously


```


### Other Example

You can set additional options such as items, customer details, and card tokens using the provided methods:

```php
$response = CoreAPI::method($method, $option)
    ->config('midtrans')
    ->invoice('JL-1234566')
    ->total(100000)
    ->va('89619925681')
    ->message('Test Payment')
    ->items([
        [
            'id' => 'a01',
            'price' => 7000,
            'quantity' => 1,
            'name' => 'Apple'
        ]
    ])
    ->customer([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'phone' => '+1234567890',
        'billing_address' => [
            // billing address details
        ],
        'shipping_address' => [
            // shipping address details
        ]
    ])
    ->card_token('card_token_here')
    ->options([
        'alfamart_free_text_1' => 'jasdfkas'
    ])
    ->params();
```


### Handling Responses

The library handles exceptions internally. However, you can catch exceptions and handle them as needed:

```php
try {
    $response = CoreAPI::method($method, $option)
        // set up parameters
        ->send();
    // Handle success response
} catch (\Exception $e) {
    // Handle exception
}
```

## License

This library is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

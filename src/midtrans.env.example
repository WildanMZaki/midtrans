<?php

/**
 * Note copy this file if you want use .env for configuration values
 */

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Merchant id that acquired when registering midtrans
 */
$config['midtrans_merchant_id'] = $_ENV['MIDTRANS_MERCHANT_ID'];

/**
 * Fill the value with production server key if the app in production environment
 * If the app still in development, then use your sandbox server key
 */
$config['midtrans_server_key'] = $_ENV['MIDTRANS_SERVER_KEY'];

/**
 * Fill the value with production client key if the app in production environment
 * If the app still in development, then use your sandbox client key
 */
$config['midtrans_client_key'] = $_ENV['MIDTRANS_CLIENT_KEY'];

/**
 * Is production state that controll midtrans api endpoint usage
 */
$config['midtrans_is_production'] = $_ENV['MIDTRANS_IS_PRODUCTION'] === 'true';

/**
 * Sanitized Configuration
 */
$config['midtrans_is_sanitized'] = $_ENV['MIDTRANS_IS_SANITIZED'] === 'true';

/**
 * 3d party secure configuration?
 */
$config['midtrans_is_3ds'] = $_ENV['MIDTRANS_IS_3DS'] === 'true';

/**
 * Invoiced method, add a little code to your invoice based on transaction method that used by user
 */
$config['midtrans_invoiced_method'] = $_ENV['MIDTRANS_INVOICED_METHOD'] === 'true';

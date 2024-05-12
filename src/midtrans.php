<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Merchant id that acquired when registering midtrans
 */
$config['midtrans_merchant_id'] = '';

/**
 * Fill the value with production server key if the app in production environment
 * If the app still in development, then use your sandbox server key
 */
$config['midtrans_server_key'] = '';

/**
 * Fill the value with production client key if the app in production environment
 * If the app still in development, then use your sandbox client key
 */
$config['midtrans_client_key'] = '';

/**
 * Is production state that controll midtrans api endpoint usage
 */
$config['midtrans_is_production'] = false;

/**
 * Sanitized Configuration
 */
$config['midtrans_is_sanitized'] = true;

/**
 * 3d party secure configuration?
 */
$config['midtrans_is_3ds'] = true;

/**
 * Invoiced method, add a little code to your invoice based on transaction method that used by user
 */
$config['midtrans_invoiced_method'] = false;

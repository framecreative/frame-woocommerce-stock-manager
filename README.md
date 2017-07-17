# Woocommerce Stock Manager
## Frame Version

This plugin is a fork of the free Woocommerce Stock Manager Plugin.

The plugin had some bugs the author didn't seem to care about, and did not allow for the changing of the delimiter in CSV files  - this was a pretty critical problem as clients using excel have a REALLY hard time opening files delimted with anything other than a comma.

## Known issues

The current code will run on Woocommerce 3.0+ stores, but contains a lot of anti-patterns - most commonly that meta information regarding products is currently updated via the `update_post_meta()` function, rather than utilising the new Woocommerce Data Stores to manage the product data.

## Changing the delimited

````php
<?php

// Define a custom delimiter, standard is now ','
define('WC_STOCK_CSV_SEPERATOR', ';');

````





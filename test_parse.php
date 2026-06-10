<?php
$recipes = '[{"name":"Turmeric Milk","qty":"1","unit":""},{"name":"ragi roti","qty":"2","unit":""}]';
$decoded = json_decode($recipes, true);
if (is_array($decoded) && isset($decoded[0]['name'])) {
    print_r($decoded);
}

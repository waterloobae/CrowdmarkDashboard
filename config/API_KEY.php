<?php
namespace Waterloobae\CrowdmarkDashboard;

$apiKey = getenv('CROWDMARK_API_KEY') ?: $_ENV['CROWDMARK_API_KEY'] ?? 'default_key';

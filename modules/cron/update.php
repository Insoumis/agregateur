<?php

$array_sources = Source::getAll();
$now = new DateTime();

foreach ($array_sources as $Source) {
    if (!$Source->getOnline()) {
        continue;
    }
    $lastCheck = $Source->getUpdated_at();
    $diff = $now->getTimestamp() - $lastCheck->getTimestamp();
    
    if ($diff > TIME_BETWEEN_CHECKS) {
        $Source->fetch();
    }
    
    $Source->setUpdated_at(new DateTime());
    $Source->save();
}
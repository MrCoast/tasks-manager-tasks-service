<?php

namespace App\Service\EntityUpdater;

interface EntityUpdaterInterface
{
    public function update($existingEntity, $newEntity);
}

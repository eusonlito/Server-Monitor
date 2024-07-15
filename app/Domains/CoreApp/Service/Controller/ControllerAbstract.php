<?php declare(strict_types=1);

namespace App\Domains\CoreApp\Service\Controller;

use App\Domains\Core\Service\Controller\ControllerAbstract as ControllerAbstractCore;
use App\Domains\Language\Model\Language as LanguageModel;

abstract class ControllerAbstract extends ControllerAbstractCore
{
    /**
     * @return array
     */
    protected function languageOptions(): array
    {
        return LanguageModel::query()
            ->relationOrder()
            ->pluck('name', 'id')
            ->all();
    }
}

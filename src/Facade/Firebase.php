<?php
namespace BudgetControl\Notifications\Facade;

class Firebase extends \Illuminate\Support\Facades\Facade
{
    /**
     * @static sendNotification(array $tokens, string $title, string $body)
     */
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @see \BudgetControl\Notifications\Services\FirebaseNotification
     */
    protected static function getFacadeAccessor()
    {
        return 'firebase';
    }
}
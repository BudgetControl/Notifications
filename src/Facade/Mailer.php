<?php
namespace BudgetControl\Notifications\Facade;

class Mailer extends \Illuminate\Support\Facades\Facade
{
    /**
     * @static send(string|array $emailTo, string $subject, \BudgetcontrolLibs\Mailer\View\ViewInterface $view)
     */
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @see \BudgetcontrolLibs\Mailer\Service\ClientMail
     */
    protected static function getFacadeAccessor()
    {
        return 'mailer';
    }
}
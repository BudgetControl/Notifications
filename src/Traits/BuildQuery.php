<?php
declare(strict_types=1);

namespace BudgetControl\Notifications\Traits;

use BudgetControl\Notifications\Entities\OptionInterface;

trait BuildQuery
{
    /**
     * Builds an array representing the WHERE condition for a query based on the provided options.
     *
     * @param OptionInterface $options An object containing the options to construct the WHERE condition.
     * @return array The resulting WHERE condition as an associative array.
     */
    public function buildWhereCondition(OptionInterface $options): array
    {
        $conditions = [];
        foreach (get_object_vars($options) as $property => $value) {
            if ($value !== null) {
                $conditions[$property] = (string)$value;
            }
        }
        return $conditions;
    }

}
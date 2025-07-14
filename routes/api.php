<?php
/**
 *  application apps
 */

 $app->get('/notify/email/contact', ['\BudgetControl\Notifications\Http\Controller\NotificationController','sendEmail']);

 $app->get('/notify/email/auth/recovery-password', ['\BudgetControl\Notifications\Http\Controller\AuthNotifyController','recoveryPassword']);
 $app->get('/notify/email/auth/sign-up', ['\BudgetControl\Notifications\Http\Controller\AuthNotifyController','signUp']);
 $app->get('/notify/email/budget/exceeded', ['\BudgetControl\Notifications\Http\Controller\BudgetNotifyController','budgetExceeded']);
 $app->get('/notify/email/workspace/share', ['\BudgetControl\Notifications\Http\Controller\WorkspaceNotifyController','workspaceShare']);



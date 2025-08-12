<?php
/**
 *  application apps
 */

 $app->post('/notify/email/contact', ['\BudgetControl\Notifications\Http\Controller\NotificationController','sendEmail']);

 $app->post('/notify/email/auth/recovery-password', ['\BudgetControl\Notifications\Http\Controller\AuthNotifyController','recoveryPassword']);
 $app->post('/notify/email/auth/sign-up', ['\BudgetControl\Notifications\Http\Controller\AuthNotifyController','signUp']);
 $app->post('/notify/email/budget/exceeded', ['\BudgetControl\Notifications\Http\Controller\BudgetNotifyController','budgetExceeded']);
 $app->post('/notify/email/workspace/share', ['\BudgetControl\Notifications\Http\Controller\WorkspaceNotifyController','workspaceShare']);
$app->post('/notify/email/workspace/un-share', ['\BudgetControl\Notifications\Http\Controller\WorkspaceNotifyController', 'workspaceUnShare']);


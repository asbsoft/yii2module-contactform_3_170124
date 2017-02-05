
Simple Yii2 contact form
========================

This is demo template of module collected in common vendor place.

You can add this module to your system (advanced Yii2-template)
by separate adding BackendModule and FrontendModule.
In basic Yii2-template you have to add this modules together.

Otherwise you can use single CommonModule for this purpose.

In your application configs must be some tunes:
* config/params.php:
    'adminPath' => 'admin', // non-empty string for BASIC Yii application template
    'adminPath' => '',      // empty string for backend of ADVANCED Yii application template
* in bootstrap:
    AdminController::$adminPath = Yii::$app->params['adminPath'];
Default is AdminController::$adminPath = 'admin'.

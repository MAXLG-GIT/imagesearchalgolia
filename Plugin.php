<?php namespace MaxLGGit\ImageSearchAlgolia;

use Backend;
use System\Classes\PluginBase;

use Illuminate\Http\Request;
use Route;


//use MaxLGGit\ImageSearchAlgolia\Classes\Helpers\DescriptionsUpdaterHelper;


/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'ImageSearchAlgolia',
            'description' => 'No description provided yet...',
            'author' => 'MaxLGGit',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        Route::match(['get', 'post'], '/my-image-search-test', function(Request $request){
            return (new \MaxLGGit\ImageSearchAlgolia\Classes\Helpers\DescriptionsUpdaterHelper())::run();
          });   //DELETE AFTER ALL
    }




    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        //
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'MaxLGGit\ImageSearchAlgolia\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'maxlggit.imagesearchalgolia.some_permission' => [
                'tab' => 'ImageSearchAlgolia',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'imagesearchalgolia' => [
                'label' => 'ImageSearchAlgolia',
                'url' => Backend::url('maxlggit/imagesearchalgolia/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['maxlggit.imagesearchalgolia.*'],
                'order' => 500,
            ],
        ];
    }

    public function registerSchedule($schedule)
    {
        // $schedule->call(function () {

            // DescriptionsUpdaterHelper::run();

        //     $processArrivalNotifications = new ArrivalNotificationProcessor;
        //     $processArrivalNotifications->run();

        // })->daily();
        //->everyMinute();
        //->daily();


    }
}

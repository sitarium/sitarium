<?php

namespace App\Providers;

use Asset;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            base_path('vendor/components/jquery')  => base_path('public/sitarium/jquery'),
            base_path('vendor/components/bootstrap')  => base_path('public/sitarium/bootstrap'),
            base_path('vendor/jungle-gecko/ajax-form')  => base_path('public/sitarium/jungle-gecko/ajax-form'),
        ], 'assets');
        
        // Jquery
        Asset::container('jquery')->add('jquery', 'sitarium/jquery/jquery.min.js');
        
        // Bootstrap
        Asset::container('bootstrap')->add('bootstrap', 'sitarium/bootstrap/css/bootstrap.min.css');
        Asset::container('bootstrap')->add('bootstrap', 'sitarium/bootstrap/js/bootstrap.min.js');
        
        // Ajax Form
        Asset::container('ajax-form')->add('ajax-form', 'sitarium/jungle-gecko/ajax-form/css/ajax-form.css');
        Asset::container('ajax-form')->add('ajax-form', 'sitarium/jungle-gecko/ajax-form/js/ajax-form.js');
        
        // Admin
        Asset::container('admin')->add('admin', 'sitarium/admin/css/admin.css');
        
        // Fly Editor
        Asset::container('fly-editor-bootstrap')->add('bootstrap', 'sitarium/fly-editor/bootstrap/js/bootstrap.js');
        Asset::container('fly-editor-bootstrap-workaround')->add('bootstrap-workaround', 'sitarium/fly-editor/js/bootstrap-workaround.js');
        //
        Asset::container('bgpos')->add('bgpos', 'sitarium/fly-editor/js/bgpos.js');
        //
        Asset::container('fly-editor')->add('sitarium', 'sitarium/fly-editor/css/fly-editor.css');
        Asset::container('fly-editor')->add('sitarium', 'sitarium/fly-editor/js/fly-editor.js');
        //
        Asset::container('html5imageupload')->add('html5imageupload', 'sitarium/fly-editor/css/html5imageupload.css');
        Asset::container('html5imageupload')->add('html5imageupload', 'sitarium/fly-editor/js/html5imageupload.js');
        //
        Asset::container('rangy')->add('rangy-core', 'sitarium/fly-editor/js/rangy-1.3.0/rangy-core.js');
        Asset::container('rangy')->add('rangy-classapplier', 'sitarium/fly-editor/js/rangy-1.3.0/rangy-classapplier.js');
        //
        Asset::container('PastePlainText')->add('PastePlainText', 'sitarium/fly-editor/js/PastePlainText.js');
        
        
        Asset::addVersioning();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

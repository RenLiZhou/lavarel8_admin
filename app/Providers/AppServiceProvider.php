<?php

namespace App\Providers;

use App\Service\RuleService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //数据库字段长度
        Schema::defaultStringLength(191);

        \Carbon\Carbon::setLocale('zh');

        //自定义标签
        Blade::if('permission', function ($rulestr) {
            return RuleService::judgeAuth($rulestr);
        });
    }
}

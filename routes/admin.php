<?php
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', 'LoginController@index')->name('login');//登录页面
    Route::post('signIn', 'LoginController@signIn')->name('sign.in');//登录

    Route::middleware(['guest:admin'])->group(function () {
        Route::get('index', 'IndexController@index')->name('index');//后台管理页面
        Route::get('first', 'IndexController@first')->name('first');//主页
        Route::get('forbidden', 'IndexController@forbidden')->name('403');//403
        Route::put('cache', 'IndexController@flushCache')->name('cache.flush');//刷新缓存
        Route::delete('cache', 'IndexController@cleanCache')->name('cache.clean');//清理缓存
        Route::get('logout', 'LoginController@logout')->name('logout');//退出系统

        //接入授权管理
        Route::middleware(['admin.rbac', 'admin.log'])->group(function () {
            // 管理员
            Route::resource('admin', 'AdminsController', ['except' => ['show']]);
            Route::put('admin/{admin}', 'AdminsController@update')->name('admin.update');
            Route::patch('admin/{admin}', 'AdminsController@updateStatus')->name('admin.active');//修改状态
            Route::get('admin/{admin}/password', 'AdminsController@editPwd')->name('admin.safe');//修改密码
            Route::patch('admin/{admin}/password', 'AdminsController@updatePwd')->name('admin.safe');//修改密码

            // 权限
            Route::resource('rule', 'RulesController', ['except' => ['show']]);
            Route::post('rule/{rule}/islog', 'RulesController@updateIsLog')->name('rule.islog'); //设置是否记录日志
            Route::post('rule/{rule}/sort', 'RulesController@updateSort')->name('rule.sort'); //修改排序

            //角色
            Route::resource('role', 'RolesController', ['except' => ['show', 'create', 'edit']]);
            Route::get('role/{role}/rule', 'RolesController@setRules')->name('role.set');
            Route::patch('role/{role}/rule', 'RolesController@settedRules')->name('role.setted');

            //日志
            Route::get('system/adminlog', 'SystemsController@adminLog')->name('admin.admin.log');
        });

    });
});

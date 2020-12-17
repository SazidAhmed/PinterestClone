<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/','FrontendController@index');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
// Category routes
Route::get('/category','CategoryController@index')->middleware('auth');
Route::post('/createCategory','CategoryController@store')->middleware('auth');

Route::put('category/{id}/edit','CategoryController@update')->middleware('auth');
Route::delete('/category/{id}/delete','CategoryController@destroy')->middleware('auth');
// Category routes end
// album routes
Route::get('/albums/create','AlbumController@create')->name('album.create')->middleware('auth');
Route::post('/albums/store','AlbumController@store')->middleware('auth');

Route::get('/albums','AlbumController@index')->middleware('auth'); //albumlist page
Route::get('/getalbums','AlbumController@getAlbums')->middleware('auth');//albumlist

Route::put('albums/{id}/edit','AlbumController@update')->middleware('auth');
Route::delete('/albums/{id}/delete','AlbumController@destroy')->middleware('auth');
// album routes end

Route::get('/{id}/category','FrontendController@albumCategory')->name('album.category');

Route::get('/profile','FollowController@profile')->name('profile')->middleware('auth');

Route::get('/user/{id}','FollowController@userProfilePic')->middleware('auth');
Route::post('profile-pic','FollowController@updateProfilePic')->middleware('auth');
Route::post('bg-pic','FollowController@updatebgPic')->middleware('auth');
Route::get('/user/bg/{id}','FollowController@userbgPic')->middleware('auth');

Route::post('/follow',"FollowController@followUnfollow")->middleware('auth');

Route::get('/user/profile/{id}','FrontendController@userAlbum')->name('user.album');

Route::get('/albums/{slug}/{id}','GalleryController@viewAlbum')->name('view.album');

Route::get('getimages','GalleryController@images')->middleware('auth');;

Route::delete('/image/{id}','GalleryController@destroy');

Route::get('upload/images/{id}','GalleryController@create')->middleware('auth');
Route::post('uploadImage','GalleryController@upload')->middleware('auth');


<?php

namespace App\Http\Controllers;

use App;
use App\Models\User;
use Auth;
use Hash;
use Input;
use Request;
use Response;
<<<<<<< HEAD
use App\Models\User;
=======
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
use Wa72\HtmlPageDom\HtmlPageCrawler;

class UpdateController extends Controller
{
    public function submit()
    {
        $this->middleware('auth');

        $site = Site::where([
<<<<<<< HEAD
            'host' => Request::server('HTTP_HOST'),
=======
            'host'   => Request::server('HTTP_HOST'),
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
            'active' => true,
        ])->first();

        if ($site != null && $site->existsOnDisk()) {
            $page = Input::get('pathname');
            if ($page == '' or $page == '/') {
                $page = 'index';
            }

            $requested_page = $site->getPagePath($page);

            if ($requested_page !== false) {
                // Filtering inputs
                $submission = Input::get('submission');
                $source_crawler = new HtmlPageCrawler($submission);
                $source_crawler->filter('include')->setInnerHtml('');

                // Saving inputs
                $target_crawler = new HtmlPageCrawler(file_get_contents($requested_page));
                $target_crawler->filter('body')->html($source_crawler);
                $site->savePage($page, $target_crawler->saveHTML());

                return Response::json([
<<<<<<< HEAD
                    'code' => 0,
=======
                    'code'    => 0,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                    'message' => 'C\'est fait !',
                ]);
            } else {
                // Page still not found...
                App::abort(404);
            }
        } else {
            // SITARIUM 404 Page
            App::abort(404);
        }
    }

    public function image_upload()
    {
        $this->middleware('auth');

        $site = Site::where([
<<<<<<< HEAD
            'host' => Request::server('HTTP_HOST'),
=======
            'host'   => Request::server('HTTP_HOST'),
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
            'active' => true,
        ])->first();

        if ($site != null && $site->existsOnDisk()) {
            $tmp_data = explode(',', Input::get('data'));
            $img_data = base64_decode($tmp_data[1]);

            $tmp_name = explode('.', Input::get('name'));
            $extension = strtolower(end($tmp_name));
            $filename = substr(Input::get('name'), 0, -(strlen($extension) + 1)).'.'.substr(sha1(time()), 0, 6).'.'.$extension;

            $site->saveImage($filename, $img_data);

            return Response::json([
<<<<<<< HEAD
                'status' => 'success',
                'url' => 'images/uploads/'.$filename.'?'.time(), // added the time to force update when editing multiple times
=======
                'status'   => 'success',
                'url'      => 'images/uploads/'.$filename.'?'.time(), // added the time to force update when editing multiple times
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                'filename' => $filename,
            ]);
        } else {
            // SITARIUM 404 Page
            App::abort(404);
        }
    }

    public function create_backup()
    {
        $this->middleware('auth');

        $site = Site::where([
<<<<<<< HEAD
            'host' => Request::server('HTTP_HOST'),
=======
            'host'   => Request::server('HTTP_HOST'),
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
            'active' => true,
        ])->first();

        if ($site != null && $site->existsOnDisk()) {
            $backup_name = preg_replace('/[^a-zA-Z0-9-]/', '', trim(Input::get('backup_name')));

            if ($backup_name != '') {
                if ($site->backupExists($backup_name)) {
                    return Response::json([
<<<<<<< HEAD
                        'code' => -1,
=======
                        'code'    => -1,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                        'message' => 'Ce nom de sauvegarde est déjà pris. Essayons autre chose.',
                    ]);
                } else {
                    $backup_date = $site->makeBackup($backup_name);

                    return Response::json([
<<<<<<< HEAD
                        'code' => 0,
                        'message' => 'C\'est fait !',
=======
                        'code'          => 0,
                        'message'       => 'C\'est fait !',
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                        'callback_vars' => [
                            'backup_name' => $backup_name,
                            'backup_date' => $backup_date,
                        ],
                    ]);
                }
            } else {
                return Response::json([
<<<<<<< HEAD
                    'code' => -1,
=======
                    'code'    => -1,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                    'message' => 'Oula, ce nom de sauvegarde n\'est pas autorisé ! Contentons-nous des lettres, chiffres et tiret.',
                ]);
            }
        } else {
            // SITARIUM 404 Page
            App::abort(404);
        }
    }

    public function delete_backup()
    {
        $this->middleware('auth');

        $site = Site::where([
<<<<<<< HEAD
            'host' => Request::server('HTTP_HOST'),
=======
            'host'   => Request::server('HTTP_HOST'),
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
            'active' => true,
        ])->first();

        if ($site != null && $site->existsOnDisk()) {
            $backup_name = preg_replace('/[^a-zA-Z0-9-]/', '', trim(Input::get('backup_name')));

<<<<<<< HEAD
            if (! $site->backupExists($backup_name)) {
                return Response::json([
                    'code' => -1,
=======
            if (!$site->backupExists($backup_name)) {
                return Response::json([
                    'code'    => -1,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                    'message' => 'Sauvegarde introuvable.',
                ]);
            } else {
                $site->deleteBackup($backup_name);

                return Response::json([
<<<<<<< HEAD
                    'code' => 0,
=======
                    'code'    => 0,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                    'message' => 'Sauvegarde supprimée !',
                ]);
            }
        } else {
            // SITARIUM 404 Page
            App::abort(404);
        }
    }

    public function restore_backup()
    {
        $this->middleware('auth');

        $site = Site::where([
<<<<<<< HEAD
            'host' => Request::server('HTTP_HOST'),
=======
            'host'   => Request::server('HTTP_HOST'),
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
            'active' => true,
        ])->first();

        if ($site != null && $site->existsOnDisk()) {
            $backup_name = preg_replace('/[^a-zA-Z0-9-]/', '', trim(Input::get('backup_name')));

<<<<<<< HEAD
            if (! $site->backupExists($backup_name)) {
                return Response::json([
                    'code' => -1,
=======
            if (!$site->backupExists($backup_name)) {
                return Response::json([
                    'code'    => -1,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                    'message' => 'Sauvegarde introuvable.',
                ]);
            } else {
                $site->restoreBackup($backup_name);

                return Response::json([
<<<<<<< HEAD
                    'code' => 0,
=======
                    'code'    => 0,
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                    'message' => 'Sauvegarde restaurée !',
                ]);
            }
        } else {
            // SITARIUM 404 Page
            App::abort(404);
        }
    }

    public function populate()
    {
        if (Auth::check() && Auth::user()->name == 'Jungle Gecko') {
            echo User::firstOrCreate([
<<<<<<< HEAD
                'name' => Input::get('name'),
                'email' => Input::get('email'),
=======
                'name'     => Input::get('name'),
                'email'    => Input::get('email'),
>>>>>>> refs/remotes/sitarium-master/analysis-8jl2wy
                'password' => Hash::make(Input::get('password')),
            ]);
        } else {
            App::abort(404);
        }
    }

    public function link()
    {
        if (Auth::check() && Auth::user()->name == 'Jungle Gecko') {
            $user = User::where('name', Input::get('name'))->orWhere('email', Input::get('email'))->first();
            if ($user == null) {
                echo 'User not found';
            } else {
                $site = Site::where('name', Input::get('site'))->orWhere('host', Input::get('host'))->first();
                if ($site == null) {
                    echo 'Site not found';
                } else {
                    $user->sites()->attach($site);
                    echo 'Done.';
                }
            }
        } else {
            App::abort(404);
        }
    }
}

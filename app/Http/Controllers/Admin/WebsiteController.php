<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteFormRequest;
use App\Models\Fs;
use App\Models\User;
use App\Models\Website;
use Request;
use Response;
use View;

class WebsiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Returns a paginated list of websites.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginate($user = null)
    {
        $websites = Website::paginate(2);

        if ($user != null) {
            $websites->map(function ($website, $key) use ($user) {
                $website->authorized = $website->users->contains(function ($value, $key) use ($user) {
                    return $value->id == intval($user);
                });

                return $website;
            });
        }

        return Response::json(View::make('admin.websites.page')->with(compact('websites', 'user'))->render());
    }

    /**
     * Browse the website.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse($id = null)
    {
        $website = Website::findOrNew($id);
        $operation = Request::input('operation');

        if (! isset($operation)) {
            return view('admin.websites.browse')->with(compact('website'));
        } else {
            $id = Request::input('id');
            $parent = Request::input('parent');
            $text = Request::input('text');

            if (isset($operation)) {
                $fs = new Fs($website->pathOnDisk());
                $rslt = null;
                switch ($operation) {
                    case 'get_node':
                        $node = isset($id) && $id !== '#' ? $id : '/';
                        $rslt = $fs->lst($node, (isset($id) && $id === '#'));
                        break;
                    case 'get_content':
                        $node = isset($id) && $id !== '#' ? $id : '/';
                        $rslt = $fs->data($node);
                        break;
                    case 'create_node':
                        $node = isset($id) && $id !== '#' ? $id : '/';
                        $rslt = $fs->create($node, isset($text) ? $text : '', (! isset($type) || $type !== 'file'));
                        break;
                    case 'rename_node':
                        $node = isset($id) && $id !== '#' ? $id : '/';
                        $rslt = $fs->rename($node, isset($text) ? $text : '');
                        break;
                    case 'delete_node':
                        $node = isset($id) && $id !== '#' ? $id : '/';
                        $rslt = $fs->remove($node);
                        break;
                    case 'move_node':
                        $node = isset($id) && $id !== '#' ? $id : '/';
                        $parn = isset($parent) && $parent !== '#' ? $parent : '/';
                        $rslt = $fs->move($node, $parn);
                        break;
                    case 'copy_node':
                        $node = isset($id) && $id !== '#' ? $id : '/';
                        $parn = isset($parent) && $parent !== '#' ? $parent : '/';
                        $rslt = $fs->copy($node, $parn);
                        break;
                    default:
                        throw new Exception('Unsupported operation: '.$operation);
                        break;
                }

                return Response::json($rslt);
            }
        }
    }

    /**
     * Functions for JSTree.
     */
    public function fs($id = null)
    {
        $operation = Request::input('operation');
        $id = Request::input('id');
        $parent = Request::input('parent');
        $text = Request::input('text');

        if (isset($operation)) {
            $fs = new Fs(dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'root'.DIRECTORY_SEPARATOR);
            $rslt = null;
            switch ($operation) {
                case 'get_node':
                    $node = isset($id) && $id !== '#' ? $id : '/';
                    $rslt = $fs->lst($node, (isset($id) && $id === '#'));
                    break;
                case 'get_content':
                    $node = isset($id) && $id !== '#' ? $id : '/';
                    $rslt = $fs->data($node);
                    break;
                case 'create_node':
                    $node = isset($id) && $id !== '#' ? $id : '/';
                    $rslt = $fs->create($node, isset($text) ? $text : '', (! isset($type) || $type !== 'file'));
                    break;
                case 'rename_node':
                    $node = isset($id) && $id !== '#' ? $id : '/';
                    $rslt = $fs->rename($node, isset($text) ? $text : '');
                    break;
                case 'delete_node':
                    $node = isset($id) && $id !== '#' ? $id : '/';
                    $rslt = $fs->remove($node);
                    break;
                case 'move_node':
                    $node = isset($id) && $id !== '#' ? $id : '/';
                    $parn = isset($parent) && $parent !== '#' ? $parent : '/';
                    $rslt = $fs->move($node, $parn);
                    break;
                case 'copy_node':
                    $node = isset($id) && $id !== '#' ? $id : '/';
                    $parn = isset($parent) && $parent !== '#' ? $parent : '/';
                    $rslt = $fs->copy($node, $parn);
                    break;
                default:
                    throw new Exception('Unsupported operation: '.$operation);
                    break;
            }

            return Response::json($rslt);
        }
    }

    /**
     * Show the website form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForm($id = null)
    {
        $website = Website::findOrNew($id);

        return view('admin.websites.form')->with(compact('website'));
    }

    /**
     * Save the updated or created website.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(WebsiteFormRequest $request)
    {
        $website = Website::updateOrCreate(['id' => $request->input('id')], $request->all());

        if (! $website) {
            return Response::json([
                'code' => 1,
                'message' => 'Failed to save website.',
            ]);
        }

        return Response::json([
            'code' => 0,
            'message' => 'Website saved.',
            'callback_vars' => [
                'id' => $website->id,
            ],
        ]);
    }

    /**
     * Delete a website.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        $id = Request::input('id');

        $website = Website::findOrFail($id);

        if (! $website->delete()) {
            return Response::json([
                'code' => 1,
                'message' => 'Failed to delete website.',
            ]);
        }

        return Response::json([
            'code' => 0,
            'message' => 'Website deleted.',
            'callback_vars' => [
                'redirect_url' => url('admin'),
            ],
        ]);
    }

    /**
     * Authorize a user on a website.
     *
     * @return \Illuminate\Http\Response
     */
    public function authorizeUser()
    {
        $user = User::findOrFail(Request::input('userId'));

        if (Request::input('authorized') == true) {
            $user->websites()->attach(Request::input('websiteId'));
        } else {
            $user->websites()->detach(Request::input('websiteId'));
        }

        return Response::json([
            'code' => 0,
            'message' => 'Saved.',
        ]);
    }
}

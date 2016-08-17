<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    private $websites_folder;

    private $backups_folder;

    public function __construct(array $attributes = [])
    {
        $this->websites_folder = public_path(env('App_WEBSITES_FOLDER', 'websites'));
        $this->backups_folder = env('App_BACKUPS_FOLDER', 'backups');
        parent::__construct($attributes);
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'websites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'host',
        'name',
        'email',
        'active',
    ];

    public function contactRequests()
    {
        return $this->hasMany('\App\Models\ContactRequest');
    }

    public function users()
    {
        return $this->belongsToMany('\App\Models\User');
    }

    public function isUserAuthorized($user)
    {
        return collect($this->users)->contains('name', $user->name);
    }

    /*
     * Functions linked to files
     */
    public function existsOnDisk()
    {
        return is_dir($this->websites_folder.'/'.$this->host);
    }

    public function getPagePath($requested_page)
    {
        $path = $this->websites_folder.'/'.$this->host.'/'.$requested_page.'.blade.php';

        if (is_file($path)) {
            return $path;
        } else {
            return false;
        }
    }

    public function getIncludeFiles()
    {
        return
            collect(glob($this->websites_folder.'/'.$this->host.'/[_]*[.blade.php]'))
            ->keyBy(function ($item) {
                return substr(strrchr($item, '/'), 2, -10);
            })
            ->toArray();
    }

    public function getEditableFiles()
    {
        return
            collect(glob($this->websites_folder.'/'.$this->host.'/[!_]*[.blade.php]'))
            ->filter(function ($item, $key) {
                return is_file($item);
            })
            ->map(function ($item, $key) {
                return [
                    'name' => substr(strrchr($item, '/'), 1, -10),
                    'date' => date('d/m/Y H:i', filemtime($item)),
                ];
            })
            ->keyBy('name')
            ->sortBy('name')
            ->toArray();
    }

    public function savePage($page, $content)
    {
        $requested_page = $this->getPagePath($page);

        if ($requested_page !== false) {
            return file_put_contents($requested_page, $content);
        } else {
            return false;
        }
    }

    public function saveImage($filename, $img_data)
    {
        if (! is_dir($this->websites_folder.'/'.$this->host.'/images/uploads/')) {
            mkdir($this->websites_folder.'/'.$this->host.'/images/uploads', null, true);
        }

        $handle = fopen($this->websites_folder.'/'.$this->host.'/images/uploads/'.$filename, 'w');
        fwrite($handle, $img_data);
        fclose($handle);
    }

    public function backupExists($backup_name)
    {
        return is_dir($this->websites_folder.'/'.$this->host.'/'.$this->backups_folder.'/'.$backup_name);
    }

    public function getBackups()
    {
        return
            collect(glob($this->websites_folder.'/'.$this->host.'/'.$this->backups_folder.'/[!_]*'))
            ->sort(function ($a, $b) {
                return filemtime($a) - filemtime($b);
            })
            ->filter(function ($item, $key) {
                return is_file($item);
            })
            ->map(function ($item, $key) {
                return [
                    'name' => substr(strrchr($item, '/'), 1),
                    'date' => date('d/m/Y H:i', filemtime($item)),
                ];
            })
            ->keyBy('name')
            ->sortBy('name')
            ->toArray();
    }

    public function makeBackup($backup_name)
    {
        FileHelper::recursiveCopy($this->websites_folder.'/'.$this->host, $this->websites_folder.'/'.$this->host.'/'.$this->backups_folder.'/'.$backup_name, [
            $this->backups_folder,
            '.svn',
        ]);

        return date('d/m/Y H:i', filemtime($this->websites_folder.'/'.$this->host.'/'.$this->backups_folder.'/'.$backup_name));
    }

    public function deleteBackup($backup_name)
    {
        FileHelper::recursiveDelete($this->websites_folder.'/'.$this->host.'/'.$this->backups_folder.'/'.$backup_name);
    }

    public function restoreBackup($backup_name)
    {
        $tmp_folder = storage_path().'/tmp/'.$this->host.'/'.date('Ymd-His');
        // copying selected backup into a safe place
        FileHelper::recursiveCopy($this->websites_folder.'/'.$this->host.'/'.$this->backups_folder.'/'.$backup_name, $tmp_folder);
        // copying also the backups
        FileHelper::recursiveCopy($this->websites_folder.'/'.$this->host.'/'.$this->backups_folder, $tmp_folder.'/'.$this->backups_folder);
        // deleting the content of the website
        FileHelper::recursiveDelete($this->websites_folder.'/'.$this->host, true);
        // moving saved backup at the right place
        FileHelper::recursiveCopy($tmp_folder, $this->websites_folder.'/'.$this->host);
        FileHelper::recursiveDelete($tmp_folder);
    }
}

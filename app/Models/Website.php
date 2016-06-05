<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\FileHelper;

class Website extends Model
{

    private $websites_folder;

    private $backups_folder;

    public function __construct()
    {
        $this->websites_folder = public_path(env('SITARIUM_WEBSITES_FOLDER', 'websites'));
        $this->backups_folder = env('SITARIUM_BACKUPS_FOLDER', 'backups');
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
        'active'
    ];

    public function contactRequests()
    {
        return $this->hasMany('\Sitarium\Models\ContactRequest');
    }

    public function users()
    {
        return $this->belongsToMany('\Sitarium\Models\User');
    }

    /*
     * Functions linked to files
     */
    public function existsOnDisk()
    {
        return is_dir($this->websites_folder . '/' . $this->host);
    }

    public function getPagePath($requested_page)
    {
        $path = $this->websites_folder . '/' . $this->host . '/' . $requested_page . '.blade.php';
        
        if (is_file($path)) {
            return $path;
        } else {
            return false;
        }
    }

    public function getIncludeFiles()
    {
        $files = glob($this->websites_folder . '/' . $this->host . '/[_]*[.blade.php]');
        $include_files = array();
        foreach ($files as $file) {
            $include_files[substr(strrchr($file, '/'), 2, - 10)] = $file;
        }
        return $include_files;
    }

    public function getEditableFiles()
    {
        /*
         * TODO order by name
         */
        $files = glob($this->websites_folder . '/' . $this->host . '/[!_]*[.blade.php]');
        $editable_files = array();
        foreach ($files as $file) {
            // Filtering folders
            if (is_file($file)) {
                $filename = substr(strrchr($file, '/'), 1, - 10);
                $editable_files[$filename] = array(
                    'name' => $filename,
                    'date' => date('d/m/Y H:i', filemtime($file))
                );
            }
        }
        return $editable_files;
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
        if (! is_dir($this->websites_folder . '/' . $this->host . '/images/uploads/')) {
            mkdir($this->websites_folder . '/' . $this->host . '/images/uploads', null, true);
        }
        
        $handle = fopen($this->websites_folder . '/' . $this->host . '/images/uploads/' . $filename, 'w');
        fwrite($handle, $img_data);
        fclose($handle);
    }

    public function backupExists($backup_name)
    {
        return is_dir($this->websites_folder . '/' . $this->host . '/' . $this->backups_folder . '/' . $backup_name);
    }

    public function getBackups()
    {
        $files = glob($this->websites_folder . '/' . $this->host . '/' . $this->backups_folder . '/[!_]*');
        usort($files, create_function('$a, $b', 'return filemtime($a) - filemtime($b);'));
        $backups = array();
        foreach ($files as $file) {
            // Filtering files
            if (is_dir($file)) {
                $filename = substr(strrchr($file, '/'), 1);
                $backups[$filename] = array(
                    'name' => $filename,
                    'date' => date('d/m/Y H:i', filemtime($file))
                );
            }
        }
        return $backups;
    }

    public function makeBackup($backup_name)
    {
        FileHelper::recursiveCopy($this->websites_folder . '/' . $this->host, $this->websites_folder . '/' . $this->host . '/' . $this->backups_folder . '/' . $backup_name, array(
            $this->backups_folder,
            '.svn'
        ));
        return date('d/m/Y H:i', filemtime($this->websites_folder . '/' . $this->host . '/' . $this->backups_folder . '/' . $backup_name));
    }

    public function deleteBackup($backup_name)
    {
        FileHelper::recursiveDelete($this->websites_folder . '/' . $this->host . '/' . $this->backups_folder . '/' . $backup_name);
    }

    public function restoreBackup($backup_name)
    {
        $tmp_folder = storage_path() . '/tmp/' . $this->host . '/' . date('Ymd-His');
        // copying selected backup into a safe place
        FileHelper::recursiveCopy($this->websites_folder . '/' . $this->host . '/' . $this->backups_folder . '/' . $backup_name, $tmp_folder);
        // copying also the backups
        FileHelper::recursiveCopy($this->websites_folder . '/' . $this->host . '/' . $this->backups_folder, $tmp_folder . '/' . $this->backups_folder);
        // deleting the content of the website
        FileHelper::recursiveDelete($this->websites_folder . '/' . $this->host, true);
        // moving saved backup at the right place
        FileHelper::recursiveCopy($tmp_folder, $this->websites_folder . '/' . $this->host);
        FileHelper::recursiveDelete($tmp_folder);
    }
}
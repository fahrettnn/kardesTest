<?php
namespace Lovesta;

defined('FCPATH') or die("Direct script access denied");

class Lovesta
{
    public function make(array $args)
    {
        $action_name = $args[1] ?? null;
        $folder = $args[2] ?? null;
        $class_name = $args[3] ?? null;

        switch ($action_name) {
            case 'make:plugin':
                $this->makePlugin($folder);
                break;
            case "make:controller":
                $this->makeController($folder, $class_name);
                break;
            case "make:service":
                $this->makeService($folder, $class_name);
                break;
            case 'make:model':
                $this->makeModel($folder, $class_name);
                break;
            case 'make:migration':
                $this->makeMigration($folder, $class_name);
                break;
            case 'make:language':
                $this->makeLanguage($folder);
                break;
            default:
                $this->message("Unknown command " . $action_name);
                break;
        }
    }

    public function migrate(array $args)
    {
        $action = $args[1] ?? null;
        $folder = $args[2] ?? null;
        $file_name = $args[3] ?? null;

        if ($action == 'migrate' || $action == 'migrate:rollback') 
        {
            $this->runMigration($action, $folder, $file_name);
        } elseif ($action == 'migrate:refresh') {
            $this->refreshMigration($folder, $file_name);
        }
    }

    public function help(string|array $version): void
    {
        $version = is_array($version) ? $version[0] : $version;

        $formattedMessage = <<<HELP
        ========================================================================================================================
        \t\t\t\tLovesta Php Framework v$version Command Line Tool
        ========================================================================================================================

        \tDatabase:
                migrate           Locates and runs a migration from the specified plugin folder.
                migrate:refresh   Does a rollback followed by a migration.
                migrate:rollback  Runs the 'down' method for a migration in the specified plugin folder.

        \tGenerators:
                make:plugin   	  Generates a new folder with all essential plugin files.
                make:migration    Generates a new migration file.
                make:controller   Generates a new controller file.  
                make:model        Generates a new model file.
                make:service      Generates a new service file.  
                make:language 	  Generates a new language.

        ========================================================================================================================
        This Framework may not be copied, reproduced or distributed without written permission. All copyrights reserved.
        ========================================================================================================================
        HELP;
        echo $formattedMessage;
    }

    private function makePlugin($folder)
    {
        $folder = $this->prepareFolder($folder, 'plugins');

        if (file_exists($folder)) {
            $this->message("The '$folder' plugin folder already exists", true);
        } else {
            $this->createFolderStructure($folder, [
                'assets/css/',
                'assets/images/',
                'assets/fonts/',
                'assets/js/',
                'controllers/',
                'models/',
                'services/',
                'views/',
                'migrations/'
            ]);

            $this->copySampleFiles([
                'app/lovesta/samples/views/view-sample.php' => $folder . '/views/view.php',
                'app/lovesta/samples/assets/js-sample.js' => $folder . '/assets/js/plugin.js',
                'app/lovesta/samples/assets/css-sample.css' => $folder . '/assets/css/style.css',
                'app/lovesta/samples/config-sample.json' => $folder . '/config.json',
                'app/lovesta/samples/plugin-sample.php' => $folder . '/plugin.php'
            ]);

            $this->message("Plugin creation complete! Plugin folder: $folder");
        }
    }

    private function makeController($folder, $class_name)
    {
        $folder = $this->preparePluginFolder($folder);
        $controller_folder = $folder . "controllers/";

        $this->checkPluginFolderExists($folder);

        $this->createFolder($controller_folder);

        $this->generateFileFromSample('app/lovesta/samples/controllers/controller-sample.php', $controller_folder, $class_name, 'Controller');

        $this->message("Controller file created. Folder: $controller_folder, Class: $class_name");
    }

    private function makeService($folder, $class_name)
    {
        $folder = $this->preparePluginFolder($folder);
        $services_folder = $folder . "services/";

        $this->checkPluginFolderExists($folder);

        $this->createFolder($services_folder);

        $this->generateFileFromSample('app/lovesta/samples/services/service-sample.php', $services_folder, $class_name, 'Services');
        $this->generateFileFromSample('app/lovesta/samples/services/iservice-sample.php', $services_folder, 'I' . $class_name, 'Service');

        $this->message("Services files created. Folder: $services_folder, Class: $class_name");
    }

    private function makeModel($folder, $class_name)
    {
        $folder = $this->preparePluginFolder($folder);
        $model_folder = $folder . "models/";

        $this->checkPluginFolderExists($folder);

        $this->createFolder($model_folder);

        $this->generateFileFromSample('app/lovesta/samples/models/model-sample.php', $model_folder, $class_name, 'Model');
        $this->generateFileFromSample('app/lovesta/samples/models/model-sample-first.php', $model_folder, $class_name, '');

        $this->message("Model file created. Folder: $model_folder, Class: $class_name");
    }

    private function makeMigration($folder, $class_name)
	{
	    $folder = $this->preparePluginFolder($folder);
	    $migration_folder = $folder . "migrations/";

	    $this->checkPluginFolderExists($folder);

	    if (!file_exists($migration_folder)) {
	        $this->createFolder($migration_folder);
	    }

	    $file_sample = 'app/lovesta/samples/migrations/migrations-sample.php';

	    if (!file_exists($file_sample)) {
	        $this->message("Sample migration file not found in: $file_sample", true);
	    }

	    if (empty($class_name)) {
	        $this->message("Please provide a valid class name for your migration file", true);
	    }

	    $class_name = $this->sanitizeClassName($class_name);
	    $table_name = strtolower($class_name);

	    $content = file_get_contents($file_sample);
	    $content = str_replace("{TABLE_NAME}", $table_name, $content);
	    $content = str_replace("{CLASS_NAME}", $class_name, $content);

	    $filename = $migration_folder . date("Y-m-d_His_") . $table_name . '.php';
	    file_put_contents($filename, $content);

	    $this->message("Migration file created. Folder: $migration_folder, Class: $class_name");
	}

    private function makeLanguage($folder)
    {
        $folder = 'language/' . strtolower($folder);

        if (file_exists($folder)) {
            $this->message("The '$folder' language folder already exists", true);
        } else {
            mkdir($folder, 0777, true);

            $language_file = $folder . '/lang.php';
            $language_file_source = 'app/lovesta/samples/language-sample.php';

            if (file_exists($language_file_source)) {
                copy($language_file_source, $language_file);
            } else {
                $this->message("Language sample file not found in: $language_file_source");
            }

            $this->message("Language creation complete! Language folder: $folder");
        }
    }

    private function runMigration($action, $pluginFolder, $file_name)
    {
        $folders = $this->prepareMigrationFolders($pluginFolder);

        foreach ($folders as $folder) {
            $migrationFolder = $folder . '/migrations/';

            if (!is_dir($migrationFolder)) {
                $this->message("No migration files found in that location: $migrationFolder", false);
                continue;
            }

            if (!empty($file_name)) {
                $this->runSingleMigration($action, $migrationFolder, $file_name);
            } else {
                $this->runAllMigrations($action, $migrationFolder);
            }
        }
    }

    private function refreshMigration($folder, $file_name)
    {
        $this->migrate(['lovesta', 'migrate:rollback', $folder, $file_name]);
        $this->migrate(['lovesta', 'migrate', $folder, $file_name]);
    }

    private function runSingleMigration($action, $folder, $file_name)
    {
        $file = $folder . $file_name;

        if (!file_exists($file)) {
            $this->message("Migration file not found: $file", true);
        }

        $this->message("Migrating file: $file");

        require_once $file;

        $class_name = $this->getClassNameFromFileName($file);
        $migrationClass = new ("\Migration\\".$class_name);

        if ($action == 'migrate') {
            $migrationClass->up();
        } else {
            $migrationClass->down();
        }

        $this->message("Migration complete for file: $file");
    }

    private function runAllMigrations($action, $folder)
    {
        $files = glob($folder . '*.php');

        if (empty($files)) {
            $this->message("No migration files found in specified folder: $folder");
        }

        foreach ($files as $file) 
        {
            [$folder, $filename] = explode("/migrations/", $file, 2);
            $folder = $folder."/migrations/";
            $this->runSingleMigration($action, $folder, $filename);
        }

        $this->message("All migrations in folder $folder complete");
    }

    private function prepareFolder($folder, $prefix)
    {
        $folder = $prefix . '/' . $folder . '/';

        return $folder;
    }

    private function preparePluginFolder($folder)
    {
        return $this->prepareFolder($folder, 'plugins');
    }

    private function prepareMigrationFolders($folder)
    {
        if ($folder == 'all') {
            return glob('plugins/*', GLOB_ONLYDIR);
        }
        return [$this->preparePluginFolder($folder)];
    }

    private function checkPluginFolderExists($folder)
    {
        if (!file_exists($folder)) {
            $this->message("Plugin folder not found: $folder", true);
        }
    }

    private function createFolder($folderPath)
    {
        mkdir($folderPath, 0777, true);

        $folderPathMsg = ucfirst($folderPath);

        $this->message("'$folderPathMsg' folder created");
    }

    private function createFolderStructure($root, $folders)
    {
        foreach ($folders as $folder) {
            $this->createFolder($root . $folder);
        }
    }

    private function generateFileFromSample($sample_path, $output_folder, $class_name, $type)
    {
        if (empty($class_name)) {
            $this->message("Please provide a valid class name for your $type file", true);
        }

        $class_name = $this->sanitizeClassName($class_name);
        $file_name = $output_folder . $class_name.$type . '.php';

        if (file_exists($file_name)) {
            $this->message("$type file already exists: $file_name", true);
        }

        $content = file_get_contents($sample_path);
        $content = str_replace("{PLUGIN_NAME}", ucfirst($this->getPluginName($output_folder)), $content);
        $content = str_replace("{CLASS_NAME}", $class_name, $content);

        file_put_contents($file_name, $content);

        $this->message("$type file created. File: $file_name");
    }

    private function sanitizeClassName($class_name)
    {
        $class_name = preg_replace("/[^a-zA-Z_\-]/", "", $class_name);
        $class_name = str_replace("-", "_", $class_name);
        $class_name = ucfirst($class_name);

        return $class_name;
    }

    private function getPluginName($folder)
    {
        return ucfirst(basename(dirname($folder)));
    }

    private function copySampleFiles($files)
    {
        foreach ($files as $source => $destination) {
            if (file_exists($source)) {
                copy($source, $destination);
                $this->message("Sample file created: $destination");
            } else {
                $this->message("Sample file not found: $source", true);
            }
        }
    }

    private function message($message, $exit = false)
    {
        echo ucfirst("\n$message\n");
        if ($exit) {
            exit;
        }
    }

    private function getClassNameFromFileName($file)
    {
        $class_name = basename($file);
        preg_match("/[a-zA-Z_]+\.php$/", $class_name, $match);
        $class_name = ucfirst(str_replace(".php", "", $match[0]));
        $class_name = trim($class_name, '_');

        return $class_name;
    }
}

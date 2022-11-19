<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create A Backup file of the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle(): int
    {
        $filename = $this->argument('fileName');

        $filePath = $this->getFilePath($filename);

        $dbConfig = (object) DB::connection()->getConfig();

        if (!config('app.dump_path')) {
            throw new \Exception('Please set the dump path in the .env file correctly.');
        }

        $command = config('app.dump_path')." --user=$dbConfig->username --password=$dbConfig->password --host=$dbConfig->host $dbConfig->database  > $filePath";

        $output = shell_exec($command);

        Log::info('Database Backup Command Output: '.$output);

        $this->info($filePath.' created successfully');

        return 0;
    }

    public function getFilePath($filename): string
    {
        return storage_path().'//backup/'.$filename;
    }
}

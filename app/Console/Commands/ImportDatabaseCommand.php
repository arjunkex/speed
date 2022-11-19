<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Database tables data';

    public function getFilePath()
    {
        return storage_path().'//import/'.'acculance.sql';
    }

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
     */
    public function handle()
    {
        $filePath = $this->getFilePath();

        Schema::disableForeignKeyConstraints();

        // exec($command, $output, $returnVar);
        DB::unprepared(file_get_contents($filePath));

        Schema::enableForeignKeyConstraints();

        $this->info('Data imported Successfully');

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate The database tables';

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
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        Schema::disableForeignKeyConstraints();

        foreach ($tableNames as $name) {
            DB::table($name)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        $this->info('Table Truncated successfully');

        return 0;
    }
}

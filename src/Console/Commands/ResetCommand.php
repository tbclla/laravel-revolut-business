<?php

namespace tbclla\Revolut\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revolut:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the Revolut tokens table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = config('revolut.tokens.database.table_name');

        if ($this->confirm('All Revolut tokens will be deleted permanently. Are you sure?')) {
            DB::table($table)->truncate();
            $this->info($table . ' table has been truncated.');
        }
    }
}

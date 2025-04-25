<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetupDbCommand extends Command
{
    /**
     * @var string
     */
    protected $name = "app:setup-db";

    /**
     * @var string
     */
    protected $description = "Run the application database setup";

    public function handle(): void
    {
        $schemaName = config("database.connections.mysql.database");
        $charset = config("database.connections.mysql.charset",'utf8mb4');
        $collation = config("database.connections.mysql.collation",'utf8mb4_unicode_ci');

        config(["database.connections.mysql.database" => null]);

        $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";

        DB::statement($query);

        $this->info('Database created successfully.');

        config(["database.connections.mysql.database" => $schemaName]);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $nameInput = $this->argument('name'); // ex: Client/AuthService
        $nameInput = str_replace('\\', '/', $nameInput); // normalize

        // Path ke file
        $path = app_path('Services/' . $nameInput . '.php');

        // Buat direktori-nya kalau belum ada
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        // Cek kalau file udah ada
        if (File::exists($path)) {
            $this->error("Service already exists at: {$path}");
            return Command::FAILURE;
        }

        // Ambil class name & namespace
        $className = class_basename($nameInput);
        $namespace = 'App\\Services\\' . str_replace('/', '\\', dirname($nameInput));
        $namespace = rtrim($namespace, '\\'); // hapus trailing slash klo kosong

        // Isi file
        $content = "<?php\n\nnamespace {$namespace};\n\nclass {$className}\n{\n    //\n}\n";

        File::put($path, $content);

        $this->info("✅ Service created at: " . str_replace(base_path(), '', $path));
        return Command::SUCCESS;
    }
}

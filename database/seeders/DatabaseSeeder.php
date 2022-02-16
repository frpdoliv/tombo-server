<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Type\Integer;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Location;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(100)->create();
        Category::factory(500)->create();
        Location::factory(100)->create();
        Book::factory(5000)->create();
        Author::factory(5000)->create();

        for($i = 1; $i <= Book::count(); $i++) {
            DB::table('books_authors')->insert([
                'book_id' => $i,
                'author_id' => $i,
                'created_at' => Carbon::now()
            ]);
            DB::table('books_categories')->insert([
                'book_id' => $i,
                'category_id' => Category::find(random_int(1, 500))->id,
                'created_at' => Carbon::now()
            ]);
        }

        User::create([
            'name' => 'Francisco Oliveira',
            'email' => 'fr.pdoliv@gmail.com',
            'password' => '$2y$10$hwocsZO1MSEMhD0u3LkzeOsU7R/pfGKwm4dpKZNR88pYQFpmC9jd.'
        ]);
    }
}

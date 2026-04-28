<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pintura',     'slug' => 'pintura',     'description' => 'Pinturas y acrílicos'],
            ['name' => 'Cerámica',    'slug' => 'ceramica',    'description' => 'Piezas de cerámica artesanal'],
            ['name' => 'Ilustración', 'slug' => 'ilustracion', 'description' => 'Ilustraciones y prints'],
            ['name' => 'Escultura',   'slug' => 'escultura',   'description' => 'Esculturas y figuras'],
            ['name' => 'Fotografía',  'slug' => 'fotografia',  'description' => 'Fotografía artística'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
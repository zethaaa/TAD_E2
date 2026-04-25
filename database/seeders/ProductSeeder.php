<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $fallbackCategory = Category::first();

        $products = [
            [
                'name' => 'Cuadro Bermellón Nº1',
                'description' => 'Pintura al óleo sobre lienzo, tonos bermellón y ocre.',
                'price' => 120.00,
                'stock' => 5,
                'sku' => 'PINT-001',
                'active' => true,
            ],
            [
                'name' => 'Jarrón Artesanal Rojo',
                'description' => 'Jarrón de cerámica hecho a mano con esmalte rojo.',
                'price' => 45.00,
                'stock' => 10,
                'sku' => 'CER-001',
                'active' => true,
            ],
            [
                'name' => 'Print Abstracto Fuego',
                'description' => 'Ilustración digital en tonos cálidos, impresión en papel.',
                'price' => 25.00,
                'stock' => 20,
                'sku' => 'ILU-001',
                'active' => true,
            ],
            [
                'name' => 'Figura Llama Cerámica',
                'description' => 'Escultura decorativa de cerámica esmaltada.',
                'price' => 60.00,
                'stock' => 8,
                'sku' => 'ESC-001',
                'active' => true,
            ],
            [
                'name' => 'Fotografía Atardecer Sevilla',
                'description' => 'Fotografía artística enmarcada, edición limitada.',
                'price' => 85.00,
                'stock' => 3,
                'sku' => 'FOT-001',
                'active' => true,
            ],
        ];

        foreach ($products as $product) {
            $createdProduct = Product::create($product);

            if ($fallbackCategory) {
                $createdProduct->categories()->syncWithoutDetaching([$fallbackCategory->id]);
            }
        }
    }
}
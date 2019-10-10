<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_a_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);

        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );
    }

    public function test_client_can_get_products()
    {
        $product = factory(Product::class)->create();

        $response = $this->json('GET', '/api/products');
        //dd($response);

        if($response->assertStatus(200)){
        // Then
        // Assert it sends the correct HTTP Status
            $response->assertJsonStructure(['data' => [
                'id',
                'name',
                'price'
            ]]);
        }
        else if($response->assertStatus(404)){
            $response-> assertEquals(null, $response->getContent());
        }
    }

    public function test_client_can_show_a_product()
    {
        // Given
        $product = factory(Product::class)->create();

        // When
        $response = $this->json('GET', '/api/products/'.$product->id);

        if($response->assertStatus(200)){
            // Then
            // Assert it sends the correct HTTP Status
               // Assert the response has the correct structure
            $response->assertJsonStructure([
                'id',
                'name',
                'price'
            ]);

            // Assert the product was created
            // with the correct data
            $response->assertJsonFragment([
                'name' => 'Super Product',
                'price' => '23.30'
            ]);

            $body = $response->decodeResponseJson();

            // Assert product is on the database
            $this->assertDatabaseHas(
                'products',
                [
                    'id' => $body['id'],
                    'name' => 'Super Product',
                    'price' => '23.30'
                ]
            );
        }

        else if($response->assertStatus(404)){
            $response-> assertEquals(null, $response->getContent());
        }
    }

    public function test_client_can_update_a_product()
    {
        $product = factory(Product::class)->create();

        $newProduct = factory(Product::class)->make([
            'id' => $product->id,
        ]);

        // When
        $response = $this->json('PUT', '/api/products/'.$product->id, $newProduct->toArray());

        if($response->assertStatus(200)){
            // Then
            // Assert it sends the correct HTTP Status
               // Assert the response has the correct structure
            $response->assertJsonStructure([
                'id',
                'name',
                'price'
            ]);

            // Assert the product was created
            // with the correct data
            $response->assertJsonFragment([
                'id' => $product->id,
                'name' => $newProduct->name,
                'price' => $newProduct->price
            ]);

            $body = $response->decodeResponseJson();

            // Assert product is on the database
            $this->assertDatabaseHas(
                'products',
                [
                    'id' => $product->id,
                    'name' => $newProduct->name,
                    'price' => $newProduct->price
                ]
            );
        }

        else if($response->assertStatus(404)){
            $response-> assertEquals(null, $response->getContent());
        }
    }

    public function test_client_can_delete_a_product()
    {
        $product = factory(Product::class)->create();

        // When
        $response = $this->json('DELETE', '/api/products/'.$product->id);

        if($response->assertStatus(200)){
            //$response-> assertEquals(null, $response->getContent());
        }

        else if($response->assertStatus(404)){
            //$response-> assertEquals(null, $response->getContent());
        }
    }
}

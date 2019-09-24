<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        // When
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        $this->json('POST', '/api/products', $productData);
        $response = $this->json('GET', '/api/products');

        if($response->assertStatus(200)){
        // Then
        // Assert it sends the correct HTTP Status
            $response->assertJsonStructure([[
                'id',
                'name',
                'price'
            ]]);
        }
        if($response->assertStatus(404)){
            $response-> assertEquals('null', $response->getContent());
        }
    }

    public function test_client_can_show_a_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        $this->json('POST', '/api/products', $productData);


        // When
        $response = $this->json('GET', '/api/products/1');
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
                'id' => '1',
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

        if($response->assertStatus(404)){
            $response-> assertEquals('null', $response->getContent());
        }
    }

    public function test_client_can_update_a_product()
    {
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        $this->json('POST', '/api/products', $productData);
        // Given

        $productnewData = [
            'id' => '1',
            'name' => 'Super XSL Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('PUT', '/api/products/1',$productnewData);
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
                'id' => '1',
                'name' => 'Super XSL Product',
                'price' => '23.30'
            ]);

            $body = $response->decodeResponseJson();

            // Assert product is on the database
            $this->assertDatabaseHas(
                'products',
                [
                    'id' => $body['id'],
                    'name' => 'Super XSL Product',
                    'price' => '23.30'
                ]
            );
        }

        if($response->assertStatus(404)){
            $response-> assertEquals('null', $response->getContent());
        }
    }

    public function test_client_can_delete_a_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        $this->json('POST', '/api/products', $productData);

        // When
        $response = $this->json('delete', '/api/products/1');
        if($response->assertStatus(200)){
            $response-> assertEquals('null', $response->getContent());
        }

        if($response->assertStatus(404)){
            $response-> assertEquals('null', $response->getContent());
        }
    }
}

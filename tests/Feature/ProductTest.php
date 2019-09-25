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
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $responsePrev = $this->json('POST', '/api/products', $productData);

        $response = $this->json('GET', '/api/products');
        //dd($response);

        if($response->assertStatus(200)){
        // Then
        // Assert it sends the correct HTTP Status
            $response->assertJsonStructure([[
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
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $responsePrev = $this->json('POST', '/api/products', $productData);
        $bodyPrev = $responsePrev->decodeResponseJson();

        // When
        $response = $this->json('GET', '/api/products/'.$bodyPrev['id']);

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
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $responsePrev = $this->json('POST', '/api/products', $productData);
        $bodyPrev = $responsePrev->decodeResponseJson();

        $productnewData = [
            'id' => $bodyPrev['id'],
            'name' => 'Super XSL Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('PUT', '/api/products/'.$bodyPrev['id'], $productnewData);

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
                'id' => $bodyPrev['id'],
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

        else if($response->assertStatus(404)){
            $response-> assertEquals(null, $response->getContent());
        }
    }

    public function test_client_can_delete_a_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $responsePrev = $this->json('POST', '/api/products', $productData);
        $responsePrev2 = $this->json('POST', '/api/products', $productData);
        $bodyPrev = $responsePrev->decodeResponseJson();

        // When
        $response = $this->json('DELETE', '/api/products/'.$bodyPrev['id']);

        if($response->assertStatus(200)){
            //$response-> assertEquals(null, $response->getContent());
        }

        else if($response->assertStatus(404)){
            //$response-> assertEquals(null, $response->getContent());
        }
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
    * CREATE-1
    */
    public function test_client_can_create_a_product()
    {
        // Given
        $product = factory(Product::class,'reFormatted')->make();

        // When
        $response = $this->json('POST', '/api/products', $product->toArray());

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links'
            ]
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => $product->data['attributes']['name'],
            'price' => $product->data['attributes']['price']
        ]);

        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['data']['id'],
                'name' => $product->data['attributes']['name'],
                'price' => $product->data['attributes']['price']
            ]
        );
    }

    /**
     * CREATE-2
     */
    public function test_client_cant_create_a_product_without_name()
    {
        // Given
        $product = factory(Product::class,'woutName')->make();

        // When
        $response = $this->json('POST', '/api/products', $product->toArray());

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

        // Assert the response has the correct structure
        $response->assertJsonStructure(['*' => ['*'=>[
            'code',
            'title',
        ]]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-1',
            'title' => 'A name is required'
        ]);

        // Assert product is on the database
        $this->assertDatabaseMissing(
            'products',
            [
                'name' => $product->data['attributes']['name'],
                'price' => $product->data['attributes']['price']
            ]
        );
    }

    /**
     * CREATE-3
     */
    public function test_client_cant_create_a_product_without_price()
    {
        // Given
        $product = factory(Product::class,'woutPrice')->make();

        // When
        $response = $this->json('POST', '/api/products', $product->toArray());

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

        // Assert the response has the correct structure
        $response->assertJsonStructure(['*' => ['*'=>[
            'code',
            'title',
        ]]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-1',
            'title' => 'A price is required'
        ]);

        // Assert product is on the database
        $this->assertDatabaseMissing(
            'products',
            [
                'name' => $product->data['attributes']['name'],
                'price' => $product->data['attributes']['price']
            ]
        );
    }

    /**
     * CREATE-4
     */
    public function test_client_cant_create_a_product_without_numeric_price()
    {
        // Given
        $product = factory(Product::class,'woutNumPrice')->make();

        // When
        $response = $this->json('POST', '/api/products', $product->toArray());

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

        // Assert the response has the correct structure
        $response->assertJsonStructure(['*' => ['*'=>[
            'code',
            'title',
        ]]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-1',
            'title' => 'The price has to be numeric'
        ]);
    }

    /**
     * CREATE-5
     */
    public function test_client_cant_create_a_product_with_a_price_small_than_one()
    {
        // Given
        $product = factory(Product::class,'subZero')->make();

        // When
        $response = $this->json('POST', '/api/products', $product->toArray());

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

        // Assert the response has the correct structure
        $response->assertJsonStructure(['*' => ['*'=>[
            'code',
            'title',
        ]]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-1',
            'title' => 'The price has to be more than 0 (zero)'
        ]);

        // Assert product is on the database
        $this->assertDatabaseMissing(
            'products',
            [
                'name' => $product->data['attributes']['name'],
                'price' => $product->data['attributes']['price']
            ]
        );
    }

    /**
     * List-1
     */
    public function test_client_can_get_products()
    {
        $products = factory(Product::class,2)->create();

        $response = $this->json('GET', '/api/products');

        //$response->dump();

        $response->assertStatus(200);
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertJsonStructure(['data'=>
            ['*' =>
                [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'price'
                    ],
                    'links'
                ]
            ]
        ]);

        $response->assertJsonFragment([
            'type' => 'products',
            'id' => $products[0]->id,
            'name' => $products[0]->name,
            'price' => strval($products[0]->price),
            'self' => route('api.get.product', ['id' => $products[0]->id])
        ]);

    }

    /**
     * List-2
     */
    public function test_client_can_get_any_products()
    {
        $response = $this->json('GET', '/api/products');

        $response->assertStatus(200);
        // Then
        // Assert it sends the correct HTTP Status
        $response->assertJsonStructure(['*'=>[
            NULL
        ]]);
    }

    /**
     * Show-1
     */
    public function test_client_can_show_a_product()
    {
        // Given
        $product = factory(Product::class)->create();

        // When
        $response = $this->json('GET', '/api/products/'.$product->id);

        $response->assertStatus(200);
            // Then
            // Assert it sends the correct HTTP Status
               // Assert the response has the correct structure
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links'
            ]
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'type' => 'products',
            'id' => $product->id,
            'name' => $product->name,
            'price' => strval($product->price),
            'self' => route('api.get.product', ['id' => $product->id])
        ]);

            // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
            ]
        );
    }

    /**
     * Show-2
     */
    public function test_client_cant_view_a_product()
    {
        // When
        $response = $this->json('GET', '/api/products/1');

        $response->assertStatus(404);

         // Assert the response has the correct structure
         $response->assertJsonStructure(['*' => ['*'=>
            'code',
            'title',
        ]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-2',
            'title' => 'ID does not exist'
        ]);

        // Assert product is on the database
        $this->assertDatabaseMissing(
            'products',
            [
                'id' => 1
            ]
        );
    }

    /**
     * Update-1
     */
    public function test_client_can_update_a_product()
    {
        $product = factory(Product::class)->create();

        $newProduct = factory(Product::class,'reFormatted')->make();

        // When
        $response = $this->json('PUT', '/api/products/'.$product->id, $newProduct->toArray());

        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'price'
                ],
                'links'
            ]
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'id' => $product->id,
            'name' => $newProduct->data['attributes']['name'],
            'price' => $newProduct->data['attributes']['price'],
            'self' => route('api.get.product', ['id' => $product->id])
        ]);

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $product->id,
                'name' => $newProduct->data['attributes']['name'],
                'price' => $newProduct->data['attributes']['price'],
            ]
        );
    }

    /**
     * Update-2
     */
    public function test_client_cant_update_a_product_without_numeric_price()
    {
        $product = factory(Product::class)->create();

        $newProduct = factory(Product::class,'woutNumPrice')->make();

        // When
        $response = $this->json('PUT', '/api/products/'.$product->id, $newProduct->toArray());

        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

         // Assert the response has the correct structure
         $response->assertJsonStructure(['*' => ['*'=>[
            'code',
            'title',
        ]]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-1',
            'title' => 'The price has to be numeric'
        ]);
    }

    /**
     * Update-3
     */
    public function test_client_cant_update_a_product_with_a_price_smaller_than_one()
    {
        $product = factory(Product::class)->create();

        $newProduct = factory(Product::class,'subZero')->make();

        // When
        $response = $this->json('PUT', '/api/products/'.$product->id, $newProduct->toArray());

        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

         // Assert the response has the correct structure
         $response->assertJsonStructure(['*' => ['*'=>[
            'code',
            'title',
        ]]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-1',
            'title' => 'The price has to be more than 0 (zero)'
        ]);

        // Assert product is on the database
        $this->assertDatabaseMissing(
            'products',
            [
                'id' => $product->id,
                'name' => $newProduct->data['attributes']['name'],
                'price' => $newProduct->data['attributes']['price']
            ]
        );
    }

    /**
     * Update-3
     */
    public function test_client_cant_update_a_product_does_not_exist()
    {

        $newProduct = factory(Product::class,'reFormatted')->make();

        // When
        $response = $this->json('PUT', '/api/products/1', $newProduct->toArray());

        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);

         // Assert the response has the correct structure
         $response->assertJsonStructure(['*' => ['*'=>
            'code',
            'title',
        ]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-2',
            'title' => 'ID does not exist'
        ]);

        // Assert product is on the database
        $this->assertDatabaseMissing(
            'products',
            [
                'id' => 1,
                'name' => $newProduct->data['attributes']['name'],
                'price' => $newProduct->data['attributes']['price']
            ]
        );
    }

    /**
     * Delete-1
     */
    public function test_client_can_delete_a_product()
    {
        $product = factory(Product::class)->create();

        // When
        $response = $this->call('delete', '/api/products/'.$product->id,['_token' => csrf_token()]);

        $response->assertStatus(204);

        //dd($response);

        $response->assertSee(NULL);
    }

    /**
     * Delete-2
     */
    public function test_client_cant_delete_a_product_does_not_exist()
    {
        // When
        $response = $this->json('delete', '/api/products/1');

        $response->assertStatus(404);

         // Assert the response has the correct structure
         $response->assertJsonStructure(['*' => ['*'=>
            'code',
            'title',
        ]]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'code' => 'Error-2',
            'title' => 'ID does not exist'
        ]);

        // Assert product is on the database
        $this->assertDatabaseMissing(
            'products',
            [
                'id' => 1
            ]
        );
    }
}

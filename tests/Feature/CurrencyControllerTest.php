<?php

namespace Tests\Feature;

use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    public function testGetById_WithValidId()
    {
        $id = 'R01010'; // valid Id

        $response = $this->get("/api/currency/$id");

        $this->assertEquals(200, $response['status']);

        $this->assertArrayHasKey('data', $response);

        $this->assertIsArray($response['data']);
        $this->assertCount(1, $response['data']);

        $item = $response['data'][0];
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('NumCode', $item);
        $this->assertArrayHasKey('CharCode', $item);
        $this->assertArrayHasKey('Nominal', $item);
        $this->assertArrayHasKey('Name', $item);
        $this->assertArrayHasKey('Value', $item);
        $this->assertArrayHasKey('VunitRate', $item);
        $this->assertArrayHasKey('created_at', $item);
        $this->assertArrayHasKey('updated_at', $item);

        $this->assertEquals("036", $item['NumCode']);
        $this->assertEquals("AUD", $item['CharCode']);
        $this->assertEquals("1", $item['Nominal']);
        $this->assertEquals("Австралийский доллар", $item['Name']);
        $this->assertEquals("59,7675", $item['Value']);
        $this->assertEquals("59,7675", $item['VunitRate']);
        $this->assertEquals("2024-03-09T13:24:07.000000Z", $item['created_at']);
        $this->assertEquals("2024-03-09T13:24:07.000000Z", $item['updated_at']);
    }

    public function testGetById_WithInvalidId()
    {
        $id = 'R01010wwda'; // invalid ID

        $response = $this->get("api/currency/R01010w");

        $this->assertEquals(400, $response['status']);
        $this->assertEquals('Validation failed', $response['message']);
        $this->assertEquals(['The selected id is invalid.'], $response['errors']['id']);

        $this->assertArrayHasKey('errors', $response);
        $this->assertArrayHasKey('id', $response['errors']);
    }
}

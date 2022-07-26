<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Point;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestPointsAssignment extends TestCase
{
    use RefreshDatabase;

    public function testDatabaseTableExists()
    {
        $this->assertTrue(Schema::hasTable('points'));
    }

    public function testIndexPage()
    {
        Point::factory()->count(5)->create();
        $request = $this->get(route('points.index'));
        $request->assertSuccessful()
            ->assertJsonStructure([
                'data' =>
                    [
                        '*' =>
                            [
                                'id',
                                'name',
                                'x',
                                'y',
                            ],
                    ],
            ]);
    }

    public function testCreateRow()
    {
        $request = $this->post(route('points.store'), [
            'name' => 'Test',
            'x' => 1,
            'y' => 1,
        ]);
        $request->assertSuccessful();
        $this->assertDatabaseHas('points', [
            'name' => 'Test',
            'x' => 1,
            'y' => 1,
        ]);
    }

    public function testUpdateRow()
    {
        $point = Point::factory()->create();
        $request = $this->put(route('points.update', $point->id), [
            'name' => 'Test',
            'x' => 2,
            'y' => 2,
        ]);
        $this->assertDatabaseHas('points', [
            'id' => $point->id,
            'name' => 'Test',
            'x' => 2,
            'y' => 2,
        ]);
    }

    public function testDeleteRow()
    {
        $point = Point::factory()->create();
        $request = $this->delete(route('points.destroy', $point->id));
        $this->assertDatabaseMissing('points', [
            'id' => $point->id,
        ]);
    }
}

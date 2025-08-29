<?php

namespace Tests\Unit\Item;

use Modules\Core\Entities\Item;
use Modules\Core\Entities\ItemInfo;
use Tests\TestCase;
use Illuminate\Database\Query\Builder;

use Mockery as m;

class ItemModelTest extends TestCase
{

    public function test_PrepareRelationNameForSorting()
    {

        // Category Order By Test Case
        $queryResult = Item::query()->prepareRelationNameForSorting('category_id@@name');

        // Assert
        $this->assertStringContainsString('join', strtolower($queryResult->toSql()));
        $this->assertStringContainsString('"psx_categories"."name" as "cat_name"', strtolower($queryResult->toSql()));

        // ----------------------------------------------------
        // Sub Category Order By Test Case
        $queryResult = Item::query()->prepareRelationNameForSorting('subcategory_id@@name');

        // Assert
        $this->assertStringContainsString('join', strtolower($queryResult->toSql()));
        $this->assertStringContainsString('"psx_subcategories"."name" as "sub_cat_name"', strtolower($queryResult->toSql()));

        // ----------------------------------------------------
        // Act - Location City
        $queryResult = Item::query()->prepareRelationNameForSorting('location_city_id@@name');

        // Assert
        $this->assertStringContainsString('join', strtolower($queryResult->toSql()));
        $this->assertStringContainsString('"psx_location_cities"."name" as "city_name"', strtolower($queryResult->toSql()));
  
        // ----------------------------------------------------
        // Act - Location Township
        $queryResult = Item::query()->prepareRelationNameForSorting( 'location_township_id@@name');

        // Assert
        $this->assertStringContainsString('join', strtolower($queryResult->toSql()));
        $this->assertStringContainsString('"psx_location_townships"."name" as "township_name"', strtolower($queryResult->toSql()));
    
        // ----------------------------------------------------
        // Act - Currency
        $queryResult = Item::query()->prepareRelationNameForSorting( 'currency_id@@currency_short_form');

        // Assert
        $this->assertStringContainsString('join', strtolower($queryResult->toSql()));
        $this->assertStringContainsString('"psx_currencies"."currency_short_form" as "curr_short_form"', strtolower($queryResult->toSql()));
    
        // ----------------------------------------------------
        // Act - User
        $queryResult = Item::query()->prepareRelationNameForSorting( 'added_user_id@@name');

        // Assert
        $this->assertStringContainsString('join', strtolower($queryResult->toSql()));
        $this->assertStringContainsString('"users"."name" as "owner_name"', strtolower($queryResult->toSql()));
    
        // ----------------------------------------------------
        // Act - Default (No order by provided)
        $query = Item::query();
        $queryResult = $query->prepareRelationNameForSorting( '');

        // Assert
        $this->assertSame($query, $queryResult); // Ensure the query remains unchanged
    
    }

    public function test_JoinWithItemInfos()
    {
        // Act
        $queryResult = Item::query()->joinWithItemInfos();

        // Assert
        $sql = str_replace('"', '', $queryResult->toSql());

        $this->assertStringContainsString('join', strtolower($queryResult->toSql()));
        $this->assertStringContainsString(
            strtolower(Item::tableName . '.' . Item::id . ' = ' . ItemInfo::tableName . '.' . ItemInfo::itemId),
            strtolower($sql)
        );
    }



    protected function tearDown(): void
    {
        m::close();
    }

    public function test_HandleOrderByWithOrderByCondition()
    {
        $query = m::mock(Builder::class);
        $query->shouldReceive('when')->once()->andReturnUsing(function ($condition, $callback) use ($query) {
            if ($condition) {
                $callback($query);
            }
            return $query;
        });

        $query->shouldReceive('join')
            ->once()
            ->with('psx_categories as c', 'c.id', '=', 'i.category_id')
            ->andReturnSelf();

        $query->shouldReceive('select')
            ->once()
            ->with('c.name as cat_name', 'i.*')
            ->andReturnSelf();

        $conds = ['order_by' => 'some_value'];
        $sort = 'category_id@@name';

        $this->handleOrderBy($query, $conds, $sort);

        $this->assertTrue(true); // Placeholder for actual assertions

    }

    public function test_HandleOrderByWithoutOrderByCondition()
    {
        $query = m::mock(Builder::class);
        $query->shouldReceive('when')->once()->andReturnUsing(function ($condition, $callback) use ($query) {
            if ($condition) {
                $callback($query);
            }
            return $query;
        });

        $query->shouldNotReceive('join');
        $query->shouldNotReceive('select');

        $conds = ['order_by' => null];
        $sort = 'category_id@@name';

        // $conds = ['order_by' => 'some_value'];
        // $sort = 'category_id@@name';

        $this->handleOrderBy($query, $conds, $sort);

        $this->assertTrue(true); // Placeholder for actual assertions

    }

    private function handleOrderBy($query, $conds, $sort)
    {
        $query->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
            if ($sort == 'category_id@@name') {
                $q->join('psx_categories as c', "c.id", '=', 'i.category_id');
                $q->select("c.name as cat_name", "i.*");
            }
        });
    }


}

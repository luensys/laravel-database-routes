<?php

use Illuminate\Contracts\Config\Repository;

final class RouteTest extends \Tests\TestCase
{
    public function test_should_replace_between_brackets()
    {
        $sut = new \Douma\Routes\Routes\Route('/test/{id}', true, 'test', 'MyController', 'test');
        $newRoute = $sut->withParameter('id', 1);
        $this->assertEquals('/test/1', $newRoute->url());
    }

    public function test_should_replace_between_brackets_multiple()
    {
        $sut = new \Douma\Routes\Routes\Route('/test/{id}/{id2}', true, 'test', 'MyController', 'test');
        $newRoute = $sut->withParameters([
            'id'=>1,
            'id2'=>2
        ]);
        $this->assertEquals('/test/1/2', $newRoute->url());
    }

    public function test_should_add_query_parameters()
    {
        $sut = new \Douma\Routes\Routes\Route('/test', false, 'test', 'MyController', 'test');
        $newRoute = $sut->withGetParameters([
            'id'=>1
        ]);
        $this->assertEquals('/test?id=1', $newRoute->url());
    }

    public function test_should_add_query_parameters_multiple()
    {
        $sut = new \Douma\Routes\Routes\Route('/test', false, 'test', 'MyController', 'test');
        $newRoute = $sut->withGetParameters([
            'id'=>1,
            'name'=>'Stefan'
        ]);
        $this->assertEquals('/test?id=1&name=Stefan', $newRoute->url());
    }
}

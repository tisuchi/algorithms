<?php

namespace Graphp\Tests\Algorithms\MaxFlow;

use Graphp\Algorithms\MaxFlow\EdmondsKarp as AlgorithmMaxFlowEdmondsKarp;
use Graphp\Graph\Graph;
use Graphp\Tests\Algorithms\TestCase;

class EdmondsKarpTest extends TestCase
{
    public function testEdgeDirected()
    {
        // 0 -[0/10]-> 1
        $graph = new Graph();
        $v0 = $graph->createVertex(0);
        $v1 = $graph->createVertex(1);

        $graph->createEdgeDirected($v0, $v1)->setCapacity(10);

        // 0 -[10/10]-> 1
        $alg = new AlgorithmMaxFlowEdmondsKarp($v0, $v1);

        $this->assertEquals(10, $alg->getFlowMax());
    }

    public function testEdgesMultiplePaths()
    {
        // 0 -[0/5]---------> 1
        // |                  ^
        // |                  |
        // \-[0/7]-> 2 -[0/9]-/
        $graph = new Graph();
        $v0 = $graph->createVertex(0);
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);

        $graph->createEdgeDirected($v0, $v1)->setCapacity(5);
        $graph->createEdgeDirected($v0, $v2)->setCapacity(7);
        $graph->createEdgeDirected($v2, $v1)->setCapacity(9);

        // 0 -[5/5]---------> 1
        // |                  ^
        // |                  |
        // \-[7/7]-> 2 -[7/9]-/
        $alg = new AlgorithmMaxFlowEdmondsKarp($v0, $v1);

        $this->assertEquals(12, $alg->getFlowMax());
    }

    public function testEdgesMultiplePathsTwo()
    {
        // 0 -[0/5]---------> 1-[0/10]-> 3
        // |                  ^          |
        // |                  |          |
        // \-[0/7]-> 2 -[0/9]-/          |
        //           ^                   |
        //           \---[0/2]-----------/
        $graph = new Graph();
        $v0 = $graph->createVertex(0);
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $v3 = $graph->createVertex(3);

        $graph->createEdgeDirected($v0, $v1)->setCapacity(5);
        $graph->createEdgeDirected($v0, $v2)->setCapacity(7);
        $graph->createEdgeDirected($v2, $v1)->setCapacity(9);
        $graph->createEdgeDirected($v1, $v3)->setCapacity(10);
        $graph->createEdgeDirected($v3, $v2)->setCapacity(2);

        $alg = new AlgorithmMaxFlowEdmondsKarp($v0, $v3);

        $this->assertEquals(10, $alg->getFlowMax());

        $alg = new AlgorithmMaxFlowEdmondsKarp($v0, $v2);

        $this->assertEquals(9, $alg->getFlowMax());
    }

    public function testEdgesMultiplePathsTree()
    {
        $graph = new Graph();
        $v0 = $graph->createVertex(0);
        $v1 = $graph->createVertex(1);
        $v2 = $graph->createVertex(2);
        $v3 = $graph->createVertex(3);

        $graph->createEdgeDirected($v0, $v1)->setCapacity(4);
        $graph->createEdgeDirected($v0, $v2)->setCapacity(2);
        $graph->createEdgeDirected($v1, $v2)->setCapacity(3);
        $graph->createEdgeDirected($v1, $v3)->setCapacity(1);
        $graph->createEdgeDirected($v2, $v3)->setCapacity(6);

        $alg = new AlgorithmMaxFlowEdmondsKarp($v0, $v3);

        $this->assertEquals(6, $alg->getFlowMax());
    }

//     public function testEdgesParallel(){
//         $graph = new Graph();
//         $v0 = $graph->createVertex(0);
//         $v1 = $graph->createVertex(1);

//         $graph->createEdgeDirected($v0, $v1)->setCapacity(3.4);
//         $graph->createEdgeDirected($v0, $v1)->setCapacity(6.6);

//         $alg = new AlgorithmMaxFlowEdmondsKarp($v0, $v1);

//         $this->assertEquals(10, $alg->getFlowMax());
//     }

    public function testEdgesUndirected()
    {
        // 0 -[0/7]- 1
        $graph = new Graph();
        $v0 = $graph->createVertex(0);
        $v1 = $graph->createVertex(1);

        $graph->createEdgeUndirected($v1, $v0)->setCapacity(7);

        // 0 -[7/7]- 1
        $alg = new AlgorithmMaxFlowEdmondsKarp($v0, $v1);

        $this->setExpectedException('UnexpectedValueException');
        $this->assertEquals(7, $alg->getFlowMax());
    }

    /**
     * run algorithm with bigger graph and check result against known result (will take several seconds)
     */
//     public function testKnownResultBig(){

//         $graph = $this->readGraph('G_1_2.txt');

//         $alg = new AlgorithmMaxFlowEdmondsKarp($graph->getVertex(0), $graph->getVertex(4));

//         $this->assertEquals(0.735802, $alg->getFlowMax());
//     }

    public function testInvalidFlowToOtherGraph()
    {
        $graph1 = new Graph();
        $vg1 = $graph1->createVertex(1);

        $graph2 = new Graph();
        $vg2 = $graph2->createVertex(2);

        $this->setExpectedException('InvalidArgumentException');
        new AlgorithmMaxFlowEdmondsKarp($vg1, $vg2);
    }

    public function testInvalidFlowToSelf()
    {
        $graph = new Graph();
        $v1 = $graph->createVertex(1);

        $this->setExpectedException('InvalidArgumentException');
        new AlgorithmMaxFlowEdmondsKarp($v1, $v1);
    }

}

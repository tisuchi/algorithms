<?php
class AlgorithmTSPMinimumSpanningTreeHeuristic{

	private $graph;

	public function __construct(Graph $inputGraph){
		$this->graph = $inputGraph;
	}

	/**
	 *
	 * @param Vertex $startVertex
	 * @return Graph
	 */
	public function getResultGraph(){
		$returnGraph = $this->graph->createGraphCloneEdgeless();				// Copy vertices of original graph

		$minimumSpanningTreeAlgorithm = new AlgorithmKruskal($this->graph);		// Create minimum spanning tree
		$minimumSpanningTree = $minimumSpanningTreeAlgorithm->getResultGraph();

		$depthFirstSearch = $minimumSpanningTree->getAnyVertex()->searchDepthFirst();	// Depth first search in minmum spanning tree (for the eulerian path)

		$startVertex = NULL;
		$oldVertex = NULL;

		foreach ($depthFirstSearch as $vertex){									// connect vertices in order of the depth first search
				
			$vertex = $this->graph->getVertex( $vertex->getId() );				// get vertex from the original graph (not from the depth first search)
																				// need to clone the edge from the original graph, therefore i need the original edge
			if ($startVertex === NULL){
				$startVertex = $vertex;											
			}
			else {
				foreach ($oldVertex->getEdgesTo( $vertex ) as $edge ){			// get edge(s) to clone, multiple edges are possible (returns an array if undirected edge)
					$returnGraph->createEdgeClone( $edge );
					break;
				}
			}
				
			$oldVertex = $vertex;
		}
		
																				// connect last vertex with start vertex
		foreach ($oldVertex->getEdgesTo( $startVertex ) as $edge ){				// multiple edges are possible (returns an array if undirected edge)
			$returnGraph->createEdgeClone( $edge );
			break;
		}

		return $returnGraph;
	}
}
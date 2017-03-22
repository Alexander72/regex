
<header>
    <script type="text/javascript" src="js/raphael-min.js"></script>
    <script type="text/javascript" src="js/graffle.js"></script>
    <script type="text/javascript" src="js/graph.js"></script>
    <script type="text/javascript">
		<!--

		var redraw;
		var height = 800;
		var width = 1200;

		/* only do all this when document has finished loading (needed for RaphaelJS) */
		window.onload = function() {

		    var g = new Graph();

		    /* add a simple node */
		    g.addNode("strawberry");
		    g.addNode("cherry");

		    /* add a node with a customized label */
		    g.addNode("id34", { label : "Tomato" });

		    /* add a node with a customized shape 
		       (the Raphael graph drawing implementation can draw this shape, please 
		       consult the RaphaelJS reference for details http://raphaeljs.com/) */
		    var render = function(r, n) {
		            /* the Raphael set is obligatory, containing all you want to display */
		            var set = r.set().push(
		                /* custom objects go here */
		                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": "#c3cfec", "stroke-width": 2, r : "9px"})).push(
		                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"14px"}));
		            /* custom tooltip attached to the set */
		//            set./*tooltip = Raphael.el.tooltip;*/items.forEach(function(el) {el.tooltip(r.set().push(r.rect(0, 0, 30, 30).attr({"fill": "#fec", "stroke-width": 1, r : "9px"})))});
		//            set.tooltip(r.set().push(r.rect(0, 0, 30, 30).attr({"fill": "#fec", "stroke-width": 1, r : "9px"})).hide());
		            return set;
		        };
		    g.addNode("id35", {
		        label : "27" ,
		        /* filling the shape with a color makes it easier to be dragged */
		        /* arguments: r = Raphael object, n : node object */
		        render : render
		    });
		    //g.addNode("Wheat", {
		    //    /* filling the shape with a color makes it easier to be dragged */
		    //    /* arguments: r = Raphael object, n : node object */
		    //    shapes : [ {
		    //            type: "rect",
		    //            x: 10,
		    //            y: 10,
		    //            width: 25,
		    //            height: 25,
		    //            stroke: "#f00"
		    //        }, {
		    //            type: "text",
		    //            x: 30,
		    //            y: 40,
		    //            text: "Dump"
		    //        }],
		    //    overlay : "<b>Hello <a href=\"http://wikipedia.org/\">World!</a></b>"
		    //});

		    /* connect nodes with edges */
		    g.addEdge("id34", "cherry");
		    g.addEdge("cherry", "apple");

		    /* a directed connection, using an arrow */
		    g.addEdge("id34", "strawberry", { directed : true } );
		    
		    /* customize the colors of that edge */
		    g.addEdge("id35", "apple", { stroke : "#bfa" , fill : "#56f", label : "Label", directed : true });
		    
		    /* add an unknown node implicitly by adding an edge */
		    g.addEdge("strawberry", "apple");

		    g.addEdge("id34", "id35");
		    g.addEdge("id35", "strawberry");
		    g.addEdge("id35", "cherry");

		    /* layout the graph using the Spring layout implementation */
		    var layouter = new Graph.Layout.Spring(g);
		    layouter.layout();
		    
		    /* draw the graph using the RaphaelJS draw implementation */
		    var renderer = new Graph.Renderer.Raphael('canvas', g, width, height);
		    renderer.draw();
		    
		    redraw = function() {
		        layouter.layout();
		        renderer.draw();
		    };
		};

		-->

    </script>
</header>
<body>
<div id="canvas"></div>
</body>

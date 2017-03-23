
<header>
    <script type="text/javascript" src="js/raphael-min.js"></script>
    <script type="text/javascript" src="js/graffle.js"></script>
    <script type="text/javascript" src="js/graph.js"></script>
    <script type="text/javascript">
		<!--

		var redraw;
		var height = 600;
		var width = 800;

		/* only do all this when document has finished loading (needed for RaphaelJS) */
		window.onload = function() {

		    var g = new Graph();

		    //g.addNode("QQQQ", {label : "QQQ" , fill : '#f00', render : render});
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
		    <?=$graph->print_in_raphaeljs()?>
			g.edges.forEach((e) => {
			  console.log(e);
			});

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
<h3>String: <?=$str?></h3>
<div style="float: left;">
	<?=$graph?>
</div>
<div id="canvas"></div>
</body>

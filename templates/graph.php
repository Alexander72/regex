
<header>
	<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
    <script type="text/javascript" src="js/raphael-min.js"></script>
    <script type="text/javascript" src="js/graffle.js"></script>
    <script type="text/javascript" src="js/graph.js"></script>
    <script type="text/javascript">
		<!--

		var redraw;
		var height = 600;
		var width = 1200;

		/* only do all this when document has finished loading (needed for RaphaelJS) */
		window.onload = function() {

		    var g = new Graph();
		    var yellow          = "#fe0";
		    var red             = "#f00";
		    var yellow_red      = "90-#fe0-#f00";
		    var self_yellow     = "90-#fe0-#485574";
		    var self_red        = "90-#485574-#f00";
		    var self_yellow_red = "90-#fe0-#485574:50-#f00";
		    var self            = "#485574";
		    var usual           = "#c3cfec";

		    /* add a node with a customized shape 
		       (the Raphael graph drawing implementation can draw this shape, please 
		       consult the RaphaelJS reference for details http://raphaeljs.com/) */
		    var render = function(r, n) {
		            /* the Raphael set is obligatory, containing all you want to display */
		            var set = r.set().push(
		                /* custom objects go here */
		                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": usual, "stroke-width": 2, r : "9px"})).push(
		                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"}));
		            /* custom tooltip attached to the set */
		//            set./*tooltip = Raphael.el.tooltip;*/items.forEach(function(el) {el.tooltip(r.set().push(r.rect(0, 0, 30, 30).attr({"fill": "#fec", "stroke-width": 1, r : "9px"})))});
		//            set.tooltip(r.set().push(r.rect(0, 0, 30, 30).attr({"fill": "#fec", "stroke-width": 1, r : "9px"})).hide());
		            return set;
		        };
		    var renderStart = function(r, n) {
		            var set = r.set().push(
			                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": yellow, "stroke-width": 2, r : "9px"})).push(
			                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"})
		               	);
		            return set;
		        };
		    var renderFinish = function(r, n) {
		            var set = r.set().push(
			                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": red, "stroke-width": 2, r : "9px"})).push(
			                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"})
		               	);
		            return set;
		        };
		    var renderStartFinish = function(r, n) {
		            var set = r.set().push(
			                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": yellow_red, "stroke-width": 2, r : "9px"})).push(
			                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"})
		               	);
		            return set;
		        };
		    var renderSelf = function(r, n) {
		            var set = r.set().push(
			                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": self, "stroke-width": 2, r : "9px"})).push(
			                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"})
		               	);
		            return set;
		        };
		    var renderSelfStart = function(r, n) {
		            var set = r.set().push(
			                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": self_yellow, "stroke-width": 2, r : "9px"})).push(
			                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"})
		               	);
		            return set;
		        };
		    var renderSelfFinish = function(r, n) {
		            var set = r.set().push(
			                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": self_red, "stroke-width": 2, r : "9px"})).push(
			                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"})
		               	);
		            return set;
		        };
		    var renderSelfStartFinish = function(r, n) {
		            var set = r.set().push(
			                r.rect(n.point[0]-20, n.point[1]-13, 62, 56).attr({"fill": self_yellow_red, "stroke-width": 2, r : "9px"})).push(
			                r.text(n.point[0]+10, n.point[1] + 15, n.label).attr({"font-size":"20px", "font-weight":"bold"})
		               	);
		            return set;
		        };
		    //g.addNode("Q34Q", {label : "usual" , render : render});
		    //g.addNode("QQQQ", {label : "start" , render : renderStart});
		    //g.addNode("QQQO", {label : "finish" , render : renderFinish});
		    //g.addNode("QQO5", {label : "startfinish" , render : renderStartFinish});
		    //g.addNode("QQOO", {label : "SelfStartFinish" , render : renderSelfStartFinish});
		    //g.addNode("QQ31", {label : "Self" , render : renderSelf});
		    //g.addNode("QQO1", {label : "SelfStart" , render : renderSelfStart});
		    //g.addNode("QQO2", {label : "SelfFinish" , render : renderSelfFinish});
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
	<section class="container">
		<h3>String: <?=$str?></h3>
		<a href="" class="btn btn-primary">Back</a>
	</section>
	<div style="float: left;">
		<?=$graph?>
	</div>
	<div id="canvas"></div>
</body>

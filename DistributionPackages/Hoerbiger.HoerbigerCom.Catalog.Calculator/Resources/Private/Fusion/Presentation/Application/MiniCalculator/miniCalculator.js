

// var newCol = document.getElementById("col");

var add = document.getElementById("add");
// var graph =document.getElementById("graph_container");

let count = 0;

$(document).ready(function(){
    

    $('#ad').on('click', function (){
        // var comNo=$(".ad").length;
        // console.log(comNo++);
        // $("myText").html(comNo);
        
        var count=$(".copyContainer").length+1;
        var newCompressor=$(".divToCopy:first").clone();
        newCompressor.find(".myText").text(count);
        // console.log(count);
        $(".table").append(newCompressor);

       
        // count++;
        // $('.myText').text(count);


        
        
    });

});

// -------------------------------------------------


am5.ready(function() {

$('#btn').on('click', function (){
    $("#graph_container").css("display","block")

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new(graph_container);


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: false,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX",
  layout: root.verticalLayout
}));


// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(am5.Legend.new(root, {
    xAxis: xAxis,
  centerX: am5.p50,
  x: am5.p50,
//   maxWidth:600,
//   maxHeight:100,
// layout: root.horizontalLayout,
// fixedWidthGrid: true
layout: am5.GridLayout.new(root, {
    maxColumns: 4,
    fixedWidthGrid: false
  })

}));

// legend.contentAlign = "right";
var data = [{
  "compressor": "C-0101",
  "opex": 5,
  "environment": 1,
  "efficiency": 2.1,
  "reliability": 1,
// }, {
//   "compressor": "C-0102",
//   "opex": 6,
//   "environment": 2.7,
//   "efficiency": 2.2,
//   "reliability": 0.5,
// }, {
//   "compressor": "C-0103",
//   "opex": 2.8,
//   "environment": 2.9,
//   "efficiency": 2.4,
//   "reliability": 0.3,
// }
}
];


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: "compressor",
  renderer: am5xy.AxisRendererX.new(root, {
    cellStartLocation: 0.2,
    cellEndLocation: 0.8,
    strokeOpacity:1,
  }),
  tooltip: am5.Tooltip.new(root, {})
  
}));




// categoryAxis.renderer.grid.template.disabled = true;
xAxis.get("renderer").grid.template.setAll({
    location: 0,
    strokeWidth: 0,
    visible:false,
  });
// CategoryAxis.renderer.line.strokeOpacity = 1;
xAxis.data.setAll(data);


var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  min: 0,
  renderer: am5xy.AxisRendererY.new(root, {
  strokeOpacity:1,

  }),
}));

yAxis.get("renderer").grid.template.setAll({
    strokeWidth: 0,
    visible:false
  });



// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
function makeSeries(name, fieldName, stacked) {
  var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    stacked: stacked,
    name: name,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: fieldName,
    categoryXField: "compressor"
  }));

  series.columns.template.setAll({
    tooltipText: "{name}, {categoryX}:{valueY}",
    width: am5.percent(80),
    columnWidth:0,
    tooltipY: am5.percent(10)
  });
  series.data.setAll(data);

  // Make stuff animate on load
  // https://www.amcharts.com/docs/v5/concepts/animations/
  series.appear();

  series.bullets.push(function () {
    return am5.Bullet.new(root, {
      locationY: 0.5,
      sprite: am5.Label.new(root, {
        text: "{valueY}",
        fill: root.interfaceColors.get("alternativeText"),
        centerY: am5.percent(50),
        centerX: am5.percent(50),
        populateText: true
      })
    });
  });

  legend.data.push(series);
}

// For bar graph colors
chart.get("colors").set("colors", [
    am5.color(0x8A9199),
    am5.color(0x1B365D),
    am5.color(0x87212E),
    am5.color(0x61BF1A),

  ]);

makeSeries("OPEX", "opex", false);
makeSeries("Reliability", "reliability", false);
makeSeries("Efficiency", "efficiency", true);
makeSeries("Environment", "environment", true);

chart.topAxesContainer.children.push(am5.Label.new(root, {
    text: "Annual Savings Potenial",
    fontSize: 20,
    fontWeight: "400",
    x: am5.p50,
    centerX: am5.p50,
    color:"0x1B365D"
  }));

chart.xAxis.get("grid").set("visible", false);





chart.appear(1000, 100);

        
    });


}); // end am5.ready()
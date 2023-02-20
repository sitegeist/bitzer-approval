//---------------------------------------- compressor replication code----------------------

var count = 0;
var validate_data = [];
var data = [];
var sortOrder = "asc";

const driverPowerValidationMessage = "Nominal driver power should be in between 0 to 20000 kW.";
const loadrequiredValidationMessage = "Load required should be in between 0 to 150 %.";
const correctiveMaintenanceInterventionsValidationMessage = "Corrective maintenance should be in between 0.1 to 10 trips/year.";
const PlannedMaintenanceIntervalValidationMessage = "Shortest planned maintenance interval MTBM should be in between 0.1 to 10 Cal-years.";
const operatingHrsValidationMessage = "Required annual operating hours should be in between 0 to 8760 h.";
const apiUrl = "/catalog/calculate";
const unitTagIndustryStandard = "C-101";
const tagIndicatorIndustryStandard = "A";
const driverPowerIndustryStandard = 1000;
const loadRequiredIndustryStandard = 75;
const PMIndustryStandard = 3;
const CMIndustryStandard = 1;
const operatingHoursIndustryStandard = 7000;
const minDriverPower = 0;
const maxDriverPower = 20000;
const minLoadRequired = 0;
const maxLoadRequired = 150;
const minCM = 0;
const maxCM = 10;
const minPM = 0;
const maxPM = 10;
const minOperatingHours = 0;
const maxOperatingHours = 8760;

$(document).ready(function () {
  if($(".miniCalculator_body").length) {
      CalculateOpexAndSavingsBasedOnCompressors();
  }
});

$("#ad").on("click", function () {
  if (count <= 8) {
    count = $(".divToCopy").length + 1;
    var newCompressor = GetCompressor(count);
    $(".miniCalculator_table").append(newCompressor);
    scrollRight();

    if (count == 9) {
      $(".miniCalculator_add_btn").css("display", "none");
    }
  } else {
    return false;
  }
});

//#region Opex calculation
$("#miniCalculator_btn").on("click", function () {
  CalculateOpexAndSavingsBasedOnCompressors();
});

function CalculateOpexAndSavingsBasedOnCompressors() {
  let form_data = GetFormData();
  if (validate(form_data)) {
    if (ValidateForTagIndicator(form_data)) {
      if (ValidateForUnitTagAndTagIndicator(form_data)) {
        $("#miniCalculator_graph_container")
          .removeClass("hideGraphContainer")
          .addClass("showGraphContainer");
        maybeDisposeRoot("miniCalculator_graph_container");
        $(".loader").show();

        CalculateOpexAndSavings(form_data)
          .then((res) => {
            $(".loader").hide();
            if (res.Status == 200) {
              data = res.data;
              SetSortOrder();
              GenerateAMChart(data);
            } else if (res.Status == 500 || res.Status == 400) {
              $("#miniCalculator_graph_container")
                .removeClass("showGraphContainer")
                .addClass("hideGraphContainer");
              ShowAlert(res.StatusMessage, "Error");
            }
          })
          .catch((Error) => {
            $(".loader").hide();
            $("#miniCalculator_graph_container")
              .removeClass("showGraphContainer")
              .addClass("hideGraphContainer");
            ShowAlert("Oops! something went wrong.", "Error");
          });
      } else {
        ShowAlert(
          "Please use different unit tag and tag indicator name for compressors.",
          "Warning"
        );
        return false;
      }
    } else {
      ShowAlert(
        "Please enter different tag indicators for compressors having same unit tags.",
        "Warning"
      );
      return false;
    }
  } else {
    ShowAlert(
      "Please fill valid inputs into the fields highlighted by red before recalculate.",
      "Warning"
    );
    return false;
  }
}

//#region Getting form data
function GetFormData() {
  var form_data = [];
  $.each($(".divToCopy"), function () {
    let compressorInputs = {
      unitTag: $(this).find(".UnitTag").val(),
      tagIndicator: $(this).find(".TagIndicator").val(),
      driverPower: $(this).find(".DriverPower").val(),
      loadRequired: $(this).find(".LoadRequired").val(),
      correctiveMaintenanceInterventions: $(this)
        .find(".CorrectiveMaintenanceInterventions")
        .val(),
      plannedMaintenanceInterval: $(this)
        .find(".PlannedMaintenanceInterval")
        .val(),
      operatingHrs: $(this).find(".OperatingHrs").val(),
    };
    form_data.push(compressorInputs);
  });
  return form_data;
}
//#endregion

function CalculateOpexAndSavings(form_data) {
  return Promise.resolve($.post(apiUrl, { data: JSON.stringify(form_data) }));
}
//#endregion

//#region Display AM chart
function GenerateAMChart(data) {
  // Create root element
  var root = am5.Root.new(miniCalculator_graph_container);

  // Set themes
  root.setThemes([am5themes_Animated.new(root)]);

  // Create chart
  var chart = root.container.children.push(
    am5xy.XYChart.new(root, {
      panX: false,
      panY: false,
      wheelX: "panX",
      wheelY: "zoomX",
      layout: root.verticalLayout,
    })
  );

  // Add legend
  var legend = chart.children.push(
    am5.Legend.new(root, {
      xAxis: xAxis,
      centerX: am5.p50,
      x: am5.p50,
      layout: am5.GridLayout.new(root, {
        maxColumns: 4,
        fixedWidthGrid: false,
      }),
    })
  );

  legend.labels.template.setAll({
    fontSize: "10px",
    fontWeight: 600,
    populateText: true,
  });

  legend.markers.template.setup = function (marker) {
    var check = am5.Graphics.new(root, {
      fill: am5.color(0xffffff),
      fillOpacity: 1,
      width: 20,
      height: 20,
      layer: 50,
      scale: 0.5,
      svgPath:
        "M15.75 2.527c-.61-.468-1.46-.328-1.902.32l-6.325 9.255L4.04 8.328a1.3 1.3 0 0 0-1.922-.062 1.505 1.505 0 0 0-.062 2.043s4.234 4.695 4.843 5.168c.61.468 1.457.328 1.903-.32L16.05 4.55c.445-.653.308-1.555-.301-2.024Zm0 0",
    });

    legend.markers.template.setAll({
      width: 9,
      height: 9,
    });

    legend.markerRectangles.template.setAll({
      cornerRadiusTL: 0,
      cornerRadiusTR: 0,
      cornerRadiusBL: 0,
      cornerRadiusBR: 0,
    });

    check.states.create("disabled", {
      fillOpacity: 0,
    });

    marker.children.push(check);
  };

  root.interfaceColors.set("grid", am5.color(0xa8a8a8));
  // Create axes
  var xRenderer = am5xy.AxisRendererX.new(root, {
    minGridDistance: 30,
    cellStartLocation: 0.2,
    cellEndLocation: 0.8,
    strokeOpacity: 1,
  });

  var xAxis = chart.xAxes.push(
    am5xy.CategoryAxis.new(root, {
      categoryField: "compressor",
      renderer: xRenderer,
      zoomX: false,
      fontSize: "12px",
      fontWeight: 500,
      fontFamily: "Inter",
      color: 0xa8a8a8,
    })
  );

  var xRenderer = xAxis.get("renderer");

  xRenderer.labels.template.setAll({
    oversizedBehavior: "truncate",
    textAlign: "center",
    ellipsis: "...",
    maxWidth: 120,
    tooltipText: "{category}",
  });

  //X axis tooltip
  var tooltip = am5.Tooltip.new(root, {
    getFillFromSprite: false,
    getStrokeFromSprite: false,
    autoTextColor: false,
    pointerOrientation: "down",
  });
  tooltip.get("background").setAll({
    fill: am5.color(0x000000),
    fillOpacity: 1,
    stroke: am5.color(0x000000),
  });

  tooltip.label.setAll({
    fill: am5.color(0xffffff),
    fontSize: 12,
    fontFamily: "Inter",
    fontWeight: 500,
  });

  xAxis.labelsContainer.set("tooltip", tooltip);

  //X-axis line
  xRenderer.labels.template.setup = function (target) {
    target.set(
      "background",
      am5.Rectangle.new(root, {
        fill: am5.color(0xa8a8a8),
        fillOpacity: 0,
      })
    );
  };

  //X axis font
  let xAxisRenderer = xAxis.get("renderer");
  xAxisRenderer.labels.template.setAll({
    fontFamily: "Inter",
    fontSize: "12px",
    fontWeight: 600,
    fill: am5.color(0x8a9199),
  });

  // Set up automatic width calculation using an adapter
  xRenderer.labels.template.adapters.add("width", function (width, target) {
    var x0 = xAxis.getDataItemCoordinateY(xAxis.dataItems[0], "category", 0);
    var x1 = xAxis.getDataItemCoordinateY(xAxis.dataItems[0], "category", 1);
    target.set("maxWidth", x1 - x0);
    return x1 - x0;
  });

  xAxis.get("renderer").grid.template.setAll({
    location: 0,
    strokeWidth: 0,
    visible: false,
  });

  xAxis.data.setAll(data);

  var yAxis = chart.yAxes.push(
    am5xy.ValueAxis.new(root, {
      min: 0,
      numberFormat: "#'%'",
      zoomY: false,
      renderer: am5xy.AxisRendererY.new(root, {
        strokeOpacity: 1,
      }),
    })
  );

  yAxis.get("renderer").grid.template.setAll({
    location: 0,
    strokeWidth: 0,
    visible: true,
    strokeOpacity: 0.3,
  });

  let yRenderer = yAxis.get("renderer");
  yRenderer.labels.template.setAll({
    fontFamily: "inter",
    fontSize: "12px",
    fontWeight: 600,
    fill: am5.color(0x8a9199),
  });

  // Add series
  function makeSeries(name, fieldName, stacked) {
    var series = chart.series.push(
      am5xy.ColumnSeries.new(root, {
        stacked: stacked,
        name: name,
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: fieldName,
        categoryXField: "compressor",
        maskBullets: false,
      })
    );

    series.columns.template.setAll({
      tooltipText: "{name}: {valueY}%",
      width: am5.percent(80),
      columnWidth: 0,
      tooltipY: am5.percent(50),
    });

    var tooltip = am5.Tooltip.new(root, {
      getFillFromSprite: false,
      getStrokeFromSprite: true,
      autoTextColor: false,
      pointerOrientation: "horizontal",
    });

    var cellSize = 30;
    series.events.on("datavalidated", function (ev) {
      // Get objects of interest
      var series = ev.target;
      var chart = series.chart;
      var xAxis = chart.xAxes.getIndex(0); // Calculate how we need to adjust chart height
      var chartHeight =
        series.data.length * 2.0 * cellSize +
        270 +
        chart.get("paddingLeft", 0) +
        chart.get("paddingRight", 0); // Set it on chart's container
      chart.root.dom.style.width = chartHeight + "px";
    });

    tooltip.get("background").setAll({
      fill: am5.color(0x000000),
      fillOpacity: 1,
      stroke: am5.color(0x000000),
    });

    tooltip.label.setAll({
      fill: am5.color(0xffffff),
      fontSize: 12,
      fontFamily: "Inter",
      fontWeight: 500,
    });
    series.set("tooltip", tooltip);
    series.data.setAll(data);

    // Make stuff animate on load
    series.appear();

    //values within the bar

    series.bullets.push(function () {
      return am5.Bullet.new(root, {
        locationY: 1,
        sprite: am5.Label.new(root, {
          numberFormat: "#",
          text: "{valueY}%",
          fill: am5.color(0x000000),
          centerY: am5.p100,
          centerX: am5.p50,
          populateText: true,
        }),
      });
    });

    legend.data.push(series);

    series.bullets.clear();
  }

  // For bar graph colors
  chart.get("colors").set("colors", [am5.color(0x8a9199), am5.color(0x1b365d)]);
  makeSeries("OPEX", "percentageOpex", false);
  makeSeries("Savings Potential", "percentageSavings", false);

  chart.topAxesContainer.children.push(
    am5.Label.new(root, {
      text: "Annualized Savings Potential",
      fontSize: 15,
      fontWeight: "700",
      textAlign: "center",
      x: am5.percent(50),
      centerX: am5.percent(50),
      fill: am5.color(0x1b365d),
      marginBottom: 20,
      fontFamily: "Inter",
    })
  );
  chart.appear(1000, 100);
  chart.zoomOutButton.set("forceHidden", true);
}

//#endregion

//#region Validation
$("body").on("keyup", ".UnitTag", function () {
  if ($(this).val().toUpperCase() != unitTagIndustryStandard) {
    $(this)
      .removeClass("miniCalculator_input_box2")
      .addClass("miniCalculator_input_box1");
  }
});

$("body").on("keyup", ".TagIndicator", function () {
  var format = /[ `!@#$%^&*()+\-=\[\]{};':"\\|,.<>\/?~]/;
  if (format.test($(this).val())) {
    $(this).val(
      $(this)
        .val()
        .replace(/[^A-Z0-9]+/i, "")
    );

    ShowAlert(
      "Special characters are not allowed for tag indicator.",
      "Warning"
    );
  }

  if ($(this).val().toUpperCase() != tagIndicatorIndustryStandard) {
    $(this)
      .removeClass("miniCalculator_input_box2")
      .addClass("miniCalculator_input_box1");
  }
});

$("body").on("keyup", ".DriverPower", function () {
  if ($(this).val() < minDriverPower) {
    $(this).val("");
    ShowAlert(driverPowerValidationMessage, "Warning");
  } else if ($(this).val() > maxDriverPower) {
    $(this).val("20000");
      ShowAlert(driverPowerValidationMessage, "Warning");
  } else if ($(this).val() != driverPowerIndustryStandard) {
    $(this)
      .removeClass("miniCalculator_input_box2")
      .addClass("miniCalculator_input_box1");
  }
});

$("body").on("keyup", ".LoadRequired", function () {
  if ($(this).val() < minLoadRequired) {
    $(this).val("");
    ShowAlert(loadrequiredValidationMessage, "Warning");
  } else if ($(this).val() > maxLoadRequired) {
    $(this).val("150");
      ShowAlert(loadrequiredValidationMessage, "Warning");
  } else if ($(this).val() != loadRequiredIndustryStandard) {
    $(this)
      .removeClass("miniCalculator_input_box2")
      .addClass("miniCalculator_input_box1");
  }
});

$("body").on("keyup", ".CorrectiveMaintenanceInterventions", function () {
  if ($(this).val() < minCM) {
    $(this).val("");
    ShowAlert(correctiveMaintenanceInterventionsValidationMessage, "Warning");
  } else if ($(this).val() > maxCM) {
    $(this).val("10");
      ShowAlert(correctiveMaintenanceInterventionsValidationMessage, "Warning");
  } else if ($(this).val() != CMIndustryStandard) {
    $(this)
      .removeClass("miniCalculator_input_box2")
      .addClass("miniCalculator_input_box1");
  }
});

$("body").on("keyup", ".PlannedMaintenanceInterval", function () {
  if ($(this).val() < minPM) {
    $(this).val("");
    ShowAlert(PlannedMaintenanceIntervalValidationMessage, "Warning");
  } else if ($(this).val() > maxPM) {
    $(this).val("10");
      ShowAlert(PlannedMaintenanceIntervalValidationMessage, "Warning");
  } else if ($(this).val() != PMIndustryStandard) {
    $(this)
      .removeClass("miniCalculator_input_box2")
      .addClass("miniCalculator_input_box1");
  }
});

$("body").on("keyup", ".OperatingHrs", function () {
  if ($(this).val() < minOperatingHours) {
    $(this).val("");
    ShowAlert(operatingHrsValidationMessage, "Warning");
  } else if ($(this).val() > maxOperatingHours) {
    $(this).val("8760");
      ShowAlert(operatingHrsValidationMessage, "Warning");
  } else if ($(this).val() != operatingHoursIndustryStandard) {
    $(this)
      .removeClass("miniCalculator_input_box2")
      .addClass("miniCalculator_input_box1");
  }
});


function checkIfNumber(e) {
    var charCode = e.which ? e.which : e.keyCode;
    if (charCode != 46 && charCode > 31
      && (charCode < 48 || charCode > 57)) {
      e.preventDefault();
    }
}


$(document).on('keydown', 'input[pattern]', function(e){
  var input = $(this);
  var oldVal = input.val();
  var regex = new RegExp(input.attr('pattern'), 'g');

  setTimeout(function(){
    var newVal = input.val();
    if(!regex.test(newVal)){
      input.val(oldVal);
    }
  }, 1);
});


$("body").on("click", ".miniCalculator_sub_btn", function () {
  $(this).closest(".divToCopy").remove();
  $.each($(".divToCopy"), function (i) {
    let countComp = i + 1;
    $(this).find(".myText").text(countComp);
  });
  count = $(".divToCopy").length;

  if (count < 9) {
    $(".miniCalculator_add_btn").css("display", "block");
  }
  CalculateOpexAndSavingsBasedOnCompressors();
});

//#endregion

//#region  Sort graph

function sortSeries() {
  maybeDisposeRoot("miniCalculator_graph_container");
  $(".loader").show();
  SetSortOrder();
  $(".loader").hide();
  GenerateAMChart(data);
}

function ChangeSortingOrder() {
  maybeDisposeRoot("miniCalculator_graph_container");
  let currentSelection = $("#filter").val();

  switch (sortOrder) {
    case "asc":
      if (parseInt(currentSelection) == "0") {
        data.sort(
          (a, b) => parseFloat(a.percentageOpex) - parseFloat(b.percentageOpex)
        );
      } else {
        data.sort(
          (a, b) =>
            parseFloat(a.percentageSavings) - parseFloat(b.percentageSavings)
        );
      }
      $('#sortImg').attr('src', 'images/ASC.svg').css('transform','rotate(90deg)').css('margin','0.5rem 0.2rem 0rem 0.2rem');
      sortOrder = "desc";
      break;
    case "desc":
      if (parseInt(currentSelection) == 0) {
        data.sort(
          (a, b) => parseFloat(b.percentageOpex) - parseFloat(a.percentageOpex)
        );
      } else {
        data.sort(
          (a, b) =>
            parseFloat(b.percentageSavings) - parseFloat(a.percentageSavings)
        );
      }
      sortOrder = "asc";
      $('#sortImg').attr('src', 'images/DESC.svg').css('transform','rotate(-90deg)').css('margin','0.5rem 0.2rem 0rem 0.2rem');
      break;
  }

  GenerateAMChart(data);

}

function SetSortOrder() {
  let currentSelection = $("#filter").val();

  switch (sortOrder) {
    case "asc":
      if (parseInt(currentSelection) == 0) {
        data.sort(
          (a, b) => parseFloat(b.percentageOpex) - parseFloat(a.percentageOpex)
        );
      } else {
        data.sort(
          (a, b) =>
            parseFloat(b.percentageSavings) - parseFloat(a.percentageSavings)
        );
      }
      break;

    case "desc":
      if (parseInt(currentSelection) == "0") {
        data.sort(
          (a, b) => parseFloat(a.percentageOpex) - parseFloat(b.percentageOpex)
        );
      } else {
        data.sort(
          (a, b) =>
            parseFloat(a.percentageSavings) - parseFloat(b.percentageSavings)
        );
      }
      break;
  }
}

//#endregion

document.addEventListener("wheel", function(event){
  if(document.activeElement.type === "number"){
      document.activeElement.blur();
  }
});

function maybeDisposeRoot(divId) {
  am5.array.each(am5.registry.rootElements, function (root) {
    if (root.dom.id == divId) {
      root.dispose();
    }
  });
}



//#endregion

//#region alert message
function ShowAlert(content, title) {
  let dialogStatus = $(".dialog").is(":visible");
  if (!dialogStatus) {
    $("#a").dialog({
      title: title,
      enterBtn: true,
      content: content,
    });
  }
}
//#endregion

//#region validation
function validate(formData) {
  var isValidForm = true;
  var elements = $(".divToCopy");

  for (var i = 0; i < formData.length; i++) {
    var element = elements.eq(i);
    if (formData[i].unitTag.trim() == "") {
      element.find(".UnitTag").removeClass("black").addClass("red");
      isValidForm = false;
    } else {
      element.find(".UnitTag").removeClass("red");
    }
    if (formData[i].driverPower < 0 || formData[i].driverPower == "") {
      element.find(".DriverPower").removeClass("black").addClass("red");
      isValidForm = false;
    } else {
      element.find(".DriverPower").removeClass("red");
    }
    if (formData[i].loadRequired == "" || formData[i].loadRequired < 0) {
      element.find(".LoadRequired").removeClass("black").addClass("red");
      isValidForm = false;
    } else {
      element.find(".LoadRequired").removeClass("red");
    }
    if (
      formData[i].correctiveMaintenanceInterventions == "" ||
      formData[i].correctiveMaintenanceInterventions < 0
    ) {
      element
        .find(".CorrectiveMaintenanceInterventions")
        .removeClass("black")
        .addClass("red");
      isValidForm = false;
    } else {
      element.find(".CorrectiveMaintenanceInterventions").removeClass("red");
    }
    if (
      formData[i].plannedMaintenanceInterval == "" ||
      formData[i].plannedMaintenanceInterval < 0
    ) {
      element
        .find(".PlannedMaintenanceInterval")
        .removeClass("black")
        .addClass("red");
      isValidForm = false;
    } else {
      element.find(".PlannedMaintenanceInterval").removeClass("red");
    }
    if (formData[i].operatingHrs == "" || formData[i].operatingHrs < 0) {
      element.find(".OperatingHrs").removeClass("black").addClass("red");
      isValidForm = false;
    } else {
      element.find(".OperatingHrs").removeClass("red");
    }
  }
  return isValidForm;
}

function ValidateForTagIndicator(formData) {
  var isValidTagIndicator = true;
  $(".TagIndicator").removeClass("red");
  var elements = $(".divToCopy");
  for (var i = 0; i < formData.length; i++) {
    let tagName = formData[i].unitTag.toLowerCase();
    let TagIndicator = formData[i].tagIndicator.toLowerCase();

    for (var j = i + 1; j < formData.length; j++) {
      var tagIndicator1 = elements.eq(i);
      var tagIndicator2 = elements.eq(j);
      if (
        tagName == formData[j].unitTag.toLowerCase() &&
        TagIndicator == formData[j].tagIndicator.toLowerCase()
      ) {
        tagIndicator1
          .find(".TagIndicator")
          .removeClass("black")
          .addClass("red");
        tagIndicator2
          .find(".TagIndicator")
          .removeClass("black")
          .addClass("red");
        isValidTagIndicator = false;
      }
    }
  }
  return isValidTagIndicator;
}

function ValidateForUnitTagAndTagIndicator(formData) {
  let isValidUnitTagAndTagIndicator = true;
  $(".TagIndicator").removeClass("red");
  var elements = $(".divToCopy");
  for (var i = 0; i < formData.length; i++) {
    var element = elements.eq(i);
    if (
      formData[i].unitTag.trim().toLowerCase() ==
      formData[i].tagIndicator.trim().toLowerCase()
    ) {
      element.find(".TagIndicator").removeClass("black").addClass("red");
      element.find(".UnitTag").removeClass("black").addClass("red");
      isValidUnitTagAndTagIndicator = false;
    }
  }
  return isValidUnitTagAndTagIndicator;
}
//#endregion

//#region Html
function GetCompressor(count) {
  return `<div class="divToCopy">
  <div class="miniCalculator_column2 copyContainer" >
      <div style="display: inline; justify-content: center; ">
          <div class="miniCalculator_newCompressor_div"><span class="miniCalculator_compressor_title">Compressor <span class="myText">${count}</span></span></div>
          <div class="miniCalculator_subtract_div"><img class="miniCalculator_sub_btn" src="images/Negative.svg" alt="add button"/></div>
      </div>
      <div class="miniCalculator_input_heading">
          <input class="miniCalculator_input_box2 UnitTag" value="C-101" placeholder="C-101" name="unitTag" maxlength="20" />
      </div>
      <div class="miniCalculator_input_heading">
          <input type="text" class="miniCalculator_input_box2 TagIndicator" placeholder="A" value="A" name="tagIndicator" maxlength="10" />
      </div>
      <div class="miniCalculator_input_heading">
          <input type="number" onkeypress="checkIfNumber(event)" pattern="^\\d*(\\.\\d{0,2})?$" class="miniCalculator_input_box2 DriverPower numberInput small-input" value="1000" placeholder="1000"/>
      </div>
      <div class="miniCalculator_input_heading">
          <input type="number" onkeypress="checkIfNumber(event)" pattern="^\\d*(\\.\\d{0,2})?$" class="miniCalculator_input_box2 LoadRequired numberInput small-input" value="75" placeholder="75"/>
      </div>
      <div class="miniCalculator_input_heading">
          <input type="number" onkeypress="checkIfNumber(event)" pattern="^\\d*(\\.\\d{0,2})?$" class="miniCalculator_input_box2 CorrectiveMaintenanceInterventions numberInput small-input" placeholder="1" value="1"/>
      </div>
      <div class="miniCalculator_input_heading">
          <input type="number" onkeypress="checkIfNumber(event)" pattern="^\\d*(\\.\\d{0,2})?$" class="miniCalculator_input_box2 PlannedMaintenanceInterval numberInput small-input" value="3" placeholder="3"/>
      </div>
      <div class="miniCalculator_input_heading">
          <input type="number" onkeypress="checkIfNumber(event)" pattern="^\\d*(\\.\\d{0,2})?$" class="miniCalculator_input_box2 OperatingHrs numberInput small-input" value="7000" placeholder="7000"/>
      </div>
  </div>
  </div>`;
}
//#endregion

//#region Auto Scroll
function scrollRight() {
  var element = document.querySelector(".miniCalculator_table");
  element.scrollLeft = element.scrollWidth;
}
//#endregion

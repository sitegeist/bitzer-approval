(function ($, window, document, undefined) {
  var Tooltip = function (ele, opt) {
    this.$element = ele;
    this.$toolitp = null;
    this.defaults = {
      icon: null,
      type: null,
      fontColor: "black",
      hideTime: 1000,
    };
    this.options = $.extend({}, this.defaults, opt);
    this.icons = {
      success:
        '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 1024 1024" style="fill:#7cac68;width:18px;display: inline;vertical-align:middle;"><path d="M529.523831 48.306192c-248.375025 0-449.722041 201.338564-449.722041 449.703165s201.347015 449.703165 449.722041 449.703165 449.722041-201.338564 449.722041-449.703165S777.898856 48.306192 529.523831 48.306192zM747.172798 477.358015 525.78349 698.738032c-11.277308 11.276834-26.081076 16.841573-40.862332 16.758686-14.781255 0.083911-29.586047-5.481851-40.863355-16.758686L327.279338 581.964468c-22.387809-22.387893-22.387809-58.685587 0-81.072457 22.388833-22.388916 58.68805-22.388916 81.07586 0l76.56596 76.561723L666.095915 396.285558c22.388833-22.388916 58.68805-22.388916 81.07586 0C769.561631 418.673451 769.561631 454.971146 747.172798 477.358015z"></path></svg>',
      error:
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" style="fill:red;width:18px;display: inline;vertical-align:middle;"><path d="M527.521137 43.544749c-248.424146 0-449.812096 201.379497-449.812096 449.793216s201.387949 449.793216 449.812096 449.793216 449.812096-201.379497 449.812096-449.793216S775.945283 43.544749 527.521137 43.544749zM703.014259 605.430926c22.392926 22.39301 22.391903 58.697867 0 81.089853s-58.698284 22.391986-81.09121 0l-94.124585-94.120634-94.124585 94.120634c-22.392926 22.391986-58.699307 22.391986-81.093257 0-22.392926-22.391986-22.392926-58.696844 0-81.089853l94.124585-94.120634-94.124585-94.119611c-22.392926-22.39301-22.392926-58.697867 0-81.089853 22.393949-22.391986 58.700331-22.391986 81.093257 0l94.124585 94.120634 94.124585-94.120634c22.391903-22.391986 58.698284-22.391986 81.09121 0 22.392926 22.391986 22.392926 58.696844 0 81.089853l-94.123561 94.119611L703.014259 605.430926z" p-id="2075"></path></svg>',
      info: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" style="fill:red;width:18px;display: inline;vertical-align:middle;"><path d="M527.521137 48.558949c-246.731527 0-446.746141 200.00622-446.746141 446.727391s200.014615 446.727391 446.746141 446.727391 446.746141-200.00622 446.746141-446.727391S774.252663 48.558949 527.521137 48.558949zM527.521137 790.815123c-31.452636 0-56.950404-25.496697-56.950404-56.948013s25.497767-56.948013 56.950404-56.948013 56.950404 25.496697 56.950404 56.948013S558.973773 790.815123 527.521137 790.815123zM584.47154 549.187971c0 31.451316-25.497767 56.948013-56.950404 56.948013s-56.950404-25.496697-56.950404-56.948013L470.570733 306.138422c0-31.450293 25.497767-56.94699 56.950404-56.94699s56.950404 25.496697 56.950404 56.948013L584.47154 549.187971z"></path></svg>',
      help: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" style="fill:gray;width:18px;display: inline;vertical-align:middle;"><path d="M563.363941 572.337203c-0.662107-10.635221 5.985569-21.937638 19.948145-33.908274 13.962576-11.964496 29.591164-25.264407 46.879625-39.893592 17.288461-14.628162 33.242474-31.083949 47.872273-49.365314 14.630823-18.286482 22.606806-39.397289 23.936137-63.332421 1.330354-25.932626-1.329331-50.199309-7.977007-72.810283-6.647676-22.604834-17.620026-42.050722-32.910909-58.33971-15.297023-16.290011-35.079386-29.258371-59.347087-38.900985-24.267702-9.636474-52.694294-14.45727-85.274661-14.45727-40.563514 0-74.308452 7.148817-101.239931 21.441335-26.925339 14.293541-48.705279 31.581276-65.326516 51.864228-16.621237 20.276812-28.26081 41.222867-34.908486 62.829977-6.647676 21.60711-9.641996 39.727817-8.974772 54.358025 0.662107 17.282618 6.150328 29.92045 16.455454 37.897122 10.305126 7.976672 21.442235 12.135388 33.413373 12.466939 11.964998 0.331551 22.937348-2.989077 32.910909-9.973142 9.973561-6.984065 14.960341-17.121959 14.960341-30.416753 0-7.982812 2.493902-17.790178 7.480682-29.424146s11.805356-22.771633 20.450609-33.411971c8.643207-10.635221 19.115139-19.615757 31.416819-26.924209 12.30168-7.315616 26.098473-10.971889 41.39038-10.971889 29.921706 0 53.857842 7.480368 71.81455 22.440082 17.950567 14.959713 26.263233 33.742499 24.934925 56.348356 0 11.302417-3.325885 21.772886-9.973561 31.4155-6.652793 9.642614-15.131241 18.948561-25.43739 27.929096-10.304103 8.974395-21.276452 17.949814-32.910909 26.924209-11.639573 8.974395-22.606806 18.286482-32.917049 27.929096-10.304103 9.636474-18.949356 20.111037-25.927574 31.4155-6.984358 11.302417-10.806567 23.603581-11.474814 36.898375l0.998789 37.903262c0 9.973142 4.656239 19.444864 13.962576 28.4254 9.311454 8.975419 21.613134 13.796214 36.906064 14.458293 15.29293-0.662079 27.427804-5.64865 36.403599-14.959713 8.974772-9.30697 13.134686-19.947308 12.467463-31.911804L563.365987 572.337203zM512.496324 817.685098c17.289484 0 31.753501-5.814426 43.387957-17.453511 11.639573-11.633968 17.454243-25.761734 17.454243-42.387389 0-17.287735-5.81467-31.747051-17.454243-43.386136-11.634456-11.633968-26.098473-17.453511-43.387957-17.453511-17.283344 0-31.747361 5.819542-43.381817 17.453511-11.639573 11.639085-17.454243 26.098401-17.454243 43.386136 0 16.625656 5.81467 30.753421 17.454243 42.387389C480.748964 811.870672 495.212981 817.685098 512.496324 817.685098zM511.503676 65.687048c61.834849 0 119.851582 11.799744 174.04099 35.403325 54.194524 23.604604 101.570473 55.522548 142.127847 95.746668 40.56249 40.225143 72.476657 87.433328 95.750687 141.625578 23.27403 54.187133 34.908486 112.201431 34.908486 174.033685 0 61.838394-11.634456 119.6818-34.908486 173.543521-23.27403 53.855582-55.188196 101.063767-95.750687 141.619438-40.557374 40.560788-87.933322 72.473615-142.127847 95.746668-54.189408 23.273053-112.206141 34.907021-174.04099 34.907021-61.840989 0-119.686823-11.633968-173.550806-34.907021-53.857842-23.273053-101.068009-55.18588-141.625382-95.746668-40.56249-40.555671-72.476657-87.763856-95.750687-141.619438C77.302771 632.17708 65.668315 574.333674 65.668315 512.496304c0-61.832254 11.634456-119.846552 34.908486-174.033685 23.27403-54.19225 55.188196-101.400435 95.750687-141.625578 40.557374-40.22412 87.76754-72.142064 141.625382-95.746668C391.816852 77.486792 449.662687 65.687048 511.503676 65.687048z"></path></svg>',
    };
  };
  Tooltip.prototype = {
    open: function (options) {
      var tooltip = this,
        $tooltip = $("<div></div>")
          .css("color", tooltip.options.fontColor)
          .addClass(tooltip.options.type || "tooltip_1")
          .hide();
      if (tooltip.options.content)
        $tooltip.append(
          '<span class="text">' + tooltip.options.content + "</span>"
        );
      else if (tooltip.$element.html) {
        $tooltip.append(
          '<span class="text">' + tooltip.$element.html() + "</span>"
        );
        tooltip.$element.empty();
      }
      if (tooltip.options.icon in tooltip.icons)
        $tooltip.prepend(tooltip.icons[tooltip.options.icon]);

      $tooltip.appendTo("body");
      tooltip.$toolitp = $tooltip;
      $tooltip.fadeIn(100, function () {
        if (tooltip.options.open) tooltip.options.open(tooltip);
        if (tooltip.options.hideTime)
          setTimeout(function () {
            tooltip.close();
          }, tooltip.options.hideTime);
      });
      return tooltip;
    },
    close: function (options) {
      var tooltip = this;
      tooltip.$toolitp.fadeOut(100, function () {
        $(this).detach();
        if (tooltip.$element.html)
          tooltip.$element.html($(this).find(".text").html());
        if (tooltip.options.close) tooltip.options.close(tooltip);
      });
      return tooltip;
    },
  };

  var Dialog = function (ele, opt) {
    this.$element = ele;
    this.$dialog_shade = null;
    this.defaults = {
      width: "400px",
      height: "200px",
      dialogStyle: "",
      title: "",
      content: "",
      hideTime: 0,
      open: null,
      close: null,
      showClose: true,
      enterBtn: null,
      cancelBtn: null,
    };
    this.options = $.extend({}, this.defaults, opt);
  };
  Dialog.prototype = {
    open: function () {
      var dialog = this,
        $shade = $('<div class="shade"></div>').hide(),
        $dialog = $("<div></div>").appendTo($shade),
        $dialog_title = $("<div></div>").appendTo($dialog),
        $dialog_tools = $('<div class="dialog_tools"></div>'),
        $dialog_body = $('<div class="dialog_body"></div>').appendTo($dialog),
        $dialog_btns = $('<div class="dialog_btns"></div>')
          .appendTo($dialog)
          .hide();

      if (dialog.options.dialogStyle)
        $dialog.addClass(dialog.options.dialogStyle);
      else $dialog.addClass("dialog");

      if (dialog.options.enterBtn) {
        var $enterBtn = $("<button></button>");
        if (dialog.options.enterBtn.text)
          $enterBtn.text(dialog.options.enterBtn.text);
        else $enterBtn.text("Ok");

        if (dialog.options.enterBtn.style)
          $enterBtn.addClass(dialog.options.enterBtn.style);
        else $enterBtn.addClass("def-btn");

        if (dialog.options.enterBtn.cb)
          $enterBtn.on("click", function () {
            dialog.options.enterBtn.cb(dialog);
          });
        else
          $enterBtn.on("click", function () {
            dialog.close();
          });

        $enterBtn.appendTo($dialog_btns);
        $dialog_btns.show();
      }

      if (dialog.options.cancelBtn) {
        var $cancelBtn = $("<button></button>");
        if (dialog.options.cancelBtn.text)
          $cancelBtn.text(dialog.options.cancelBtn.text);
        else $cancelBtn.text("Cancel");

        if (dialog.options.cancelBtn.style)
          $cancelBtn.addClass(dialog.options.cancelBtn.style);
        else $cancelBtn.addClass("def-btn");

        if (dialog.options.cancelBtn.cb)
          $cancelBtn.on("click", function () {
            dialog.options.cancelBtn.cb(dialog);
          });
        else
          $cancelBtn.on("click", function () {
            dialog.close();
          });

        $cancelBtn.appendTo($dialog_btns);
        $dialog_btns.show();
      }
      if (dialog.options.showClose) $dialog_tools.appendTo($dialog);
      if (dialog.options.content)
        $dialog_body.append(dialog.options.content).css("padding-top", "20px");
      else if (dialog.$element.html) {
        $dialog_body.html(dialog.$element.html());
        dialog.$element.empty();
      }
      if (dialog.options.width) $dialog.width(dialog.options.width);
      if (dialog.options.height) $dialog.height(dialog.options.height);
      if (dialog.options.title)
        $dialog_title.addClass("dialog_title").html(dialog.options.title);
      else $dialog_title.detach();
      dialog.$dialog_shade = $shade.appendTo("body");
      $shade.fadeIn(200, function () {
        if (dialog.options.open) dialog.options.open(dialog);
        if (dialog.options.hideTime)
          setTimeout(function () {
            dialog.close();
          }, dialog.options.hideTime);
      });
      return dialog;
    },
    close: function () {
      var dialog = this;
      dialog.$dialog_shade.fadeOut(200, function () {
        $(this).detach();
        if (dialog.$element.html)
          dialog.$element.html($(this).find(".dialog_body").html());
        if (dialog.options.close) dialog.options.close(dialog);
      });
      return dialog;
    },
  };

  $.extend({
    dialog: function (options) {
      var dialog = new Dialog(this, options);
      return dialog.open();
    },

    tooltip: function (options) {
      var tooltip = new Tooltip(this, options);
      return tooltip.open();
    },
  });

  $.fn.dialog = function (options) {
    var dialog = new Dialog(this, options);
    return dialog.open();
  };

  $.fn.tooltip = function (options) {
    var tooltip = new Tooltip(this, options);
    return tooltip.open();
  };
})(jQuery, window, document);

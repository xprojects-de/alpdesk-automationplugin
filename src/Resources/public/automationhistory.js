
if ($("#alpdeskautomationplugin_historychart_reload").length > 0) {
  $("#alpdeskautomationplugin_historychart_reload").click(function () {
    var event = new CustomEvent("alpdesk", {
      detail: {
        type: 'route',
        target: 'automationhistory'
      }
    });
    document.dispatchEvent(event);
  });
}


$('div.automationhistorychart').each(function () {

  $(this).html('');

  var x = $(this).attr('data-x').split('||');
  var min = x[0];
  var max = x[0];
  if (x.length > 24) {
    min = x[x.length - 24];
  }
  if (x.length > 0) {
    max = x[x.length - 1];
  }

  var trace = {
    x: x,
    y: $(this).attr('data-y').split('||'),
    mode: 'lines+markers',
    marker: {
      color: 'rgb(139, 195, 74)',
      size: 5,
      line: {
        color: 'rgb(139, 195, 74)',
        width: 1
      }
    }
  };

  var data = [trace];

  var layout = {
    dragmode: 'pan',
    margin: {
      l: 50,
      r: 30,
      b: 75,
      t: 25,
      pad: 2
    }, xaxis: {
      range: [min, max]
    }
  };

  var config = {responsive: true}

  Plotly.newPlot(document.getElementById($(this).attr('id')), data, layout, config);

});



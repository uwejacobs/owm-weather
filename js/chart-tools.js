function loadImage(svg) {
  return new Promise((fulfill, reject) => {
    let imageObj = new Image(30,30);
    imageObj.onload = () => fulfill(imageObj);
    imageObj.src = "data:image/svg+xml," + svg;
  });
}

Chart.Tooltip.positioners.bottom = function(elements, eventPosition) {
  const pos = Chart.Tooltip.positioners.average(elements);

  if (pos === false) {
    return false;
  }

  const chart = this.chart;

  return {
    x: pos.x,
    y: chart.chartArea.bottom,
    xAlign: "center",
    yAlign: "bottom",
  };
};

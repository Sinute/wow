var margin = {top: 20, right: 20, bottom: 30, left: 100},
    width = 960 - margin.left - margin.right,
    height = 340 - margin.top - margin.bottom;

var parseDateTime = d3.time.format("%Y-%m-%d %H:%M:%S").parse;
var parseDate = d3.time.format("%Y-%m-%d").parse;

var xDayRecord = d3.time.scale()
    .range([0, width]);

var yDayRecord = d3.scale.linear()
    .range([height, 0]);

var xMonthRecord = d3.time.scale()
    .range([0, width]);

var yMonthRecord = d3.scale.linear()
    .range([height, 0]);

var xAxisDayRecord = d3.svg.axis()
    .scale(xDayRecord)
    .ticks(d3.time.minutes, 60)
    .tickFormat(d3.time.format("%H"))
    .orient("bottom");

var xAxisMonthRecord = d3.svg.axis()
    .scale(xMonthRecord)
    .ticks(d3.time.days, 1)
    .tickFormat(d3.time.format("%d"))
    .orient("bottom");

var yAxisDayRecord = d3.svg.axis()
    .scale(yDayRecord)
    .tickFormat(d3.format(",.4f"))
    .orient("left");

var yAxisMonthRecord = d3.svg.axis()
    .scale(yMonthRecord)
    .tickFormat(d3.format(",.4f"))
    .orient("left");

var lineDayRecord = d3.svg.line()
    .x(function(d) { return xDayRecord(d.dateTime); })
    .y(function(d) { return yDayRecord(d.price); });

var lineMonthRecord = d3.svg.line()
    .x(function(d) { return xMonthRecord(d.date); })
    .y(function(d) { return yMonthRecord(d.price); });

function drawChart(url){
  $(".loading").show();
  d3.json(url, function(error, result) {
    if(error) return;
    if(result.status){
      var dayRecord = [];
      if(result.data.dayRecord && result.data.dayRecord.length > 0) {
        result.data.dayRecord.forEach(function(d) {
          dayRecord.push({
            'dateTime': parseDateTime(d.date + " " + d.time),
            'timeStr': d.time,
            'dateStr' : d.date,
            'price': parseInt(d.gold_buyout) + parseInt(d.silver_buyout) / 100 + parseInt(d.copper_buyout) / 10000, 
            'gold': parseInt(d.gold_buyout), 
            'silver': parseInt(d.silver_buyout), 
            'copper': parseInt(d.copper_buyout)
          });
        });
      }

      var monthRecord = [];
      if(result.data.monthRecord && result.data.monthRecord.length > 0) {
        result.data.monthRecord.forEach(function(d) {
          monthRecord.push({
            'date': parseDate(d.date),
            'dateStr' : d.date,
            'price': parseInt(d.gold_buyout) + parseInt(d.silver_buyout) / 100 + parseInt(d.copper_buyout) / 10000, 
            'gold': parseInt(d.gold_buyout), 
            'silver': parseInt(d.silver_buyout), 
            'copper': parseInt(d.copper_buyout)
          });
        });
      }

      if(dayRecord.length == 0 && monthRecord.length == 0) {
        $(".loading").hide();
        $(".not-found").show();
        return;
      }

      if(dayRecord.length > 0) {
        $("#day-g").remove();
      }

    }else{
      return;
    }

    // day record
    if(dayRecord.length > 0) {
      var dayRecordSvg = d3.select("#dayRecord").append("svg")
          .attr("width", width + margin.left + margin.right)
          .attr("height", height + margin.top + margin.bottom)
          .attr("id", "day-g")
        .append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      xDayRecord.domain(d3.extent(dayRecord, function(d) { return d.dateTime; }));
      yDayRecord.domain(d3.extent(dayRecord, function(d) { return d.price; }));
      dayRecordSvg.append("g")
          .attr("class", "x axis")
          .attr("transform", "translate(0," + height + ")")
          .call(xAxisDayRecord);

      dayRecordSvg.append("g")
          .attr("class", "y axis")
          .call(yAxisDayRecord)
        .append("text")
          .attr("transform", "rotate(-90)")
          .attr("y", 6)
          .attr("dy", ".71em")
          .style("text-anchor", "end")
          .text("单价 (金)");

      dayRecordSvg.append("path")
          .datum(dayRecord)
          .attr("class", "line")
          .attr("d", lineDayRecord);

      dayRecordSvg.selectAll('.point')
        .data(dayRecord)
        .enter().append("svg:circle")
          .attr("class", 'point')
          .attr("cx", function(d) { return xDayRecord(d.dateTime); })
          .attr("cy", function(d) { return yDayRecord(d.price); })
          .on('mouseover', function(d) {
            $("#tooltip .popover-title").text(d.dateStr + " " + d.timeStr);
            $("#tooltip .popover-content").text(d.gold + ' 金 ' + d.silver + ' 银 ' + d.copper + ' 铜');
            d3.select('#tooltip')
              .style("display", "block")
              .style("top", yDayRecord(d.price) + $("#top-bar").height() + $(".chart-header").height() + $(".chart-title").height() - $("#tooltip").height() + 'px')
              .style("left", xDayRecord(d.dateTime) - $('#tooltip').width() / 2 + margin.left + 18 + 'px');
            d3.select(this).transition().attr('r', 15); 
          })
          .on('mouseout', function(d) {
            d3.select('#tooltip')
              .style("display", "none");
            d3.select(this).transition().attr('r', 5); 
          })
          .attr("r", 5);

      $(".day-line-chart").show();
    }

    // month record
    if(monthRecord.length > 0) {
      var monthRecordSvg = d3.select("#monthRecord").append("svg")
          .attr("width", width + margin.left + margin.right)
          .attr("height", height + margin.top + margin.bottom)
          .attr("id", "month-g")
        .append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      xMonthRecord.domain(d3.extent(monthRecord, function(d) { return d.date; }));
      yMonthRecord.domain(d3.extent(monthRecord, function(d) { return d.price; }));
      monthRecordSvg.append("g")
          .attr("class", "x axis")
          .attr("transform", "translate(0," + height + ")")
          .call(xAxisMonthRecord);

      monthRecordSvg.append("g")
          .attr("class", "y axis")
          .call(yAxisMonthRecord)
        .append("text")
          .attr("transform", "rotate(-90)")
          .attr("y", 6)
          .attr("dy", ".71em")
          .style("text-anchor", "end")
          .text("单价 (金)");

      monthRecordSvg.append("path")
          .datum(monthRecord)
          .attr("class", "line")
          .attr("d", lineMonthRecord);

      monthRecordSvg.selectAll('.point')
        .data(monthRecord)
        .enter().append("svg:circle")
          .attr("class", 'point')
          .attr("cx", function(d) { return xMonthRecord(d.date); })
          .attr("cy", function(d) { return yMonthRecord(d.price); })
          .attr("date", function(d) { return d.dateStr; })
          .on('mouseover', function(d) {
            $("#tooltip .popover-title").text(d.dateStr);
            $("#tooltip .popover-content").text(d.gold + ' 金 ' + d.silver + ' 银 ' + d.copper + ' 铜');
            d3.select('#tooltip')
              .style("display", "block")
              .style("top", yMonthRecord(d.price) + $("#top-bar").height() + $(".chart-header").height() + $(".chart-title").height() * 2 + 20 + $(".dayRecord").height() - $("#tooltip").height() + 'px')
              .style("left", xMonthRecord(d.date) - $('#tooltip').width() / 2 + margin.left + 18 + 'px');
            d3.select(this).transition().attr('r', 15); 
          })
          .on('mouseout', function(d) {
            d3.select('#tooltip')
              .style("display", "none");
            d3.select(this).transition().attr('r', 5); 
          })
          .on('click', function(d) {
            if($(this).attr("date") == $("#dayRecord").attr("date")) return false;
            $("#dayRecord").attr("date", $(this).attr("date"));
            drawChart(url + "/date/" + $(this).attr("date"));
          })
          .attr("r", 5);

      $(".month-line-chart").show();
    }

    $(".loading").hide();
  });
};

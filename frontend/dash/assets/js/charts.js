/*
const config = {
  type: 'pie',
  data: data,
  options: {
    cutout: '90%',
    responsive: true,
    plugins: {
      legend: {
        display: false,
      },
      title: {
        display: false,
      }
    }
  },
};
*/


const getPercentages = (outcome, total) => ((outcome / total) * 100).toFixed(1);

function MethodsChart() {
  $(".methodsUsage span").remove()
  
  $.ajax({
    url: "/dash/rest/charts/methods/",
    type: "post",
    cache: false,
    success: function (data) {
      var barColors = [
        "#9B1CFF",
        "#875BFF",
        "#4A56FF",
        "#33C9FF",
        "#FFF"
      ];

      var xValues = [];
      var yValues = [];

      var legendHTML = ``

      var otherCount = 0;
      var allCount = 0;

      for (var idx in data) {
        allCount = allCount + data[idx].value
        legendHTML += `
          <div style="
              display: flex;
              justify-content: center;
              align-items: center;
          ">
            <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 8 8" fill="none" style="margin-right: 5px;">
              <circle cx="4" cy="4" r="4" fill="${barColors[idx] ? barColors[idx] : "#FFF"}"/>
            </svg>
            ${data[idx].method}
          </div>
        `;

        if(xValues.length < 4) {
          xValues.push(data[idx].method);
          yValues.push(data[idx].value);
        } else {
          otherCount = otherCount + data[idx].value
        }
      }

      $("#methods_top").html(`
        <span class="methods_top_title">${getPercentages(data[0].value, allCount)}%</span>
        </br>
        <span class="methods_top_subtitle">${data[0].method}
      `)

      $("#methods_legend").html(legendHTML)

      xValues.push("Other")
      yValues.push(otherCount)
  
    
      new Chart(
        document.getElementById('methodsUsage_chart'),
        {
          type: 'pie',
          data: {
            labels: xValues,
            datasets: [{
              borderWidth: 0,
              backgroundColor: barColors,
              data: yValues
            }]
          },
          options: {
            cutout: '90%',
            responsive: true,
            plugins: {
              legend: {
                display: false,
              },
              title: {
                display: false,
              }
            },
          },
        }
      );
    }
  })
}

function NetworkUsage() {
	var data1 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	var totalPoints = 50;

	var session = ' ';
  
	var GetData = (init) => {
		data1.shift();
		
    if(!init) {
      $.post('/dash/rest/charts/network', (r) => {
        data1.push(r.usage);
      })
    }

		var result = [];
		for (var i = 0; i < data1.length; ++i) {
			result.push([i, data1[i]]);
		}
		return result;
	}

	var plot = $.plot($("#realnetwork"), [GetData(true)], {
		colors: ['#EE2D41', '#187DE4'],
		series: {
			lines: {
				show: true,
				fill: true,
				lineWidth: 3,
			},
			shadowSize: 0
		},
		yaxis: {
			min: 0,
			max: 100,
			tickColor: "#2B2B40",
			ticks: 10,
		},
		xaxis: {
			show: false
		},
		grid: {
			hoverable: true,
			clickable: true,
			borderWidth: 2,
			borderColor: "#2B2B40",
		},
		colors: ['#187DE4'],
		tooltip: true,
		tooltipOpts: {
			defaultTheme: false,
			content: "<b>%y%</b>",
		}
	});

	var update = () => {
		plot.setData([GetData()]);
		plot.draw();
		setTimeout(update, 5000);
	}
	update();
}

let initialized = false

$(document).ready(() => {
  if(initialized) return;
  initialized = true
  
  MethodsChart()
  NetworkUsage()
})

/*TodayChart();
TotalAChart();
TotalUsers();
MethodsChart();
function TotalAChart() {
  $.ajax({
    url: "rest/charts/total_attacks",
    type: "post",
    cache: false,
    success: function (_0x489e47) {
      var _0x5c832a = [];
      var _0x2206c9 = [];
      for (var _0x242a03 in _0x489e47) {
        _0x5c832a.push(_0x489e47[_0x242a03].date);
        _0x2206c9.push(_0x489e47[_0x242a03].attacks);
      }
      var _0x12a2ec = new Chart(document.getElementById("totalattacks"), {
        type: "line",
        data: {
          labels: _0x5c832a,
          datasets: [{
            label: "Total Attacks",
            backgroundColor: "#B4C6FC",
            data: _0x2206c9,
            fill: true
          }]
        },
        options: {
          plugins: {
            legend: {
              display: false
            }
          },
          maintainAspectRatio: false,
          scales: {
            x: {
              display: false
            },
            y: {
              display: false
            }
          },
          elements: {
            line: {
              borderWidth: 2,
              tension: 0.4
            },
            point: {
              radius: 0,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }
  });
}
function TodayChart() {
  (function () {})();
  $.ajax({
    url: "rest/charts/today",
    type: "post",
    cache: false,
    success: function (_0x4b67de) {
      var _0x3998bc = [];
      var _0xed483e = [];
      for (var _0x444aa3 in _0x4b67de) {
        _0x3998bc.push(_0x4b67de[_0x444aa3].hour);
        _0xed483e.push(_0x4b67de[_0x444aa3].attacks);
      }
      var _0xba462a = new Chart(document.getElementById("todayattacks"), {
        type: "line",
        data: {
          labels: _0x3998bc,
          datasets: [{
            label: "Today Attacks",
            backgroundColor: "#B4C6FC",
            data: _0xed483e,
            fill: true
          }]
        },
        options: {
          plugins: {
            legend: {
              display: false
            }
          },
          maintainAspectRatio: false,
          scales: {
            x: {
              display: false
            },
            y: {
              display: false
            }
          },
          elements: {
            line: {
              borderWidth: 2,
              tension: 0.4
            },
            point: {
              radius: 0,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }
  });
}
var runningattacksresult = [{
  x: "18:00",
  y: "200"
}, {
  x: "19:00",
  y: "900"
}, {
  x: "20:00",
  y: "300"
}, {
  x: "22:00",
  y: "800"
}];
var runningattackslabels = runningattacksresult.map(_0x134743 => moment(_0x134743.x, "HH:mm"));
var runningattacksdata = runningattacksresult.map(_0x3cd394 => +_0x3cd394.y);
var runningattacks = new Chart(document.getElementById("runningattacks"), {
  type: "line",
  data: {
    labels: runningattackslabels,
    datasets: [{
      label: "Running Attacks",
      backgroundColor: "#B4C6FC",
      data: runningattacksdata,
      fill: true
    }]
  },
  options: {
    plugins: {
      legend: {
        display: false
      }
    },
    maintainAspectRatio: false,
    scales: {
      x: {
        display: false
      },
      y: {
        display: false
      }
    },
    elements: {
      line: {
        borderWidth: 2,
        tension: 0.4
      },
      point: {
        radius: 0,
        hitRadius: 10,
        hoverRadius: 4
      }
    }
  }
});
function TotalUsers() {
  $.ajax({
    url: "rest/charts/total_users",
    type: "post",
    cache: false,
    success: function (_0x193721) {
      var _0x48cd70 = [];
      var _0x695935 = [];
      for (var _0x984b29 in _0x193721) {
        _0x48cd70.push(_0x193721[_0x984b29].date);
        _0x695935.push(_0x193721[_0x984b29].users);
      }
      var _0x52f8ac = new Chart(document.getElementById("totalusers"), {
        type: "line",
        data: {
          labels: _0x48cd70,
          datasets: [{
            label: "Total Users",
            backgroundColor: "#B4C6FC",
            data: _0x695935,
            fill: true
          }]
        },
        options: {
          plugins: {
            legend: {
              display: false
            }
          },
          maintainAspectRatio: false,
          scales: {
            x: {
              display: false
            },
            y: {
              display: false
            }
          },
          elements: {
            line: {
              borderWidth: 2,
              tension: 0.4
            },
            point: {
              radius: 0,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }
  });
}
function MethodsChart() {
  $.ajax({
    url: "rest/charts/methods",
    type: "post",
    cache: false,
    success: function (_0x4eb419) {
      var _0x391f90 = [];
      var _0x331380 = [];
      for (var _0x828ca1 in _0x4eb419) {
        _0x391f90.push(_0x4eb419[_0x828ca1].method);
        _0x331380.push(_0x4eb419[_0x828ca1].value);
      }
      var _0x1a5e11 = {
        series: _0x331380,
        chart: {
          width: 380,
          type: "pie",
          foreColor: "#D1D5DB"
        },
        labels: _0x391f90,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 300
            },
            legend: {
              position: "bottom"
            }
          }
        }],
        colors: ["#B4C6FC", "#a4b8f5", "#92a9f0", "#89a3f5", "#7e9bf7"],
        stroke: {
          show: false
        }
      };
      var _0x1fdaaf = new ApexCharts(document.querySelector("#statisticschart"), _0x1a5e11);
      _0x1fdaaf.render();
    }
  });
}*/
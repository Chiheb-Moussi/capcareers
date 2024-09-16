// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Acceptés  ", "Réfusées", " En attente"],
    datasets: [{
      data: chartDataFromController,
      backgroundColor: ['#4e73df', '#a50835', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#af2c2c', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});


var ctxMatchingSecteurChart = document.getElementById("matchingSecteurChart");
var myMatchingSecteurChart = new Chart(ctxMatchingSecteurChart, {
  type: 'doughnut',
  data: {
    labels: Object.keys(dataMatchingOffreSecteur),
    datasets: [{
      data: Object.values(dataMatchingOffreSecteur),
      backgroundColor: ['#4e73df', '#a50835', '#36b9cc', '#ccc', '#dedede'],
      hoverBackgroundColor: ['#2e59d9', '#af2c2c', '#2c9faf', '#ccc', '#dedede'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true
    },
    cutoutPercentage: 80,
  },
});


var ctxMatchingSkillChart = document.getElementById("matchingSkillChart");
var myMatchingSkillChart = new Chart(ctxMatchingSkillChart, {
  type: 'doughnut',
  data: {
    labels: Object.keys(dataMatchingCandidatSkills),
    datasets: [{
      data: Object.values(dataMatchingCandidatSkills),
      backgroundColor: ['#4e73df', '#a50835', '#36b9cc', '#ccc', '#dedede'],
      hoverBackgroundColor: ['#2e59d9', '#af2c2c', '#2c9faf', '#ccc', '#dedede'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true
    },
    cutoutPercentage: 80,
  },
});

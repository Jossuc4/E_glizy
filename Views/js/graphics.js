var id=document.querySelector(".id").value
console.log(id)

fetch('http://localhost/Eglise/index.php?route=stat&id='+id)           //api for the get request
  .then(response => response.json())
  .then(data =>{
    console.log(data.entree)
    console.log(data.sortie)
    var ctx=document.getElementById("myChart")
var myChart = new Chart(ctx, {
  type: 'scatter',
  data: {
    datasets: [
      {
        label: 'Entrée',
        data: data.entree,
        showLine: true,
        fill: false,
        borderColor: 'rgba(0, 200, 0, 0.8)'
      },
      {
        label: 'Sortie',
        data: data.sortie,
        showLine: true,
        fill: false,
        borderColor: 'rgba(200, 0, 0, 0.8)'
      }
    ]
  },
  options: {
    tooltips: {
      mode: 'index',
      intersect: false,
    },
    hover: {
      mode: 'nearest',
      intersect: true
    },
    scales: {
      y:{
        ticks: {
          beginAtZero:true
        }
      },
      x: {
        type: 'time',
        time:{
          unit:'day'
        }
      }
    },
  }
});
  });
